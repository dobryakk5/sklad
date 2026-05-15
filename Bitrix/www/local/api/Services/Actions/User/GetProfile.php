<?php


namespace Api\Services\Actions\User;


use Api\Models\User;
use Api\Services\ActionResult;

class GetProfile extends ActionWithUserAbstract
{
    public function execute()
    {
        $user = $this->getUser();

        $result = array(
            'type' => $user->defineUserType(),
            'surname' => $user->getLastName(),
            'firstname' => $user->getName(),
            'middlename' => $user->getSecondName(),
            'birthday' => $user->getBirthDay(),
            'addressRegister' => $user->getAddress(),
            'addressFact' => $user->getActualAddress(),
            'phone' => !empty($user->getPhone()) ? $user->getPhone() : $user->getPhoneFromAdditional(),
            'email' => $user->getEmail(),
            'docSeries' => $user->getPassportSeries(),
            'docNumber' => $user->getPassportNumber(),
            'inn' => $user->getInn(),
            'kpp' => $user->getKpp(),
            'companyName' => $user->defineUserType() == User::TYPE_LEGAL_ENTITY ? $user->getNameFromAdditional() : null,
            'contactName' => $user->getContactFio(),
            'isEmailOn' => $user->getEmailNotify(),
            'isSmsOn' => $user->getSmsNotify(),
        );

        $apiCode = 200;
        $actionResult = new ActionResult();
        $actionResult->setParams($result);
        $actionResult->setApiCode($apiCode);
        return $actionResult;
    }

}