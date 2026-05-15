<?php

namespace Api\Services\Actions\Pushes;

use Api\Services\ActionResult;
use Api\Services\Actions\User\ActionWithUserAbstract;

/**
 * Абстрактный класс действий с пушами
 * Class PushActionAbstract
 * @package Api\Services\Actions\Pushes
 */
abstract class PushActionAbstract extends ActionWithUserAbstract
{
    protected $needParams = [
        'token'
    ];

    abstract function concreteExecute($token);

    public function execute()
    {
        $data = $this->data;
        $token = $data['token'];
        try {
            $this->concreteExecute($token);
        } catch (\Exception $e) {
            throw $e;
        }

        $actionResult = new ActionResult();
        $actionResult->setParams([]);
        $actionResult->setApiCode(204);
        return $actionResult;
    }
}