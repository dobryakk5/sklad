<?php

namespace App\Console\Commands;

use App\Services\DebtPayments\DebtPaymentStatusService;
use Illuminate\Console\Command;

final class SyncDebtPaymentStatusesCommand extends Command
{
    protected $signature = 'debt-payments:sync-statuses';

    protected $description = 'Synchronize recent debt payment statuses from Bitrix.';

    public function handle(DebtPaymentStatusService $statusService): int
    {
        $paid = $statusService->syncFreshStatuses();
        $this->info("Marked paid: {$paid}");

        return self::SUCCESS;
    }
}
