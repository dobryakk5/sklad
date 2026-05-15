<?php
/**
 * =============================================================================
 * ФАЗА 2: Добавить в Rest.php
 * Файл: local/php_interface/classes/enum/Rest.php
 *
 * Три блока вставки:
 *   A) OnRestServiceBuildDescription() — регистрация новых REST-методов
 *   B) Методы-обработчики REST: addReferralFrom1C(), updateReferralFrom1C()
 *   C) OnAfterReferralAddHandler() — event handler CRM → сайт
 *
 * Каждый блок помечен точкой вставки относительно существующего кода.
 * =============================================================================
 */


// =============================================================================
// БЛОК A: OnRestServiceBuildDescription()
//
// Точка вставки: найти в Rest.php метод OnRestServiceBuildDescription()
// и добавить два новых метода В КОНЕЦ массива, рядом с:
//   'enum.getContract'    => [...],
//   'enum.updateContract' => [...],
// =============================================================================

/*
    // --- вставить внутрь возвращаемого массива OnRestServiceBuildDescription() ---

    'enum.addReferralFrom1C' => [
        'callback' => [static::class, 'RestAddReferralFrom1C'],
        'options'  => [],
    ],
    'enum.updateReferralFrom1C' => [
        'callback' => [static::class, 'RestUpdateReferralFrom1C'],
        'options'  => [],
    ],

    // --- конец вставки ---
*/


// =============================================================================
// БЛОК B: REST-обёртки для вызова из 1С
//
// Точка вставки: добавить рядом с updateOrderDeal() / getContract() в том
// же классе Rest. Это тонкие обёртки — вся логика в HelperRest.php.
// =============================================================================

/**
 * REST-метод enum.addReferralFrom1C
 * 1С вызывает: POST /rest/1/[token]/enum.addReferralFrom1C
 *
 * Входные параметры (query или POST body):
 *   CONTACT_ID     int     — ID контакта в CRM (обязательно)
 *   REF_CODE       string  — реферальный код ALFA-XXXXX (обязательно)
 *   STATUS         string  — ACTIVE | INACTIVE | PENDING (обязательно)
 *   BONUS_AMOUNT   float   — сумма бонуса в рублях
 *   REFERRAL_URL   string  — https://alfasklad.ru/?ref=ALFA-XXXXX
 *   DATE_CREATE    string  — дата генерации Y-m-d
 *   REFERRAL_COUNT int     — кол-во привлечённых клиентов
 *
 * @param array  $params  — параметры REST-запроса от Bitrix
 * @param string $start   — курсор пагинации (не используется, для совместимости)
 * @param \CRestServer $server
 * @return array
 */
public static function RestAddReferralFrom1C(array $params, string $start, \CRestServer $server): array
{
    self::writeRestLog('addReferralsFrom1C.txt', '[RestAddReferralFrom1C] Incoming params: ' . json_encode($params, JSON_UNESCAPED_UNICODE));

    // Очищаем входные данные (по аналогии с updateOrderDeal)
    $cleanParams = self::sanitizeReferralParams($params);

    // Делегируем в HelperRest
    $result = HelperRest::addReferralFrom1C($cleanParams);

    self::writeRestLog(
        'addReferralsFrom1C.txt',
        '[RestAddReferralFrom1C] Result: ' . json_encode($result, JSON_UNESCAPED_UNICODE)
    );

    return $result;
}

/**
 * REST-метод enum.updateReferralFrom1C
 * 1С вызывает: POST /rest/1/[token]/enum.updateReferralFrom1C
 *
 * Входные параметры — те же, что у addReferralFrom1C.
 * Дополнительно можно передать CRM_REFERRAL_ID (int) для точного поиска.
 *
 * @param array  $params
 * @param string $start
 * @param \CRestServer $server
 * @return array
 */
public static function RestUpdateReferralFrom1C(array $params, string $start, \CRestServer $server): array
{
    self::writeRestLog('addReferralsFrom1C.txt', '[RestUpdateReferralFrom1C] Incoming params: ' . json_encode($params, JSON_UNESCAPED_UNICODE));

    $cleanParams = self::sanitizeReferralParams($params);

    $result = HelperRest::updateReferralFrom1C($cleanParams);

    self::writeRestLog(
        'addReferralsFrom1C.txt',
        '[RestUpdateReferralFrom1C] Result: ' . json_encode($result, JSON_UNESCAPED_UNICODE)
    );

    return $result;
}

