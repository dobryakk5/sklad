<?php
/**
 * =============================================================================
 * ФАЗА 1: Добавить в HelperRest.php
 * Файл: local/php_interface/classes/enum/HelperRest.php
 *
 * Вставить методы addReferralFrom1C() и updateReferralFrom1C() рядом с
 * существующими addInvoiceFrom1C() / updateInvoiceFrom1C().
 * =============================================================================
 */

// =============================================================================
// КОНСТАНТЫ — добавить в начало файла HelperRest.php (если ещё нет)
// =============================================================================

if (!defined('REFERRAL_LOG_FILE')) {
    define('REFERRAL_LOG_FILE', $_SERVER['DOCUMENT_ROOT'] . '/local/logs/addReferralsFrom1C.txt');
}

// ID смарт-процесса «Промокод/Реферал» в CRM Bitrix24
// Узнать после создания: CRM → Смарт-процессы → ID из URL
if (!defined('CRM_REFERRAL_SMART_PROCESS_ID')) {
    define('CRM_REFERRAL_SMART_PROCESS_ID', 0); // <-- ЗАМЕНИТЬ на реальный ID
}

// Допустимые статусы реферального кода
if (!defined('REFERRAL_STATUS_ACTIVE')) {
    define('REFERRAL_STATUS_ACTIVE',  'ACTIVE');
    define('REFERRAL_STATUS_INACTIVE','INACTIVE');
    define('REFERRAL_STATUS_PENDING', 'PENDING');
}


// =============================================================================
// МЕТОД 1: addReferralFrom1C()
// Создаёт новую реферальную запись в CRM на основе данных из 1С.
// По аналогии с addInvoiceFrom1C() + CCrmInvoice::Add.
// =============================================================================

/**
 * Принимает данные реферала от 1С и создаёт запись в CRM.
 *
 * Ожидаемые поля $params:
 *   CONTACT_ID    (int)    — ID контакта в CRM
 *   REF_CODE      (string) — реферальный код, напр. ALFA-XXXXX
 *   STATUS        (string) — ACTIVE | INACTIVE | PENDING
 *   BONUS_AMOUNT  (float)  — сумма бонуса в рублях
 *   REFERRAL_URL  (string) — полная ссылка с ref-параметром
 *   DATE_CREATE   (string) — дата генерации в 1С, формат Y-m-d
 *   REFERRAL_COUNT(int)    — кол-во привлечённых клиентов (опционально)
 *
 * @param array $params
 * @return array ['result' => true|false, 'ID' => int|null, 'error' => string]
 */
