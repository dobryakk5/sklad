<?php

namespace Api\DomainServices\User;

use Api\DomainServices\Common\Traits\Form;
use Api\DomainServices\User\Data\BoxInventoryData;
use Api\DomainServices\User\Data\BoxRequestData;
use Api\Exceptions\BoxInventoryNotFoundException;
use Api\Exceptions\BoxNotFoundException;
use Api\Exceptions\BoxRequestFormNotFoundException;
use Api\Models\Box;
use Api\Models\BoxInventory;
use Api\Models\Contract;
use Api\Models\User;

/**
 * Сервис для работы с боксами
 * Class BoxesService
 * @package Api\DomainServices
 */
class BoxesService
{
    use Form;

    public static function getBoxesByIdList($boxIds, $limit = null, int $lastId = null)
    {
        return Box::getBoxesByIdList($boxIds, $limit, $lastId);
    }

    public static function getBoxBySpace($space = 1)
    {
        return Box::getBySpace($space);
    }

    /**
     * Получает данные описи бокса пользователя
     * @param $userId
     * @param $boxId
     * @return array
     * @throws BoxInventoryNotFoundException
     * @throws BoxNotFoundException
     */
    public function getInventory($userId, $boxId)
    {
        $boxInventory = $this->getBoxInventory($userId, $boxId);
        $inventory = [
            'text' => $boxInventory->getText(),
            'images' => []
        ];
        $files = $boxInventory->getFiles();
        foreach ($files as $fileId) {
            $inventory['images'][] = SITE_FULL_DOMAIN . \CFile::GetPath($fileId);
        }
        return $inventory;
    }

    /**
     * Обновляет опись бокса пользователя
     * @param $boxInventoryData
     * @return array
     */
    public function updateInventory(BoxInventoryData $boxInventoryData)
    {
        $user = $boxInventoryData->getUser();
        $boxId = $boxInventoryData->getBoxId();

        $boxInventory = $this->getBoxInventory($user->getId(), $boxId);

        $text = $boxInventoryData->getText();
        $deletedImages = $boxInventoryData->getDeletedImages();

        // Получаем текущие изображения
        $currentFiles = $boxInventory->getFiles();
        $currentFilesProperties = $boxInventory->getFilesProperties();
        $currentImages = [];
        foreach ($currentFiles as $key => $fileId) {
            $currentImages[SITE_FULL_DOMAIN . \CFile::GetPath($fileId)] = [
                'propId' => $currentFilesProperties[$key],
                'id' => $currentFiles[$key],
            ];
        }

        // Удаляем из массива те, которые надо удалить
        $deletedFiles = [];
        foreach ($deletedImages as $deletedImage) {
            if (array_key_exists($deletedImage, $currentImages)) {
                $fileParams = $currentImages[$deletedImage];
                $fileId = $fileParams['id'];
                $filePropId = $fileParams['propId'];
                $deletedFiles[] = $filePropId;
                \CFile::Delete($fileId);
            }
        }

        // Сохраняем новые картинки
        $addedImages = $boxInventoryData->getAddedImages();
        $addFiles = [];
        foreach ($addedImages as $file) {
            $addFiles[$file['name']] = [
                'path' => $file['tmp_name'],
                'size' => $file['size'],
                'type' => $file['type'],
            ];
        }

        $savedFiles = [];
        foreach ($addFiles as $fileName => $fileData) {
            $file = [
                "name" => $fileName,
                "size" => $fileData['size'],
                "content" => $fileData['path'],
                "type" => $fileData['type'],
                "MODULE_ID" => 'iblock'
            ];
            $savedFiles[] = \CFile::SaveFile($file, 'iblock');
        }

        foreach ($deletedFiles as $fileId) {
            $savedFiles[$fileId] = [
                'del' => 'Y'
            ];
        }

        $arFields = [
            'PROPERTY_VALUES' => $boxInventory->getProperties(),
            'DETAIL_TEXT' => $text
        ];
        $arFields['PROPERTY_VALUES']['FILES'] = $savedFiles;

        // Сохраняем все данные
        $updateResult = (new \CIBlockElement())->Update($boxInventory->getId(), $arFields, false, true,true);
        return $updateResult;
    }

    /**
     * Проверяет существует ли опись бокса для пользователя
     * @param $userId
     * @param $boxId
     * @return bool
     * @throws BoxNotFoundException
     */
    public function existsBoxInventory($userId, $boxId)
    {
        $box = Box::getById($boxId, true);
        if ($box === null) {
            throw new BoxNotFoundException("Бокс №$boxId не найден");
        }

        $boxId = $box->getId();
        $boxInventory = BoxInventory::getBoxInventory($userId, $boxId);
        return $boxInventory !== null ? true : false;
    }
    /**
     * Получает опись бокса пользователя
     * @param $userId
     * @param $boxId
     * @throws BoxInventoryNotFoundException
     * @throws BoxNotFoundException
     */
    private function getBoxInventory($userId, $boxId)
    {
        $box = Box::getById($boxId, true);
        if ($box === null) {
            throw new BoxNotFoundException("Бокс №$boxId не найден");
        }

        $boxId = $box->getId();

        $boxInventory = BoxInventory::getBoxInventory($userId, $boxId);

        if ($boxInventory === null) {
            throw new BoxInventoryNotFoundException("Опись бокса №$boxId не найдена");
        }

        return $boxInventory;
    }

    public function getContractForUserBox($userId, Box $box)
    {
        $boxId = $box->getId();
        $contract = Contract::getByUserAndBox($userId, $boxId);

        if ($contract === null) {
            throw new BoxNotFoundException("Договор пользователя для бокса №$boxId не найден");
        }

        return $contract;
    }

    public function createBoxRequest(BoxRequestData $boxRequestData)
    {
        /**
         * @var User $user
         */
        $user = $boxRequestData->getUser();
        $type = $boxRequestData->getType();

        $typeFormsMap = [
            BoxRequestData::TYPE_FEEDBACK => 'formBoxRequestFeedback',
            BoxRequestData::TYPE_VIDEO_SURVEILLANCE => 'formVideoSecurity',
            BoxRequestData::TYPE_PACKAGING_SHELVING => 'formRequestBoxPackagingShelving',
            BoxRequestData::TYPE_REPAIR_CLEANING => 'formRepair',
            BoxRequestData::TYPE_BOARD => 'formAdBoard',
            BoxRequestData::TYPE_TERMINATE_CONTRACT => 'formCancelContract',
        ];

        // По типу заявки определяем форму и данные
        $formSID = $typeFormsMap[$type] ?? null;
        if (empty($formSID)) {
            throw new BoxRequestFormNotFoundException('Для заданного типа заявки не найдена форма');
        }

        $formId = $this->getFormIdBySID($formSID);
        $fields = $boxRequestData->getDataForForm();
        $arValues = $this->collectCorrectFormData($formId, $fields);

        // Сохраняем результат формы
        return $this->saveResult($formId, $arValues, $user->getId());
    }
}