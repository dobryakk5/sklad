<?php
/**
 * =============================================================================
 * ФАЗА 3: exchange/index.php — патч под реальную архитектуру файла
 *
 * ВАЖНО: после изучения реального index.php патч полностью переписан.
 * Исходная версия была несовместима по 4 пунктам — см. комментарии ниже.
 *
 * Два блока вставки:
 *   [A] Два новых case в switch внутри start() — рядом с 'addInvoice'
 *   [B] Два новых instance-метода AddReferral() и DeleteReferral() — рядом с AddInvoice()
 * =============================================================================
 */


// =============================================================================
// БЛОК A: вставить в метод start(), в switch($action)
// Точка вставки: после блока case 'addInvoice': ... break;
//
// ИСПРАВЛЕНО vs исходного патча:
//   - убрана повторная проверка ключа внутри case — ключ уже проверен
//     в start() через ($keyRequest == $this->key), второй checkKey() избыточен
//   - вызов через $this-> вместо Exchange:: — класс использует instance-методы
// =============================================================================

/*

            case 'addInvoice':
                $data = $_REQUEST;
                $this->AddInvoice($data);
                break;

            // ↓↓↓ ВСТАВИТЬ ↓↓↓

            case 'addReferral':
                $data = $_REQUEST;
                $result = $this->AddReferral($data);
                echo json_encode($result);
                break;

            case 'deleteReferral':
                $data = $_REQUEST;
                $result = $this->DeleteReferral($data);
                echo json_encode($result);
                break;

            // ↑↑↑ КОНЕЦ ВСТАВКИ ↑↑↑

*/


// =============================================================================
// БЛОК B: вставить в класс Exchange рядом с методом AddInvoice()
//
// ИСПРАВЛЕНО vs исходного патча:
//   1. static → instance методы: весь класс Exchange использует instance-методы
//      (AddInvoice, AddContract и тд). static вызовы несовместимы с $this->
//
//   2. self::writeLog() → Helper::log(): в классе нет writeLog(),
//      логирование везде через Helper::log($file, $data). Добавляем свойство
//      $file_recive_referral по аналогии с $file_recive_invoice.
//
//   3. self::getUserIdByEmail() → $this->getUserByEmail(): метод getUserByEmail()
//      уже есть в классе и возвращает ['USER_ID' => ..., 'PROFILE_ID' => ...].
//      Дублировать логику не нужно, просто используем существующий.
//
//   4. checkKey() убран полностью: ключ проверяется один раз в start(),
//      отдельный статический метод не нужен и не вписывается в архитектуру.
// =============================================================================

// Добавить в список свойств класса Exchange (рядом с $file_recive_invoice):
// public $file_recive_referral = '/logs/resiveReferralFromB24.txt';


/**
 * Создаёт или обновляет запись реферала в HL-блоке ReferralProfile.
 * Вызывается из start() по action=addReferral.
 * Идемпотентен: повторный вызов не создаёт дублей.
 *
 * Ожидаемые поля $data (из $_REQUEST, приходят от CRM через SendRequest):
 *   USER_ID         int     — ID пользователя сайта
 *   REF_CODE        string  — напр. ALFA-XXXXX (обязательно)
 *   STATUS          string  — ACTIVE | INACTIVE | PENDING
 *   BONUS_AMOUNT    float   — сумма бонуса в рублях
 *   REFERRAL_URL    string  — https://alfasklad.ru/?ref=ALFA-XXXXX
 *   DATE_CREATE     string  — Y-m-d (дата генерации в 1С)
 *   REFERRAL_COUNT  int     — кол-во привлечённых клиентов
 *
 * @param array $data
 * @return array ['success' => bool, 'action' => 'add'|'update', 'error' => string]
 */
