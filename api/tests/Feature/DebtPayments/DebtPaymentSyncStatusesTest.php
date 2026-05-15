<?php

use App\Models\DebtPaymentLink;
use App\Services\DebtPayments\BitrixDebtPaymentVerifier;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('polls Bitrix database order status and marks payment created link paid', function () {
    createDebtPaymentLink([
        'status' => DebtPaymentLink::STATUS_PAYMENT_CREATED,
        'bitrix_order_id' => 310238,
    ]);

    $this->mock(BitrixDebtPaymentVerifier::class, function ($mock): void {
        $mock->shouldReceive('getOrderStatus')
            ->once()
            ->with(310238)
            ->andReturn([
                'order_id' => 310238,
                'amount' => '208800.00',
                'currency' => 'RUB',
                'payed' => true,
                'paid_at' => '2026-06-01 12:30:00',
            ]);
    });

    $this->artisan('debt-payments:sync-statuses')
        ->expectsOutput('Marked paid: 1')
        ->assertSuccessful();

    expect(DebtPaymentLink::first()->status)->toBe(DebtPaymentLink::STATUS_PAID);
});

it('keeps payment created link open when Bitrix order is not paid', function () {
    createDebtPaymentLink([
        'status' => DebtPaymentLink::STATUS_PAYMENT_CREATED,
        'bitrix_order_id' => 310238,
    ]);

    $this->mock(BitrixDebtPaymentVerifier::class, function ($mock): void {
        $mock->shouldReceive('getOrderStatus')
            ->once()
            ->andReturn([
                'order_id' => 310238,
                'amount' => '208800.00',
                'currency' => 'RUB',
                'payed' => false,
                'paid_at' => null,
            ]);
    });

    $this->artisan('debt-payments:sync-statuses')
        ->expectsOutput('Marked paid: 0')
        ->assertSuccessful();

    expect(DebtPaymentLink::first()->status)->toBe(DebtPaymentLink::STATUS_PAYMENT_CREATED);
});

it('records an error when Bitrix order is missing during polling', function () {
    createDebtPaymentLink([
        'status' => DebtPaymentLink::STATUS_PAYMENT_CREATED,
        'bitrix_order_id' => 310238,
    ]);

    $this->mock(BitrixDebtPaymentVerifier::class, function ($mock): void {
        $mock->shouldReceive('getOrderStatus')
            ->once()
            ->andReturn(null);
    });

    $this->artisan('debt-payments:sync-statuses')
        ->expectsOutput('Marked paid: 0')
        ->assertSuccessful();

    expect(DebtPaymentLink::first()->last_error)->toBe('bitrix_order_not_found');
});