public static function addReferralFrom1C(array $params): array
{
    $logPrefix = '[addReferralFrom1C] ';

    // [FIX bug3] Если константа не задана — дальше идти бессмысленно
    if (!CRM_REFERRAL_SMART_PROCESS_ID) {
        $msg = $logPrefix . 'CRM_REFERRAL_SMART_PROCESS_ID is not set. Check init.php.';
        self::writeLog(REFERRAL_LOG_FILE, $msg);
        return ['result' => false, 'ID' => null, 'error' => $msg];
    }

    // ------------------------------------------------------------------
    // 1. Валидация обязательных полей
    // ------------------------------------------------------------------
    $required = ['CONTACT_ID', 'REF_CODE', 'STATUS'];
    foreach ($required as $field) {
        if (empty($params[$field])) {
            $msg = $logPrefix . 'Missing required field: ' . $field;
            self::writeLog(REFERRAL_LOG_FILE, $msg);
            return ['result' => false, 'ID' => null, 'error' => $msg];
        }
    }

    $contactId    = (int)    $params['CONTACT_ID'];
    $refCode      = (string) trim($params['REF_CODE']);
    $status       = (string) trim($params['STATUS']);
    $bonusAmount  = (float)  ($params['BONUS_AMOUNT']   ?? 0);
    $referralUrl  = (string) ($params['REFERRAL_URL']   ?? '');
    $dateCreate   = (string) ($params['DATE_CREATE']    ?? date('Y-m-d'));
    $referralCount= (int)    ($params['REFERRAL_COUNT'] ?? 0);

    // ------------------------------------------------------------------
    // 2. Проверка допустимого статуса
    // ------------------------------------------------------------------
    $allowedStatuses = [REFERRAL_STATUS_ACTIVE, REFERRAL_STATUS_INACTIVE, REFERRAL_STATUS_PENDING];
    if (!in_array($status, $allowedStatuses, true)) {
        $msg = $logPrefix . 'Invalid STATUS value: ' . $status;
        self::writeLog(REFERRAL_LOG_FILE, $msg);
        return ['result' => false, 'ID' => null, 'error' => $msg];
    }

    // ------------------------------------------------------------------
    // 3. Проверка существования контакта в CRM
    // ------------------------------------------------------------------
    $contact = \CCrmContact::GetByID($contactId);
    if (!$contact) {
        $msg = $logPrefix . 'Contact not found in CRM: CONTACT_ID=' . $contactId;
        self::writeLog(REFERRAL_LOG_FILE, $msg);
        return ['result' => false, 'ID' => null, 'error' => $msg];
    }

    // ------------------------------------------------------------------
    // 4. Проверка дубля: один REF_CODE на один CONTACT_ID
    //    Если запись уже есть — перенаправляем в updateReferralFrom1C()
    // ------------------------------------------------------------------
    $existingId = self::findReferralByContactId($contactId);
    if ($existingId) {
        self::writeLog(
            REFERRAL_LOG_FILE,
            $logPrefix . 'Referral already exists for CONTACT_ID=' . $contactId
                       . ', redirecting to update. Existing ID=' . $existingId
        );
        return self::updateReferralFrom1C(array_merge($params, ['CRM_REFERRAL_ID' => $existingId]));
    }

    // ------------------------------------------------------------------
    // 5. Подготовка полей для смарт-процесса CRM
    //    UF_* — пользовательские поля смарт-процесса (создать в CRM заранее)
    // ------------------------------------------------------------------
    $fields = [
        'TITLE'             => 'Реферал: ' . $refCode,
        'CONTACT_ID'        => $contactId,
        'UF_REF_CODE'       => $refCode,
        'UF_STATUS'         => $status,
        'UF_BONUS_AMOUNT'   => $bonusAmount,
        'UF_REFERRAL_URL'   => $referralUrl,
        'UF_DATE_CREATE'    => $dateCreate,
        'UF_REFERRAL_COUNT' => $referralCount,
        'UF_SOURCE'         => '1C',            // чтобы отличать записи от 1С
        'ASSIGNED_BY_ID'    => 1,               // ответственный по умолчанию
    ];

    // ------------------------------------------------------------------
    // 6. Создание элемента смарт-процесса через API Bitrix24
    // ------------------------------------------------------------------
    try {
        // Получаем класс Item фабрики смарт-процесса
        $factory  = \Bitrix\Crm\Service\Container::getInstance()
                        ->getFactory(CRM_REFERRAL_SMART_PROCESS_ID);

        if (!$factory) {
            throw new \Exception('Smart process factory not found. Check CRM_REFERRAL_SMART_PROCESS_ID constant.');
        }

        $item = $factory->createItem($fields);
        $operation = $factory->getAddOperation($item);
        $result = $operation->launch();

        if (!$result->isSuccess()) {
            $errorMsg = implode('; ', $result->getErrorMessages());
            throw new \Exception('Add operation failed: ' . $errorMsg);
        }

        $newId = $item->getId();

        self::writeLog(
            REFERRAL_LOG_FILE,
            $logPrefix . 'SUCCESS. Created referral ID=' . $newId
                       . ' REF_CODE=' . $refCode
                       . ' CONTACT_ID=' . $contactId
        );

        return ['result' => true, 'ID' => $newId, 'error' => ''];

    } catch (\Exception $e) {
        $msg = $logPrefix . 'Exception: ' . $e->getMessage();
        self::writeLog(REFERRAL_LOG_FILE, $msg);
        return ['result' => false, 'ID' => null, 'error' => $msg];
    }
}


