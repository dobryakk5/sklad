<?php
require_once dirname(__DIR__) . '/_shared.php';

cabinet_require_post();
$data = cabinet_verify_request();

$userId     = (int)($data['user_id']     ?? 0);
$contractId = (int)($data['contract_id'] ?? 0);
$amount     = (float)($data['amount']    ?? 0);

if ($userId <= 0 || $contractId <= 0) {
    cabinet_error('FORBIDDEN', 'Forbidden', 403);
}

if ($amount <= 0 || $amount > 500000) {
    cabinet_error('INVALID_AMOUNT', 'Некорректная сумма', 422);
}

// 1. Загрузить договор из БД — данные берём отсюда, не из POST
$rsContract = CIBlockElement::GetList(
    [],
    [
        'IBLOCK_ID'       => CONTRACTS_IBLOCK_ID,
        'ID'              => $contractId,
        'PROPERTY_USER'   => $userId,
        'PROPERTY_STATUS' => CONTRACT_STATUS_ACTIVE_ID,
        'ACTIVE'          => 'Y',
    ],
    false,
    ['nTopCount' => 1],
    ['ID', 'NAME', 'PROPERTY_NUMBER', 'PROPERTY_CONTRACT_GUID',
     'PROPERTY_BOX', 'PROPERTY_USER']
);
$arContract = $rsContract ? $rsContract->GetNext() : null;

if (!$arContract) {
    cabinet_error('CONTRACT_NOT_FOUND', 'Договор не найден', 404);
}

// 2. Дополнительная проверка владельца
if ((int)$arContract['PROPERTY_USER_VALUE'] !== $userId) {
    cabinet_error('FORBIDDEN', 'Forbidden', 403);
}

// 3. Установить контекст пользователя
cabinet_authorize_user($userId);
global $USER;

// 4. Очистить корзину, добавить товар пополнения баланса
$siteId = SITE_ID;
CSaleBasket::DeleteAll(CSaleBasket::GetBasketUserID());

$addResult = CSaleBasket::Add([
    'FUSER_ID'     => CSaleBasket::GetBasketUserID(),
    'SITE_ID'      => $siteId,
    'PRODUCT_ID'   => BALANCE_PRODUCT_ID,
    'QUANTITY'     => 1,
    'CURRENCY'     => 'RUB',
    'LID'          => $siteId,
    'CUSTOM_PRICE' => 'Y',
    'PRICE'        => $amount,
    'NAME'         => 'Пополнение баланса по договору ' . $arContract['PROPERTY_NUMBER_VALUE'],
    'PROPS'        => [
        ['NAME' => 'CONTRACT_ID',     'CODE' => 'CONTRACT_ID',     'VALUE' => (string)$contractId],
        ['NAME' => 'CONTRACT_NUMBER', 'CODE' => 'CONTRACT_NUMBER', 'VALUE' => $arContract['PROPERTY_NUMBER_VALUE'] ?? ''],
        ['NAME' => 'CONTRACT_GUID',   'CODE' => 'CONTRACT_GUID',   'VALUE' => $arContract['PROPERTY_CONTRACT_GUID_VALUE'] ?? ''],
        ['NAME' => 'BOX_ID',          'CODE' => 'BOX_ID',          'VALUE' => (string)($arContract['PROPERTY_BOX_VALUE'] ?? '')],
    ],
]);

if (!$addResult) {
    cabinet_error('BITRIX_ERROR', 'Ошибка создания корзины', 502);
}

// 5. Создать заказ
$basket  = \Bitrix\Sale\Basket::loadItemsForFUser(CSaleBasket::GetBasketUserID(), $siteId);
$orderId = \Api\DomainServices\Orders\OrdersService::createOrder($USER, $basket, ORDER_DEAL_CATEGORY_ID);

if (!$orderId) {
    cabinet_error('BITRIX_ERROR', 'Ошибка создания заказа', 502);
}

cabinet_success([
    'order_id'    => (int)$orderId,
    'payment_url' => PAYMENT_URL_BASE . $orderId,
]);
