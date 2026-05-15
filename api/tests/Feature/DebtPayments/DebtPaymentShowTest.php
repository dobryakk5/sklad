<?php

use App\Models\DebtPaymentLink;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns ready state with debt payment data', function () {
    createDebtPaymentLink();

    $this->getJson('/api/debt-payments/aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa')
        ->assertOk()
        ->assertJsonPath('state', 'ready')
        ->assertJsonPath('data.customer_name', 'Иванов Иван')
        ->assertJsonPath('data.amount', '208800.00');
});

it('returns payment url when payment is already created', function () {
    createDebtPaymentLink([
        'status' => DebtPaymentLink::STATUS_PAYMENT_CREATED,
        'payment_url' => 'https://alfasklad.ru/order/?ORDER_ID=310238',
    ]);

    $this->getJson('/api/debt-payments/aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa')
        ->assertOk()
        ->assertJsonPath('state', 'payment_created')
        ->assertJsonPath('data.payment_url', 'https://alfasklad.ru/order/?ORDER_ID=310238');
});

it('returns expired state for expired active link', function () {
    createDebtPaymentLink([
        'expires_at' => now()->subDay(),
    ]);

    $this->getJson('/api/debt-payments/aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa')
        ->assertOk()
        ->assertJsonPath('state', 'expired');
});
