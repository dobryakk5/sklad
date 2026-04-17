<?php

use App\DTO\WarehouseDTO;
use App\DTO\WarehouseFiltersDTO;
use App\Repositories\Contracts\WarehouseRepositoryInterface;

it('returns warehouse by slug without crashing', function () {
    $repository = new class implements WarehouseRepositoryInterface
    {
        public function getAll(WarehouseFiltersDTO $filters): array
        {
            return [];
        }

        public function getById(int $id): ?WarehouseDTO
        {
            return null;
        }

        public function getByCode(string $code): ?WarehouseDTO
        {
            return new WarehouseDTO(
                id: 503,
                name: 'Склад на Звенигородском ш.',
                code: $code,
                address: 'Звенигородское шоссе, 1',
                phone: '+7 (495) 000-00-00',
                accessHours: '24/7',
                receptionHours: '09:00-21:00',
                lat: 55.0,
                lng: 37.0,
                metro: ['Улица 1905 года'],
                pricePerSqm: 1500.0,
                description: 'Тестовый склад',
                photos: ['/upload/test.jpg'],
                district: 'ЦАО',
                availableBoxesCount: 7,
                totalBoxesCount: 11,
            );
        }

        public function getPhotos(int $warehouseId): array
        {
            return [];
        }
    };

    app()->instance(WarehouseRepositoryInterface::class, $repository);

    $this->getJson('/api/warehouses/sklad-na-zvenigorodskom-sh')
        ->assertOk()
        ->assertJsonPath('data.slug', 'sklad-na-zvenigorodskom-sh')
        ->assertJsonPath('data.id', 503)
        ->assertJsonPath('data.available_boxes_count', 7)
        ->assertJsonPath('data.total_boxes_count', 11);
});