function AddReferral(array $data): array
{
    $logPrefix = '[Exchange::AddReferral] ';

    // ------------------------------------------------------------------
    // 1. Валидация обязательных полей
    // ------------------------------------------------------------------
    if (empty($data['REF_CODE'])) {
        $msg = $logPrefix . 'Missing REF_CODE';
        Helper::log($this->file_recive_referral, $msg);
        return ['success' => false, 'action' => '', 'error' => $msg];
    }

    // ------------------------------------------------------------------
    // 2. Resolve USER_ID
    //    Сначала из $data, запасной путь — getUserByEmail() который уже есть в классе
    // ------------------------------------------------------------------
    $userId = (int) ($data['USER_ID'] ?? 0);

    if (!$userId && !empty($data['EMAIL'])) {
        // getUserByEmail() уже есть в Exchange, возвращает ['USER_ID'=>..., 'PROFILE_ID'=>...]
        $found = $this->getUserByEmail((string) $data['EMAIL']);
        $userId = $found ? (int) $found['USER_ID'] : 0;
    }

    if (!$userId) {
        $msg = $logPrefix . 'Cannot resolve USER_ID. REF_CODE=' . $data['REF_CODE'];
        Helper::log($this->file_recive_referral, $msg);
        return ['success' => false, 'action' => '', 'error' => $msg];
    }

    // ------------------------------------------------------------------
    // 3. Проверяем что пользователь существует на сайте
    // ------------------------------------------------------------------
    $userCheck = \CUser::GetByID($userId)->Fetch();
    if (!$userCheck) {
        $msg = $logPrefix . 'User not found. USER_ID=' . $userId;
        Helper::log($this->file_recive_referral, $msg);
        return ['success' => false, 'action' => '', 'error' => $msg];
    }

    // ------------------------------------------------------------------
    // 4. Получаем HL-блок ReferralProfile
    // ------------------------------------------------------------------
    if (!defined('HL_REFERRAL_ID') || !HL_REFERRAL_ID) {
        $msg = $logPrefix . 'HL_REFERRAL_ID is not defined. Check init.php.';
        Helper::log($this->file_recive_referral, $msg);
        return ['success' => false, 'action' => '', 'error' => $msg];
    }

    \Bitrix\Main\Loader::includeModule('highloadblock');

    try {
        $hlBlock = \Bitrix\Highloadblock\HighloadBlockTable::getById(HL_REFERRAL_ID)->fetch();
        if (!$hlBlock) {
            throw new \Exception('HL-block not found. HL_REFERRAL_ID=' . HL_REFERRAL_ID);
        }
        $hlEntity    = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlBlock);
        $hlDataClass = $hlEntity->getDataClass();
    } catch (\Exception $e) {
        $msg = $logPrefix . 'HL init error: ' . $e->getMessage();
        Helper::log($this->file_recive_referral, $msg);
        return ['success' => false, 'action' => '', 'error' => $msg];
    }

    // ------------------------------------------------------------------
    // 5. Ищем существующую запись по USER_ID
    // ------------------------------------------------------------------
    $existing = $hlDataClass::getList([
        'filter' => ['=UF_USER_ID' => $userId],
        'select' => ['ID'],
        'limit'  => 1,
    ])->fetch();

    // ------------------------------------------------------------------
    // 6. Подготовка полей
    //    STATUS валидируем явно — HL не знает об enum, запишет что угодно
    //    DATE_CREATE конвертируем в DateTime — поле имеет тип datetime
    // ------------------------------------------------------------------
    $allowedStatuses = ['ACTIVE', 'INACTIVE', 'PENDING'];
    $status = (string) ($data['STATUS'] ?? 'PENDING');
    if (!in_array($status, $allowedStatuses, true)) {
        Helper::log($this->file_recive_referral, $logPrefix . 'Invalid STATUS "' . $status . '", fallback to PENDING');
        $status = 'PENDING';
    }

    $dateCreate = $this->parseDateCreate((string) ($data['DATE_CREATE'] ?? ''));

    $fields = [
        'UF_USER_ID'        => $userId,
        'UF_REF_CODE'       => (string) ($data['REF_CODE']       ?? ''),
        'UF_STATUS'         => $status,
        'UF_BONUS_AMOUNT'   => (float)  ($data['BONUS_AMOUNT']   ?? 0),
        'UF_REFERRAL_URL'   => (string) ($data['REFERRAL_URL']   ?? ''),
        'UF_DATE_CREATE'    => $dateCreate,
        'UF_REFERRAL_COUNT' => (int)    ($data['REFERRAL_COUNT'] ?? 0),
        'UF_DATE_UPDATE'    => new \Bitrix\Main\Type\DateTime(),
    ];

    // ------------------------------------------------------------------
    // 7. Add или Update
    // ------------------------------------------------------------------
    try {
        if ($existing) {
            $result = $hlDataClass::update($existing['ID'], $fields);
            if (!$result->isSuccess()) {
                throw new \Exception('HL update failed: ' . implode('; ', $result->getErrorMessages()));
            }
            Helper::log($this->file_recive_referral,
                $logPrefix . 'UPDATE OK. HL_ID=' . $existing['ID']
                           . ' USER_ID=' . $userId
                           . ' REF_CODE=' . $fields['UF_REF_CODE']
            );
            return ['success' => true, 'action' => 'update', 'error' => ''];

        } else {
            $result = $hlDataClass::add($fields);
            if (!$result->isSuccess()) {
                throw new \Exception('HL add failed: ' . implode('; ', $result->getErrorMessages()));
            }
            Helper::log($this->file_recive_referral,
                $logPrefix . 'ADD OK. HL_ID=' . $result->getId()
                           . ' USER_ID=' . $userId
                           . ' REF_CODE=' . $fields['UF_REF_CODE']
            );
            return ['success' => true, 'action' => 'add', 'error' => ''];
        }

    } catch (\Exception $e) {
        $msg = $logPrefix . 'Exception: ' . $e->getMessage();
        Helper::log($this->file_recive_referral, $msg);
        return ['success' => false, 'action' => '', 'error' => $msg];
    }
}


