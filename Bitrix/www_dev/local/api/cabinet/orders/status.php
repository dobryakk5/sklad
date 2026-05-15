<?php

require_once dirname(__DIR__) . '/_shared.php';

cabinet_require_post();
$data = cabinet_verify_request();

$orderId = (int)($data['order_id'] ?? 0);

if ($orderId <= 0) {
    cabinet_error('FORBIDDEN', 'Forbidden', 403);
}

$order = \Bitrix\Sale\Order::load($orderId);

if (!$order) {
    cabinet_error('ORDER_NOT_FOUND', 'Заказ не найден', 404);
}

$payment = null;
$paymentCollection = $order->getPaymentCollection();
foreach ($paymentCollection as $item) {
    $payment = $item;
    break;
}

$paidAt = $order->getField('DATE_PAYED');
if (!$paidAt && $payment) {
    $paidAt = $payment->getField('DATE_PAID');
}

$amount = $payment ? $payment->getSum() : $order->getPrice();
$currency = $payment ? $payment->getField('CURRENCY') : $order->getCurrency();

cabinet_success([
    'order_id' => $orderId,
    'payed' => $order->isPaid(),
    'status' => $order->isPaid() ? 'paid' : 'pending',
    'paid_at' => $paidAt ? $paidAt->format('c') : null,
    'amount' => number_format((float)$amount, 2, '.', ''),
    'currency' => $currency ?: 'RUB',
]);
