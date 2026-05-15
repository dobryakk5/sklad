<?php

namespace Api\Services\Actions\User\Contract;


use Api\DomainServices\User\DocumentsService;
use Api\Services\ActionResult;
use Api\Services\Actions\User\ActionWithUserAbstract;

/**
 * Действие получения информации о балансе и долге по договору
 * Class GetContractDebt
 * @package Api\Services\Actions\User\Contract
 */
class GetContractDebt extends ActionWithUserAbstract
{
    public function execute()
    {
        $contractId = $this->urlParams['contractId'];
        $user = $this->user;
        $documentService = new DocumentsService();
        $contractDebtDetail = $documentService->getContractDebtDetail($user->getId(), $contractId);


        $actionResult = new ActionResult();
        $actionResult->setParams($contractDebtDetail);
        $actionResult->setApiCode(200);
        return $actionResult;
    }
}