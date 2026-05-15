<?php

namespace App\Services\Sms;

interface SmsProviderInterface
{
    /**
     * @return array{provider_message_id?: string|null, response?: array<string, mixed>|null}
     */
    public function send(string $phone, string $text): array;
}
