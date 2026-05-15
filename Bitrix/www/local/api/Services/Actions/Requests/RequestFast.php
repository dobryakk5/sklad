<?php

namespace Api\Services\Actions\Requests;

use Api\DomainServices\Requests\Data\FastRequestData;
use Api\DomainServices\Requests\RequestFormsService;
use Api\Services\ActionResult;
use Api\Services\Actions\ActionAbstract;

/**
 * Действие формы “Быстрая заявка”
 * Class RequestFast
 * @package Api\Services\Actions\Requests
 */
class RequestFast extends ActionAbstract
{
    protected $needParams = [
        'name',
        'phone',
        'email'
    ];

    public function execute()
    {
        $data = $this->data;

        $fastRequestData = new FastRequestData();
        $fastRequestData->setFromArray($data);

        $requestFormsService = new RequestFormsService();
        try {
            $requestFormsService->requestFastForm($fastRequestData);
        } catch (\Exception $e) {
            throw $e;
        }

        $actionResult = new ActionResult();
        $actionResult->setParams([]);
        $actionResult->setApiCode(204);
        return $actionResult;
    }
}