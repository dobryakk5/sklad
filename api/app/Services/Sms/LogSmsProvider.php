<?php

namespace App\Services\Sms;

use Illuminate\Support\Facades\Log;

final class LogSmsProvider implements SmsProviderInterface
{
    public function send(string $phone, string $text): array
    {
        Log::channel('debt_payments')->info('SMS notification logged.', [
            'phone' => $phone,
            'text' => $text,
        ]);

        return [
            'provider_message_id' => null,
            'response' => ['logged' => true],
        ];
    }
}
