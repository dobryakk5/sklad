<?php

namespace App\Console\Commands;

use App\Models\DebtPaymentCampaign;
use App\Models\DebtPaymentLink;
use App\Models\DebtPaymentNotification;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;

final class DebtPaymentsStatusCommand extends Command
{
    protected $signature = 'debt-payments:status {--date= : Campaign date, YYYY-MM-DD}';

    protected $description = 'Show debt payment campaign status.';

    public function handle(): int
    {
        $date = CarbonImmutable::parse($this->option('date') ?: now()->toDateString())->toDateString();
        $campaign = DebtPaymentCampaign::where('source', config('debt-payments.source', 'monthly_debt'))
            ->whereDate('campaign_date', $date)
            ->first();

        if ($campaign === null) {
            $this->error('Campaign not found.');

            return self::FAILURE;
        }

        $this->line('campaign_date: ' . $campaign->campaign_date->toDateString());
        $this->line('status: ' . $campaign->status);
        $this->newLine();

        $this->table(['metric', 'value'], [
            ['links_total', $campaign->links()->count()],
            ['notifications_email_sent', $this->notifications($campaign->id, 'email', 'sent')],
            ['notifications_sms_sent', $this->notifications($campaign->id, 'sms', 'sent')],
            ['notifications_sms_skipped', $this->notifications($campaign->id, 'sms', 'skipped')],
            ['opened', $this->links($campaign->id, DebtPaymentLink::STATUS_OPENED)],
            ['payment_created', $this->links($campaign->id, DebtPaymentLink::STATUS_PAYMENT_CREATED)],
            ['paid', $this->links($campaign->id, DebtPaymentLink::STATUS_PAID)],
            ['error', $this->links($campaign->id, DebtPaymentLink::STATUS_ERROR)],
        ]);

        return self::SUCCESS;
    }

    private function links(int $campaignId, string $status): int
    {
        return DebtPaymentLink::where('campaign_id', $campaignId)->where('status', $status)->count();
    }

    private function notifications(int $campaignId, string $channel, string $status): int
    {
        return DebtPaymentNotification::whereHas('link', fn ($query) => $query->where('campaign_id', $campaignId))
            ->where('channel', $channel)
            ->where('status', $status)
            ->count();
    }
}
