<?php

namespace App\DTO\DebtPayments;

final readonly class DebtPaymentCandidate
{
    public function __construct(
        public int $bitrixUserId,
        public string $customerName,
        public ?string $email,
        public ?string $phone,
        public int $invoiceId,
        public string $invoiceNumber,
        public ?string $invoiceGuid,
        public ?int $contractId,
        public string $contractNumber,
        public ?string $contractGuid,
        public string $amount,
        public string $currency = 'RUB',
    ) {}
}