/**
 * Очищает и типизирует входные параметры от 1С.
 * Вынесено отдельно чтобы не дублировать в add и update.
 *
 * @param array $raw
 * @return array
 */
private static function sanitizeReferralParams(array $raw): array
{
    return [
        'CONTACT_ID'      => isset($raw['CONTACT_ID'])      ? (int)    $raw['CONTACT_ID']      : 0,
        'REF_CODE'        => isset($raw['REF_CODE'])        ? (string) trim($raw['REF_CODE'])   : '',
        'STATUS'          => isset($raw['STATUS'])          ? (string) trim($raw['STATUS'])     : '',
        'BONUS_AMOUNT'    => isset($raw['BONUS_AMOUNT'])    ? (float)  $raw['BONUS_AMOUNT']     : 0.0,
        'REFERRAL_URL'    => isset($raw['REFERRAL_URL'])    ? (string) trim($raw['REFERRAL_URL']): '',
        'DATE_CREATE'     => isset($raw['DATE_CREATE'])     ? (string) trim($raw['DATE_CREATE']) : date('Y-m-d'),
        'REFERRAL_COUNT'  => isset($raw['REFERRAL_COUNT'])  ? (int)    $raw['REFERRAL_COUNT']   : 0,
        'CRM_REFERRAL_ID' => isset($raw['CRM_REFERRAL_ID']) ? (int)    $raw['CRM_REFERRAL_ID']  : 0,
    ];
}


// =============================================================================
// БЛОК C: OnAfterReferralAddHandler()
//
// Точка вставки: добавить в конец класса Rest, рядом с
// OnAfterCrmInvoiceAddHandler(). Этот handler вешается на событие
// создания/обновления item-а смарт-процесса «Реферал».
//
// РЕГИСТРАЦИЯ ОБРАБОТЧИКА (добавить в init.php или в OnRestServiceBuildDescription):
//
//   \Bitrix\Main\EventManager::getInstance()->addEventHandler(
//       'crm',
//       'onCrmDynamicItemAdd',      // событие создания item смарт-процесса
//       [Rest::class, 'OnAfterReferralAddHandler']
//   );
//   \Bitrix\Main\EventManager::getInstance()->addEventHandler(
//       'crm',
//       'onCrmDynamicItemUpdate',   // событие обновления item смарт-процесса
//       [Rest::class, 'OnAfterReferralUpdateHandler']
//   );
//   \Bitrix\Main\EventManager::getInstance()->addEventHandler(
//       'crm',
//       'onCrmDynamicItemDelete',   // событие удаления item смарт-процесса
//       [Rest::class, 'OnAfterReferralDeleteHandler']
//   );
//
// =============================================================================

/**
 * Срабатывает после удаления item-а смарт-процесса «Реферал».
 * Отправляет action=deleteReferral на сайт — Exchange::DeleteReferral() удаляет запись из HL-блока.
 *
 * РЕГИСТРАЦИЯ (добавить в init.php рядом с двумя другими handlers):
 *
 *   \Bitrix\Main\EventManager::getInstance()->addEventHandler(
 *       'crm',
 *       'onCrmDynamicItemDelete',
 *       [\Rest::class, 'OnAfterReferralDeleteHandler']
 *   );
 *
 * @param \Bitrix\Main\Event $event
 */
