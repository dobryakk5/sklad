<?php

namespace Api\DomainServices\User\Data;

use Api\DomainServices\Common\Data\DataAbstract;

/**
 * Класс данных для обновление описи бокса пользователя
 * Class BoxInventoryData
 * @package Api\DomainServices\User\Data
 */
class BoxInventoryData extends DataAbstract
{
    public $boxId;
    public $text;
    public $deletedImages = [];
    public $addedImages;
    public $user;

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
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return array
     */
    public function getDeletedImages(): array
    {
        return (array) $this->deletedImages;
    }

    /**
     * @param array $deletedImages
     */
    public function setDeletedImages(array $deletedImages)
    {
        $this->deletedImages = $deletedImages;
    }

    /**
     * @return mixed
     */
    public function getAddedImages()
    {
        return $this->addedImages;
    }

    /**
     * @param mixed $addedImages
     */
    public function setAddedImages($addedImages)
    {
        $this->addedImages = $addedImages;
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
}