<?php

namespace Api\Services\Actions\Storages;

use Api\DomainServices\Storages\StorageGalleryService;
use Api\DomainServices\Storages\StorageService;
use Api\Exceptions\StorageNotFoundException;
use Api\Services\ActionResult;
use Api\Services\Actions\ActionAbstract;
use Api\Services\SquareImageService;

class StorageDetails extends ActionAbstract
{
    const TYPE_SHORT = 'short',
        TYPE_FULL = 'full';

    public function execute()
    {
        $storageId = (int) $this->urlParams['storageId'];
        $descriptionType = $this->urlParams['descriptionType'];

        $service = new StorageService();
        $storage = $service->getStorage($storageId);

        if ($storage === null) {
            throw new StorageNotFoundException('Склад не найден.');
        }

        $result = array(
            'id' => $storage->getId(),
            'title' => $storage->getName(),
            'address' => $storage->getAddress(),
            'worktime' => $storage->getWorkTime(),
            'accessMode' => $storage->getAccessMode(),
            'phone' => $storage->getPhone(),
        );

        $squareFrom = 1;
        $squareTo = $service->getSquareToForStorage($storage->getCode());
        $volumeFrom = 1;
        $volumeTo = $service->getVolumeToForStorage($storage->getCode());

        if ($descriptionType === self::TYPE_SHORT) {
            $priceFrom = $service->getStorageMinPricePerMonth(StorageService::SIZE_TYPE_M2, $squareFrom, $storage->getCode());
            if (empty($priceFrom)) {
                $priceFrom = (float) $storage->getPriceOnMap();
            }
            $result['priceFrom'] = $priceFrom;
        } elseif ($descriptionType === self::TYPE_FULL) {

            $galleryService = new StorageGalleryService();
            $imageGallery = $galleryService->getGalleryPictures($storage->getGalleryId());
            $calcPicture = SITE_FULL_DOMAIN .
                SquareImageService::getRentalCatalogSquareImage(SquareImageService::PICTURE_SIZE_BIG, $squareFrom);
            $imagesForCalculator = array_merge(array($calcPicture), $imageGallery);

            $result = array_merge($result, array(
                'metroStations' => (array) $storage->getMetroStations(),
                'busStations' => (array) $storage->getBusStations(),
                'images' => (array) $imageGallery,
                'description' => (string) $storage->getStorageDescription(),
                'volumeFrom' => (float) $volumeFrom,
                'volumeTo' => (float) $volumeTo,
                'spaceFrom' => (float) $squareFrom,
                'spaceTo' => (float) $squareTo,
                'rentDurationFrom' => 1,
                'rentDurationTo' => 12,
                'imagesForCalculator' => (array) $imagesForCalculator,
            ));
        }

        $actionResult = new ActionResult();
        $apiCode = 200;
        $actionResult->setParams($result);
        $actionResult->setApiCode($apiCode);

        return $actionResult;
    }
}