public static function OnAfterReferralDeleteHandler(\Bitrix\Main\Event $event): void
{
    $item = $event->getParameter('item');

    if (!$item || (int) $item->getEntityTypeId() !== CRM_REFERRAL_SMART_PROCESS_ID) {
        return;
    }

    $contactId = (int) $item->getContactId();
    $userId    = self::getSiteUserIdByContactId($contactId);
    $refCode   = (string) $item->get('UF_REF_CODE');

    self::writeRestLog(
        'addReferralsFrom1C.txt',
        '[OnAfterReferralDeleteHandler] Triggered. CRM_ITEM_ID=' . $item->getId()
            . ' CONTACT_ID=' . $contactId
            . ' USER_ID=' . $userId
            . ' REF_CODE=' . $refCode
    );

    if (!$userId || !$refCode) {
        self::writeRestLog(
            'addReferralsFrom1C.txt',
            '[OnAfterReferralDeleteHandler] SKIP: USER_ID or REF_CODE is empty, nothing to send.'
        );
        return;
    }

    $payload = [
        'action'   => 'deleteReferral',
        'key'      => self::GetKey(),
        'trace_id' => uniqid('ref_', true),
        'USER_ID'  => $userId,
        'REF_CODE' => $refCode,
    ];

    self::sendReferralToSite($payload, 'OnAfterReferralDeleteHandler');
}

/**
 * Срабатывает после создания нового item-а смарт-процесса «Реферал».
 * Формирует payload и отправляет action=addReferral на сайт.
 * По аналогии с OnAfterCrmInvoiceAddHandler → action=addInvoice.
 *
 * @param \Bitrix\Main\Event $event
 */
public static function OnAfterReferralAddHandler(\Bitrix\Main\Event $event): void
{
    $item = $event->getParameter('item');

    // Проверяем что это именно наш смарт-процесс, а не чужой
    if (!$item || (int) $item->getEntityTypeId() !== CRM_REFERRAL_SMART_PROCESS_ID) {
        return;
    }

    self::writeRestLog(
        'addReferralsFrom1C.txt',
        '[OnAfterReferralAddHandler] Triggered for item ID=' . $item->getId()
    );

    $payload = self::buildReferralPayload($item, 'addReferral');

    self::sendReferralToSite($payload, 'OnAfterReferralAddHandler');
}

/**
 * Срабатывает после обновления item-а смарт-процесса «Реферал».
 * Отправляет тот же action=addReferral — сайт сам определит Add или Update
 * по наличию записи в HL-блоке (идемпотентная логика на стороне Exchange::AddReferral).
 *
 * @param \Bitrix\Main\Event $event
 */
public static function OnAfterReferralUpdateHandler(\Bitrix\Main\Event $event): void
{
    $item = $event->getParameter('item');

    if (!$item || (int) $item->getEntityTypeId() !== CRM_REFERRAL_SMART_PROCESS_ID) {
        return;
    }

    self::writeRestLog(
        'addReferralsFrom1C.txt',
        '[OnAfterReferralUpdateHandler] Triggered for item ID=' . $item->getId()
    );

    $payload = self::buildReferralPayload($item, 'addReferral'); // action тот же — сайт идемпотентен

    self::sendReferralToSite($payload, 'OnAfterReferralUpdateHandler');
}

/**
 * Собирает payload для отправки на сайт из item-а смарт-процесса.
 * Маппинг: UF_* поля CRM → ключи, которые ждёт exchange/index.php.
 *
 * @param \Bitrix\Crm\Item $item
 * @param string           $action
 * @return array
 */
private static function buildReferralPayload(\Bitrix\Crm\Item $item, string $action): array
{
    // Получаем USER_ID сайта через контакт CRM
    $contactId = (int) $item->getContactId();
    $userId    = self::getSiteUserIdByContactId($contactId);

    // [FIX bug6] Явно логируем если пользователь не найден — иначе падение молча в AddReferral
    if (!$userId) {
        self::writeRestLog(
            'addReferralsFrom1C.txt',
            '[buildReferralPayload] WARNING: could not resolve site USER_ID for CONTACT_ID=' . $contactId
            . ' CRM_ITEM_ID=' . $item->getId()
        );
    }

    // Используем self::GetKey() — тот же механизм что у всех других handler'ов в Rest.php
    return [
        'action'          => $action,
        'key'             => self::GetKey(),
        'trace_id'        => uniqid('ref_', true),
        'CRM_ITEM_ID'     => $item->getId(),
        'USER_ID'         => $userId,
        'CONTACT_ID'      => $contactId,
        'REF_CODE'        => (string) $item->get('UF_REF_CODE'),
        'STATUS'          => (string) $item->get('UF_STATUS'),
        'BONUS_AMOUNT'    => (float)  $item->get('UF_BONUS_AMOUNT'),
        'REFERRAL_URL'    => (string) $item->get('UF_REFERRAL_URL'),
        // UF_DATE_CREATE хранится как Bitrix\Main\Type\DateTime — форматируем явно в Y-m-d
        'DATE_CREATE'     => self::formatItemDate($item->get('UF_DATE_CREATE')),
        'REFERRAL_COUNT'  => (int)    $item->get('UF_REFERRAL_COUNT'),
    ];
}

