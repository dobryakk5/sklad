<?php

namespace Api\DomainServices;
use Api\Models\SpaceCalcPosition;

/**
 * Сервис услуг
 * Class ServiceService
 * @package Api\DomainServices
 */
class ServiceService
{
    /**
     * Получает список позиций для калькулятора площади
     * @param null $limit
     * @param null $lastId
     * @return array
     */
    public function getSpaceCalcPositions($limit = null, $lastId = null)
    {
        $positions = SpaceCalcPosition::getAfterId($limit, $lastId);
        return $positions;
    }
}