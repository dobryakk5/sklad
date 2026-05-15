<?php

namespace Api\DomainServices\Storages\Data;

use Api\DomainServices\Common\Data\DataAbstract;

/**
 * Класс данных для расчета стоимости аренды боксов
 * Class StoragesCostData
 * @package Api\DomainServices\Storages
 */
class StoragesCostData extends DataAbstract
{
    public $sizeType;
    public $size;
    public $rentDuration;
    public $storageId;

    /**
     * @return mixed
     */
    public function getSizeType()
    {
        return $this->sizeType;
    }

    /**
     * @param mixed $sizeType
     */
    public function setSizeType($sizeType)
    {
        $this->sizeType = $sizeType;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param mixed $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * @return mixed
     */
    public function getRentDuration()
    {
        return $this->rentDuration;
    }

    /**
     * @param mixed $rentDuration
     */
    public function setRentDuration($rentDuration)
    {
        $this->rentDuration = $rentDuration;
    }

    /**
     * @return mixed
     */
    public function getStorageId()
    {
        return $this->storageId;
    }

    /**
     * @param mixed $storageId
     */
    public function setStorageId($storageId)
    {
        $this->storageId = $storageId;
    }

    /**
     * Валидирует заданные данные
     */
    public function validate()
    {
        $checksEmpty = [
            'sizeType',
            'size',
            'rentDuration',
            'storageId',
        ];

        $checksInteger = [
            'sizeType',
            'rentDuration',
            'storageId',
        ];

        $this->checkEmpty($checksEmpty);
        $this->checkInteger($checksInteger);
    }
}