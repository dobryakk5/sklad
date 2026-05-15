<?php


namespace Api\Services\Actions\User\Contract;


use Api\DomainServices\User\Data\AutoDebitData;
use Api\DomainServices\User\DocumentsService;
use Api\Services\ActionResult;
use Api\Services\Actions\User\ActionWithUserAbstract;

class SetAutoDebit extends ActionWithUserAbstract
{
    protected $needParams = [
        'isAutoEnabled'
    ];

    public function execute()
    {
        $data = $this->data;
        $user = $this->getUser();
        $contractId = $this->urlParams['contractId'];

        $updateData = new AutoDebitData();
        $updateData->setFromArray($data);
        $updateData->setUser($user);
        $updateData->setContractId($contractId);
        $service = new DocumentsService();
        $result = array();

        try {
            if ($service->setAutoDebit($updateData)) {
                $updatedContract = $service->getContractForUser($user->getId(), $updateData->getContractId());
                $autoEnabled = $updatedContract->getAutoDebitEnabled() === null ? false : $updatedContract->getAutoDebitEnabled();
                $result = array(
                    'isAutoEnabled' => $autoEnabled,
                );
            }
        } catch (\Exception $e) {
            throw $e;
        }

        $actionResult = new ActionResult();
        $actionResult->setParams($result);
        $actionResult->setApiCode(200);
        return $actionResult;
    }

}