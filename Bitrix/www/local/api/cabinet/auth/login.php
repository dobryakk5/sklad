<?php

require_once dirname(__DIR__) . '/_shared.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    cabinet_error(403);
}

$data = cabinet_verify_request();
$login = trim($data['login'] ?? '');
$password = $data['password'] ?? '';

if (! $login || ! $password) {
    cabinet_error(401, 'INVALID_CREDENTIALS');
}

$userRow = \Bitrix\Main\UserTable::getList([
    'filter' => [
        [
            'LOGIC' => 'OR',
            ['=LOGIN' => $login],
            ['=EMAIL' => $login],
        ],
        '=ACTIVE' => 'Y',
    ],
    'select' => ['ID', 'LOGIN', 'EMAIL', 'NAME', 'SECOND_NAME', 'LAST_NAME', 'PERSONAL_PHONE', 'PERSONAL_MOBILE'],
    'limit' => 1,
])->fetch();

if (! $userRow) {
    cabinet_error(401, 'INVALID_CREDENTIALS');
}

$tmpUser = new CUser();
$authLogin = (string) ($userRow['LOGIN'] ?: $login);
if ($tmpUser->Login($authLogin, $password, 'N') !== true) {
    cabinet_error(401, 'INVALID_CREDENTIALS');
}

$authorizedUserId = (int) $tmpUser->GetID();
$authorizedUserRow = \Bitrix\Main\UserTable::getList([
    'filter' => [
        '=ID' => $authorizedUserId,
        '=ACTIVE' => 'Y',
    ],
    'select' => ['ID', 'LOGIN', 'EMAIL', 'NAME', 'SECOND_NAME', 'LAST_NAME', 'PERSONAL_PHONE', 'PERSONAL_MOBILE'],
    'limit' => 1,
])->fetch();

if (! $authorizedUserRow) {
    cabinet_error(401, 'INVALID_CREDENTIALS');
}

cabinet_success([
    'user_id' => $authorizedUserId,
    'email' => $authorizedUserRow['EMAIL'] ?? '',
    'name' => trim(implode(' ', array_filter([
        trim((string) ($authorizedUserRow['NAME'] ?? '')),
        trim((string) ($authorizedUserRow['SECOND_NAME'] ?? '')),
        trim((string) ($authorizedUserRow['LAST_NAME'] ?? '')),
    ]))),
    'phone' => $authorizedUserRow['PERSONAL_PHONE'] ?: ($authorizedUserRow['PERSONAL_MOBILE'] ?? ''),
]);
