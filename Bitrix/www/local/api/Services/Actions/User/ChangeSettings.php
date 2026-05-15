<?php


namespace Api\Services\Actions\User;


use Api\DomainServices\User\Data\SettingsData;
use Api\DomainServices\User\SettingsService;
use Api\DomainServices\User\UserService;
use Api\Services\ActionResult;

class ChangeSettings extends ActionWithUserAbstract
{
    protected $needParams = [
        'isEmailOn',
        'isSmsOn',
    ];

    public function execute()
    {
        $data = $this->data;
        $user = $this->getUser();

        $updateData = new SettingsData();
        $updateData->setFromArray($data);
        $updateData->setUser($user);
        $service = new SettingsService();
        $result = array();

        try {
            if ($service->updateSettings($updateData)) {
                $updatedUser = UserService::getUserById($user->getId());

                $result = array(
                    'isEmailOn' => $updatedUser->getEmailNotify(),
                    'isSmsOn' => $updatedUser->getSmsNotify(),
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