// =============================================================================
// МЕТОД 2: updateReferralFrom1C()
// Обновляет существующую реферальную запись в CRM.
// По аналогии с updateInvoiceFrom1C() + CCrmInvoice::Update.
// =============================================================================

/**
 * Обновляет реферал в CRM по данным из 1С.
 *
 * Обязательные поля $params — те же, что у addReferralFrom1C().
 * Дополнительно можно передать CRM_REFERRAL_ID (int) для точного поиска.
 *
 * @param array $params
 * @return array ['result' => true|false, 'ID' => int|null, 'error' => string]
 */
public static function updateReferralFrom1C(array $params): array
{
    $logPrefix = '[updateReferralFrom1C] ';

    if (!CRM_REFERRAL_SMART_PROCESS_ID) {
        $msg = $logPrefix . 'CRM_REFERRAL_SMART_PROCESS_ID is not set. Check init.php.';
        self::writeLog(REFERRAL_LOG_FILE, $msg);
        return ['result' => false, 'ID' => null, 'error' => $msg];
    }

    // ------------------------------------------------------------------
    // 1. Валидация
    // ------------------------------------------------------------------
    if (empty($params['CONTACT_ID']) && empty($params['CRM_REFERRAL_ID'])) {
        $msg = $logPrefix . 'Missing CONTACT_ID or CRM_REFERRAL_ID';
        self::writeLog(REFERRAL_LOG_FILE, $msg);
        return ['result' => false, 'ID' => null, 'error' => $msg];
    }

    // ------------------------------------------------------------------
    // 2. Поиск существующей записи
    // ------------------------------------------------------------------
    $itemId = (int) ($params['CRM_REFERRAL_ID'] ?? 0);

    if (!$itemId && !empty($params['CONTACT_ID'])) {
        $itemId = self::findReferralByContactId((int) $params['CONTACT_ID']);
    }

    if (!$itemId) {
        // Запись не найдена — создаём новую
        self::writeLog(
            REFERRAL_LOG_FILE,
            $logPrefix . 'Referral not found for CONTACT_ID=' . ($params['CONTACT_ID'] ?? '?')
                       . ', redirecting to add.'
        );
        return self::addReferralFrom1C($params);
    }

    // ------------------------------------------------------------------
    // 3. Формируем поля для обновления (обновляем только то, что пришло)
    // ------------------------------------------------------------------
    $updateFields = [];

    if (!empty($params['REF_CODE']))       $updateFields['UF_REF_CODE']       = trim($params['REF_CODE']);
    if (!empty($params['STATUS'])) {
        $updStatus = trim($params['STATUS']);
        $allowedStatuses = [REFERRAL_STATUS_ACTIVE, REFERRAL_STATUS_INACTIVE, REFERRAL_STATUS_PENDING];
        if (in_array($updStatus, $allowedStatuses, true)) {
            $updateFields['UF_STATUS'] = $updStatus;
        } else {
            self::writeLog(REFERRAL_LOG_FILE, $logPrefix . 'Invalid STATUS "' . $updStatus . '", skipping field.');
        }
    }
    if (isset($params['BONUS_AMOUNT']))    $updateFields['UF_BONUS_AMOUNT']   = (float) $params['BONUS_AMOUNT'];
    if (!empty($params['REFERRAL_URL']))   $updateFields['UF_REFERRAL_URL']   = $params['REFERRAL_URL'];
    if (!empty($params['DATE_CREATE']))    $updateFields['UF_DATE_CREATE']    = $params['DATE_CREATE'];
    if (isset($params['REFERRAL_COUNT'])) $updateFields['UF_REFERRAL_COUNT'] = (int) $params['REFERRAL_COUNT'];

    // [FIX bug4] Проверяем ДО добавления UF_DATE_UPDATE — иначе массив никогда не пустой
    if (empty($updateFields)) {
        $msg = $logPrefix . 'Nothing to update for ID=' . $itemId;
        self::writeLog(REFERRAL_LOG_FILE, $msg);
        return ['result' => false, 'ID' => $itemId, 'error' => $msg];
    }

    $updateFields['UF_DATE_UPDATE'] = date('Y-m-d H:i:s');

    // ------------------------------------------------------------------
    // 4. Обновление через API смарт-процесса
    // ------------------------------------------------------------------
    try {
        $factory = \Bitrix\Crm\Service\Container::getInstance()
                       ->getFactory(CRM_REFERRAL_SMART_PROCESS_ID);

        if (!$factory) {
            throw new \Exception('Smart process factory not found.');
        }

        $item = $factory->getItem($itemId);
        if (!$item) {
            throw new \Exception('Item not found in smart process. ID=' . $itemId);
        }

        $item->setData($updateFields);
        $operation = $factory->getUpdateOperation($item);
        $result = $operation->launch();

        if (!$result->isSuccess()) {
            $errorMsg = implode('; ', $result->getErrorMessages());
            throw new \Exception('Update operation failed: ' . $errorMsg);
        }

        self::writeLog(
            REFERRAL_LOG_FILE,
            $logPrefix . 'SUCCESS. Updated referral ID=' . $itemId
                       . ' Fields: ' . implode(', ', array_keys($updateFields))
        );

        return ['result' => true, 'ID' => $itemId, 'error' => ''];

    } catch (\Exception $e) {
        $msg = $logPrefix . 'Exception: ' . $e->getMessage();
        self::writeLog(REFERRAL_LOG_FILE, $msg);
        return ['result' => false, 'ID' => $itemId, 'error' => $msg];
    }
}


