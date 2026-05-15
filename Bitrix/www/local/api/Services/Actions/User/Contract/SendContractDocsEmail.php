<?php

namespace Api\Services\Actions\User\Contract;

use Api\DomainServices\User\Data\ContractPayData;
use Api\DomainServices\User\DocumentsService;
use Api\Services\ActionResult;
use Api\Services\Actions\User\ActionWithUserAbstract;

class SendContractDocsEmail extends ActionWithUserAbstract
{
    protected $needParams = [
        'docs'
    ];

    public function execute()
    {
        $data = $this->data;

        $data['contractId'] = $this->urlParams['contractId'];
        $data['user'] = $this->user;

        $dataContractDocuments = new ContractPayData();
        $dataContractDocuments->setFromArray($data);

        $documentsService = new DocumentsService();
        try {
            $documentsService->sendDocsEmail($dataContractDocuments);
        } catch (\Exception $e) {
            throw $e;
        }

        $actionResult = new ActionResult();
        $actionResult->setParams([]);
        $actionResult->setApiCode(204);
        return $actionResult;
    }
}