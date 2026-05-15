<?php

namespace Api\DomainServices;

use Api\Firebase\Firebase;
use Api\Models\User;

/**
 * Сервис для отправки пушей
 * Class PushesService
 * @package Api\DomainServices
 */
class PushesService
{
    const TYPE_PUSH_NOT_PAID_DOCUMENT = '1',
        TYPE_PUSH_NEW_INFORM_RECORD = '2';

    const TOPIC_GENERAL = 'general';

    /**
     * Добавляет токен для пушей пользователю, если такой у него не задан.
     * Также сначала удаляет такой токен у всех пользователей
     * @param User $user
     * @param $token
     * @return bool
     */
    public function addPushTokenForUser(User $user, $token)
    {
        $this->deleteTokenForAllUsers($user->getId(), $token);
        $tokens = $user->getTokens();
        // Если токен уже есть в наборе токенов, то выходим, иначе добавляем его
        if (in_array($token, $tokens)) {
            return true;
        }

        $tokens[] = $token;

        return $this->updateUserTokens($user, $tokens);
    }

    /**
     * Удаляет токе пушей у заданного пользователя
     * @param User $user
     * @param $token
     * @return bool
     */
    public function removePushTokenForUser(User $user, $token)
    {
        $tokens = $user->getTokens();
        // Если токена нет в наборе токенов, то выходим, иначе удаляем его
        if (!in_array($token, $tokens)) {
            return true;
        }
        $existsTokenIndex = array_search($token, $tokens);
        unset($tokens[$existsTokenIndex]);

        return $this->updateUserTokens($user, $tokens);
    }

    /**
     * Обновляет пуш токены у пользователей
     * @param User $user
     * @param $tokens
     * @return bool
     */
    private function updateUserTokens(User $user, $tokens)
    {
        $newFields = array(
            'UF_APP_TOKEN' => $tokens,
        );

        $user->updateUserFields($newFields);
        return true;
    }

    /**
     * Удаляет заданный токен у всех пользователей, кроме нужного
     * @param $excludeUserId
     * @param $token
     * @return bool
     */
    public function deleteTokenForAllUsers($excludeUserId, $token)
    {
        $arFilter = [
            "!ID" => $excludeUserId,
            "UF_APP_TOKEN" => $token
        ];

        $users = User::getByFilter($arFilter);
        if (empty($users)) {
            return true;
        }
        foreach ($users as $user) {
            $this->removePushTokenForUser($user, $token);
        }
        return true;
    }

    /**
     * Отправляет пуш уведомление для заданного id устройства
     * @param $deviceToken
     * @param $title
     * @param $text
     * @param array $data
     * @param null $imageUrl
     * @return array|bool
     */
    public static function sendPush($deviceToken, $title, $text, $data = [], $imageUrl = null)
    {
        return Firebase::push($deviceToken, $title, $text, $data, $imageUrl);
    }

    /**
     * Отправляет пуш уведомления на все токены пользователя
     * @param $userId
     * @param $title
     * @param $text
     * @param array $data
     * @param null $imageUrl
     * @return bool
     */
    public static function sendPushToUser($userId, $title, $text, $data = [], $imageUrl = null)
    {
        $user = User::getUserById($userId);
        // Если пользователь не получен
        if (empty($user)) {
            return true;
        }
        $tokens = $user->getTokens();
        // Если у пользователя не заданы пуш токены
        if (empty($tokens)) {
            return true;
        }

        foreach ($tokens as $token) {
            self::sendPush($token, $title, $text, $data, $imageUrl);
        }
        return true;
    }

    /**
     * Отправляет пуш в заданный топик
     * @param string $topic
     * @param $title
     * @param $text
     * @param array $data
     * @param null $imageUrl
     * @return array|bool
     */
    public static function sendPushToTopic($topic = 'general', $title, $text, $data = [], $imageUrl = null)
    {
        return Firebase::pushTopic($topic, $title, $text, $data, $imageUrl);
    }
}