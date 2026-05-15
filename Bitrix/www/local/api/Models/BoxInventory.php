<?php

namespace Api\Models;

/**
 * Модель описи вещей бокса
 * Class BoxInventory
 * @package Api\Models
 */
class BoxInventory extends Model
{
    const IBLOCK_ID = 58;
    public $files = [];
    public $filesProperties = [];
    public $user;
    public $box;

    public static function getBoxInventory($userId, $boxId)
    {
        $arFilter = [
            'IBLOCK_ID' => self::IBLOCK_ID,
            'PROPERTY_BOX' => $boxId,
            'ACTIVE' => 'Y',
            'PROPERTY_USER' => $userId
        ];
        $arSelectFields = [
            '*',
            'PROPERTY_FILES',
            'PROPERTY_USER',
            'PROPERTY_BOX',
        ];

        $dbRes = \CIBlockElement::GetList(['ID' => 'asc'], $arFilter, false, false, $arSelectFields);

        $boxInventory = $dbRes->GetNextElement();
        // Если описи бокса нет, то создаём пустую
        if ($boxInventory == null) {
            return self::createBoxInventory($userId, $boxId);
        }
        $properties = $boxInventory->GetProperties();
        $files = $properties['FILES']['VALUE'] ?? [];
        if (empty($files)) {
            $files = [];
        }
        $filesProperties = $properties['FILES']['PROPERTY_VALUE_ID'] ?? [];
        if (empty($filesProperties)) {
            $filesProperties = [];
        }
        $boxInventoryObj = new static((array) $boxInventory->GetFields());
        $boxInventoryObj->setFiles($files);
        $boxInventoryObj->setFilesProperties($filesProperties);
        $boxInventoryObj->setUser($properties['USER']['VALUE']);
        $boxInventoryObj->setBox($properties['BOX']['VALUE']);
        return $boxInventoryObj;
    }

    /**
     * Создаёт опись бокса пользователя
     * @param $userId
     * @param $boxId
     * @return BoxInventory|null
     */
    public static function createBoxInventory($userId, $boxId)
    {
        $arFields = [
            "IBLOCK_ID" => self::IBLOCK_ID,
            "NAME" => 'Опись на бокс с ID=' . $boxId,
            "PROPERTY_VALUES" => [
                "BOX" => $boxId,
                "USER" => $userId
            ]
        ];
        (new \CIBlockElement())->Add($arFields);
        $boxInventory = BoxInventory::getBoxInventory($userId, $boxId);
        return $boxInventory;

    }
    /**
     * @return array
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * @param array $files
     */
    public function setFiles(array $files)
    {
        $this->files = $files;
    }

    public function getText()
    {
        return $this->fields['DETAIL_TEXT'];
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getBox()
    {
        return $this->box;
    }

    /**
     * @param mixed $box
     */
    public function setBox($box)
    {
        $this->box = $box;
    }

    /**
     * @return array
     */
    public function getFilesProperties(): array
    {
        return $this->filesProperties;
    }

    /**
     * @param array $filesProperties
     */
    public function setFilesProperties(array $filesProperties)
    {
        $this->filesProperties = $filesProperties;
    }

    public function getProperties()
    {
        return [
            'FILES' => $this->files,
            'USER' => $this->user,
            'BOX' => $this->box,
        ];
    }
}