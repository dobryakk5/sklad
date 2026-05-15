<?php

use App\Models\DebtPaymentLink;
use App\Services\DebtPayments\BitrixDebtPaymentClient;
use App\Services\DebtPayments\BitrixDebtPaymentVerifier;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates payment through Bitrix bridge and ignores client amount', function () {
    createDebtPaymentLink();

    $this->mock(BitrixDebtPaymentVerifier::class, function ($mock): void {
        $mock->shouldReceive('verifyInvoicePayable')
            ->once()
            ->andReturn(['ok' => true, 'invoice' => []]);
    });

    $this->mock(BitrixDebtPaymentClient::class, function ($mock): void {
        $mock->shouldReceive('payInvoice')
            ->once()
            ->with(123, 260726)
            ->andReturn([
                'order_id' => 310238,
                'payment_url' => 'https://alfasklad.ru/order/?ORDER_ID=310238',
            ]);
    });

    $this->postJson('/api/debt-payments/aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/pay', [
        'amount' => '1.00',
    ])
        ->assertOk()
        ->assertJsonPath('status', DebtPaymentLink::STATUS_PAYMENT_CREATED)
        ->assertJsonPath('payment_url', 'https://alfasklad.ru/order/?ORDER_ID=310238');

    $link = DebtPaymentLink::first();

    expect($link->status)->toBe(DebtPaymentLink::STATUS_PAYMENT_CREATED)
        ->and($link->amount)->toBe('208800.00')
        ->and($link->bitrix_order_id)->toBe(310238);
});

it('does not create payment when Bitrix invoice amount mismatches', function () {
    createDebtPaymentLink();

    $this->mock(BitrixDebtPaymentVerifier::class, function ($mock): void {
        $mock->shouldReceive('verifyInvoicePayable')
            ->once()
            ->andReturn([
                'ok' => false,
                'code' => 'AMOUNT_MISMATCH',
                'expected' => '208800.00',
                'actual' => '1.00',
            ]);
    });

    $this->mock(BitrixDebtPaymentClient::class, function ($mock): void {
        $mock->shouldNotReceive('payInvoice');
    });

    $this->postJson('/api/debt-payments/aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/pay')
        ->assertStatus(422)
        ->assertJsonPath('error.code', 'AMOUNT_MISMATCH');

    $link = DebtPaymentLink::first();

    expect($link->status)->toBe(DebtPaymentLink::STATUS_ERROR)
        ->and($link->last_error)->toBe('amount_mismatch');
});

it('does not create payment when Bitrix invoice is no longer not-paid', function () {
    createDebtPaymentLink();

    $this->mock(BitrixDebtPaymentVerifier::class, function ($mock): void {
        $mock->shouldReceive('verifyInvoicePayable')
            ->once()
            ->andReturn([
                'ok' => false,
                'code' => 'INVOICE_NOT_PAYABLE',
                'status_id' => 421,
            ]);
    });

    $this->mock(BitrixDebtPaymentClient::class, function ($mock): void {
        $mock->shouldNotReceive('payInvoice');
    });

    $this->postJson('/api/debt-payments/aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/pay')
        ->assertStatus(422)
        ->assertJsonPath('error.code', 'INVOICE_NOT_PAYABLE')
        ->assertJsonPath('error.status_id', 421);

    expect(DebtPaymentLink::first()->last_error)->toBe('invoice_not_payable:421');
});
