<?php

namespace Api\Firebase;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

/**
 * Сервис для работы с firebase
 * Class Firebase
 * @package Api\Firebase
 */
class Firebase
{
    /**
     * Отправляет пуш уведомление пользователю по id устройства
     * @param $deviceToken
     * @param $title
     * @param $text
     * @param array $data
     * @param null $imageUrl
     * @return array|bool
     */
    public static function push($deviceToken, $title, $text, $data = [], $imageUrl = null)
    {
        $path = realpath(PATH_LOCAL . '/Api/Firebase/firebase_credentials.json');

        $factory = (new Factory())->withServiceAccount($path);
        $messaging = $factory->createMessaging();
        $notification = Notification::create($title, $text, $imageUrl);

        $message = CloudMessage::withTarget('token', $deviceToken)
            ->withNotification($notification);

        if (!empty($data)) {
            $message = $message->withData($data);
        }

        try {
            $result = $messaging->send($message);
            AddMessage2Log("Было отправлено пуш уведомление на deviceToken: " . $deviceToken . ' Тайтл: ' . $title . ' Текст: ' . $text);
        } catch (\Exception $e) {
            AddMessage2Log("Ошибка отправки пуша пользователю $deviceToken: " . $e->getMessage());
            return false;
        }
        return $result;
    }

    /**
     * Отправляет пуш уведомление в топик
     * @param string $topic
     * @param $title
     * @param $text
     * @param array $data
     * @param null $imageUrl
     * @return array|bool
     */
    public static function pushTopic($topic = 'general', $title, $text, $data = [], $imageUrl = null)
    {
        $path = realpath(PATH_LOCAL . '/Api/Firebase/firebase_credentials.json');
        $factory = (new Factory())->withServiceAccount($path);
        $messaging = $factory->createMessaging();
        $notification = Notification::create($title, $text, $imageUrl);

        $message = CloudMessage::withTarget('topic', $topic)
            ->withNotification($notification);

        if (!empty($data)) {
            $message = $message->withData($data);
        }

        try {
            $result = $messaging->send($message);
        } catch (\Exception $e) {
            AddMessage2Log("Ошибка отправки пуша В топик $topic: " . $e->getMessage());
            return false;
        }

        return $result;
    }
}