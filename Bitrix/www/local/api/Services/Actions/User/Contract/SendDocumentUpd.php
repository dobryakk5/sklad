<?php

namespace Api\Services\Actions\User\Contract;

use Api\DomainServices\User\Data\UpdData;
use Api\DomainServices\User\DocumentsService;
use Api\Services\ActionResult;
use Api\Services\Actions\User\ActionWithUserAbstract;

class SendDocumentUpd extends ActionWithUserAbstract
{
    protected $needParams = [
        'name',
        'docs',
    ];

    public function execute()
    {
        $data = $this->data;

        $data['contractId'] = $this->urlParams['contractId'];
        $data['documentId'] = $this->urlParams['documentId'];
        $data['user'] = $this->user;
        $updData = new UpdData();
        $updData->setFromArray($data);
        $documentsService = new DocumentsService();
        try {
            $documentsService->sendUpd($updData);
        } catch (\Exception $e) {
            throw $e;
        }

        $actionResult = new ActionResult();
        $actionResult->setParams([]);
        $actionResult->setApiCode(204);
        return $actionResult;
    }
}