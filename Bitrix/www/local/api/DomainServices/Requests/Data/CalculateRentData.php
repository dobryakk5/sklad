<?php

namespace Api\DomainServices\Requests\Data;


use Api\DomainServices\Common\Data\DataAbstract;
use Api\DomainServices\Storages\StorageService;
use Api\Exceptions\ValidationException;
use Api\Models\Storage;

/**
 * Данные формы расчёта стоимости
 * Class CalculateRentData
 * @package Api\DomainServices\Requests\Data
 */
class CalculateRentData extends DataAbstract
{
    public $storageId; // id склада
    public $sizeType; // тип размера (м2 или м3)
    public $size; // размер
    public $rentDuration; // срок аренды
    public $withDelivery; // с доставкой
    public $withPackage; // с упаковкой
    public $name; // Имя
    public $phone; // Телефон
    public $email; // Email

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
    public function getWithDelivery()
    {
        return $this->withDelivery;
    }

    /**
     * @param mixed $withDelivery
     */
    public function setWithDelivery($withDelivery)
    {
        $this->withDelivery = $withDelivery;
    }

    /**
     * @return mixed
     */
    public function getWithPackage()
    {
        return $this->withPackage;
    }

    /**
     * @param mixed $withPackage
     */
    public function setWithPackage($withPackage)
    {
        $this->withPackage = $withPackage;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Валидирует заданные данные
     */
    public function validate()
    {
        $emptyChecks = [
            'storageId',
	        'sizeType',
	        'size',
	        'rentDuration',
	        'name',
	        'phone',
	        'email',
        ];

        $integerCheck = [
            'storageId',
	        'sizeType',
        ];

        $numericCheck = [
            'size',
            'rentDuration',
        ];

        $booleanCheck = [
            'withDelivery',
            'withPackage',
        ];

        $this->checkEmpty($emptyChecks);
        $this->checkInteger($integerCheck);
        $this->checkNumeric($numericCheck);
        $this->checkBoolean($booleanCheck);

        $storageId = $this->storageId;
        $storage = Storage::getStorage($storageId);

        if (!$storage) {
            throw new ValidationException("Склад заданного id не найден");
        }

        $sizeType = $this->sizeType;

        if (!in_array($sizeType, [StorageService::SIZE_TYPE_M2, StorageService::SIZE_TYPE_M3])) {
            throw new ValidationException("Тип размера должен быть " . StorageService::SIZE_TYPE_M2 . " или " . StorageService::SIZE_TYPE_M3);
        }
    }

    /**
     * Собирает набор данных для формы
     * @return array
     */
    public function getDataForForm()
    {
        $data = [
            'EMAIL' => $this->getEmail(),
            'PHONE' => $this->getPhone(),
            'NAME' => $this->getName(),
            'RENT_DURATION' => $this->getRentDuration(),
            'SIZE' => $this->getSize(),
            'SIZE_TYPE' => $this->getSizeType(),
            'STORAGE' => $this->getStorageId()
        ];

        if ($this->getWithPackage()) {
            $data["WITH_PACKAGE"] = true;
        }

        if ($this->getWithDelivery()) {
            $data["WITH_DELIVERY"] = true;
        }

        return $data;
    }
}