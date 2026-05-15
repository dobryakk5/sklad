<?php

namespace Api\Services\Actions\User\Contract;


use Api\DomainServices\User\Data\ContractPayData;
use Api\DomainServices\User\DocumentsService;
use Api\Services\ActionResult;
use Api\Services\Actions\User\ActionWithUserAbstract;

class GetContractPayUrl extends ActionWithUserAbstract
{
    protected $needParams = [
        'docs'
    ];

    public function execute()
    {
        $data = $this->data;

        $data['user'] = $this->user;
        $data['contractId'] = $this->urlParams['contractId'];

        $documentsService = new DocumentsService();
        $contractPayData = new ContractPayData();
        $contractPayData->setFromArray($data);

        try {
            $paymentLink = $documentsService->getPayLinkForContractDocuments($contractPayData);
        } catch (\Exception $e) {
            throw $e;
        }

        $actionResult = new ActionResult();
        $actionResult->setParams([
            'url' => $paymentLink
        ]);
        $actionResult->setApiCode(200);
        return $actionResult;
    }
}