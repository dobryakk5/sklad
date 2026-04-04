<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

abstract class ApiController extends Controller
{
    /**
     * Единый формат успешного ответа со списком.
     *
     * {
     *   "data": [...],
     *   "meta": { "total": 1200, "page": 1, "per_page": 30 }
     * }
     *
     * @param int $total Полное количество записей (без LIMIT/OFFSET).
     *                   Для не-пагинированных списков (getAll) = count($items).
     */
    protected function collection(
        array $items,
        int   $total,
        int   $page    = 1,
        int   $perPage = 30,
        int   $status  = 200,
    ): JsonResponse {
        return response()->json([
            'data' => $items,
            'meta' => [
                'total'    => $total,   // реальный COUNT(*), не count($items)
                'page'     => $page,
                'per_page' => $perPage,
            ],
        ], $status);
    }

    /**
     * Единый формат успешного ответа с одним объектом.
     *
     * { "data": {...} }
     */
    protected function item(array $data, int $status = 200): JsonResponse
    {
        return response()->json(['data' => $data], $status);
    }

    /**
     * 404 — ресурс не найден.
     */
    protected function notFound(string $message = 'Not found'): JsonResponse
    {
        return response()->json(['error' => $message], 404);
    }

    /**
     * 503 — внешний сервис (Битрикс БД) недоступен.
     */
    protected function serviceUnavailable(string $message = 'Service unavailable'): JsonResponse
    {
        return response()->json(['error' => $message], 503);
    }
}
