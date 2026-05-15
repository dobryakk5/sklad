<?php

namespace Api\Services\Actions\User\Boxes;

use Api\DomainServices\User\BoxesService;

class BoxInventory extends \Api\Services\Actions\User\ActionWithUserAbstract
{
    public function execute()
    {
        $boxId = (int) $this->urlParams['boxId'];
        $boxService = new BoxesService();
        $inventoryData = $boxService->getInventory($this->user->getId(), $boxId);

        $actionResult = new \Api\Services\ActionResult();
        $actionResult->setParams($inventoryData);
        $actionResult->setApiCode(200);
        return $actionResult;
    }
}