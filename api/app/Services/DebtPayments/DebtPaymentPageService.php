<?php

namespace App\Services\DebtPayments;

use App\Models\DebtPaymentLink;
use Carbon\CarbonImmutable;

final class DebtPaymentPageService
{
    public function show(string $token): array
    {
        $link = DebtPaymentLink::where('token', $token)->first();

        if ($link === null) {
            return [
                'state' => 'not_found',
                'message' => 'Ссылка не найдена или была удалена.',
            ];
        }

        if ($this->isExpired($link)) {
            if (! in_array($link->status, [DebtPaymentLink::STATUS_PAID, DebtPaymentLink::STATUS_CANCELLED, DebtPaymentLink::STATUS_EXPIRED], true)) {
                $link->update(['status' => DebtPaymentLink::STATUS_EXPIRED]);
            }

            return [
                'state' => 'expired',
                'message' => 'Срок действия ссылки истёк. Для оплаты обратитесь в поддержку.',
                'data' => $this->payload($link->fresh()),
            ];
        }

        return match ($link->status) {
            DebtPaymentLink::STATUS_PAID => [
                'state' => 'paid',
                'message' => 'Счёт уже оплачен.',
                'data' => $this->payload($link),
            ],
            DebtPaymentLink::STATUS_CANCELLED => [
                'state' => 'cancelled',
                'message' => 'Ссылка больше не актуальна.',
                'data' => $this->payload($link),
            ],
            DebtPaymentLink::STATUS_ERROR => [
                'state' => 'error',
                'message' => 'Не удалось подготовить оплату. Обратитесь в поддержку.',
                'data' => $this->payload($link),
            ],
            DebtPaymentLink::STATUS_PAYMENT_CREATED => [
                'state' => 'payment_created',
                'data' => $this->payload($link, includePaymentUrl: true),
            ],
            default => $this->readyState($link),
        };
    }

    public function payload(DebtPaymentLink $link, bool $includePaymentUrl = false): array
    {
        $payload = [
            'customer_name' => (string) $link->customer_name,
            'contract_number' => (string) $link->contract_number,
            'invoice_number' => (string) ($link->invoice_number ?: $link->invoice_id),
            'amount' => DecimalString::normalize((string) $link->amount),
            'currency' => (string) $link->currency,
            'expires_at' => optional($link->expires_at)->toIso8601String(),
        ];

        if ($includePaymentUrl) {
            $payload['payment_url'] = $link->payment_url;
        }

        return $payload;
    }

    public function isExpired(DebtPaymentLink $link): bool
    {
        return $link->expires_at !== null
            && CarbonImmutable::parse($link->expires_at)->isPast()
            && ! in_array($link->status, [DebtPaymentLink::STATUS_PAID, DebtPaymentLink::STATUS_CANCELLED], true);
    }

    private function readyState(DebtPaymentLink $link): array
    {
        if ($link->opened_at === null) {
            $link->forceFill([
                'opened_at' => now(),
                'status' => in_array($link->status, [DebtPaymentLink::STATUS_NEW, DebtPaymentLink::STATUS_SENT], true)
                    ? DebtPaymentLink::STATUS_OPENED
                    : $link->status,
            ])->save();
        }

        return [
            'state' => 'ready',
            'data' => $this->payload($link->fresh()),
        ];
    }
}
