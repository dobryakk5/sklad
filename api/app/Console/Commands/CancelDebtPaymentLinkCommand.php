<?php

namespace App\Console\Commands;

use App\Models\DebtPaymentLink;
use Illuminate\Console\Command;

final class CancelDebtPaymentLinkCommand extends Command
{
    protected $signature = 'debt-payments:cancel {--link-id=}';

    protected $description = 'Cancel one debt payment link.';

    public function handle(): int
    {
        $link = DebtPaymentLink::find((int) $this->option('link-id'));

        if ($link === null) {
            $this->error('Link not found.');

            return self::FAILURE;
        }

        if ($link->status === DebtPaymentLink::STATUS_PAID) {
            $this->error('Paid link cannot be cancelled.');

            return self::FAILURE;
        }

        $link->update([
            'status' => DebtPaymentLink::STATUS_CANCELLED,
            'cancelled_at' => now(),
        ]);

        $this->info('Link cancelled.');

        return self::SUCCESS;
    }
}
