<?php


namespace Api\DomainServices\User;


use Api\DomainServices\User\Data\SettingsData;

class SettingsService
{
    public function updateSettings(SettingsData $updateData)
    {
        $result = false;
        $user = $updateData->getUser();

        $newFields = array(
            'UF_EMAIL_NOTIFY' => $updateData->getIsEmailOn(),
            'UF_SMS_NOTIFY' => $updateData->getIsSmsOn()
        );

        try {
            $user->updateUserFields($newFields);
            $result = true;
        } catch (\Exception $e) {
            throw $e;
        }

        return $result;
    }
}