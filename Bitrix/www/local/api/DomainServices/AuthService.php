<?php

namespace Api\DomainServices;

use Api\Exceptions\UserNotFoundException;
use Api\Exceptions\ValidationException;

class AuthService
{
    /**
     * Пытается залогинить пользователя и если успешно, то выдаёт токен
     * @param $login
     * @param $password
     * @return null|string
     */
    public function authUser($login, $password)
    {
        $cUser = new \CUser();

        $loginResult = $cUser->Login($login,$password);
	
	if (is_bool($loginResult) && $loginResult) {
            $dbRes = \CUser::GetByID((int) $cUser->GetID());
            $user = $dbRes->Fetch();
            if (!$user) {
                return null;
            }

            $payload = array(
                "email" => $user['EMAIL'],
                "user" => (int) $user['ID']
            );
            $jwtService = new JwtService();

            $token = $jwtService->generateToken($payload);
        } else {
            $token = null;
        }

        return $token;
    }

    /**
     * Восстановление пароля пользователя
     * @param $email
     */
    public function restorePassword($email)
    {
        if (empty($email)) {
            throw new ValidationException('Не заполнено поле email');
        }

        $email = trim($email);

        $cUser = new \CUser();

        // Получаем пользователя по email
        $filter = Array("EMAIL" => $email);
        $rsUser = $cUser::GetList($by = "id", $order = "desc", $filter);
        $user = $rsUser->Fetch();
        if (!$user) {
            throw new UserNotFoundException('Такой пользователь не найден');
        }

        $result = $cUser->SendPassword($user['LOGIN'], $user['EMAIL']);
        if (!empty($result) && $result['TYPE'] == 'ERROR') {
            throw new \Exception($result['MESSAGE']);
        }
        return true;
    }
}
