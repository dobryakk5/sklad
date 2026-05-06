<?php

namespace App\Services\Cabinet;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

final class BitrixCabinetAuthClient
{
    public function login(string $login, string $password): array
    {
        $url = rtrim((string) config('bitrix.service_base_url', config('services.bitrix_cabinet.base_url')), '/')
            . '/auth/login.php';
        $secret = (string) config('bitrix.service_secret', config('services.bitrix_cabinet.secret'));
        $timeout = (int) config('bitrix.service_timeout', 10);

        if ($url === '/auth/login.php' || $secret === '') {
            throw new BitrixCabinetException('BITRIX_ERROR', 502, 'AUTH_BRIDGE_NOT_CONFIGURED');
        }

        $payload = [
            'login' => $login,
            'password' => $password,
        ];

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
                ->post($url);
        } catch (ConnectionException $e) {
            throw new BitrixCabinetException('BITRIX_ERROR', 502, 'Cabinet auth bridge is unavailable.', $e);
        }

        $body = $response->json();

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
}
