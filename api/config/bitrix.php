<?php

return [
    'status_ids' => [
        'free'       => 346,
        'rented'     => 341,
        'reserved'   => 347,
        'freeing_7'  => 344,
        'freeing_14' => 345,

        // Пока не используются в публичном API, но оставлены в конфиге
        // как инфраструктурные знания о Bitrix.
        'service'    => 342,
        'abandoned'  => 343,
        'deleted'    => 401,
    ],

    'iblock' => [
        'contracts' => 52,
        'invoices' => 53,
        'payment_methods' => 69,
    ],

    'contract_status_active_id' => (int) env('BITRIX_CONTRACT_STATUS_ACTIVE_ID', 352),

    'invoice_status_map' => [
        354 => 'not_paid',
        355 => 'paid',
        356 => 'partial',
        400 => 'cancelled',
        421 => 'processing',
    ],

    'invoice_payable_status_ids' => [
        354,
        421,
        356,
    ],

    'service_base_url' => env('BITRIX_CABINET_BASE_URL', env('BITRIX_API_BASE_URL')),
    'service_secret' => env('BITRIX_API_SECRET', env('CABINET_SERVICE_SECRET')),
    'service_timeout' => (int) env('BITRIX_SERVICE_TIMEOUT', 10),
];
