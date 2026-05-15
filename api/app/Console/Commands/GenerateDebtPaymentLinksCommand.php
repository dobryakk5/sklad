<?php

namespace App\Console\Commands;

use App\Models\DebtPaymentCampaign;
use App\Models\DebtPaymentLink;
use App\Services\DebtPayments\BitrixDebtSource;
use App\Services\DebtPayments\DebtPaymentLinkService;
use App\Services\DebtPayments\DebtPaymentNotificationService;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

final class GenerateDebtPaymentLinksCommand extends Command
{
    protected $signature = 'debt-payments:generate
        {--date= : Campaign date, YYYY-MM-DD}
        {--dry-run : Show candidates without creating links}
        {--resume : Resume failed/running campaign}
        {--force : Reprocess existing campaign without recreating paid/cancelled links}';

    protected $description = 'Generate debt payment links and queue email/SMS notifications.';

    private const ACTIVE_LINK_STATUSES = [
        DebtPaymentLink::STATUS_NEW,
        DebtPaymentLink::STATUS_SENT,
        DebtPaymentLink::STATUS_OPENED,
        DebtPaymentLink::STATUS_PAYMENT_CREATED,
    ];

    public function handle(
        BitrixDebtSource $source,
        DebtPaymentLinkService $linkService,
        DebtPaymentNotificationService $notificationService,
    ): int {
        $date = CarbonImmutable::parse($this->option('date') ?: now()->toDateString())->startOfDay();
        $candidates = $source->findDebtorsForDate($date);

        if ($this->option('dry-run')) {
            $this->info('Candidates: ' . $candidates->count());
            $this->table(
                ['user_id', 'invoice_id', 'invoice', 'contract', 'amount', 'email', 'phone'],
                $candidates->map(fn ($candidate) => [
                    $candidate->bitrixUserId,
                    $candidate->invoiceId,
                    $candidate->invoiceNumber,
                    $candidate->contractNumber,
                    $candidate->amount . ' ' . $candidate->currency,
                    $candidate->email ?? '-',
                    $candidate->phone ?? '-',
                ])->all(),
            );

            return self::SUCCESS;
        }

        $campaign = $linkService->findOrCreateCampaign($date, (bool) $this->option('force'));

        if (! $this->canRun($campaign)) {
            return self::FAILURE;
        }

        $createdLinks = 0;
        $queuedNotifications = 0;

        try {
            $campaign->update([
                'status' => DebtPaymentCampaign::STATUS_RUNNING,
                'started_at' => now(),
                'finished_at' => null,
                'failed_at' => null,
                'last_error' => null,
            ]);

            foreach ($candidates as $candidate) {
                $existing = DebtPaymentLink::query()
                    ->where('campaign_id', $campaign->id)
                    ->where('invoice_id', $candidate->invoiceId)
                    ->first();

                if ($existing !== null && in_array($existing->status, [DebtPaymentLink::STATUS_PAID, DebtPaymentLink::STATUS_CANCELLED], true)) {
                    continue;
                }

                if ($existing === null && $this->hasActiveLinkForInvoice($candidate->invoiceId)) {
                    $this->line("Skipped invoice {$candidate->invoiceId}: active link already exists.");
                    continue;
                }

                [$link, $notifications] = DB::transaction(function () use ($campaign, $candidate, $linkService, $notificationService): array {
                    $link = $linkService->createLink($campaign, $candidate);

                    return [$link, $notificationService->createNotifications($link)];
                });

                if ($link->wasRecentlyCreated) {
                    $createdLinks++;
                }

                $queuedNotifications += $notifications->where('status', 'pending')->count();
                $notificationService->dispatchPending($notifications);
            }

            $campaign->update([
                'status' => DebtPaymentCampaign::STATUS_DONE,
                'finished_at' => now(),
            ]);
        } catch (Throwable $e) {
            $campaign->update([
                'status' => DebtPaymentCampaign::STATUS_FAILED,
                'failed_at' => now(),
                'last_error' => $e->getMessage(),
            ]);

            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->info("Campaign {$campaign->id} done. Links created: {$createdLinks}. Notifications queued: {$queuedNotifications}.");

        return self::SUCCESS;
    }

    private function hasActiveLinkForInvoice(int $invoiceId): bool
    {
        return DebtPaymentLink::query()
            ->where('invoice_id', $invoiceId)
            ->whereIn('status', self::ACTIVE_LINK_STATUSES)
            ->where(function ($query): void {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now());
            })
            ->exists();
    }

    private function canRun(DebtPaymentCampaign $campaign): bool
    {
        if ($campaign->status === DebtPaymentCampaign::STATUS_CANCELLED) {
            $this->error('Campaign is cancelled.');

            return false;
        }

        if ($campaign->status === DebtPaymentCampaign::STATUS_DONE && ! $this->option('force')) {
            $this->error('Campaign is already done. Use --force to reprocess missing links.');

            return false;
        }

        if ($campaign->status === DebtPaymentCampaign::STATUS_RUNNING && ! $this->option('resume') && ! $this->option('force')) {
            $this->error('Campaign is running. Use --resume to continue.');

            return false;
        }

        if ($campaign->status === DebtPaymentCampaign::STATUS_FAILED && ! $this->option('resume') && ! $this->option('force')) {
            $this->error('Campaign failed. Use --resume or --force.');

            return false;
        }

        return true;
    }
}
