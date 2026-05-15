<?php

namespace Api\Services\Actions\User;


use Api\DomainServices\JwtService;
use Api\DomainServices\User\UserService;
use Api\Exceptions\UserNotFoundException;
use Api\Models\User;
use Api\Services\Actions\ActionAbstract;

/**
 * Абстрактный класс действия с пользователем
 * Class ActionWithUserAbstract
 * @package Api\Services\Actions\User
 */
abstract class ActionWithUserAbstract extends ActionAbstract
{
    /**
     * @var User $user
     */
    protected $user;

    /**
     * Инициализация действия
     */
    public function init()
    {
        // Проверяем токен в заголовке
        $remote_user = $_SERVER["REMOTE_USER"]
            ? $_SERVER["REMOTE_USER"] : $_SERVER["REDIRECT_REMOTE_USER"];
        $token = substr($remote_user,7);
                    
        if (empty($token)) {
            throw new UserNotFoundException('User not found');
        }
        $jwtService = new JwtService();
	try {
            $payload = $jwtService->decodePayload($token);
        } catch (\Exception $e) {
    	    AddMessage2Log("Ошибка декодирования токена $token: " . $e->getMessage());
    	    throw new UserNotFoundException('User not found');
        }
        // Получаем id пользователя и проверяем есть ли он и активен ли
        $userId = $payload->user;
        $user = UserService::getUserById($userId);
        if ($user === null) {
            throw new UserNotFoundException('User not found');
        }
        $this->user = $user;

        parent::init();
    }

    public function getUser(): ?User
    {
        return $this->user;
    }
}