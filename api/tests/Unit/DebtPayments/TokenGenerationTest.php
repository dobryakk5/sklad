<?php

use App\Models\DebtPaymentCampaign;
use App\Models\DebtPaymentLink;
use App\Services\DebtPayments\DebtPaymentLinkService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('generates 32-character hex tokens and avoids existing token collisions', function () {
    $campaign = DebtPaymentCampaign::create([
        'source' => 'monthly_debt',
        'campaign_date' => '2026-06-01',
        'status' => DebtPaymentCampaign::STATUS_DONE,
    ]);

    DebtPaymentLink::create([
        'campaign_id' => $campaign->id,
        'token' => str_repeat('a', 32),
        'bitrix_user_id' => 123,
        'invoice_id' => 260726,
        'amount' => '1.00',
        'currency' => 'RUB',
        'status' => DebtPaymentLink::STATUS_NEW,
        'expires_at' => now()->addDay(),
    ]);

    $tokens = collect(range(1, 100))
        ->map(fn () => app(DebtPaymentLinkService::class)->generateUniqueToken());

    expect($tokens->unique()->count())->toBe(100);
    $tokens->each(function (string $token): void {
        expect($token)->toMatch('/^[a-f0-9]{32}$/')
            ->and($token)->not->toBe(str_repeat('a', 32));
    });
});
