<?php

namespace App\Services\Cabinet;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class BitrixCabinetAuthClient
{
    public function login(string $login, string $password): array
    {
        $payload = [
            'login' => $login,
            'password' => $password,
        ];

        [$response, $body] = $this->postToBridge('/auth/login.php', $payload);

        if ($response->status() === 401) {
            throw new BitrixCabinetException('INVALID_CREDENTIALS', 401);
        }

        if (! $response->successful() || ! is_array($body)) {
            throw new BitrixCabinetException('BITRIX_ERROR', $response->status() > 0 ? $response->status() : 502);
        }

        $userId = (int) ($body['user_id'] ?? 0);

        if ($userId <= 0) {
            throw new BitrixCabinetException('BITRIX_ERROR', $response->status() > 0 ? $response->status() : 502);
        }

        return [
            'id' => $userId,
            'name' => (string) ($body['name'] ?? ''),
            'email' => (string) ($body['email'] ?? ''),
            'phone' => (string) ($body['phone'] ?? ''),
        ];
    }

    public function submitRequest(
        int $userId,
        string $type,
        array $fields,
    ): array {
        [$response, $body] = $this->postToBridge('/requests/create.php', [
            'user_id' => $userId,
            'type' => $type,
            'fields' => $fields,
        ]);

        if (! $response->successful() || ! is_array($body)) {
            $code = is_array($body) ? (string) ($body['error']['code'] ?? 'BITRIX_ERROR') : 'BITRIX_ERROR';

            Log::warning('Cabinet request bridge returned an error.', [
                'user_id' => $userId,
                'type' => $type,
                'status' => $response->status(),
                'code' => $code,
                'message' => is_array($body) ? ($body['error']['message'] ?? null) : null,
                'body_preview' => is_array($body) ? null : mb_substr($response->body(), 0, 500),
            ]);

            throw new BitrixCabinetException(
                $code,
                $response->status() > 0 ? $response->status() : 502,
                is_array($body) ? ($body['error']['message'] ?? null) : null,
            );
        }

        $resultId = (int) ($body['result_id'] ?? 0);

        if ($resultId <= 0) {
            Log::warning('Cabinet request bridge returned response without a valid result id.', [
                'user_id' => $userId,
                'type' => $type,
                'status' => $response->status(),
                'body' => $body,
            ]);

            throw new BitrixCabinetException('BITRIX_ERROR', 502);
        }

        return [
            'result_id' => $resultId,
            'web_form_id' => (int) ($body['web_form_id'] ?? 0),
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
            Log::warning('Cabinet bridge connection failed.', [
                'path' => $path,
                'base_url' => $baseUrl,
                'error' => $e->getMessage(),
            ]);

            throw new BitrixCabinetException('BITRIX_ERROR', 502, 'Cabinet bridge is unavailable.', $e);
        }

        return [$response, $response->json()];
    }
}
