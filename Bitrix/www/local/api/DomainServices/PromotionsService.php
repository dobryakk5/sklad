<?php


namespace Api\DomainServices;


use Api\Models\Promotion;

class PromotionsService
{
    /**
     * Получение промо-акции по ID
     * @param int $id
     * @return Promotion
     */
    public function getPromotion(int $id)
    {
        return Promotion::getPromotion($id);
    }

    /**
     * Получение списка промо-акций
     * @param null $limit
     * @param null $lastId
     * @return array
     */
    public function getPromotions($limit = null, $lastId = null)
    {
        return Promotion::getPromotionsList($limit, $lastId);
    }
}