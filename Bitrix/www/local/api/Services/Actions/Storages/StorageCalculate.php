<?php

namespace Api\Services\Actions\Storages;

use Api\DomainServices\Storages\Data\StoragesCostData;
use Api\DomainServices\Storages\StorageGalleryService;
use Api\DomainServices\Storages\StorageService;
use Api\Models\Storage;
use Api\Services\ActionResult;
use Api\Services\Actions\ActionAbstract;
use Api\Services\SquareImageService;

class StorageCalculate extends ActionAbstract
{

    protected $needParams = [
        'sizeType',
        'size',
        'rentDuration',
        'storageId'
    ];

    public function execute()
    {
        $data = $this->data;

        $storagesCostData = new StoragesCostData();
        $storagesCostData->setFromArray($data);

        $requestFormsService = new StorageService();
        try {
            $perMonthCost = $requestFormsService->calculatePerMonthCost($storagesCostData);

            $storage = Storage::getStorage($storagesCostData->getStorageId());
            $squareFrom = (float) $storagesCostData->getSize();

            $galleryService = new StorageGalleryService();
            $calcPicture = SquareImageService::getRentalCatalogSquareImage(SquareImageService::PICTURE_SIZE_BIG, $squareFrom);
            $calcPicturePath = $_SERVER["DOCUMENT_ROOT"] . $calcPicture;
            $fileExists = file_exists($calcPicturePath);
            $imagesForCalculator = [];

            if ($fileExists) {
                $calcPicture = SITE_FULL_DOMAIN . $calcPicture;
                $imagesForCalculator[] = $calcPicture;
                $needImagesCount = 2;
            } else {
                $needImagesCount = false;
            }

            $imagesGallery = $galleryService->getGalleryPictures($storage->getGalleryId(), $needImagesCount);

            $imagesForCalculator = array_merge($imagesForCalculator, $imagesGallery);

            $result = [
                'costPerMonth' => $perMonthCost,
                'images' => $imagesForCalculator
            ];
        } catch (\Exception $e) {
            throw $e;
        }

        $actionResult = new ActionResult();
        $actionResult->setParams($result);
        $actionResult->setApiCode(200);
        return $actionResult;
    }
}