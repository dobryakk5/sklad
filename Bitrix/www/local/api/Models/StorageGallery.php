<?php


namespace Api\Models;


class StorageGallery extends Model
{
    const IBLOCK_ID = 43;

    public static function getGallery(int $galleryId)
    {
        $arSelect = Array("ID", "NAME", "DETAIL_PICTURE");

        $arFilter = Array(
            "IBLOCK_ID" => self::IBLOCK_ID,
            "ACTIVE" => "Y",
            "SECTION_ID" => $galleryId
        );

        $res = \CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, Array(), $arSelect);

        $picturesArray = array();

        while($ob = $res->GetNextElement())
        {
            $arFields = $ob->GetFields();
            $path = SITE_FULL_DOMAIN . \CFile::GetPath($arFields["DETAIL_PICTURE"]);
            $picturesArray[] = $path;
        }

        $fields = array('images' => $picturesArray);

        return new static($fields);
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function getImages()
    {
        return $this->fields['images'];
    }
}