// =============================================================================
// ВСПОМОГАТЕЛЬНЫЙ МЕТОД: findReferralByContactId()
// Ищет запись реферала в смарт-процессе по CONTACT_ID.
// =============================================================================

/**
 * Возвращает ID записи смарт-процесса или 0 если не найдено.
 *
 * @param int $contactId
 * @return int
 */
private static function findReferralByContactId(int $contactId): int
{
    if (!$contactId || !CRM_REFERRAL_SMART_PROCESS_ID) {
        return 0;
    }

    try {
        $factory = \Bitrix\Crm\Service\Container::getInstance()
                       ->getFactory(CRM_REFERRAL_SMART_PROCESS_ID);

        if (!$factory) {
            return 0;
        }

        // [FIX bug2] getItemsFilteredByPermissions требует пользовательскую сессию —
        // в event handler'е сессии нет, метод падает. Используем getItems() напрямую.
        $items = $factory->getItems([
            'filter' => ['=CONTACT_ID' => $contactId],
            'select' => ['ID'],
            'limit'  => 1,
        ]);

        if (!empty($items)) {
            return (int) $items[0]->getId();
        }

    } catch (\Exception $e) {
        self::writeLog(REFERRAL_LOG_FILE, '[findReferralByContactId] Exception: ' . $e->getMessage());
    }

    return 0;
}


// =============================================================================
// ВСПОМОГАТЕЛЬНЫЙ МЕТОД: writeLog()
// Универсальная запись в лог-файл. Тот же паттерн что используется
// в существующих методах HelperRest.php для updateDealsFrom1C.txt и пр.
// Если метод уже есть в классе — этот блок не добавлять повторно.
// =============================================================================

/**
 * Пишет строку лога с таймштампом в указанный файл.
 *
 * @param string $filePath
 * @param string $message
 */
private static function writeLog(string $filePath, string $message): void
{
    $dir = dirname($filePath);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    $line = '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL;
    file_put_contents($filePath, $line, FILE_APPEND | LOCK_EX);
}
