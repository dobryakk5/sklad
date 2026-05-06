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
    'select' => ['ID', 'LOGIN', 'EMAIL', 'NAME', 'LAST_NAME', 'PERSONAL_PHONE', 'PERSONAL_MOBILE'],
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

cabinet_success([
    'user_id' => (int) $tmpUser->GetID(),
    'email' => $userRow['EMAIL'] ?? '',
    'name' => trim(($userRow['NAME'] ?? '') . ' ' . ($userRow['LAST_NAME'] ?? '')),
    'phone' => $userRow['PERSONAL_PHONE'] ?: ($userRow['PERSONAL_MOBILE'] ?? ''),
]);
