<?php

namespace Api\Models;


class Storage extends Model
{
    const IBLOCK_ID = 40;

    /**
     * Получение всех полей одного склада по ID
     * @param int $id
     * @return static
     */
    public static function getStorage(int $id)
    {
        $arFilter = array(
            'IBLOCK_ID' => self::IBLOCK_ID,
            'ACTIVE' => 'Y',
            'ID' => $id,
        );

        $dbRes = \CIBlockSection::GetList(array(), $arFilter, false, array('UF_*'), array());

        $fields = $dbRes->Fetch();

        $result = null;

        if ($fields) {
            $result = new static($fields);
        }

        return $result;
    }

    public static function getStoragesList(array $idList = null, int $limit = null, int $lastId = null)
    {
        $arFilter = array(
            'IBLOCK_ID' => self::IBLOCK_ID,
            'ACTIVE' => 'Y',
        );

        if ($idList) {
            $arFilter['ID'] = $idList;
        }

        $arOrder = array('ID' => 'DESC');

        if ($lastId) {
            $arFilter['<ID'] = $lastId;
        }

        $arNavStartParams = array();

        if ($limit) {
            $arNavStartParams = [
                "nPageSize" => $limit,
            ];
        }

        $dbRes = \CIBlockSection::GetList($arOrder, $arFilter, false, array('UF_*'), $arNavStartParams);

        $result = array();

        while ($fields = $dbRes->Fetch()) {
            $result[] = new static($fields);
        }

        return $result;
    }

    public function getGalleryId()
    {
        return (int) $this->fields['UF_PHOTOGALLERY'];
    }

    /**
     * @return mixed
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param mixed $fields
     */
    public function setFields($fields): void
    {
        $this->fields = $fields;
    }

    public function getAddress()
    {
        return (string) $this->fields['UF_ADDRESS'] ?? '';
    }

    public function getWorkTime()
    {
        return (string) $this->fields['UF_RECEPTION'] ?? '';
    }

    public function getAccessMode()
    {
        return (string) $this->fields['UF_DOSTUP_TIME'] ?? '';
    }

    public function getPhone()
    {
        return (string) $this->fields['UF_PHONE'] ?? '';
    }

    public function getMetroStations()
    {
        return (array) $this->fields['UF_METRO'] ?? [];
    }

    public function getBusStations()
    {
        return (array) $this->fields['UF_BUS_STATION'] ?? [];
    }

    public function getStorageDescription()
    {
        return (string) $this->fields['DESCRIPTION'] ?? '';
    }

    public function getPriceOnMap()
    {
        return (string) $this->fields['UF_PRICE_ON_MAP'] ?? '';
    }

    public function getCode()
    {
        return (string) $this->fields['CODE'] ?? '';
    }

    public function getCoords()
    {
        return (string) $this->fields['UF_MAP'] ?? '';
    }
}