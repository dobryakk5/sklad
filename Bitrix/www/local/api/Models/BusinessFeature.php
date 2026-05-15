<?php

namespace Api\Models;

class BusinessFeature extends Model
{
    const IBLOCK_ID = 38;

    public static function getAfterId($lastId, $limit)
    {
        $arFilter = [
            'IBLOCK_ID' => self::IBLOCK_ID,
            'IBLOCK_SECTION_ID' => 56
        ];
        if ($lastId) {
            $arFilter['<ID'] = $lastId;
        }

        $paginate = [];

        if ($limit) {
            $paginate = [
                "nPageSize" => $limit
            ];
        }

        $dbRes = \CIBlockElement::GetList(array("ID" => "desc"), $arFilter, false, $paginate, array('*', 'PROPERTY_ICON'));
        $result = [];
        while ($item = $dbRes->Fetch()) {
            $result[] = new BusinessFeature($item);
        }

        return $result;
    }

    public function getIcon()
    {
        $iconId = $this->fields['PROPERTY_ICON_VALUE'];

        if (empty($iconId)) {
            return null;
        }
        $path = SITE_FULL_DOMAIN . \CFile::GetPath($iconId);
        return $path;
    }

    public function getTitle()
    {
        return $this->fields['NAME'];
    }
}