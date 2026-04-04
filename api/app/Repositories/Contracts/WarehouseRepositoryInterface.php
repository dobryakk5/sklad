<?php

namespace App\Repositories\Contracts;

use App\DTO\WarehouseDTO;

interface WarehouseRepositoryInterface
{
    /**
     * Все активные склады с базовыми полями.
     *
     * @return WarehouseDTO[]
     */
    public function getAll(): array;

    /**
     * Один склад по числовому ID секции (b_iblock_section.id).
     */
    public function getById(int $id): ?WarehouseDTO;

    /**
     * Один склад по строковому коду/слагу (b_iblock_section.CODE).
     * Используется фронтендом: GET /api/warehouses/{slug}
     */
    public function getByCode(string $code): ?WarehouseDTO;

    /**
     * Фотогалерея склада — массив URL вида /upload/subdir/file_name.
     *
     * @return string[]
     */
    public function getPhotos(int $warehouseId): array;
}
