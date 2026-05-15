<?php

namespace Api\Services\Actions\User\Boxes;


use Api\DomainServices\User\BoxesService;
use Api\DomainServices\User\Data\BoxInventoryData;
use Api\Services\ActionResult;
use Api\Services\Actions\User\ActionWithUserAbstract;

class BoxInventoryPatch extends ActionWithUserAbstract
{
    protected $needParams = [
        'text',
        'deletedImages',
    ];

    public function execute()
    {
        $data = $this->data;
        $files = $this->files;
        $boxId = (int) $this->urlParams['boxId'];
        $data['addedImages'] = $files['addedImages'] ?? [];
        $data['user'] = $this->user;
        $data['boxId'] = $boxId;
        $boxInventoryData = new BoxInventoryData();
        $boxInventoryData->setFromArray($data);
        $boxesService = new BoxesService();
        $result = $boxesService->updateInventory($boxInventoryData);
        if (!$result) {
            throw new \Exception('Неизвестная ошибка');
        }

        $resultArr = $boxesService->getInventory($this->user->getId(), $boxId);

        $apiCode = 200;
        $actionResult = new ActionResult();
        $actionResult->setParams($resultArr);
        $actionResult->setApiCode($apiCode);
        return $actionResult;
    }
}