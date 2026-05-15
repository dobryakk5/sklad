<?php

namespace Api\DomainServices\Requests\Data;

use Api\DomainServices\Common\Data\DataAbstract;
use Api\Exceptions\ValidationException;

/**
 * Класс данных для формы быстрой заявки
 * Class FastRequestData
 * @package Api\DomainServices\Requests\Data
 */
class FastRequestData extends DataAbstract
{
    public $name; // Имя пользователя

    public $phone; // Номер телефона пользователя

    public $email;

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
    } // Email пользователя



    public function validate()
    {
        $emptyChecks = [
            'name',
            'phone',
            'email',
        ];

        $this->checkEmpty($emptyChecks);
    }
}