<?php

namespace App\DTO;

use App\Domain\Box\BoxStatus;

final class BoxDTO
{
    /**
     * Маппинг текстовых enum-значений этажа (b_iblock_property_enum.VALUE) → фронтовые строки.
     * Всё что не в карте → 'other'.
     */
    private const FLOOR_MAP = [
        '1 этаж'         => 'first',
        '2 этаж'         => 'second',
        '3 этаж'         => 'third',
        '1этаж'          => 'first',
        '2этаж'          => 'second',
        '3этаж'          => 'third',
        '4 этаж'         => 'other',
        '5 этаж'         => 'other',
        '6 этаж'         => 'other',
        'контейнеры'     => 'other',
        'офисы склад 5'  => 'other',
        'офисы склад 7'  => 'other',
    ];

    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $boxNumber,
        public readonly string $code1c,
        public readonly int $warehouseId,
        public readonly ?float $square,
        public readonly ?float $volume,
        public readonly BoxStatus $status,
        public readonly ?string $floor,
        public readonly ?string $objectType,
        public readonly ?string $rentType,
        public readonly ?float $pricePerSqm,
        public readonly ?float $price,
        public readonly ?string $photoUrl,
    ) {}

    public function isAvailable(): bool
    {
        return $this->status->isAvailable();
    }

    private function normalizeFloor(): string
    {
        if ($this->floor === null || trim($this->floor) === '') {
            return 'other';
        }

        $key = mb_strtolower(trim($this->floor));

        return self::FLOOR_MAP[$key] ?? 'other';
    }

    public function toArray(): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'box_number'    => $this->boxNumber,
            'code_1c'       => $this->code1c,
            'warehouse_id'  => $this->warehouseId,
            'square'        => $this->square,
            'volume'        => $this->volume,
            'status'        => $this->status->value,
            'floor'         => $this->normalizeFloor(),
            'object_type'   => $this->objectType,
            'rent_type'     => $this->rentType,
            'price_per_sqm' => $this->pricePerSqm,
            'price'         => $this->price,
            'photo_url'     => $this->photoUrl,
        ];
    }
}
