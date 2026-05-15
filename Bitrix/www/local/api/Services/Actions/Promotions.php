<?php


namespace Api\Services\Actions;


use Api\DomainServices\PromotionsService;
use Api\Models\Promotion;
use Api\Services\ActionResult;

class Promotions extends ActionAbstract
{
    protected $needParams = [
        'limit',
        'lastId'
    ];

    public function execute()
    {
        $data = $this->data;
        $limit = $data['limit'] !== null ? (int) $data['limit'] : null;
        $lastId = $data['lastId'] !== null ? (int) $data['lastId'] : null;

        $service = new PromotionsService();
        $items = $service->getPromotions($limit, $lastId);
        $result = [];

        foreach ($items as $item) {
            /**
             * @var Promotion $item
             */

            $result[] = [
                'id' => $item->getId(),
                'image' => $item->getPreviewPicture()
            ];
        }

        $apiCode = 200;
        $actionResult = new ActionResult();
        $actionResult->setParams($result);
        $actionResult->setApiCode($apiCode);

        return $actionResult;
    }
}