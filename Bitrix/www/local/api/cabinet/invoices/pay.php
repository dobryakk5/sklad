<?php
require_once dirname(__DIR__) . '/_shared.php';

cabinet_require_post();
$data = cabinet_verify_request();

$userId = (int) ($data['user_id'] ?? 0);
$invoiceId = (int) ($data['invoice_id'] ?? 0);

if ($userId <= 0 || $invoiceId <= 0) {
    cabinet_error('FORBIDDEN', 'Forbidden', 403);
}

// 1. Загрузить счёт из ИБ 53
$rsInvoice = CIBlockElement::GetList(
    [],
    ['IBLOCK_ID' => INVOICES_IBLOCK_ID, 'ID' => $invoiceId, 'ACTIVE' => 'Y'],
    false,
    ['nTopCount' => 1],
    ['ID', 'NAME', 'PROPERTY_USER', 'PROPERTY_STATUS', 'PROPERTY_CONTRACT_GUID', 'PROPERTY_NUMBER']
);
$arInvoice = $rsInvoice ? $rsInvoice->GetNext() : null;

if (! $arInvoice) {
    cabinet_error('INVOICE_NOT_FOUND', 'Счёт не найден', 404);
}

if ((int) $arInvoice['PROPERTY_USER_VALUE'] !== $userId) {
    cabinet_error('FORBIDDEN', 'Forbidden', 403);
}

$invoiceStatusId = (int) $arInvoice['PROPERTY_STATUS_VALUE'];

if (! in_array($invoiceStatusId, INVOICE_PAYABLE_STATUSES, true)) {
    cabinet_error('INVOICE_NOT_PAYABLE', 'Счёт не может быть оплачен', 422);
}

$priceRow = \Bitrix\Catalog\PriceTable::getList([
    'filter' => ['=PRODUCT_ID' => $invoiceId, '=CATALOG_GROUP_ID' => 1],
    'select' => ['PRICE', 'CURRENCY'],
    'limit' => 1,
])->fetch();

if (! $priceRow || (float) $priceRow['PRICE'] <= 0) {
    cabinet_error('INVOICE_NOT_PAYABLE', 'Цена счёта не определена', 422);
}

$contractGuid = $arInvoice['PROPERTY_CONTRACT_GUID_VALUE'];
$rsContract = CIBlockElement::GetList(
    [],
    ['IBLOCK_ID' => CONTRACTS_IBLOCK_ID, 'PROPERTY_CONTRACT_GUID' => $contractGuid],
    false,
    ['nTopCount' => 1],
    ['ID', 'NAME', 'PROPERTY_NUMBER', 'PROPERTY_CONTRACT_GUID', 'PROPERTY_BOX']
);
$arContract = $rsContract ? $rsContract->GetNext() : null;

cabinet_authorize_user($userId);
global $USER;

$siteId = SITE_ID;
CSaleBasket::DeleteAll(CSaleBasket::GetBasketUserID());

$addResult = CSaleBasket::Add([
    'FUSER_ID' => CSaleBasket::GetBasketUserID(),
    'SITE_ID' => $siteId,
    'PRODUCT_ID' => $invoiceId,
    'QUANTITY' => 1,
    'CURRENCY' => $priceRow['CURRENCY'],
    'LID' => $siteId,
    'NAME' => $arInvoice['NAME'],
    'PROPS' => [
        ['NAME' => 'CONTRACT_ID', 'CODE' => 'CONTRACT_ID', 'VALUE' => (string) ($arContract['ID'] ?? '')],
        ['NAME' => 'CONTRACT_NUMBER', 'CODE' => 'CONTRACT_NUMBER', 'VALUE' => $arContract['PROPERTY_NUMBER_VALUE'] ?? ''],
        ['NAME' => 'CONTRACT_GUID', 'CODE' => 'CONTRACT_GUID', 'VALUE' => $contractGuid],
        ['NAME' => 'BOX_ID', 'CODE' => 'BOX_ID', 'VALUE' => (string) ($arContract['PROPERTY_BOX_VALUE'] ?? '')],
    ],
]);

if (! $addResult) {
    cabinet_error('BITRIX_ERROR', 'Ошибка создания корзины', 502);
}

$basket = \Bitrix\Sale\Basket::loadItemsForFUser(CSaleBasket::GetBasketUserID(), $siteId);
$orderId = \Api\DomainServices\Orders\OrdersService::createOrder($USER, $basket, ORDER_DEAL_CATEGORY_ID);

if (! $orderId) {
    cabinet_error('BITRIX_ERROR', 'Ошибка создания заказа', 502);
}

cabinet_success([
    'order_id' => (int) $orderId,
    'payment_url' => PAYMENT_URL_BASE . $orderId,
]);
