<?php


namespace Api\DomainServices\User\Data;


use Api\DomainServices\Common\Data\DataAbstract;
use Api\Exceptions\ValidationException;
use Api\Models\User;

class AutoDebitData extends DataAbstract
{
    /**
     * @var User
     */
    public $user;

    public $isAutoEnabled;
    public $contractId;

    public function validate()
    {
        if (!is_bool($this->isAutoEnabled)) {
            throw new ValidationException('Значения полей некорректны.');
        }
    }

    /**
     * @return mixed
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getIsAutoEnabled()
    {
        return isset($this->isAutoEnabled) ? $this->isAutoEnabled : null;
    }

    /**
     * @return mixed
     */
    public function getContractId()
    {
        return $this->contractId;
    }

    public function setContractId($value)
    {
        $this->contractId = $value;
    }

}