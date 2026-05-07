<?php
require_once dirname(__DIR__, 2) . '/_shared.php';

cabinet_require_post();
$data = cabinet_verify_request();

$userId = (int)($data['user_id'] ?? 0);
if ($userId <= 0) {
    cabinet_error('FORBIDDEN', 'Forbidden', 403);
}

// TODO: Адаптировать из существующего autopay/ajax.php (блок: case 'plug_autopay')
// Путь: /local/templates/aspro-priority/components/bitrix/news.list/autopay/ajax.php
//
// Логика:
// 1. Создать pending-элемент ИБ 69 с AUTOPAY=Y для $userId
// 2. Запросить confirmation URL у ЮKassa
// 3. Вернуть confirmation_url
//
// Ожидаемый ответ:
// cabinet_success(['confirmation_url' => 'https://yookassa.ru/payments/...']);

cabinet_error('BITRIX_ERROR', 'Не реализовано — адаптировать из autopay/ajax.php', 501);