/**
 * Удаляет запись реферала из HL-блока.
 * Вызывается из start() по action=deleteReferral.
 * Используется когда 1С отзывает реферальный код (клиент заблокирован и тп).
 *
 * Ожидаемые поля $data:
 *   USER_ID   int    — ID пользователя сайта
 *   REF_CODE  string — для верификации (защита от случайного удаления)
 *
 * @param array $data
 * @return array ['success' => bool, 'error' => string]
 */
function DeleteReferral(array $data): array
{
    $logPrefix = '[Exchange::DeleteReferral] ';

    $userId  = (int)    ($data['USER_ID']  ?? 0);
    $refCode = (string) ($data['REF_CODE'] ?? '');

    if (!$userId || !$refCode) {
        $msg = $logPrefix . 'Missing USER_ID or REF_CODE';
        Helper::log($this->file_recive_referral, $msg);
        return ['success' => false, 'error' => $msg];
    }

    if (!defined('HL_REFERRAL_ID') || !HL_REFERRAL_ID) {
        $msg = $logPrefix . 'HL_REFERRAL_ID is not defined.';
        Helper::log($this->file_recive_referral, $msg);
        return ['success' => false, 'error' => $msg];
    }

    \Bitrix\Main\Loader::includeModule('highloadblock');

    try {
        $hlBlock = \Bitrix\Highloadblock\HighloadBlockTable::getById(HL_REFERRAL_ID)->fetch();
        if (!$hlBlock) {
            throw new \Exception('HL-block not found. HL_REFERRAL_ID=' . HL_REFERRAL_ID);
        }
        $hlEntity    = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlBlock);
        $hlDataClass = $hlEntity->getDataClass();

        $existing = $hlDataClass::getList([
            'filter' => [
                '=UF_USER_ID'  => $userId,
                '=UF_REF_CODE' => $refCode,
            ],
            'select' => ['ID'],
            'limit'  => 1,
        ])->fetch();

        if (!$existing) {
            $msg = $logPrefix . 'Record not found. USER_ID=' . $userId . ' REF_CODE=' . $refCode;
            Helper::log($this->file_recive_referral, $msg);
            return ['success' => false, 'error' => $msg];
        }

        $result = $hlDataClass::delete($existing['ID']);
        if (!$result->isSuccess()) {
            throw new \Exception('HL delete failed: ' . implode('; ', $result->getErrorMessages()));
        }

        Helper::log($this->file_recive_referral,
            $logPrefix . 'DELETE OK. HL_ID=' . $existing['ID']
                       . ' USER_ID=' . $userId
                       . ' REF_CODE=' . $refCode
        );
        return ['success' => true, 'error' => ''];

    } catch (\Exception $e) {
        $msg = $logPrefix . 'Exception: ' . $e->getMessage();
        Helper::log($this->file_recive_referral, $msg);
        return ['success' => false, 'error' => $msg];
    }
}


/**
 * Конвертирует строку Y-m-d в объект Bitrix\Main\Type\DateTime.
 * Поле UF_DATE_CREATE имеет тип datetime — plain string вызывает ошибку типа.
 *
 * @param string $dateStr
 * @return \Bitrix\Main\Type\DateTime
 */
private function parseDateCreate(string $dateStr): \Bitrix\Main\Type\DateTime
{
    try {
        if ($dateStr) {
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateStr)) {
                $dateStr .= ' 00:00:00';
            }
            return new \Bitrix\Main\Type\DateTime($dateStr, 'Y-m-d H:i:s');
        }
    } catch (\Exception $e) {
        // fallthrough
    }
    return new \Bitrix\Main\Type\DateTime();
}
