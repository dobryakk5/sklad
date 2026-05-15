<?php

namespace Api\Services\Actions;

use Api\DomainServices\AuthService;
use Api\Services\ActionResult;

/**
 * Действие восстановления пароля
 * Class ForgotPassword
 * @package Api\Services\Actions
 */
class ForgotPassword extends ActionAbstract
{
    protected $needParams = [
        'email'
    ];

    public function execute()
    {
        $data = $this->data;
        $email = $data['email'];

        $authService = new AuthService();
        $authService->restorePassword($email);

        $apiCode = 204;
        $actionResult = new ActionResult();
        $actionResult->setParams([]);
        $actionResult->setApiCode($apiCode);
        return $actionResult;
    }
}