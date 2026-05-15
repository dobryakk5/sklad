<?php

namespace Api\DomainServices\User\Data;

use Api\DomainServices\Common\Data\DataAbstract;

/**
 * Класс данных запроса УПД
 * Class UpdData
 * @package Api\DomainServices\User\Data
 */
class UpdData extends DataAbstract
{
    public $user;
    public $contractId;
    public $documentId;

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
    public function getContractId()
    {
        return $this->contractId;
    }

    /**
     * @param mixed $contractId
     */
    public function setContractId($contractId)
    {
        $this->contractId = $contractId;
    }

    /**
     * @return mixed
     */
    public function getDocumentId()
    {
        return $this->documentId;
    }

    /**
     * @param mixed $documentId
     */
    public function setDocumentId($documentId)
    {
        $this->documentId = $documentId;
    }


}

