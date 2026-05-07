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
$userRow = \Bitrix\Main\UserTable::getList([
    'filter' => [
        ['LOGIC' => 'OR',
            ['=LOGIN' => $login],
            ['=EMAIL' => $login],
        ],
        '=ACTIVE' => 'Y',
    ],
    'select' => ['ID', 'LOGIN', 'EMAIL', 'NAME', 'LAST_NAME',
                 'PERSONAL_PHONE', 'PERSONAL_MOBILE'],
    'limit'  => 1,
])->fetch();

if (!$userRow) {
    cabinet_error('INVALID_CREDENTIALS', 'Неверный логин или пароль', 401);
}

// Верификация пароля через Bitrix.
// Это server-to-server запрос: Set-Cookie в ответе уйдёт в Laravel и будет проигнорирован.
// Bitrix-сессия на сервере истечёт штатно.
$tmpUser     = new CUser();
$loginResult = $tmpUser->Login($login, $password, 'N');

if ($loginResult !== true) {
    cabinet_error('INVALID_CREDENTIALS', 'Неверный логин или пароль', 401);
}

$userId = (int)$tmpUser->GetID();

cabinet_success([
    'user_id' => $userId,
    'email'   => $userRow['EMAIL'] ?? '',
    'name'    => trim(($userRow['NAME'] ?? '') . ' ' . ($userRow['LAST_NAME'] ?? '')),
    'phone'   => $userRow['PERSONAL_PHONE'] ?: ($userRow['PERSONAL_MOBILE'] ?? ''),
]);
