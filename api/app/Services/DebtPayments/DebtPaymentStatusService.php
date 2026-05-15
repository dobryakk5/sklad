<?php

namespace App\Services\DebtPayments;

use App\Models\DebtPaymentLink;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Log;

final class DebtPaymentStatusService
{
    public function __construct(
        private readonly BitrixDebtPaymentVerifier $verifier,
    ) {}

    public function applyBitrixStatus(array $payload): array
    {
        $orderId = (int) ($payload['order_id'] ?? 0);

        if ($orderId <= 0) {
            return ['ok' => false, 'code' => 'invalid_order_id'];
        }

        $link = DebtPaymentLink::where('bitrix_order_id', $orderId)->first();

        if ($link === null) {
            return ['ok' => false, 'code' => 'link_not_found'];
        }

        if (isset($payload['amount']) && ! DecimalString::equals((string) $link->amount, (string) $payload['amount'])) {
            $link->update([
                'status' => DebtPaymentLink::STATUS_ERROR,
                'last_error' => 'amount_mismatch',
            ]);

            Log::channel('debt_payments')->warning('Debt payment callback amount mismatch.', [
                'link_id' => $link->id,
                'order_id' => $orderId,
                'expected' => (string) $link->amount,
                'actual' => (string) $payload['amount'],
            ]);

            return ['ok' => false, 'code' => 'amount_mismatch'];
        }

        if (($payload['status'] ?? null) !== 'paid' && ($payload['payed'] ?? null) !== true) {
            return ['ok' => true, 'status' => $link->status, 'ignored' => true];
        }

        if ($link->status === DebtPaymentLink::STATUS_PAID) {
            return ['ok' => true, 'status' => DebtPaymentLink::STATUS_PAID];
        }

        $link->update([
            'status' => DebtPaymentLink::STATUS_PAID,
            'paid_at' => $this->paidAt($payload),
            'last_error' => null,
        ]);

        Log::channel('debt_payments')->info('Debt payment marked paid.', [
            'link_id' => $link->id,
            'order_id' => $orderId,
        ]);

        return ['ok' => true, 'status' => DebtPaymentLink::STATUS_PAID];
    }

    public function syncFreshStatuses(): int
    {
        $days = (int) config('debt-payments.status_sync_days', 30);
        $count = 0;

        DebtPaymentLink::query()
            ->where('status', DebtPaymentLink::STATUS_PAYMENT_CREATED)
            ->whereNotNull('bitrix_order_id')
            ->where('created_at', '>=', now()->subDays($days))
            ->orderBy('id')
            ->chunkById(100, function ($links) use (&$count): void {
                foreach ($links as $link) {
                    $status = $this->verifier->getOrderStatus((int) $link->bitrix_order_id);

                    if ($status === null) {
                        $link->update(['last_error' => 'bitrix_order_not_found']);
                        continue;
                    }

                    if (($status['payed'] ?? false) !== true) {
                        continue;
                    }

                    $result = $this->applyBitrixStatus([
                        'order_id' => $link->bitrix_order_id,
                        'status' => 'paid',
                        'paid_at' => $status['paid_at'] ?? null,
                        'amount' => $status['amount'] ?? null,
                        'currency' => $status['currency'] ?? null,
                    ]);

                    if (($result['status'] ?? null) === DebtPaymentLink::STATUS_PAID) {
                        $count++;
                    }
                }
            });

        return $count;
    }

    private function paidAt(array $payload): CarbonImmutable
    {
        $paidAt = $payload['paid_at'] ?? $payload['date_paid'] ?? null;

        return $paidAt ? CarbonImmutable::parse($paidAt) : CarbonImmutable::now();
    }
}
