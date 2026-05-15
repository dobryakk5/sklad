<?php

namespace Api\Models;

use Api\Helpers\Date;

/**
 * Модель договора
 * Class Contract
 * @package Api\Models
 */
class Contract extends Model
{
    const IBLOCK_ID = 52;

    public static function getForUserById($userId, $id)
    {
        $arFilter = [
            'IBLOCK_ID' => self::IBLOCK_ID,
            'ID' => $id,
            'PROPERTY_USER' => $userId,
            'ACTIVE' => 'Y'
        ];
        $dbRes = \CIBlockElement::GetList(['ID' => 'asc'], $arFilter);
        $contract = $dbRes->GetNextElement();
        if ($contract) {
            $arProperties = [];
            $properties = $contract->GetProperties();
            foreach ($properties as $property) {
                $arProperties[$property['CODE']] = $property['VALUE'];
            }

            $fields = $contract->GetFields();
            $arContract = array_merge($fields, $arProperties);
            $contract = new static($arContract);
        } else {
            $contract = null;
        }
        return $contract;
    }

    /**
     * Получает все активные договоры пользователя
     * @param null $limit
     * @param int $lastId
     * @param $userId
     * @return array
     */
    public static function getAllForUser($userId, $limit = null, int $lastId = null)
    {
        $arFilter = [
            'IBLOCK_ID' => self::IBLOCK_ID,
            'PROPERTY_USER' => $userId,
            'ACTIVE' => 'Y',
        ];

        if ($lastId) {
            $arFilter['<ID'] = $lastId;
        }

        $arNavStartParams = array();
        if ($limit) {
            $arNavStartParams = [
                "nPageSize" => $limit,
            ];
        }

        $dbRes = \CIBlockElement::GetList(['ID' => 'DESC'], $arFilter, false, $arNavStartParams);
        $contracts = [];

        while ($contract = $dbRes->GetNextElement()) {
            $arContract = [];
            $properties = $contract->GetProperties();
            foreach ($properties as $property) {
                $arContract[$property['CODE']] = $property['VALUE'];
            }
            $arContract = array_merge($contract->GetFields(), $arContract);
            $contracts[] = new static($arContract);
        }

        return $contracts;
    }

    public static function getByGUID($GUID)
    {
        $arFilter = [
            'IBLOCK_ID' => self::IBLOCK_ID,
            'PROPERTY_CONTRACT_GUID' => $GUID,
        ];
        $dbRes = \CIBlockElement::GetList(['ID' => 'asc'], $arFilter);
        $contract = $dbRes->GetNextElement();
        if ($contract) {
            $arProperties = [];
            $properties = $contract->GetProperties();
            foreach ($properties as $property) {
                $arProperties[$property['CODE']] = $property['VALUE'];
            }

            $fields = $contract->GetFields();
            $arContract = array_merge($fields, $arProperties);
            $contract = new static($arContract);
        } else {
            $contract = null;
        }
        return $contract;
    }

    /**
     * Получает договор по пользователю и боксу
     * @param $userId
     * @param $boxId
     * @return \_CIBElement|array|null|static
     */
    public static function getByUserAndBox($userId, $boxId)
    {
        $arFilter = [
            'IBLOCK_ID' => self::IBLOCK_ID,
            'PROPERTY_BOX' => $boxId,
            'PROPERTY_USER' => $userId,
            'ACTIVE' => 'Y'
        ];
        $dbRes = \CIBlockElement::GetList(['ID' => 'asc'], $arFilter);
        $contract = $dbRes->GetNextElement();
        if ($contract) {
            $arProperties = [];
            $properties = $contract->GetProperties();
            foreach ($properties as $property) {
                $arProperties[$property['CODE']] = $property['VALUE'];
            }

            $fields = $contract->GetFields();
            $arContract = array_merge($fields, $arProperties);
            $contract = new static($arContract);
        } else {
            $contract = null;
        }
        return $contract;
    }

    public function getNumber()
    {
        return $this->fields['NUMBER'];
    }

    public function getGUID()
    {
        return $this->fields['CONTRACT_GUID'];
    }

    public function getBalance()
    {
        return (float) $this->fields['BALANCE'];
    }

    public function getDateCreate($timestamp = false)
    {
        $date = $this->fields['DATE_CREATE'];
        if ($timestamp) {
            $date = Date::getTimestampWithMilliseconds($date);
        }
        return $date ?? null;
    }

    public function getPaidDateTo($timestamp = false)
    {
        $date = $this->fields['PAID_DATE_TO'];
        if ($timestamp) {
            $date = Date::getTimestampWithMilliseconds($date);
        }
        return $date ?? null;
    }

    public function getStatus()
    {
        return $this->fields['STATUS'];
    }

    public function getBoxId()
    {
        return (int) $this->fields['BOX'];
    }

    public function getUserId()
    {
        return (int) $this->fields['USER'];
    }

    public function getAutoDebitEnabled()
    {
        //TODO: заменить на название настоящего поля, когда будет готов функционал со стороны клиента
        return $this->fields['AUTO_DEBIT_ENABLED'];
    }

    public function updateFields(array $newFields): void
    {
        try {
            \CIBlockElement::SetPropertyValuesEx($this->getId(), self::IBLOCK_ID, $newFields);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}