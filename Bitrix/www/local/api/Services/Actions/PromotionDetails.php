<?php


namespace Api\Services\Actions;


use Api\DomainServices\PromotionsService;
use Api\Exceptions\PromotionNotFoundException;
use Api\Exceptions\StorageNotFoundException;
use Api\Services\ActionResult;

class PromotionDetails extends ActionAbstract
{
    public function execute()
    {
        $id = (int) $this->urlParams['promotionId'];

        $service = new PromotionsService();
        $promotion = $service->getPromotion($id);

        if ($promotion === null) {
            throw new PromotionNotFoundException('Акция не найдена.');
        }

        $result = array(
            'id' => $promotion->getId(),
            'title' => $promotion->getName(),
            'image' => $promotion->getPreviewPicture(),
            'text' => $promotion->getDetailText(),
        );

        $actionResult = new ActionResult();
        $apiCode = 200;
        $actionResult->setParams($result);
        $actionResult->setApiCode($apiCode);

        return $actionResult;
    }

}