<?php
/**
 * =============================================================================
 * ФАЗА 4: HL-блок ReferralProfile
 * Файл: local/php_interface/migrations/hl_referral_profile_install.php
 *
 * Запускать ОДИН РАЗ через браузер или CLI:
 *   php -f local/php_interface/migrations/hl_referral_profile_install.php
 *
 * Скрипт идемпотентен: повторный запуск ничего не сломает.
 * После успешного выполнения — скопировать выведенный HL_REFERRAL_ID
 * и вставить в константу в init.php.
 * =============================================================================
 */

// --- bootstrap Bitrix ---
$_SERVER['DOCUMENT_ROOT'] = dirname(__DIR__, 3); // поправить путь если нужно
define('NO_KEEP_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

use Bitrix\Main\Loader;
use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\UserField\Types\StringType;
use Bitrix\Main\UserField\Types\IntegerType;
use Bitrix\Main\UserField\Types\FloatType;
use Bitrix\Main\UserField\Types\DateTimeType;

Loader::includeModule('highloadblock');

// =============================================================================
// ШАГ 1: Создание HL-блока
// =============================================================================

$hlBlockName  = 'ReferralProfile';   // Название объекта (латиница, без пробелов)
$hlTableName  = 'b_hl_referral_profile'; // Название таблицы в БД

// Проверяем — вдруг уже создан
$existing = HighloadBlockTable::getList([
    'filter' => ['=NAME' => $hlBlockName],
    'select' => ['ID', 'NAME', 'TABLE_NAME'],
    'limit'  => 1,
])->fetch();

if ($existing) {
    $hlId = (int) $existing['ID'];
    echo "[INFO] HL-block '{$hlBlockName}' already exists. ID={$hlId}" . PHP_EOL;
} else {
    $result = HighloadBlockTable::add([
        'NAME'       => $hlBlockName,
        'TABLE_NAME' => $hlTableName,
    ]);

    if (!$result->isSuccess()) {
        echo '[ERROR] Failed to create HL-block: ' . implode('; ', $result->getErrorMessages()) . PHP_EOL;
        exit(1);
    }

    $hlId = $result->getId();
    echo "[OK] HL-block created. ID={$hlId}" . PHP_EOL;
}


// =============================================================================
// ШАГ 2: Создание пользовательских полей
//
// Структура каждого поля:
//   ENTITY_ID   — 'HLBLOCK_' . $hlId
//   FIELD_NAME  — UF_*
//   USER_TYPE_ID— тип из Bitrix
//   MANDATORY   — Y / N
//   EDIT_FORM_LABEL / LIST_COLUMN_LABEL — подписи в админке
// =============================================================================

$entityId = 'HLBLOCK_' . $hlId;

$fields = [
    // UF_USER_ID — уникальный, каждый пользователь один раз
    [
        'FIELD_NAME'        => 'UF_USER_ID',
        'USER_TYPE_ID'      => 'integer',
        'MANDATORY'         => 'Y',
        'EDIT_FORM_LABEL'   => ['ru' => 'ID пользователя сайта', 'en' => 'Site User ID'],
        'LIST_COLUMN_LABEL' => ['ru' => 'User ID',               'en' => 'User ID'],
        'SETTINGS'          => [],
    ],
    // UF_REF_CODE — сам реферальный код ALFA-XXXXX
    [
        'FIELD_NAME'        => 'UF_REF_CODE',
        'USER_TYPE_ID'      => 'string',
        'MANDATORY'         => 'Y',
        'EDIT_FORM_LABEL'   => ['ru' => 'Реферальный код',  'en' => 'Referral Code'],
        'LIST_COLUMN_LABEL' => ['ru' => 'REF_CODE',          'en' => 'REF_CODE'],
        'SETTINGS'          => ['SIZE' => 32, 'MAX_LENGTH' => 32],
    ],
    // UF_STATUS — ACTIVE | INACTIVE | PENDING
    [
        'FIELD_NAME'        => 'UF_STATUS',
        'USER_TYPE_ID'      => 'string',
        'MANDATORY'         => 'Y',
        'EDIT_FORM_LABEL'   => ['ru' => 'Статус',   'en' => 'Status'],
        'LIST_COLUMN_LABEL' => ['ru' => 'Статус',   'en' => 'Status'],
        'SETTINGS'          => ['SIZE' => 16, 'MAX_LENGTH' => 16, 'DEFAULT_VALUE' => 'PENDING'],
    ],
    // UF_BONUS_AMOUNT — сумма начисленного бонуса (из 1С)
    [
        'FIELD_NAME'        => 'UF_BONUS_AMOUNT',
        'USER_TYPE_ID'      => 'double',
        'MANDATORY'         => 'N',
        'EDIT_FORM_LABEL'   => ['ru' => 'Сумма бонуса (руб.)', 'en' => 'Bonus Amount'],
        'LIST_COLUMN_LABEL' => ['ru' => 'Бонус',               'en' => 'Bonus'],
        'SETTINGS'          => ['DEFAULT_VALUE' => 0],
    ],
    // UF_REFERRAL_URL — полная ссылка для приглашения
    [
        'FIELD_NAME'        => 'UF_REFERRAL_URL',
        'USER_TYPE_ID'      => 'string',
        'MANDATORY'         => 'N',
        'EDIT_FORM_LABEL'   => ['ru' => 'Реферальная ссылка', 'en' => 'Referral URL'],
        'LIST_COLUMN_LABEL' => ['ru' => 'Ссылка',             'en' => 'URL'],
        'SETTINGS'          => ['SIZE' => 255, 'MAX_LENGTH' => 255],
    ],
    // UF_DATE_CREATE — дата генерации кода в 1С
    [
        'FIELD_NAME'        => 'UF_DATE_CREATE',
        'USER_TYPE_ID'      => 'datetime',
        'MANDATORY'         => 'N',
        'EDIT_FORM_LABEL'   => ['ru' => 'Дата создания (из 1С)', 'en' => 'Date Created'],
        'LIST_COLUMN_LABEL' => ['ru' => 'Создан',                'en' => 'Created'],
        'SETTINGS'          => [],
    ],
    // UF_REFERRAL_COUNT — кол-во привлечённых клиентов (из 1С)
    [
        'FIELD_NAME'        => 'UF_REFERRAL_COUNT',
        'USER_TYPE_ID'      => 'integer',
        'MANDATORY'         => 'N',
        'EDIT_FORM_LABEL'   => ['ru' => 'Кол-во рефералов', 'en' => 'Referral Count'],
        'LIST_COLUMN_LABEL' => ['ru' => 'Рефералов',         'en' => 'Referrals'],
        'SETTINGS'          => ['DEFAULT_VALUE' => 0],
    ],
    // UF_DATE_UPDATE — штамп последнего обновления из CRM
    [
        'FIELD_NAME'        => 'UF_DATE_UPDATE',
        'USER_TYPE_ID'      => 'datetime',
        'MANDATORY'         => 'N',
        'EDIT_FORM_LABEL'   => ['ru' => 'Дата обновления', 'en' => 'Date Updated'],
        'LIST_COLUMN_LABEL' => ['ru' => 'Обновлён',         'en' => 'Updated'],
        'SETTINGS'          => [],
    ],
];

$oUserField = new CUserTypeEntity();

foreach ($fields as $fieldDef) {
    // Проверяем — поле уже есть?
    $existingField = CUserTypeEntity::GetList(
        [],
        ['ENTITY_ID' => $entityId, 'FIELD_NAME' => $fieldDef['FIELD_NAME']]
    )->Fetch();

    if ($existingField) {
        echo "[SKIP] Field {$fieldDef['FIELD_NAME']} already exists." . PHP_EOL;
        continue;
    }

    $fieldId = $oUserField->Add(array_merge(
        ['ENTITY_ID' => $entityId],
        $fieldDef
    ));

    if ($fieldId) {
        echo "[OK] Field {$fieldDef['FIELD_NAME']} created. ID={$fieldId}" . PHP_EOL;
    } else {
        echo "[ERROR] Failed to create field {$fieldDef['FIELD_NAME']}." . PHP_EOL;
    }
}


// =============================================================================
// ШАГ 3: Уникальный индекс по UF_USER_ID
//
// Bitrix не создаёт уникальные индексы через API — делаем через SQL напрямую.
// Один пользователь = одна запись. Без индекса возможны дубли при гонке.
// =============================================================================

global $DB;

$indexName  = 'ux_hl_referral_user_id';
$checkIndex = $DB->Query(
    "SELECT COUNT(*) as CNT FROM information_schema.statistics
     WHERE table_schema = DATABASE()
       AND table_name   = '{$hlTableName}'
       AND index_name   = '{$indexName}'"
)->Fetch();

if ((int) $checkIndex['CNT'] === 0) {
    $addIndex = $DB->Query(
        "ALTER TABLE `{$hlTableName}`
         ADD UNIQUE INDEX `{$indexName}` (`UF_USER_ID`)"
    );
    if ($addIndex) {
        echo "[OK] Unique index '{$indexName}' on UF_USER_ID created." . PHP_EOL;
    } else {
        echo "[ERROR] Failed to create unique index. Error: " . $DB->GetErrorMessage() . PHP_EOL;
    }
} else {
    echo "[SKIP] Unique index '{$indexName}' already exists." . PHP_EOL;
}


// =============================================================================
// ШАГ 4: Обычный индекс по UF_REF_CODE
//
// Нужен для быстрого поиска по коду при переходе по реферальной ссылке
// и при проверке дублей.
// =============================================================================

$refIndexName  = 'idx_hl_referral_ref_code';
$checkRefIndex = $DB->Query(
    "SELECT COUNT(*) as CNT FROM information_schema.statistics
     WHERE table_schema = DATABASE()
       AND table_name   = '{$hlTableName}'
       AND index_name   = '{$refIndexName}'"
)->Fetch();

if ((int) $checkRefIndex['CNT'] === 0) {
    $addRefIndex = $DB->Query(
        "ALTER TABLE `{$hlTableName}`
         ADD INDEX `{$refIndexName}` (`UF_REF_CODE`)"
    );
    if ($addRefIndex) {
        echo "[OK] Index '{$refIndexName}' on UF_REF_CODE created." . PHP_EOL;
    } else {
        echo "[ERROR] Failed to create index on UF_REF_CODE." . PHP_EOL;
    }
} else {
    echo "[SKIP] Index '{$refIndexName}' already exists." . PHP_EOL;
}


// =============================================================================
// ШАГ 5: Итог — выводим константу для вставки в init.php
// =============================================================================

echo PHP_EOL;
echo "==============================================================================" . PHP_EOL;
echo "ГОТОВО. Вставьте в local/php_interface/init.php:" . PHP_EOL;
echo "==============================================================================" . PHP_EOL;
echo "define('HL_REFERRAL_ID', {$hlId});" . PHP_EOL;
echo PHP_EOL;
echo "А также убедитесь что в Rest.php задана константа:" . PHP_EOL;
echo "define('CRM_REFERRAL_SMART_PROCESS_ID', <ID смарт-процесса из CRM>);" . PHP_EOL;
echo "==============================================================================" . PHP_EOL;


// =============================================================================
// СПРАВКА: что добавить в init.php после запуска миграции
// =============================================================================
/*

// --- local/php_interface/init.php ---

// ID HL-блока ReferralProfile (получить из миграции hl_referral_profile_install.php)
define('HL_REFERRAL_ID', XX); // <-- вставить реальный ID

// ID смарт-процесса «Реферал» в CRM Bitrix24
define('CRM_REFERRAL_SMART_PROCESS_ID', XX); // <-- вставить реальный ID

// Регистрация event-обработчиков CRM → сайт (Фаза 2)
\Bitrix\Main\EventManager::getInstance()->addEventHandler(
    'crm',
    'onCrmDynamicItemAdd',
    [\Rest::class, 'OnAfterReferralAddHandler']
);
\Bitrix\Main\EventManager::getInstance()->addEventHandler(
    'crm',
    'onCrmDynamicItemUpdate',
    [\Rest::class, 'OnAfterReferralUpdateHandler']
);
\Bitrix\Main\EventManager::getInstance()->addEventHandler(
    'crm',
    'onCrmDynamicItemDelete',
    [\Rest::class, 'OnAfterReferralDeleteHandler']
);

*/
