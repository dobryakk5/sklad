<?php

namespace Api\Models;

use Api\Exceptions\DocumentNotFoundException;

use Api\Helpers\Date;

/**
 * Модель счета
 * Class Document
 * @package Api\Models
 */
class Document extends Model
{
    const IBLOCK_ID = 53;

    const STATUS_NOT_PAID = 354,
        STATUS_PAID = 355,
        STATUS_PART_PAID = 356,
        STATUS_CANCEL = 400;

    const STATUS_NOT_PAID_NAME = 'Не оплачен',
        STATUS_PAID_NAME = 'Оплачен',
        STATUS_PART_PAID_NAME = 'Частично оплачен',
        STATUS_CANCEL_NAME = 'Отменен';

    const STATUS_MAP = [
         self::STATUS_NOT_PAID_NAME => self::STATUS_NOT_PAID,
         self::STATUS_PAID_NAME => self::STATUS_PAID,
         self::STATUS_PART_PAID_NAME => self::STATUS_PART_PAID,
         self::STATUS_CANCEL_NAME => self::STATUS_CANCEL,
    ];

    /**
     * Проверяет существует ли счёт
     * @param $documentId
     * @return bool
     */
    public static function checkExists($documentId)
    {
        $arFilter = [
            'IBLOCK_ID' => self::IBLOCK_ID,
            'ID' => $documentId,
        ];

        $dbRes = \CIBlockElement::GetList(['ID' => 'asc'], $arFilter);
        $documentObj = $dbRes->GetNextElement();

        if (!$documentObj) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }

    public static function getForContract($guidContract, $documentId)
    {
        $arFilter = [
            'IBLOCK_ID' => self::IBLOCK_ID,
            'ID' => $documentId,
            'PROPERTY_CONTRACT_GUID' => $guidContract
        ];
        $dbRes = \CIBlockElement::GetList(['ID' => 'asc'], $arFilter);
        $documentObj = $dbRes->GetNextElement();
        if (!$documentObj) {
            return null;
        }

        $arDocument = [];
        $properties = $documentObj->GetProperties();

        foreach ($properties as $property) {
            $arDocument[$property['CODE']] = $property['VALUE'];
        }
        $arDocument = array_merge($documentObj->GetFields(), $arDocument);
        $document = new static($arDocument);

        return $document;
    }

    /**
     * Получает все активные счета для договора
     * @param $guidContract
     * @return array
     */
    public static function getAllForContract($guidContract)
    {
        $arFilter = [
            'IBLOCK_ID' => self::IBLOCK_ID,
            'PROPERTY_CONTRACT_GUID' => $guidContract,
            'ACTIVE' => 'Y',
            'PROPERTY_STATUS' => [
                Document::STATUS_NOT_PAID,
                Document::STATUS_PAID,
                Document::STATUS_PART_PAID,
            ]
        ];
        $dbRes = \CIBlockElement::GetList(['ID' => 'DESC'], $arFilter);
        $documents = [];
        while ($document = $dbRes->GetNextElement()) {
            $arDocument = [];
            $properties = $document->GetProperties();
            foreach ($properties as $property) {
                $arDocument[$property['CODE']] = $property['VALUE'];
            }
            $arDocument = array_merge($document->GetFields(), $arDocument);
            $documents[] = new static($arDocument);
        }
        return $documents;
    }

    /**
     * Получает активные неоплаченные счета
     * @return array
     */
    public static function getActiveNotPaidNotExpired()
    {
        $currentDate = date("Y-m-d");
        $arFilter = [
            'IBLOCK_ID' => self::IBLOCK_ID,
            'ACTIVE' => 'Y',
            'PROPERTY_STATUS' => [
                Document::STATUS_NOT_PAID,
            ],
            '<=PROPERTY_DATE_FROM' => $currentDate,
            '>=PROPERTY_DATE_TO' => $currentDate
        ];
        $dbRes = \CIBlockElement::GetList(['ID' => 'asc'], $arFilter);
        $documents = [];
        while ($document = $dbRes->GetNextElement()) {
            $arDocument = [];
            $properties = $document->GetProperties();
            foreach ($properties as $property) {
                $arDocument[$property['CODE']] = $property['VALUE'];
            }
            $arDocument = array_merge($document->GetFields(), $arDocument);
            $documents[] = new static($arDocument);
        }
        return $documents;
    }

    public function getName()
    {
        return $this->fields['NAME'];
    }

    public function getNumber()
    {
        return $this->fields['NUMBER'];
    }

    public function getGUID()
    {
        return $this->fields['INVOICE_GUID'];
    }

    public function getContractGUID()
    {
        return $this->fields['CONTRACT_GUID'];
    }

    public function getDateFrom($timestamp = false)
    {
        $date = $this->fields['DATE_FROM'];
        if ($timestamp) {
            $date = Date::getTimestampWithMilliseconds($date);
        }
        return $date ?? null;
    }

    public function getDateTo($timestamp = false)
    {
        $date = $this->fields['DATE_TO'];
        if ($timestamp) {
            $date = Date::getTimestampWithMilliseconds($date);
        }
        return $date ?? null;
    }

    public function getUserId()
    {
        return $this->fields['USER'];
    }

    public function getStatusId()
    {
        $statusName = $this->fields['STATUS'];
        $statusId = self::STATUS_MAP[$statusName] ?? 0;
        return $statusId;
    }

    public function getStatus()
    {
        return $this->fields['STATUS'];
    }

    public function getContractNumber()
    {
        return $this->fields['CONTRACT_NUMBER'];
    }
}