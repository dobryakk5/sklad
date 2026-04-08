<?php

namespace App\Support\Cache;

use App\DTO\BoxFiltersDTO;
use App\DTO\WarehouseFiltersDTO;

/**
 * Централизованное управление ключами и TTL Redis-кэша.
 *
 * Правила именования ключей: bitrix:{сущность}:{параметры}
 * Тег-группы: bitrix.warehouses, bitrix.boxes
 *
 * TTL по чеклисту:
 *   - availability (склады с counts)  → 1 мин
 *   - boxes list / single box         → 5 мин
 *   - photos                          → 30 мин (меняются редко)
 */
final class CacheKeys
{
    public const TTL_AVAILABILITY = 60;       // 1 мин — наличие боксов
    public const TTL_BOXES        = 300;      // 5 мин — список/карточка бокса
    public const TTL_PHOTOS       = 1800;     // 30 мин — фотогалерея

    // ------------------------------------------------------------------ //
    //  Warehouse keys                                                      //
    // ------------------------------------------------------------------ //

    public static function warehouseAll(WarehouseFiltersDTO $filters): string
    {
        $params = [
            'rm' => $filters->rentalMode?->value,
        ];

        return 'bitrix:warehouses:all:' . md5(serialize($params));
    }

    public static function warehouseById(int $id): string
    {
        return "bitrix:warehouses:id:{$id}";
    }

    public static function warehouseByCode(string $code): string
    {
        return "bitrix:warehouses:code:{$code}";
    }

    public static function warehousePhotos(int $warehouseId): string
    {
        return "bitrix:warehouses:{$warehouseId}:photos";
    }

    // ------------------------------------------------------------------ //
    //  Box keys                                                            //
    // ------------------------------------------------------------------ //

    public static function boxList(BoxFiltersDTO $filters): string
    {
        // Детерминированный ключ из параметров фильтрации
        $params = [
            'wh'   => $filters->warehouseId,
            'st'   => $filters->status,
            'smin' => $filters->squareMin,
            'smax' => $filters->squareMax,
            'ot'   => $filters->objectType,
            'rm'   => $filters->rentalMode?->value,
            'pg'   => $filters->page,
            'pp'   => $filters->perPage,
        ];

        return 'bitrix:boxes:list:' . md5(serialize($params));
    }

    public static function boxById(int $id): string
    {
        return "bitrix:boxes:id:{$id}";
    }

    // ------------------------------------------------------------------ //
    //  Invalidation tag groups                                             //
    // ------------------------------------------------------------------ //

    /** Все ключи складов (для flush при необходимости). */
    public static function warehousePattern(): string
    {
        return 'bitrix:warehouses:*';
    }

    /** Все ключи боксов. */
    public static function boxPattern(): string
    {
        return 'bitrix:boxes:*';
    }
}
