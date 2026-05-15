<?php

namespace Api\DomainServices\Requests\Data;
use Api\DomainServices\Common\Data\DataAbstract;
use Api\Exceptions\ValidationException;
use Api\Models\Storage;

/**
 * Данные для формы заказа доставки
 * Class DeliveryData
 * @package Api\DomainServices\Requests\Data
 */
class DeliveryData extends DataAbstract
{
    const DIRECTION_TO_STORAGE = 1,
        DIRECTION_FROM_STORAGE = 2;

    const DIRECTION_TO_STORAGE_ID = 137,
        DIRECTION_FROM_STORAGE_ID = 138;

    public $direction; // enum: 1 - на склад, 2 - со склада
    public $fromAddress; // если на склад
    public $toStoreId = 0;// если на склад
    public $fromStoreId = 0;// если со склада
    public $toAddress;// если со склада
    public $description;// Что перевозить
    public $date;// timestamp, дата перевозки
    public $needPackage;// Упаковка
    public $phone;
    public $email;
    public $name;

    /**
     * @return mixed
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * @return integer
     */
    public function getDirectionId()
    {
        return $this->direction == self::DIRECTION_TO_STORAGE ? self::DIRECTION_TO_STORAGE_ID : self::DIRECTION_FROM_STORAGE_ID;
    }

    /**
     * @param mixed $direction
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;
    }

    /**
     * @return mixed
     */
    public function getFromAddress()
    {
        return $this->fromAddress;
    }

    /**
     * @param mixed $fromAddress
     */
    public function setFromAddress($fromAddress)
    {
        $this->fromAddress = $fromAddress;
    }

    /**
     * @return mixed
     */
    public function getToStoreId()
    {
        return $this->toStoreId;
    }

    /**
     * @param mixed $toStoreId
     */
    public function setToStoreId($toStoreId)
    {
        $this->toStoreId = $toStoreId;
    }

    /**
     * @return mixed
     */
    public function getFromStoreId()
    {
        return $this->fromStoreId;
    }

    /**
     * @param mixed $fromStoreId
     */
    public function setFromStoreId($fromStoreId)
    {
        $this->fromStoreId = $fromStoreId;
    }

    /**
     * @return mixed
     */
    public function getToAddress()
    {
        return $this->toAddress;
    }

    /**
     * @param mixed $toAddress
     */
    public function setToAddress($toAddress)
    {
        $this->toAddress = $toAddress;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getNeedPackage()
    {
        return $this->needPackage;
    }

    /**
     * @param mixed $needPackage
     */
    public function setNeedPackage($needPackage)
    {
        $this->needPackage = $needPackage;
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
    } // Номер телефона


    /**
     * Валидирует заданные данные
     */
    public function validate()
    {
        if (empty($this->direction)) {
            throw new ValidationException("Не заполнено поле direction");
        }

        if (!is_integer($this->direction)) {
            throw new ValidationException("Поле direction должно быть целым числом");
        }

        $emptyChecks = [
            'description',
            'date',
            'phone',
            'email',
            'name'
        ];

        $integerCheck = [];

        $toStorage = false;

        if ($this->direction == self::DIRECTION_TO_STORAGE) {
            $emptyChecks[] = 'fromAddress';
            $emptyChecks[] = 'toStoreId';
            $integerCheck[] = 'toStoreId';
            $toStorage = true;
        } else {
            $emptyChecks[] = 'fromStoreId';
            $emptyChecks[] = 'toAddress';
            $integerCheck[] = 'fromStoreId';
        }

        $this->checkEmpty($emptyChecks);
        $this->checkInteger($integerCheck);

        $storageId = (int) ($toStorage ? $this->toStoreId : $this->fromStoreId);
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
        $timestampDate = $this->date;
        //Если дата пришла в миллисекундах, то укорачиваем её до секунд
        if (strlen($timestampDate) == 13) {
            $timestampDate = $timestampDate / 1000;
        }
        $date = ConvertTimeStamp($timestampDate);
        $data = [
            "DATE" => $date,
            "ACTION_TYPE" => $this->getDirectionId(),
            "CARGO" => $this->getDescription(),
            "EMAIL" => $this->getEmail(),
            "PHONE" => $this->getPhone(),
            "NAME" => $this->getName(),
        ];

        if ($this->getNeedPackage()) {
            $data["PACKING"] = true;
        }

        if ($this->direction == self::DIRECTION_TO_STORAGE) {
            $data['LOCATION_FROM_1'] = $this->getFromAddress();
            $data['LOCATION_TO_1'] = $this->getToStoreId();
        } else {
            $data['LOCATION_FROM_2'] = $this->getFromStoreId();// Со склада - склад Id
            $data['LOCATION_TO_2'] = $this->getToAddress();
        }
        return $data;
    }
}