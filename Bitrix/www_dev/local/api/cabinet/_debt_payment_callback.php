<?php

// Подключать из production-обработчика успешной оплаты после того,
// как Bitrix уже пометил заказ оплаченным.

function cabinet_notify_debt_payment_paid(int $orderId): void
{
    if ($orderId <= 0 || BITRIX_INTERNAL_SECRET === '') {
        return;
    }

    $order = \Bitrix\Sale\Order::load($orderId);
    if (!$order) {
        return;
    }

    $payment = null;
    foreach ($order->getPaymentCollection() as $item) {
        $payment = $item;
        break;
    }

    $paidAt = $order->getField('DATE_PAYED');
    if (!$paidAt && $payment) {
        $paidAt = $payment->getField('DATE_PAID');
    }

    $amount = $payment ? $payment->getSum() : $order->getPrice();
    $currency = $payment ? $payment->getField('CURRENCY') : $order->getCurrency();
    $paymentId = $payment ? $payment->getField('PS_INVOICE_ID') : null;

    $payload = json_encode([
        'order_id' => $orderId,
        'payment_id' => $paymentId ?: null,
        'status' => 'paid',
        'paid_at' => $paidAt ? $paidAt->format('c') : date('c'),
        'amount' => number_format((float)$amount, 2, '.', ''),
        'currency' => $currency ?: 'RUB',
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    $http = new \Bitrix\Main\Web\HttpClient([
        'socketTimeout' => 5,
        'streamTimeout' => 5,
    ]);
    $http->setHeader('Content-Type', 'application/json', true);
    $http->setHeader('Accept', 'application/json', true);
    $http->setHeader('X-Internal-Secret', BITRIX_INTERNAL_SECRET, true);

    $http->post(
        rtrim(LARAVEL_INTERNAL_BASE_URL, '/') . '/api/internal/debt-payments/payment-status',
        $payload
    );
}
