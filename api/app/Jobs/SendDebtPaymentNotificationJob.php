<?php

namespace App\Jobs;

use App\Mail\DebtPaymentLinkMail;
use App\Models\DebtPaymentLink;
use App\Models\DebtPaymentNotification;
use App\Services\Sms\SmsProviderInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Throwable;

final class SendDebtPaymentNotificationJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $timeout = 60;

    public function __construct(
        public readonly int $notificationId,
    ) {}

    public function handle(SmsProviderInterface $smsProvider): void
    {
        $notification = DebtPaymentNotification::with('link')->find($this->notificationId);

        if ($notification === null || $notification->status !== DebtPaymentNotification::STATUS_PENDING) {
            return;
        }

        $notification->update(['status' => DebtPaymentNotification::STATUS_SENDING]);

        try {
            if ($notification->channel === DebtPaymentNotification::CHANNEL_EMAIL) {
                Mail::to((string) $notification->recipient)->send(new DebtPaymentLinkMail($notification));
                $providerResult = ['provider_message_id' => null, 'response' => ['sent' => true]];
            } else {
                $providerResult = $smsProvider->send((string) $notification->recipient, (string) $notification->body);
            }

            $notification->update([
                'status' => DebtPaymentNotification::STATUS_SENT,
                'provider_message_id' => $providerResult['provider_message_id'] ?? null,
                'provider_response' => $providerResult['response'] ?? null,
                'error_message' => null,
                'queued_at' => $notification->queued_at ?? now(),
                'sent_at' => now(),
            ]);

            $this->markLinkSentWhenComplete($notification->link);
        } catch (Throwable $e) {
            $notification->update([
                'status' => DebtPaymentNotification::STATUS_FAILED,
                'error_message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    private function markLinkSentWhenComplete(?DebtPaymentLink $link): void
    {
        if ($link === null || $link->status !== DebtPaymentLink::STATUS_NEW) {
            return;
        }

        $hasOpenNotifications = $link->notifications()
            ->whereIn('status', [
                DebtPaymentNotification::STATUS_PENDING,
                DebtPaymentNotification::STATUS_SENDING,
                DebtPaymentNotification::STATUS_FAILED,
            ])
            ->exists();

        if (! $hasOpenNotifications) {
            $link->update(['status' => DebtPaymentLink::STATUS_SENT]);
        }
    }
}
