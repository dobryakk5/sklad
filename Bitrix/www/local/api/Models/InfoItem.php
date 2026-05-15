<?php


namespace Api\Models;


class InfoItem extends Model
{
    const IBLOCK_ID = 55;

    public static function getInfoItem(int $id)
    {
        $arFilter = array(
            'IBLOCK_ID' => self::IBLOCK_ID,
            'ACTIVE' => 'Y',
            'ID' => $id,
        );

        $dbRes = \CIBlockElement::GetList(array(), $arFilter, false, array());

        $fields = $dbRes->Fetch();

        $result = null;

        if ($fields) {
            $result = new static($fields);
        }

        return $result;
    }

    public static function getInfoItemsList(int $limit = null, int $lastId = null)
    {
        $arFilter = array(
            'IBLOCK_ID' => self::IBLOCK_ID,
            'ACTIVE' => 'Y',
        );

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

        $dbRes = \CIBlockElement::GetList($arOrder, $arFilter, false, $arNavStartParams);

        $result = array();

        while ($fields = $dbRes->Fetch()) {
            $result[] = new static($fields);
        }

        return $result;
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

    public function getPreviewPicture()
    {
        $path = '';
        $iconId = $this->fields['PREVIEW_PICTURE'];
        $iconPath = \CFile::GetPath($iconId);
        if (!empty($iconPath)) {
            $path = SITE_FULL_DOMAIN . \CFile::GetPath($iconId);
        }
        return (string) $path;
    }

    public function getText()
    {
        return $this->fields['DETAIL_TEXT'];
    }

}