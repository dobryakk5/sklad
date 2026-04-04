<?php

namespace App\Repositories\Contracts;

use App\DTO\BoxDTO;
use App\DTO\BoxFiltersDTO;

interface BoxRepositoryInterface
{
    /**
     * Список боксов с опциональной фильтрацией и пагинацией.
     *
     * @return BoxDTO[]
     */
    public function getList(BoxFiltersDTO $filters): array;

    /**
     * Полное количество боксов, удовлетворяющих фильтру (без LIMIT/OFFSET).
     * Нужен для корректного meta.total в ответе на GET /api/boxes.
     */
    public function countList(BoxFiltersDTO $filters): int;

    /**
     * Один бокс по ID элемента (b_iblock_element.id).
     */
    public function getById(int $id): ?BoxDTO;
}
