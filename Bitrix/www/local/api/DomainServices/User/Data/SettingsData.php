<?php


namespace Api\DomainServices\User\Data;


use Api\DomainServices\Common\Data\DataAbstract;
use Api\Exceptions\ValidationException;
use Api\Models\User;

class SettingsData extends DataAbstract
{
    /**
     * @var User
     */
    public $user;

    public $isEmailOn;
    public $isSmsOn;

    public function validate()
    {
        if (!is_bool($this->isEmailOn) || !is_bool($this->isSmsOn)) {
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
    public function getIsEmailOn()
    {
        return isset($this->isEmailOn) ? $this->isEmailOn : null;
    }

    /**
     * @return mixed
     */
    public function getIsSmsOn()
    {
        return isset($this->isSmsOn) ? $this->isSmsOn : null;
    }
}