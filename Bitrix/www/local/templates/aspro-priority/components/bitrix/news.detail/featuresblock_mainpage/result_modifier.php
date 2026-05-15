<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
if(strlen($arResult["PREVIEW_PICTURE"]["ID"]) > 0) {
    $arResult["PREVIEW_PICTURE"]["RESIZE"] = CFile::ResizeImageGet($arResult["PREVIEW_PICTURE"]["ID"], array("width"=>1000, "height"=>300), BX_RESIZE_IMAGE_EXACT, false);
}

$arResult["FEATURES"] = Array();
if(!empty($arResult["PROPERTIES"]["FEATURES"]["VALUE"])) {
    $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL", "DETAIL_TEXT", "PROPERTY_*");
    $arFilter = Array("IBLOCK_ID"=>38, "ACTIVE"=>"Y", "ID"=>$arResult["PROPERTIES"]["FEATURES"]["VALUE"]);
    $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, Array(), $arSelect);
    while($ob = $res->GetNextElement()) { 
        $arFields = $ob->GetFields();  
        $arProps = $ob->GetProperties();
        $arResult["FEATURES"][] = Array("ID"=>$arFields["ID"], "NAME"=>$arFields["NAME"], "ICON"=>CFile::GetPath($arProps["ICON"]["VALUE"]), "DETAIL_PAGE_URL"=>$arFields["DETAIL_PAGE_URL"], "DETAIL_TEXT"=>$arFields["DETAIL_TEXT"]);
    }
}
?>