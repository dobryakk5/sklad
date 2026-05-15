<?php

namespace Api\Services\Actions\Pushes;

use Api\DomainServices\PushesService;

/**
 * Выполняет подписку пуш токена
 * Class PushSubscribe
 * @package Api\Services\Actions\Pushes
 */
class PushSubscribe extends PushActionAbstract
{
    public function concreteExecute($token)
    {
        $pushService = new PushesService();
        return $pushService->addPushTokenForUser($this->user, $token);
    }
}