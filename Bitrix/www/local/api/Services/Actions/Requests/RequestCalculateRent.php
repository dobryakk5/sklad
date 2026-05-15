<?php

namespace Api\Services\Actions\Requests;


use Api\DomainServices\Requests\Data\CalculateRentData;
use Api\DomainServices\Requests\RequestFormsService;
use Api\Services\ActionResult;
use Api\Services\Actions\ActionAbstract;

class RequestCalculateRent extends ActionAbstract
{
    protected $needParams = [
        'storageId',
	    'sizeType',
	    'size',
	    'rentDuration',
	    'withDelivery',
	    'withPackage',
	    'name',
	    'phone',
	    'email',
    ];

    public function execute()
    {
        $data = $this->data;
        $calculateRentData = new CalculateRentData();
        $calculateRentData->setFromArray($data);

        $requestFormsService = new RequestFormsService();
        try {
            $requestFormsService->requestCalculateRent($calculateRentData);
        } catch (\Exception $e) {
            throw $e;
        }

        $actionResult = new ActionResult();
        $actionResult->setParams([]);
        $actionResult->setApiCode(204);
        return $actionResult;
    }
}