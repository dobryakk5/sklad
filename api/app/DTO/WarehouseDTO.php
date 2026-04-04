<?php

namespace App\DTO;

final class WarehouseDTO
{
    public function __construct(
        public readonly int     $id,
        public readonly string  $name,
        public readonly string  $code,               // slug для URL (= b_iblock_section.CODE)
        public readonly string  $address,
        public readonly string  $phone,
        public readonly string  $accessHours,        // UF_DOSTUP_TIME ("КРУГЛОСУТОЧНО")
        public readonly string  $receptionHours,     // UF_RECEPTION ("08:30–20:30")
        public readonly ?float  $lat,
        public readonly ?float  $lng,
        public readonly array   $metro,              // UF_METRO unserialize → string[]
        public readonly ?float  $pricePerSqm,        // числовое значение (парсится из "1550 р/м2")
        public readonly ?string $description,
        public readonly array   $photos,
        public readonly ?string $district    = null, // UF_DISTRICT (enumeration, b_user_field ID=139)
        public readonly int     $availableBoxesCount = 0,
        public readonly int     $totalBoxesCount     = 0,
    ) {}

    public function toArray(): array
    {
        return [
            'id'                    => $this->id,
            'name'                  => $this->name,
            'slug'                  => $this->code,
            'district'              => $this->district ?? '',
            'address'               => $this->address,
            'phone'                 => $this->phone,
            'access_hours'          => $this->accessHours,
            'reception_hours'       => $this->receptionHours,
            'coords'                => $this->lat !== null
                ? ['lat' => $this->lat, 'lng' => $this->lng]
                : null,
            'metro'                 => $this->metro,
            'price_per_sqm'         => $this->pricePerSqm,
            'description'           => $this->description,
            'photo_url'             => $this->photos[0] ?? null,
            'photos'                => $this->photos,
            'available_boxes_count' => $this->availableBoxesCount,
            'total_boxes_count'     => $this->totalBoxesCount,
        ];
    }
}
