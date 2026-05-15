<?php

namespace Api\Services\Actions\User;


use Api\DomainServices\User\DocumentsService;
use Api\Helpers\Date;
use Api\Models\Contract;
use Api\Models\User;
use Api\Services\ActionResult;

class GetUserInfo extends ActionWithUserAbstract
{
    public function execute()
    {
        $user = $this->getUser();

        /**
         * @var User $user
         */
        $documentsService = new DocumentsService();
        $userContracts = $documentsService->getUserContracts($user->getId());
        $userContractsData = [];

        // Общий баланс
        $userBalance = $user->getBalance();

        foreach ($userContracts as $userContract) {
            /**
             * @var Contract $userContract
             */

            $contractBalance = $userContract->getBalance();
            if ($contractBalance != 0) {
                $contractBalance = $contractBalance * (-1);
            }

            $userContractsData[] = [
                'id' => $userContract->getId(),
                'number' => $userContract->getNumber(),
                'balanceValue' => $contractBalance,
                'balanceDate' => Date::getNowMilliseconds(),
                'payedTill' => $userContract->getPaidDateTo(true),
            ];
        }

        $result = array(
            'email' => $user->getEmail(),
            'balance' => $userBalance,
            'contracts' => $userContractsData,
        );

        $apiCode = 200;
        $actionResult = new ActionResult();
        $actionResult->setParams($result);
        $actionResult->setApiCode($apiCode);
        return $actionResult;
    }
}