<?php

namespace Api\DomainServices\Requests\Data;


use Api\DomainServices\Common\Data\DataAbstract;
use Api\Exceptions\ValidationException;
use Api\Models\Storage;

class SpaceVolumeStorageRentData extends DataAbstract
{
    public $storageId;
    public $volume;
    public $space;
    public $name;
    public $phone;
    public $email;

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
    public function getVolume()
    {
        return $this->volume;
    }

    /**
     * @param mixed $volume
     */
    public function setVolume($volume)
    {
        $this->volume = $volume;
    }

    /**
     * @return mixed
     */
    public function getSpace()
    {
        return $this->space;
    }

    /**
     * @param mixed $space
     */
    public function setSpace($space)
    {
        $this->space = $space;
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
            'name',
            'phone',
            'email',
        ];

        $numericCheck = [
            'volume',
            'space',
            'storageId'
        ];

        $this->checkEmpty($emptyChecks);
        $this->checkNumeric($numericCheck);

        $storageId = (int) $this->storageId;
        $storage = Storage::getStorage($storageId);

        if (!$storage) {
            throw new ValidationException("Склад заданного id не найден");
        }
    }

    /**
     * Собирает набор данных для формы
     * @return array
     */
    public function getDataForForm()
    {
        $data = [
            'STORAGE' => $this->getStorageId(),
            'VOLUME' => $this->getVolume(),
            'SPACE' => $this->getSpace(),
            'NAME' => $this->getName(),
            'PHONE' => $this->getPhone(),
            'EMAIL' => $this->getEmail(),
        ];

        return $data;
    }
}