<?php

namespace Api\DomainServices\User\Data;

use Api\DomainServices\Common\Data\DataAbstract;
use Api\DomainServices\User\BoxesService;
use Api\Exceptions\BoxNotFoundException;
use Api\Exceptions\ValidationException;
use Api\Models\Box;
use Api\Models\Contract;

/**
 * Класс данных создания заявок по боксам пользователя
 * Class BoxRequestData
 * @package Api\DomainServices\User\Data
 */
class BoxRequestData extends DataAbstract
{
    const TYPE_FEEDBACK = 1,
        TYPE_VIDEO_SURVEILLANCE = 2,
        TYPE_PACKAGING_SHELVING = 3,
        TYPE_REPAIR_CLEANING = 4,
        TYPE_BOARD = 5,
        TYPE_TERMINATE_CONTRACT = 6;

    const AVAILABLE_TYPES = [
        self::TYPE_FEEDBACK,
        self::TYPE_VIDEO_SURVEILLANCE,
        self::TYPE_PACKAGING_SHELVING,
        self::TYPE_REPAIR_CLEANING,
        self::TYPE_BOARD,
        self::TYPE_TERMINATE_CONTRACT,
    ];

    public $user;
    public $boxId;
    public $type;
    public $name;
    public $email;
    public $phone;
    public $comment = '';
    public $isWithDelivery;

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
    public function getBoxId()
    {
        return $this->boxId;
    }

    /**
     * @param mixed $boxId
     */
    public function setBoxId($boxId)
    {
        $this->boxId = $boxId;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
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
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return mixed
     */
    public function getisWithDelivery()
    {
        return $this->isWithDelivery;
    }

    /**
     * @param mixed $isWithDelivery
     */
    public function setIsWithDelivery($isWithDelivery)
    {
        $this->isWithDelivery = $isWithDelivery;
    }

    public function validate()
    {
        $emptyCheck = [
            'boxId',
            'type',
            'name',
            'email',
            'phone',
        ];

        $integerCheck = [
            'boxId',
            'type'
        ];

        $this->checkEmpty($emptyCheck);
        $this->checkInteger($integerCheck);

        $type = $this->getType();

        if (!in_array($type, self::AVAILABLE_TYPES)) {
            throw new ValidationException('Передан неверный тип заявки (поле type)');
        }

        // Если тип расторгнуть договор
        if ($type == self::TYPE_TERMINATE_CONTRACT) {
            $booleanCheck = [
                'isWithDelivery',
            ];
            $this->checkBoolean($booleanCheck);
        }
    }

    public function getDataForForm()
    {
        $data = [
            'NAME' => $this->getName(),
            'EMAIL' => $this->getEmail(),
            'PHONE' => $this->getPhone(),
            'MESSAGE' => $this->getComment(),
        ];

        $user = $this->getUser();
        $boxId = $this->getBoxId();

        $box = Box::getById($boxId, true);
        if ($box === null) {
            throw new BoxNotFoundException("Бокс №$boxId не найден");
        }
        $boxName = $box->getName();

        /**
         * @var Contract $contract
         */
        $contract = (new BoxesService())->getContractForUserBox($user->getId(), $box);

        $data['SKLAD'] = $box->getSectionId();
        $data['BOX_NAME'] = $boxName;
        $data['CONTRACT_NUMBER'] = $contract->getNumber();

        if ($this->type == self::TYPE_TERMINATE_CONTRACT) {
            $nowDate = ConvertTimeStamp(time());
            $data['DATE_SEND'] = $nowDate;
            $data['DATE_CANCEL'] = $nowDate;

            if ($this->getisWithDelivery()) {
                $data['WITH_DELIVERY'] = true;
            }
        }

        return $data;
    }
}