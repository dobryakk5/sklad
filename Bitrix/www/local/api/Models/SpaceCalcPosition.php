<?php

namespace Api\Models;


class SpaceCalcPosition extends Model
{
    const IBLOCK_ID = 47;

    public static function getAfterId($lastId, $limit)
    {
        $arFilter = [
            'IBLOCK_ID' => self::IBLOCK_ID,
            'ACTIVE' => "Y"
        ];

        $dbRes = \CIBlockElement::GetList(array("ID" => "asc"), ['IBLOCK_ID' => self::IBLOCK_ID, 'ACTIVE' => "Y"], false, $paginate, array('ID'));
        $allCount = $dbRes->SelectedRowsCount();
        $lotOfData = $allCount >= 30;

        if ($lotOfData && $lastId) {
            $arFilter['>ID'] = $lastId;
        }

        $paginate = [];

        // Если задан лимит, то проверяем сколько всего записей и если меньше 30, то не применяем лимит
        if ($lotOfData && $limit) {
            $paginate = [
                "nPageSize" => $limit
            ];
        }

        $dbRes = \CIBlockElement::GetList(array("SORT" => "asc"), $arFilter, false, $paginate, array('*', 'PROPERTY_SQUARE'));
        $result = [];
        while ($item = $dbRes->Fetch()) {
            $result[] = new SpaceCalcPosition($item);
        }

        return $result;
    }

    public function getIcon()
    {
        $iconId = $this->fields['PREVIEW_PICTURE'];
        $path = SITE_FULL_DOMAIN . \CFile::GetPath($iconId);
        return $path;
    }

    public function getTitle()
    {
        return $this->fields['NAME'];
    }

    public function getSpace()
    {
        return (float) $this->fields['PROPERTY_SQUARE_VALUE'];
    }
}