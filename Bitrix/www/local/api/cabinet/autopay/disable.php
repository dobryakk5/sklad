<?php
require_once dirname(__DIR__, 2) . '/_shared.php';

cabinet_require_post();
$data = cabinet_verify_request();

$userId = (int) ($data['user_id'] ?? 0);

if ($userId <= 0) {
    cabinet_error('FORBIDDEN', 'Forbidden', 403);
}

$userRow = \Bitrix\Main\UserTable::getList([
    'filter' => ['=ID' => $userId, '=ACTIVE' => 'Y'],
    'select' => ['ID', 'UF_AUTOPAYMEN_METHOD'],
    'limit' => 1,
])->fetch();

if (! $userRow || empty($userRow['UF_AUTOPAYMEN_METHOD'])) {
    cabinet_error('FORBIDDEN', 'Forbidden', 403);
}

$pmElementId = (int) $userRow['UF_AUTOPAYMEN_METHOD'];

CIBlockElement::SetPropertyValuesEx($pmElementId, PAYMENT_METHODS_IBLOCK_ID, [
    'AUTOPAY' => 'N',
]);

cabinet_success(['success' => true]);
