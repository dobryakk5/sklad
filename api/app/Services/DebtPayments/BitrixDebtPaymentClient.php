<?php

namespace App\Services\DebtPayments;

use App\Services\Cabinet\BitrixCabinetException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BitrixDebtPaymentClient
{
    public function payInvoice(int $userId, int $invoiceId): array
    {
        [$response, $body] = $this->postToBridge('/invoices/pay.php', [
            'user_id' => $userId,
            'invoice_id' => $invoiceId,
        ]);

        if (! $response->successful() || ! is_array($body)) {
            throw new BitrixCabinetException(
                is_array($body) ? (string) ($body['error']['code'] ?? 'BITRIX_ERROR') : 'BITRIX_ERROR',
                $response->status() > 0 ? $response->status() : 502,
                is_array($body) ? ($body['error']['message'] ?? null) : null,
            );
        }

        $orderId = (int) ($body['order_id'] ?? 0);
        $paymentUrl = (string) ($body['payment_url'] ?? '');

        if ($orderId <= 0 || $paymentUrl === '') {
            throw new BitrixCabinetException('BITRIX_ERROR', 502, 'Invalid payment bridge response.');
        }

        return [
            'order_id' => $orderId,
            'payment_url' => $paymentUrl,
        ];
    }

    private function postToBridge(string $path, array $payload): array
    {
        $baseUrl = rtrim((string) config('bitrix.service_base_url', config('services.bitrix_cabinet.base_url')), '/');
        $secret = (string) config('bitrix.service_secret', config('services.bitrix_cabinet.secret'));
        $timeout = (int) config('bitrix.service_timeout', 10);

        if ($baseUrl === '' || $secret === '') {
            throw new BitrixCabinetException('BITRIX_ERROR', 502, 'CABINET_BRIDGE_NOT_CONFIGURED');
        }

        $rawBody = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $timestamp = (string) time();
        $signature = hash_hmac('sha256', $rawBody . $timestamp, $secret);

        try {
            $response = Http::timeout($timeout)
                ->acceptJson()
                ->withHeaders([
                    'X-Cabinet-Timestamp' => $timestamp,
                    'X-Cabinet-Signature' => $signature,
                ])
                ->withBody($rawBody, 'application/json')
                ->post($baseUrl . $path);
        } catch (ConnectionException $e) {
            Log::channel('debt_payments')->warning('Bitrix debt payment bridge connection failed.', [
                'path' => $path,
                'base_url' => $baseUrl,
                'error' => $e->getMessage(),
            ]);

            throw new BitrixCabinetException('BITRIX_ERROR', 502, 'Cabinet bridge is unavailable.', $e);
        }

        return [$response, $response->json()];
    }
}
