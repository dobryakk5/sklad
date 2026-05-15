<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 31.07.2020
 * Time: 17:05
 */

namespace Api\Services\Actions\Pushes;


use Api\Services\ActionResult;
use Api\Services\Actions\ActionAbstract;

class PushTest extends ActionAbstract
{
    protected $needParams = [
        'user',
        'title',
        'text'
    ];

    public function execute()
    {
        $data = $this->data;
        $userId = $data['user'] !== null ? (int) $data['user'] : null;
        $title = $data['title'] !== null ? $data['title'] : null;
        $text = $data['text'] !== null ? $data['text'] : null;

        if (empty($userId)) {
            dd('Не передан id пользователя');
        }

        \Api\DomainServices\PushesService::sendPushToUser($userId , $title, $text, []);

        $apiCode = 200;
        $actionResult = new ActionResult();
        $actionResult->setApiCode($apiCode);
        return $actionResult;
    }
}