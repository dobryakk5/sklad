<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ClientController extends ApiController
{
    /**
     * GET /api/client/rentals
     *
     * Этап 1–2: личный кабинет работает через iframe на alfasklad.ru.
     * Этот эндпоинт — заглушка для совместимости роутинга.
     *
     * Этап 3: реализовать через Bitrix24 REST API с JWT-авторизацией.
     * Вернуть реальный список аренд клиента вместо пустого массива.
     */
    public function rentals(Request $request): JsonResponse
    {
        return $this->collection(
            items:   [],
            total:   0,
            page:    1,
            perPage: 30,
        );
    }
}
