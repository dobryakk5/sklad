<?php


namespace Api\Services\Actions\User;


use Api\DomainServices\User\InfoItemsService;
use Api\Exceptions\EntityNotFoundException;
use Api\Exceptions\ValidationException;
use Api\Models\InfoItem;
use Api\Services\ActionResult;
use Api\Services\Actions\ActionAbstract;

class InfoItemDetails extends ActionAbstract
{
    public function execute()
    {
        $id = !empty($this->urlParams['infoItemId']) ? $this->urlParams['infoItemId'] : null;

        if ($id == null) {
            throw new ValidationException('Параметр id должен быть передан.');
        }
        if (!is_numeric($id)) {
            throw new ValidationException('Параметр id должен быть числовым значением.');
        }

        $id = intval($id);

        $service = new InfoItemsService();

        /**
         * @var InfoItem $infoItem
         */
        $infoItem = $service->getInfoItem($id);

        if ($infoItem === null) {
            throw new EntityNotFoundException('Акция не найдена.');
        }

        $result = array(
            'id' => $infoItem->getId(),
            'title' => $infoItem->getName(),
            'image' => $infoItem->getPreviewPicture(),
            'text' => $infoItem->getText()
        );

        $actionResult = new ActionResult();
        $apiCode = 200;
        $actionResult->setParams($result);
        $actionResult->setApiCode($apiCode);

        return $actionResult;
    }

}