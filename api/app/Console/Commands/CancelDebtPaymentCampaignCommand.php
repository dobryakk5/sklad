<?php

namespace App\Console\Commands;

use App\Models\DebtPaymentCampaign;
use App\Models\DebtPaymentLink;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

final class CancelDebtPaymentCampaignCommand extends Command
{
    protected $signature = 'debt-payments:cancel-campaign
        {--date= : Campaign date, YYYY-MM-DD}
        {--campaign-id= : Campaign id}
        {--force : Cancel without confirmation}';

    protected $description = 'Cancel active debt payment links for a campaign.';

    public function handle(): int
    {
        $campaign = $this->findCampaign();

        if ($campaign === null) {
            $this->error('Campaign not found.');

            return self::FAILURE;
        }

        if (! $this->option('force') && ! $this->confirm("Cancel campaign {$campaign->id}?")) {
            $this->info('Cancelled by user.');

            return self::SUCCESS;
        }

        $cancelledLinks = 0;

        DB::transaction(function () use ($campaign, &$cancelledLinks): void {
            $cancelledLinks = DebtPaymentLink::query()
                ->where('campaign_id', $campaign->id)
                ->whereNotIn('status', [
                    DebtPaymentLink::STATUS_PAID,
                    DebtPaymentLink::STATUS_CANCELLED,
                ])
                ->update([
                    'status' => DebtPaymentLink::STATUS_CANCELLED,
                    'cancelled_at' => now(),
                    'updated_at' => now(),
                ]);

            $campaign->update([
                'status' => DebtPaymentCampaign::STATUS_CANCELLED,
                'finished_at' => now(),
            ]);
        });

        $this->info("Campaign {$campaign->id} cancelled. Links cancelled: {$cancelledLinks}.");

        return self::SUCCESS;
    }

    private function findCampaign(): ?DebtPaymentCampaign
    {
        if ($this->option('campaign-id')) {
            return DebtPaymentCampaign::find((int) $this->option('campaign-id'));
        }

        $dateOption = $this->option('date');
        if (! $dateOption) {
            return null;
        }

        $date = CarbonImmutable::parse($dateOption)->toDateString();

        return DebtPaymentCampaign::where('source', config('debt-payments.source', 'monthly_debt'))
            ->whereDate('campaign_date', $date)
            ->first();
    }
}
