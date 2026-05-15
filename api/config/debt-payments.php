<?php

return [
    'source' => env('DEBT_PAYMENT_SOURCE', 'monthly_debt'),
    'link_ttl_days' => (int) env('DEBT_PAYMENT_LINK_TTL_DAYS', 30),
    'public_base_url' => env('DEBT_PAYMENT_PUBLIC_BASE_URL', 'https://alfasklad.ru'),

    'email_enabled' => filter_var(env('DEBT_PAYMENT_EMAIL_ENABLED', true), FILTER_VALIDATE_BOOLEAN),
    'sms_enabled' => filter_var(env('DEBT_PAYMENT_SMS_ENABLED', true), FILTER_VALIDATE_BOOLEAN),

    'queue' => env('DEBT_PAYMENT_QUEUE', 'debt-payments'),
    'status_sync_days' => (int) env('DEBT_PAYMENT_STATUS_SYNC_DAYS', 30),

    'sms_provider' => env('SMS_PROVIDER', 'log'),
    'smsc' => [
        'login' => env('SMS_LOGIN'),
        'password' => env('SMS_PASSWORD'),
        'from' => env('SMS_FROM', 'ALFASKLAD'),
        'base_url' => env('SMSC_BASE_URL', 'https://smsc.ru/sys/send.php'),
    ],
];
