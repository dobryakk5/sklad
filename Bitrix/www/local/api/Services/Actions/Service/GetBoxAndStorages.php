<?php


namespace Api\Services\Actions\Service;


use Api\DomainServices\Storages\StorageService;
use Api\DomainServices\User\BoxesService;
use Api\Exceptions\ValidationException;
use Api\Models\Storage;
use Api\Services\ActionResult;
use Api\Services\Actions\ActionAbstract;
use Api\Services\SquareImageService;

class GetBoxAndStorages extends ActionAbstract
{
    protected $needParams = [
        'space'
    ];

    public function execute()
    {
        $maxBoxSpace = 50;
        $data = $this->data;
        $space =  !empty($data['space']) ? $data['space'] : 1;

        if (!is_numeric($space)) {
            throw new ValidationException('Параметр space должен быть числовым значением.');
        }

        $space = floatval($space);

        $storageService = new StorageService();
        $storages = $storageService->getStorages();
        $storagesResult = array();

        /**
         * @var Storage $storage
         */
        foreach ($storages as $storage) {
            $storagesResult[] = array(
                'id' => $storage->getId(),
                'title' => $storage->getName(),
                'address' => $storage->getAddress()
            );
        }

        $box = BoxesService::getBoxBySpace($space);
        $boxVolume = null;
        $boxSpace = $space;

        if ($box) {
            $boxVolume = $box->getVolume();
            $boxSpace = $box->getSquare();
        }

        $squareFrom = $boxSpace > $maxBoxSpace ? $maxBoxSpace : $boxSpace;
        $image = SITE_FULL_DOMAIN .
            SquareImageService::getRentalCatalogSquareImage(SquareImageService::PICTURE_SIZE_BIG, $squareFrom);

        $result = array(
            'box' => array(
                'volume' => !empty($boxVolume) ? (float) $boxVolume : null,
                'space' => (float) $boxSpace,
                'image' => $image,
            ),
            'storages' => $storagesResult
        );

        $apiCode = 200;
        $actionResult = new ActionResult();
        $actionResult->setParams($result);
        $actionResult->setApiCode($apiCode);

        return $actionResult;
    }

}