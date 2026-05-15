<?php

use App\DTO\DebtPayments\DebtPaymentCandidate;
use App\Models\DebtPaymentCampaign;
use App\Models\DebtPaymentLink;
use App\Models\DebtPaymentNotification;
use App\Services\DebtPayments\BitrixDebtSource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Collection;

uses(RefreshDatabase::class);

it('does not create a new monthly link when an active link already exists for the invoice', function () {
    createDebtPaymentLink([
        'token' => 'bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb',
        'status' => DebtPaymentLink::STATUS_SENT,
        'expires_at' => now()->addDays(20),
    ]);

    $this->mock(BitrixDebtSource::class, function ($mock): void {
        $mock->shouldReceive('findDebtorsForDate')
            ->once()
            ->andReturn(new Collection([
                new DebtPaymentCandidate(
                    bitrixUserId: 123,
                    customerName: 'Иванов Иван',
                    email: 'client@example.ru',
                    phone: '+79990000000',
                    invoiceId: 260726,
                    invoiceNumber: '260726',
                    invoiceGuid: null,
                    contractId: null,
                    contractNumber: '5/23625',
                    contractGuid: null,
                    amount: '208800.00',
                    currency: 'RUB',
                ),
            ]));
    });

    $this->artisan('debt-payments:generate --date=2026-07-01')
        ->expectsOutput('Skipped invoice 260726: active link already exists.')
        ->assertSuccessful();

    expect(DebtPaymentCampaign::count())->toBe(2)
        ->and(DebtPaymentLink::count())->toBe(1)
        ->and(DebtPaymentNotification::count())->toBe(0);
});

it('does not duplicate links notifications or queued jobs when resumed', function () {
    Queue::fake();
    mockDebtSourceCandidate();

    $this->artisan('debt-payments:generate --date=2026-06-01')
        ->assertSuccessful();

    DebtPaymentCampaign::first()->update(['status' => DebtPaymentCampaign::STATUS_FAILED]);

    mockDebtSourceCandidate();

    $this->artisan('debt-payments:generate --date=2026-06-01 --resume')
        ->assertSuccessful();

    expect(DebtPaymentCampaign::count())->toBe(1)
        ->and(DebtPaymentLink::count())->toBe(1)
        ->and(DebtPaymentNotification::count())->toBe(2)
        ->and(DebtPaymentNotification::whereNotNull('queued_at')->count())->toBe(2);

    Queue::assertPushed(\App\Jobs\SendDebtPaymentNotificationJob::class, 2);
});

it('marks sms notification skipped when phone is missing', function () {
    Queue::fake();
    mockDebtSourceCandidate(phone: null);

    $this->artisan('debt-payments:generate --date=2026-06-01')
        ->assertSuccessful();

    $sms = DebtPaymentNotification::where('channel', DebtPaymentNotification::CHANNEL_SMS)->first();

    expect($sms->status)->toBe(DebtPaymentNotification::STATUS_SKIPPED)
        ->and($sms->error_message)->toBe('phone_missing')
        ->and($sms->queued_at)->toBeNull();
});

it('cancels a whole campaign without deleting financial records', function () {
    $link = createDebtPaymentLink(['status' => DebtPaymentLink::STATUS_SENT]);

    DebtPaymentLink::create([
        'campaign_id' => $link->campaign_id,
        'token' => 'cccccccccccccccccccccccccccccccc',
        'bitrix_user_id' => 123,
        'invoice_id' => 260727,
        'invoice_number' => '260727',
        'amount' => '208800.00',
        'currency' => 'RUB',
        'status' => DebtPaymentLink::STATUS_PAID,
        'expires_at' => now()->addDays(10),
    ]);

    $this->artisan('debt-payments:cancel-campaign --date=2026-06-01 --force')
        ->expectsOutput("Campaign {$link->campaign_id} cancelled. Links cancelled: 1.")
        ->assertSuccessful();

    expect(DebtPaymentCampaign::first()->status)->toBe(DebtPaymentCampaign::STATUS_CANCELLED)
        ->and(DebtPaymentLink::where('status', DebtPaymentLink::STATUS_CANCELLED)->count())->toBe(1)
        ->and(DebtPaymentLink::where('status', DebtPaymentLink::STATUS_PAID)->count())->toBe(1);
});

function mockDebtSourceCandidate(?string $phone = '+79990000000'): void
{
    app()->bind(BitrixDebtSource::class, fn () => new class($phone) extends BitrixDebtSource {
        public function __construct(private readonly ?string $phone) {}

        public function findDebtorsForDate(\Carbon\CarbonImmutable $date): Collection
        {
            return new Collection([
                new DebtPaymentCandidate(
                    bitrixUserId: 123,
                    customerName: 'Иванов Иван',
                    email: 'client@example.ru',
                    phone: $this->phone,
                    invoiceId: 260726,
                    invoiceNumber: '260726',
                    invoiceGuid: null,
                    contractId: null,
                    contractNumber: '5/23625',
                    contractGuid: null,
                    amount: '208800.00',
                    currency: 'RUB',
                ),
            ]);
        }
    });
}
