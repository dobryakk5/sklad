<?php

namespace Api\Services\Actions\Service;

use Api\DomainServices\ServiceService;
use Api\Services\ActionResult;
use Api\Services\Actions\ActionAbstract;

class SpaceCalcPosition extends ActionAbstract
{
    protected $needParams = [
        'limit',
        'lastId',
    ];

    public function execute()
    {
        $data = $this->data;
        $limit = $data['limit'] !== null ? (int)$data['limit'] : null;
        $lastId = $data['lastId'] !== null ? (int)$data['lastId'] : null;

        $featuresService = new ServiceService();
        $positions = $featuresService->getSpaceCalcPositions($lastId, $limit);
        $result = [];
        foreach ($positions as $position) {
            /**
             * @var \Api\Models\SpaceCalcPosition $position
             */
            $result[] = [
                'id' => $position->getId(),
                'icon' => $position->getIcon(),
                'title' => $position->getTitle(),
                'space' => $position->getSpace(),
            ];
        }

        $apiCode = 200;
        $actionResult = new ActionResult();
        $resultArray = $result;
        $actionResult->setParams($resultArray);
        $actionResult->setApiCode($apiCode);
        return $actionResult;
    }
}