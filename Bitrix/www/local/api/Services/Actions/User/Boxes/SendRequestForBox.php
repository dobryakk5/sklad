<?php

namespace Api\Services\Actions\User\Boxes;


use Api\DomainServices\User\BoxesService;
use Api\DomainServices\User\Data\BoxRequestData;
use Api\Services\ActionResult;
use Api\Services\Actions\User\ActionWithUserAbstract;

/**
 * Отправка формы заявки по боксу
 * Class SendRequestForBox
 * @package Api\Services\Actions\User\Boxes
 */
class SendRequestForBox extends ActionWithUserAbstract
{
    protected $needParams = [
        'type',
        'name',
        'email',
        'phone',
        'comment',
        'isWithDelivery'
    ];

    public function execute()
    {
        $data = $this->data;
        $boxId = (int) $this->urlParams['boxId'];

        $data['user'] = $this->user;
        $data['boxId'] = $boxId;

        $boxRequestData = new BoxRequestData();
        $boxRequestData->setFromArray($data);

        $boxesService = new BoxesService();
        $result = $boxesService->createBoxRequest($boxRequestData);

        if (!$result) {
            throw new \Exception('Неизвестная ошибка');
        }

        $apiCode = 204;
        $actionResult = new ActionResult();
        $actionResult->setParams([]);
        $actionResult->setApiCode($apiCode);
        return $actionResult;
    }
}