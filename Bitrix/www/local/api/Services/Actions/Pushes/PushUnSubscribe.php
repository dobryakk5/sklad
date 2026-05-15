<?php

namespace Api\Services\Actions\Pushes;

use Api\DomainServices\PushesService;

/**
 * Выполняет отписку пуш токена
 * Class PushUnSubscribe
 * @package Api\Services\Actions\Pushes
 */
class PushUnSubscribe extends PushActionAbstract
{
    public function concreteExecute($token)
    {
        $pushService = new PushesService();
        return $pushService->removePushTokenForUser($this->user, $token);
    }
}