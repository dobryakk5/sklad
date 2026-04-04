<?php

namespace App\Repositories\Cached;

use App\DTO\WarehouseDTO;
use App\Repositories\Contracts\WarehouseRepositoryInterface;
use App\Support\Cache\CacheKeys;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Кэш-декоратор над WarehouseRepositoryInterface.
 *
 * Не содержит логики работы с БД — только обёртки remember/forget.
 * При падении Redis прозрачно проваливается в Bitrix-репозиторий.
 *
 * TTL:
 *   getAll / getById / getByCode  → 1 мин (содержат счётчики наличия — TTL_AVAILABILITY)
 *   getPhotos                     → 30 мин (TTL_PHOTOS)
 *
 * ⚠️  Важно про getPhotos():
 *   BitrixWarehouseRepository::getById() вызывает $this->getPhotos() напрямую —
 *   то есть кэш-декоратор для фото НЕ задействуется в пути getById/getByCode.
 *   Фотографии кэшируются как часть WarehouseDTO на TTL_AVAILABILITY (60с).
 *
 *   getPhotos() через декоратор актуален только для прямых вызовов интерфейса
 *   (например, будущий API-эндпоинт GET /api/warehouses/{id}/photos).
 *   Если потребуется 30-минутный TTL для фото в getById — нужно вынести
 *   getPhotos() из BitrixWarehouseRepository в отдельный injectable-сервис.
 */
final class CachedWarehouseRepository implements WarehouseRepositoryInterface
{
    public function __construct(
        private readonly WarehouseRepositoryInterface $inner,
    ) {}

    /**
     * @return WarehouseDTO[]
     */
    public function getAll(): array
    {
        return $this->remember(
            CacheKeys::warehouseAll(),
            CacheKeys::TTL_AVAILABILITY,
            fn() => $this->inner->getAll(),
        );
    }

    public function getById(int $id): ?WarehouseDTO
    {
        return $this->remember(
            CacheKeys::warehouseById($id),
            CacheKeys::TTL_AVAILABILITY,
            fn() => $this->inner->getById($id),
        );
    }

    public function getByCode(string $code): ?WarehouseDTO
    {
        return $this->remember(
            CacheKeys::warehouseByCode($code),
            CacheKeys::TTL_AVAILABILITY,
            fn() => $this->inner->getByCode($code),
        );
    }

    /**
     * @return string[]
     */
    public function getPhotos(int $warehouseId): array
    {
        return $this->remember(
            CacheKeys::warehousePhotos($warehouseId),
            CacheKeys::TTL_PHOTOS,
            fn() => $this->inner->getPhotos($warehouseId),
        );
    }

    // ------------------------------------------------------------------ //
    //  Internal                                                            //
    // ------------------------------------------------------------------ //

    /**
     * Cache::remember с fallback: если Redis недоступен — идём прямо в БД.
     * Падение Redis не должно ронять сайт — это вспомогательный слой.
     */
    private function remember(string $key, int $ttl, callable $callback): mixed
    {
        try {
            return Cache::store('redis')->remember($key, $ttl, $callback);
        } catch (Throwable $e) {
            Log::warning("Cache miss (Redis unavailable): {$key}", [
                'error' => $e->getMessage(),
            ]);

            return $callback();
        }
    }
}
