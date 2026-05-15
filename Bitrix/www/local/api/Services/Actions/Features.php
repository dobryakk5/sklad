<?php

namespace Api\Services\Actions;

use Api\DomainServices\FeaturesService;
use Api\Models\BusinessFeature;
use Api\Services\ActionResult;

class Features extends ActionAbstract
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

        $featuresService = new FeaturesService();
        $features = $featuresService->getFeatures($limit, $lastId);
        $result = [];
        foreach ($features as $feature) {
            /**
             * @var BusinessFeature $feature
             */
            $result[] = [
                'id' => $feature->getId(),
                'icon' => $feature->getIcon(),
                'title' => $feature->getTitle(),
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