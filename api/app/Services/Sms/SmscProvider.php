<?php

namespace App\Services\Sms;

use RuntimeException;
use Illuminate\Support\Facades\Http;

final class SmscProvider implements SmsProviderInterface
{
    public function send(string $phone, string $text): array
    {
        $login = (string) config('debt-payments.smsc.login');
        $password = (string) config('debt-payments.smsc.password');

        if ($login === '' || $password === '') {
            throw new RuntimeException('SMSC credentials are not configured.');
        }

        $response = Http::timeout(10)->asForm()->post((string) config('debt-payments.smsc.base_url'), [
            'login' => $login,
            'psw' => $password,
            'phones' => $phone,
            'mes' => $text,
            'sender' => (string) config('debt-payments.smsc.from'),
            'fmt' => 3,
            'charset' => 'utf-8',
        ]);

        if (! $response->successful()) {
            throw new RuntimeException('SMSC request failed with HTTP ' . $response->status());
        }

        $body = $response->json();

        if (is_array($body) && isset($body['error'])) {
            throw new RuntimeException((string) $body['error']);
        }

        return [
            'provider_message_id' => is_array($body) ? (string) ($body['id'] ?? '') : null,
            'response' => is_array($body) ? $body : ['raw' => $response->body()],
        ];
    }
}
