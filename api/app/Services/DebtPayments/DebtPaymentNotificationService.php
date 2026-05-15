<?php

namespace App\Services\DebtPayments;

use App\Jobs\SendDebtPaymentNotificationJob;
use App\Models\DebtPaymentLink;
use App\Models\DebtPaymentNotification;
use Illuminate\Support\Collection;

final class DebtPaymentNotificationService
{
    public function __construct(
        private readonly DebtPaymentLinkService $linkService,
    ) {}

    /**
     * @return Collection<int, DebtPaymentNotification>
     */
    public function createNotifications(DebtPaymentLink $link): Collection
    {
        $notifications = collect([
            $this->createEmailNotification($link),
            $this->createSmsNotification($link),
        ]);

        $this->markLinkSentIfComplete($link);

        return $notifications;
    }

    public function dispatchPending(Collection $notifications): void
    {
        $notifications->each(function (DebtPaymentNotification $notification): void {
            $notification = $notification->fresh();

            if (
                $notification === null
                || $notification->status !== DebtPaymentNotification::STATUS_PENDING
                || $notification->queued_at !== null
            ) {
                return;
            }

            SendDebtPaymentNotificationJob::dispatch($notification->id)
                ->onQueue((string) config('debt-payments.queue', 'debt-payments'));

            $notification->update(['queued_at' => now()]);
        });
    }

    private function createEmailNotification(DebtPaymentLink $link): DebtPaymentNotification
    {
        $recipient = $link->customer_email;
        $enabled = (bool) config('debt-payments.email_enabled', true);

        return DebtPaymentNotification::firstOrCreate(
            [
                'debt_payment_link_id' => $link->id,
                'channel' => DebtPaymentNotification::CHANNEL_EMAIL,
            ],
            [
                'recipient' => $recipient,
                'subject' => 'Счёт на оплату по договору №' . ($link->contract_number ?: '-'),
                'body' => $this->emailBody($link),
                'status' => $enabled && $recipient ? DebtPaymentNotification::STATUS_PENDING : DebtPaymentNotification::STATUS_SKIPPED,
                'provider' => config('mail.default', config('mail.mailer')),
                'error_message' => $enabled ? ($recipient ? null : 'email_missing') : 'email_disabled',
            ],
        );
    }

    private function createSmsNotification(DebtPaymentLink $link): DebtPaymentNotification
    {
        $recipient = $link->customer_phone;
        $enabled = (bool) config('debt-payments.sms_enabled', true);

        return DebtPaymentNotification::firstOrCreate(
            [
                'debt_payment_link_id' => $link->id,
                'channel' => DebtPaymentNotification::CHANNEL_SMS,
            ],
            [
                'recipient' => $recipient,
                'subject' => null,
                'body' => $this->smsBody($link),
                'status' => $enabled && $recipient ? DebtPaymentNotification::STATUS_PENDING : DebtPaymentNotification::STATUS_SKIPPED,
                'provider' => (string) config('debt-payments.sms_provider', 'log'),
                'error_message' => $enabled ? ($recipient ? null : 'phone_missing') : 'sms_disabled',
            ],
        );
    }

    private function emailBody(DebtPaymentLink $link): string
    {
        return implode("\n", [
            'Здравствуйте, ' . ($link->customer_name ?: 'клиент') . '.',
            '',
            'По договору №' . ($link->contract_number ?: '-') . ' есть неоплаченный счёт №' . ($link->invoice_number ?: $link->invoice_id) . '.',
            'Сумма к оплате: ' . $link->amount . ' ₽.',
            '',
            'Для оплаты перейдите по ссылке:',
            $this->linkService->paymentUrl($link),
            '',
            'Ссылка действует до ' . optional($link->expires_at)->timezone(config('app.timezone'))->format('d.m.Y H:i') . '.',
        ]);
    }

    private function smsBody(DebtPaymentLink $link): string
    {
        return 'Альфа-Склад: по договору №' . ($link->contract_number ?: '-')
            . ' к оплате ' . $link->amount . ' ₽. Оплатить: '
            . $this->linkService->paymentUrl($link);
    }

    private function markLinkSentIfComplete(DebtPaymentLink $link): void
    {
        if ($link->status !== DebtPaymentLink::STATUS_NEW) {
            return;
        }

        $hasPendingNotifications = $link->notifications()
            ->whereIn('status', [
                DebtPaymentNotification::STATUS_PENDING,
                DebtPaymentNotification::STATUS_SENDING,
                DebtPaymentNotification::STATUS_FAILED,
            ])
            ->exists();

        if (! $hasPendingNotifications) {
            $link->update(['status' => DebtPaymentLink::STATUS_SENT]);
        }
    }
}
