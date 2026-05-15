<?php

namespace Api\Services\Actions;


use Api\DomainServices\AuthService;
use Api\Exceptions\UserNotFoundException;
use Api\Services\ActionResult;

class Auth extends ActionAbstract
{
    protected $needParams = [
        'login',
        'password'
    ];

    public function execute()
    {
        $data = $this->data;
        $login = $data['login'];
        $passowrd = $data['password'];

        $authService = new AuthService();
        $token = $authService->authUser($login, $passowrd);


//        file_put_contents(__DIR__ . '/log.txt', date(DATE_ATOM), FILE_APPEND);
//        file_put_contents(__DIR__ . '/log.txt', print_r($data, true), FILE_APPEND);
//        file_put_contents(__DIR__ . '/log.txt', 'token:' . print_r($token, true)."\n", FILE_APPEND);
        if (!$token) {


            throw new UserNotFoundException('Неверный логин или пароль');
        }
        $resultArray = [
            'token' => $token
        ];
        $apiCode = 200;
        $actionResult = new ActionResult();
        $actionResult->setParams($resultArray);
        $actionResult->setApiCode($apiCode);
        return $actionResult;
    }
}