<?php

namespace Api\Services\Actions\Storages;

use Api\DomainServices\Storages\StorageGalleryService;
use Api\DomainServices\Storages\StorageService;
use Api\Models\Storage;
use Api\Services\ActionResult;
use Api\Services\Actions\ActionAbstract;
use Api\Services\SquareImageService;

class Storages extends ActionAbstract
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

        $service = new StorageService();
        $items = $service->getStorages(null, $limit, $lastId);
        $result = [];

        foreach ($items as $item) {
            /**
             * @var Storage $item
             */

            $squareFrom = 1;
            $squareTo = $service->getSquareToForStorage($item->getCode());
            $volumeTo = $service->getVolumeToForStorage($item->getCode());

            $galleryService = new StorageGalleryService();
            $imageGallery = $galleryService->getGalleryPictures($item->getGalleryId());
            $calcPicture = SITE_FULL_DOMAIN .
                SquareImageService::getRentalCatalogSquareImage(SquareImageService::PICTURE_SIZE_BIG, $squareFrom);
            $imagesForCalculator = array_merge(array($calcPicture), $imageGallery);

            $result[] = [
                'id' => $item->getId(),
                'title' => $item->getName(),
                'address' => $item->getAddress(),
                'worktime' => $item->getWorkTime(),
                'accessMode' => $item->getAccessMode(),
                'phone' => $item->getPhone(),
                'metroStations' => $item->getMetroStations(),
                'busStations' => $item->getBusStations(),
                'images' => (array) $imageGallery,
                'description' => $item->getStorageDescription(),
                'volumeFrom' => 1,
                'volumeTo' => $volumeTo,
                'spaceFrom' => 1,
                'spaceTo' => $squareTo,
                'rentDurationFrom' => 1,
                'rentDurationTo' => 12,
                'imagesForCalculator' => (array) $imagesForCalculator,
            ];
        }

        $apiCode = 200;
        $actionResult = new ActionResult();
        $actionResult->setParams($result);
        $actionResult->setApiCode($apiCode);

        return $actionResult;
    }
}