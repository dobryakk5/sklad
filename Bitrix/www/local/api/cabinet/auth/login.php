<?php
require_once dirname(__DIR__) . '/_shared.php';

cabinet_require_post();
$data = cabinet_verify_request();

$login    = trim($data['login'] ?? '');
$password = $data['password'] ?? '';

// Не логировать пароль нигде ниже этой точки
if ($login === '' || $password === '') {
    cabinet_error('INVALID_CREDENTIALS', 'Неверный логин или пароль', 401);
}

// Проверить существование активного пользователя до попытки логина
// (чтобы вернуть одинаковое сообщение при несуществующем логине и неверном пароле)
$isEmailLogin = filter_var($login, FILTER_VALIDATE_EMAIL) !== false;
$userFilter = [
    '=ACTIVE' => 'Y',
];

if ($isEmailLogin) {
    $userFilter['=EMAIL'] = $login;
} else {
    $userFilter['=LOGIN'] = $login;
}

$userRow = \Bitrix\Main\UserTable::getList([
    'filter' => $userFilter,
    'select' => ['ID', 'LOGIN', 'EMAIL', 'NAME', 'SECOND_NAME', 'LAST_NAME',
                 'PERSONAL_PHONE', 'PERSONAL_MOBILE'],
    'order'  => ['ID' => 'ASC'],
    'limit'  => 2,
])->fetchAll();

if (!$userRow) {
    cabinet_error('INVALID_CREDENTIALS', 'Неверный логин или пароль', 401);
}

if (count($userRow) > 1) {
    cabinet_error('INVALID_CREDENTIALS', 'Неверный логин или пароль', 401);
}

$userRow = $userRow[0];

// Верификация пароля через Bitrix.
// Это server-to-server запрос: Set-Cookie в ответе уйдёт в Laravel и будет проигнорирован.
// Bitrix-сессия на сервере истечёт штатно.
$authLogin = (string)($userRow['LOGIN'] ?: $login);
$tmpUser     = new CUser();
$loginResult = $tmpUser->Login($authLogin, $password, 'N');

if ($loginResult !== true) {
    cabinet_error('INVALID_CREDENTIALS', 'Неверный логин или пароль', 401);
}

$userId = (int)$tmpUser->GetID();

if ($userId !== (int)$userRow['ID']) {
    cabinet_error('INVALID_CREDENTIALS', 'Неверный логин или пароль', 401);
}

cabinet_success([
    'user_id' => $userId,
    'email'   => $userRow['EMAIL'] ?? '',
    'name'    => trim(implode(' ', array_filter([
        trim((string)($userRow['NAME'] ?? '')),
        trim((string)($userRow['SECOND_NAME'] ?? '')),
        trim((string)($userRow['LAST_NAME'] ?? '')),
    ]))),
    'phone'   => $userRow['PERSONAL_PHONE'] ?: ($userRow['PERSONAL_MOBILE'] ?? ''),
]);
