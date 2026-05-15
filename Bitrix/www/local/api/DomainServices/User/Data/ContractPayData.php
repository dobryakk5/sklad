<?php


namespace Api\DomainServices\User\Data;


use Api\DomainServices\Common\Data\DataAbstract;
use Api\Exceptions\DocumentNotFoundException;
use Api\Exceptions\ValidationException;
use Api\Models\Document;

class ContractPayData extends DataAbstract
{
    public $docs;
    public $user;
    public $contractId;

    /**
     * @return mixed
     */
    public function getDocs()
    {
        return $this->docs;
    }

    /**
     * @param mixed $docs
     */
    public function setDocs($docs): void
    {
        $this->docs = $docs;
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

    public function validate()
    {
        $checks = array('docs');
        $this->checkEmpty($checks);
        $this->checkArray($checks);

        foreach ($this->docs as $doc) {
            if (!is_integer($doc)) {
                throw new ValidationException('ID документов должны быть числовыми значениями.');
            }

            $documentExists = Document::checkExists($doc);

            if (!$documentExists) {
                throw new ValidationException('Счёт №' . $doc . ' не найден.');
            }

        }
    }
}