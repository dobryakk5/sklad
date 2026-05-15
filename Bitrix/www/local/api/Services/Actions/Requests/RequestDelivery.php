<?php

namespace Api\Services\Actions\Requests;


use Api\DomainServices\Requests\RequestFormsService;
use Api\DomainServices\Requests\Data\DeliveryData;
use Api\Services\ActionResult;
use Api\Services\Actions\ActionAbstract;

/**
 * Действие формы заказа доставки
 * Class RequestDelivery
 * @package Api\Services\Actions\Requests
 */
class RequestDelivery extends ActionAbstract
{
    protected $needParams = [
        'direction',
	    'fromAddress',
	    'toStoreId',
	    'fromStoreId',
	    'toAddress',
	    'description',
	    'date',
	    'needPackage',
	    'phone',
        'email',
        'name'
    ];

    public function execute()
    {
        $data = $this->data;

        $deliveryData = new DeliveryData();
        $deliveryData->setFromArray($data);

        $requestFormsService = new RequestFormsService();
        try {
            $requestFormsService->requestDelivery($deliveryData);
        } catch (\Exception $e) {
            throw $e;
        }

        $actionResult = new ActionResult();
        $actionResult->setParams([]);
        $actionResult->setApiCode(204);
        return $actionResult;
    }
}