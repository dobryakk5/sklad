<?php

namespace Api\Services\Actions\Requests;


use Api\DomainServices\Requests\Data\SpaceVolumeStorageRentData;
use Api\DomainServices\Requests\RequestFormsService;
use Api\Services\ActionResult;
use Api\Services\Actions\ActionAbstract;

/**
 * Отправка формы заявки на аренду по площади, объему и складу
 * Class RequestSpaceVolumeStorageRent
 * @package Api\Services\Actions\Requests
 */
class RequestSpaceVolumeStorageRent extends ActionAbstract
{
    protected $needParams = [
        'storageId',
        'volume',
        'space',
        'name',
        'phone',
        'email',
    ];

    public function execute()
    {
        $data = $this->data;
        $spaceVolumeStorageData = new SpaceVolumeStorageRentData();
        $spaceVolumeStorageData->setFromArray($data);

        $requestFormsService = new RequestFormsService();
        try {
            $requestFormsService->requestSpaceVolumeStorage($spaceVolumeStorageData);
        } catch (\Exception $e) {
            throw $e;
        }

        $actionResult = new ActionResult();
        $actionResult->setParams([]);
        $actionResult->setApiCode(204);
        return $actionResult;
    }
}