<?php

namespace Api\Services\Actions\Requests;


use Api\DomainServices\Requests\Data\BoxData;
use Api\DomainServices\Requests\RequestFormsService;
use Api\Services\ActionResult;
use Api\Services\Actions\ActionAbstract;

class RequestBox extends ActionAbstract
{
    protected $needParams = [
        'destination',
        'reason',
        'thingsAmount',
        'itemsAmount',
        'rooms',
        'volume',
        'typeToStore',
        'boxesCount',
        'workplacesCount',
        'frequency',
        'name',
        'phone',
        'email',
    ];

    public function execute()
    {
        $data = $this->data;
        $boxData = new BoxData();
        $boxData->setFromArray($data);

        $requestFormsService = new RequestFormsService();
        try {
            $requestFormsService->requestBox($boxData);
        } catch (\Exception $e) {
            throw $e;
        }

        $actionResult = new ActionResult();
        $actionResult->setParams([]);
        $actionResult->setApiCode(204);
        return $actionResult;
    }

}