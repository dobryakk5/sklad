<?php

use App\Models\DebtPaymentLink;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('rejects callback without internal secret', function () {
    config(['services.bitrix.internal_secret' => 'secret']);
    createDebtPaymentLink(['bitrix_order_id' => 310238]);

    $this->postJson('/api/internal/debt-payments/payment-status', [
        'order_id' => 310238,
        'status' => 'paid',
    ])->assertForbidden();
});

it('marks link paid from valid callback', function () {
    config(['services.bitrix.internal_secret' => 'secret']);
    createDebtPaymentLink(['bitrix_order_id' => 310238]);

    $this->withHeader('X-Internal-Secret', 'secret')
        ->postJson('/api/internal/debt-payments/payment-status', [
            'order_id' => 310238,
            'status' => 'paid',
            'amount' => '208800.00',
            'currency' => 'RUB',
        ])
        ->assertOk()
        ->assertJsonPath('status', DebtPaymentLink::STATUS_PAID);

    expect(DebtPaymentLink::first()->status)->toBe(DebtPaymentLink::STATUS_PAID);
});

it('does not mark paid when callback amount mismatches', function () {
    config(['services.bitrix.internal_secret' => 'secret']);
    createDebtPaymentLink(['bitrix_order_id' => 310238]);

    $this->withHeader('X-Internal-Secret', 'secret')
        ->postJson('/api/internal/debt-payments/payment-status', [
            'order_id' => 310238,
            'status' => 'paid',
            'amount' => '1.00',
            'currency' => 'RUB',
        ])
        ->assertStatus(422)
        ->assertJsonPath('code', 'amount_mismatch');

    $link = DebtPaymentLink::first();

    expect($link->status)->toBe(DebtPaymentLink::STATUS_ERROR)
        ->and($link->last_error)->toBe('amount_mismatch');
});
