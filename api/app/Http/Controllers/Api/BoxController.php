<?php

namespace App\Http\Controllers\Api;

use App\DTO\BoxFiltersDTO;
use App\Http\Requests\Api\BoxListRequest;
use App\Repositories\Contracts\BoxRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

final class BoxController extends ApiController
{
    public function __construct(
        private readonly BoxRepositoryInterface $boxes,
    ) {}

    /**
     * GET /api/boxes
     *
     * Список боксов с фильтрацией и пагинацией.
     *
     * Query params:
     *   stock_id    — ID склада (b_iblock_section.id)
     *   status      — free | rented | reserved | freeing_7 | freeing_14
     *   size_min    — минимальная площадь, м²
     *   size_max    — максимальная площадь, м²
     *   object_type — Бокс | Ячейка | Контейнер | Антресольный бокс
     *   page        — страница (default 1)
     *   per_page    — элементов на странице (default 30, max 100)
     *
     * Response meta.total — полное количество записей по фильтру (для пагинации).
     */
    public function index(BoxListRequest $request): JsonResponse
    {
        try {
            $filters = BoxFiltersDTO::fromArray($request->validated());
            $boxes   = $this->boxes->getList($filters);
            $total   = $this->boxes->countList($filters);

            return $this->collection(
                items:   array_map(fn($b) => $b->toArray(), $boxes),
                total:   $total,
                page:    $filters->page,
                perPage: $filters->perPage,
            );
        } catch (Throwable $e) {
            Log::error('BoxController@index failed', [
                'error'  => $e->getMessage(),
                'params' => $request->validated(),
            ]);

            return $this->serviceUnavailable('Не удалось загрузить список боксов');
        }
    }

    /**
     * GET /api/boxes/{id}
     *
     * Карточка конкретного бокса.
     * Возвращает 404 если бокс не найден или неактивен.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $box = $this->boxes->getById($id);

            if ($box === null) {
                return $this->notFound("Бокс #$id не найден");
            }

            return $this->item($box->toArray());
        } catch (Throwable $e) {
            Log::error("BoxController@show failed for id=$id", [
                'error' => $e->getMessage(),
            ]);

            return $this->serviceUnavailable('Не удалось загрузить данные бокса');
        }
    }
}
