<?php

namespace App\Repositories\Cached;

use App\DTO\BoxDTO;
use App\DTO\BoxFiltersDTO;
use App\Repositories\Contracts\BoxRepositoryInterface;
use App\Support\Cache\CacheKeys;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Кэш-декоратор над BoxRepositoryInterface.
 *
 * TTL:
 *   getList / countList  → 5 мин (TTL_BOXES) — один ключ на набор фильтров
 *   getById              → 5 мин (TTL_BOXES)
 */
final class CachedBoxRepository implements BoxRepositoryInterface
{
    public function __construct(
        private readonly BoxRepositoryInterface $inner,
    ) {}

    /**
     * @return BoxDTO[]
     */
    public function getList(BoxFiltersDTO $filters): array
    {
        return $this->remember(
            CacheKeys::boxList($filters),
            CacheKeys::TTL_BOXES,
            fn() => $this->inner->getList($filters),
        );
    }

    /**
     * Кэшируем count отдельным ключом (тот же TTL, что и список).
     * Ключ: boxList-ключ + суффикс ':count' — инвалидируется вместе со списком
     * при сбросе через php artisan bitrix:cache:clear --boxes.
     */
    public function countList(BoxFiltersDTO $filters): int
    {
        return $this->remember(
            CacheKeys::boxList($filters) . ':count',
            CacheKeys::TTL_BOXES,
            fn() => $this->inner->countList($filters),
        );
    }

    public function getById(int $id): ?BoxDTO
    {
        return $this->remember(
            CacheKeys::boxById($id),
            CacheKeys::TTL_BOXES,
            fn() => $this->inner->getById($id),
        );
    }

    // ------------------------------------------------------------------ //
    //  Internal                                                            //
    // ------------------------------------------------------------------ //

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
