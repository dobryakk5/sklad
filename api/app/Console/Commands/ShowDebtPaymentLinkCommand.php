<?php

namespace App\Console\Commands;

use App\Models\DebtPaymentLink;
use Illuminate\Console\Command;

final class ShowDebtPaymentLinkCommand extends Command
{
    protected $signature = 'debt-payments:show {--token=} {--link-id=}';

    protected $description = 'Show one debt payment link.';

    public function handle(): int
    {
        $query = DebtPaymentLink::with('notifications');

        if ($this->option('token')) {
            $query->where('token', $this->option('token'));
        } elseif ($this->option('link-id')) {
            $query->where('id', (int) $this->option('link-id'));
        } else {
            $this->error('Pass --token or --link-id.');

            return self::FAILURE;
        }

        $link = $query->first();

        if ($link === null) {
            $this->error('Link not found.');

            return self::FAILURE;
        }

        $this->table(['field', 'value'], [
            ['id', $link->id],
            ['token', $link->token],
            ['status', $link->status],
            ['customer', $link->customer_name],
            ['invoice', $link->invoice_number ?: $link->invoice_id],
            ['contract', $link->contract_number],
            ['amount', $link->amount . ' ' . $link->currency],
            ['expires_at', optional($link->expires_at)->toIso8601String()],
            ['bitrix_order_id', $link->bitrix_order_id],
            ['payment_url', $link->payment_url],
        ]);

        $this->table(
            ['channel', 'recipient', 'status', 'error'],
            $link->notifications->map(fn ($notification) => [
                $notification->channel,
                $notification->recipient,
                $notification->status,
                $notification->error_message,
            ])->all(),
        );

        return self::SUCCESS;
    }
}
