<?php

namespace Api\Services\Actions\Storages;


use Api\DomainServices\Storages\StorageService;
use Api\Services\ActionResult;
use Api\Services\Actions\ActionAbstract;

class StoragesShort extends ActionAbstract
{
    public function execute()
    {
        $service = new StorageService();
        $storages = $service->getStorages(null);

        $result = [];

        foreach ($storages as $storage) {

            $coords = $storage->getCoords();
            $coords = explode(',', $coords);

            $result[] = array(
                'id' => $storage->getId(),
                'title' => $storage->getName(),
                'address' => $storage->getAddress(),
                'worktime' => $storage->getWorkTime(),
                'accessMode' => $storage->getAccessMode(),
                'phone' => $storage->getPhone(),
                'priceFrom' => (float)trim(str_replace('р/м2', '', $storage->getPriceOnMap())),
                'latitude' => $coords[0], // Широта
                'longitude' => $coords[1], // Долгота
            );
        }

        $actionResult = new ActionResult();
        $apiCode = 200;
        $actionResult->setParams($result);
        $actionResult->setApiCode($apiCode);

        return $actionResult;
    }
}