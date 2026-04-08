<?php

namespace App\DTO;

use App\Domain\Box\RentalMode;
use App\Support\Bitrix\BitrixBoxStatusMapper;

final class BoxFiltersDTO
{
    public function __construct(
        public readonly ?int    $warehouseId = null,
        public readonly ?string $status = null,
        public readonly ?float  $squareMin = null,
        public readonly ?float  $squareMax = null,
        public readonly ?string $objectType = null,
        public readonly ?RentalMode $rentalMode = null,
        public readonly int     $page = 1,
        public readonly int     $perPage = 30,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            warehouseId: isset($data['stock_id']) ? (int) $data['stock_id'] : null,
            status: $data['status'] ?? null,
            squareMin: isset($data['size_min']) ? (float) $data['size_min'] : null,
            squareMax: isset($data['size_max']) ? (float) $data['size_max'] : null,
            objectType: $data['object_type'] ?? null,
            rentalMode: isset($data['rental_mode']) ? RentalMode::tryFrom((string) $data['rental_mode']) : null,
            page: isset($data['page']) ? max(1, (int) $data['page']) : 1,
            perPage: isset($data['per_page']) ? min(100, max(1, (int) $data['per_page'])) : 30,
        );
    }

    /**
     * @return int[]|null
     */
    public function statusEnumIds(): ?array
    {
        return BitrixBoxStatusMapper::toEnumIds($this->status);
    }

    public function offset(): int
    {
        return ($this->page - 1) * $this->perPage;
    }
}
