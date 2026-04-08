<?php

namespace App\Http\Controllers\Api;

use App\DTO\WarehouseFiltersDTO;
use App\Http\Requests\Api\WarehouseListRequest;
use App\Repositories\Contracts\WarehouseRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

final class WarehouseController extends ApiController
{
    public function __construct(
        private readonly WarehouseRepositoryInterface $warehouses,
    ) {}

    /**
     * GET /api/warehouses
     *
     * Список всех активных складов (без пагинации — складов немного).
     */
    public function index(WarehouseListRequest $request): JsonResponse
    {
        try {
            $filters    = WarehouseFiltersDTO::fromArray($request->validated());
            $warehouses = $this->warehouses->getAll($filters);
            $items      = array_map(fn($w) => $w->toArray(), $warehouses);

            return $this->collection(
                items:   $items,
                total:   count($items),  // getAll() не пагинирует — total = count
                page:    1,
                perPage: count($items),
            );
        } catch (Throwable $e) {
            Log::error('WarehouseController@index failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->serviceUnavailable('Не удалось загрузить список складов');
        }
    }

    /**
     * GET /api/warehouses/{slug}
     *
     * Карточка склада. Принимает строковый слаг (CODE) или числовой ID.
     * Фронтенд передаёт слаг из URL (warehouse.slug === b_iblock_section.CODE).
     */
    public function show(string $slug): JsonResponse
    {
        try {
            $warehouse = ctype_digit($slug)
                ? $this->warehouses->getById((int) $slug)
                : $this->warehouses->getByCode($slug);

            if ($warehouse === null) {
                return $this->notFound("Склад «{$slug}» не найден");
            }

            return $this->item($warehouse->toArray());
        } catch (Throwable $e) {
            Log::error("WarehouseController@show failed for slug={$slug}", [
                'error' => $e->getMessage(),
            ]);

            return $this->serviceUnavailable('Не удалось загрузить данные склада');
        }
    }
}