/**
 * Отправляет payload на сайт через HelperRest::SendRequest().
 * Логирует результат.
 *
 * @param array  $payload
 * @param string $callerName  — для читаемых логов
 */
private static function sendReferralToSite(array $payload, string $callerName): void
{
    $logPrefix = '[' . $callerName . '→sendReferralToSite] ';

    // [FIX bug5] && → || : пропускаем если ЛЮБОЙ из ключевых идентификаторов пустой
    if (empty($payload['USER_ID']) || empty($payload['REF_CODE'])) {
        self::writeRestLog(
            'addReferralsFrom1C.txt',
            $logPrefix . 'SKIP: USER_ID or REF_CODE is empty, nothing to send.'
        );
        return;
    }

    self::writeRestLog(
        'addReferralsFrom1C.txt',
        $logPrefix . 'Sending payload to site: ' . json_encode($payload, JSON_UNESCAPED_UNICODE)
    );

    // SendRequest() — универсальный HTTP POST из HelperRest, уже используется для счетов
    $response = HelperRest::SendRequest($payload, self::$urlToSite);

    self::writeRestLog(
        'addReferralsFrom1C.txt',
        $logPrefix . 'Site response: ' . json_encode($response, JSON_UNESCAPED_UNICODE)
    );
}

/**
 * Безопасно форматирует дату из поля смарт-процесса в строку Y-m-d.
 * UF_DATE_CREATE возвращает Bitrix\Main\Type\DateTime, не строку.
 * Прямой каст (string) даст локализованный формат — parseDateCreate его не распознает.
 *
 * @param mixed $value
 * @return string  Y-m-d или пустая строка
 */
private static function formatItemDate($value): string
{
    if ($value instanceof \Bitrix\Main\Type\Date) {
        return $value->format('Y-m-d');
    }
    if (is_string($value) && $value) {
        return $value;
    }
    return '';
}

/**
 * Получает ID пользователя сайта по ID контакта CRM.
 * Ищет через поле EMAIL контакта → пользователь сайта с тем же email.
 * По аналогии с тем, как AddContact() на сайте матчит пользователей.
 *
 * @param int $contactId
 * @return int  — 0 если не найден
 */
private static function getSiteUserIdByContactId(int $contactId): int
{
    if (!$contactId) {
        return 0;
    }

    // Достаём email из CRM-контакта
    $contact = \CCrmContact::GetByID($contactId, false);
    if (empty($contact)) {
        return 0;
    }

    // Email хранится в многозначном поле FM (Field Multivalue)
    $emails = \CCrmFieldMulti::GetEntityFields('CONTACT', $contactId, 'EMAIL', true, false);
    $email  = '';
    foreach ($emails as $em) {
        if (!empty($em['VALUE'])) {
            $email = trim($em['VALUE']);
            break;
        }
    }

    if (!$email) {
        return 0;
    }

    // Ищем пользователя сайта по email
    $user = \CUser::GetList(
        ($by = 'id'), ($ord = 'asc'),
        ['EMAIL' => $email, 'ACTIVE' => 'Y'],
        ['SELECT' => ['ID'], 'NAV_PARAMS' => ['nTopCount' => 1]]
    )->Fetch();

    return $user ? (int) $user['ID'] : 0;
}

/**
 * Пишет строку в лог внутри /local/logs/.
 * Если writeRestLog уже есть в классе Rest — этот метод не добавлять.
 *
 * @param string $fileName  — только имя файла, напр. 'addReferralsFrom1C.txt'
 * @param string $message
 */
private static function writeRestLog(string $fileName, string $message): void
{
    $dir  = $_SERVER['DOCUMENT_ROOT'] . '/local/logs/';
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    $line = '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL;
    file_put_contents($dir . $fileName, $line, FILE_APPEND | LOCK_EX);
}
