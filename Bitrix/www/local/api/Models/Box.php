<?php

namespace Api\Models;

/**
 * Модель бокса
 * Class Box
 * @package Api\Models
 */
class Box extends Model
{
    const IBLOCK_ID = 40;

    /**
     * Получение бокса по ID
     * @param int $id
     * @return static
     */
    public static function getById($id, $all = false)
    {
        $arFilter = array(
            'IBLOCK_ID' => self::IBLOCK_ID,
            'ID' => $id,
        );

        if (!$all) {
            $arFilter['ACTIVE'] = 'Y';
        }

        $dbRes = \CIBlockElement::GetList(array(), $arFilter, false, array());

        $fields = $dbRes->Fetch();

        $result = null;

        if ($fields) {
            $result = new static($fields);
        }

        return $result;
    }

    public static function getBoxesByIdList(array $boxIds, $limit = null, int $lastId = null)
    {
        $arFilter = [
            'IBLOCK_ID' => self::IBLOCK_ID,
            'ID' => $boxIds,
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

        $arOrder = array('ID' => 'DESC');

        $arSelect = array(
            '*',
            'PROPERTY_VIDEO_LINK_ACTIVE',
            'PROPERTY_VIDEO_LINK',
            'PROPERTY_FLOOR',
            'PROPERTY_SQUARE',
            'PROPERTY_VOLUME',
            'PROPERTY_BOX_NUMBER',
        );

        $dbRes = \CIBlockElement::GetList($arOrder, $arFilter, false, $arNavStartParams, $arSelect);

        $result = array();

        while ($fields = $dbRes->Fetch()) {
            $result[] = new static($fields);
        }

        return $result;
    }

    public static function getBySpace($space = 1)
    {
        $arFilter = array(
            'IBLOCK_ID' => self::IBLOCK_ID,
            'ACTIVE' => 'Y',
            '>=PROPERTY_SQUARE' => $space,
            '>PROPERTY_VOLUME' => 0
        );

        $arOrder = array('PROPERTY_SQUARE' => 'ASC');

        $arNavStartParams = array(
            "nPageSize" => 1,
        );

        $arSelect = array(
            '*',
            'PROPERTY_SQUARE',
            'PROPERTY_VOLUME',
        );

        $dbRes = \CIBlockElement::GetList($arOrder, $arFilter, false, $arNavStartParams, $arSelect);

        $fields = $dbRes->Fetch();

        $result = null;

        if ($fields) {
            $result = new static($fields);
        }

        return $result;
    }

    public function getNumber()
    {
        return $this->fields['PROPERTY_BOX_NUMBER_VALUE'];
    }

    public function getFloor()
    {
        return $this->fields['PROPERTY_FLOOR_VALUE'];
    }

    public function getSquare()
    {
        return $this->fields['PROPERTY_SQUARE_VALUE'];
    }

    public function getVolume()
    {
        return $this->fields['PROPERTY_VOLUME_VALUE'];
    }

    public function getVideoUrl()
    {
        $link = '';

        if ($this->fields['PROPERTY_VIDEO_LINK_ACTIVE_VALUE']) {
            $link = $this->fields['PROPERTY_VIDEO_LINK_VALUE'];
        }

        return $link;
    }
}