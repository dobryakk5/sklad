<?php

namespace Api\Services\Actions\User\Contract;


use Api\DomainServices\User\DocumentsService;
use Api\Services\ActionResult;
use Api\Services\Actions\User\ActionWithUserAbstract;

class GetContractDetail extends ActionWithUserAbstract
{
    public function execute()
    {
        $data = $this->data;

        $contractId = $this->urlParams['contractId'];
        $user = $this->user;

        $documentsService = new DocumentsService();

        try {
            $detail = $documentsService->getContractDetail($user->getId(), $contractId);
        } catch (\Exception $e) {
            throw $e;
        }

        $actionResult = new ActionResult();
        $actionResult->setParams($detail);
        $actionResult->setApiCode(200);
        return $actionResult;
    }
}