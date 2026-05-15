<?php

namespace App\Console\Commands;

use App\Jobs\SendDebtPaymentNotificationJob;
use App\Models\DebtPaymentNotification;
use Illuminate\Console\Command;

final class ResendDebtPaymentNotificationCommand extends Command
{
    protected $signature = 'debt-payments:resend {--link-id=} {--channel=}';

    protected $description = 'Resend one debt payment notification.';

    public function handle(): int
    {
        $channel = (string) $this->option('channel');
        if (! in_array($channel, ['email', 'sms'], true)) {
            $this->error('Channel must be email or sms.');

            return self::FAILURE;
        }

        $notification = DebtPaymentNotification::where('debt_payment_link_id', (int) $this->option('link-id'))
            ->where('channel', $channel)
            ->first();

        if ($notification === null) {
            $this->error('Notification not found.');

            return self::FAILURE;
        }

        if (! $notification->recipient) {
            $this->error('Notification recipient is empty.');

            return self::FAILURE;
        }

        $notification->update([
            'status' => DebtPaymentNotification::STATUS_PENDING,
            'error_message' => null,
        ]);

        SendDebtPaymentNotificationJob::dispatch($notification->id)
            ->onQueue((string) config('debt-payments.queue', 'debt-payments'));

        $this->info('Notification queued.');

        return self::SUCCESS;
    }
}
