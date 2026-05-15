<?php


namespace Api\Services\Actions\User;


use Api\DomainServices\User\InfoItemsService;
use Api\Models\InfoItem;
use Api\Services\ActionResult;
use Api\Services\Actions\ActionAbstract;

class InfoItems extends ActionAbstract
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

        $service = new InfoItemsService();
        $items = $service->getInfoItems($limit, $lastId);
        $result = [];

        foreach ($items as $item) {
            /**
             * @var InfoItem $item
             */
            $result[] = [
                'id' => $item->getId(),
                'image' => $item->getPreviewPicture(),
            ];
        }

        $apiCode = 200;
        $actionResult = new ActionResult();
        $actionResult->setParams($result);
        $actionResult->setApiCode($apiCode);
        return $actionResult;
    }

}