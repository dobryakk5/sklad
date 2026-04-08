<?php

namespace App\DTO;

use App\Domain\Box\RentalMode;

final class WarehouseFiltersDTO
{
    public function __construct(
        public readonly ?RentalMode $rentalMode = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            rentalMode: isset($data['rental_mode'])
                ? RentalMode::tryFrom((string) $data['rental_mode'])
                : null,
        );
    }
}
