<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?
if(strlen($arResult["SECTION"]["UF_FEATURES_PIC"]) > 0) {
	$arResult["SECTION"]["FEATURES_PIC"] = CFile::ResizeImageGet($arResult["SECTION"]["UF_FEATURES_PIC"], array("width"=>1000, "height"=>300), BX_RESIZE_IMAGE_EXACT, false);
}


$arResult["SECTION"]["FEATURES"] = Array();
if(count($arResult["SECTION"]["UF_FEATURES"]) > 0) {
	$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL", "DETAIL_TEXT", "PROPERTY_*");
	$arFilter = Array("IBLOCK_ID"=>38, "ACTIVE"=>"Y", "ID"=>$arResult["SECTION"]["UF_FEATURES"]);
	$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, Array(), $arSelect);
	while($ob = $res->GetNextElement()) { 
		$arFields = $ob->GetFields();  
		$arProps = $ob->GetProperties();
		$arResult["SECTION"]["FEATURES"][] = Array("ID"=>$arFields["ID"], "NAME"=>$arFields["NAME"], "ICON"=>CFile::GetPath($arProps["ICON"]["VALUE"]), "DETAIL_PAGE_URL"=>$arFields["DETAIL_PAGE_URL"], "DETAIL_TEXT"=>$arFields["DETAIL_TEXT"]);
	}
}
?>