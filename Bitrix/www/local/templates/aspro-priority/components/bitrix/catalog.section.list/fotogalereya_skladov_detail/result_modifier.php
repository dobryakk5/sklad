<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?
$arResult["SECTION"]["GALLERY"] = Array();
if(strlen($arResult["SECTION"]["UF_PHOTOGALLERY"]) > 0) {
	$arSelect = Array("ID", "NAME", "DETAIL_PICTURE");
	$arFilter = Array("IBLOCK_ID"=>43, "ACTIVE"=>"Y", "SECTION_ID"=>$arResult["SECTION"]["UF_PHOTOGALLERY"]);
	$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, Array(), $arSelect);
	$cnt = 0;
	while($ob = $res->GetNextElement())
	{
		$arFields = $ob->GetFields();
		$arResult["SECTION"]["GALLERY"][$cnt]["BIG"]["RESIZE"] = CFile::ResizeImageGet($arFields["DETAIL_PICTURE"], array("width"=>1500, "height"=>1500), BX_RESIZE_IMAGE_PROPORTIONAL, false);
		$arResult["SECTION"]["GALLERY"][$cnt]["MEDIUM"]["RESIZE"] = CFile::ResizeImageGet($arFields["DETAIL_PICTURE"], array("width"=>800, "height"=>420), BX_RESIZE_IMAGE_EXACT, false);
		$arResult["SECTION"]["GALLERY"][$cnt]["SMALL"]["RESIZE"] = CFile::ResizeImageGet($arFields["DETAIL_PICTURE"], array("width"=>100, "height"=>100), BX_RESIZE_IMAGE_EXACT, false);
		$arResult["SECTION"]["GALLERY"][$cnt]["DESCRIPTION"] = $arFields["NAME"];
		$cnt++;
	}	
}


//получаем адреса всех меток на Яндекс карту
CModule::IncludeModule("iblock");
$arResult["MAP_POINTS"] = Array();
$res = CIBlockSection::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>$arParams["IBLOCK_ID"], "ACTIVE"=>"Y", "DEPTH_LEVEL"=>1), false, Array("UF_MAP", "UF_ADDRESS", "UF_RECEPTION", "UF_DOSTUP_TIME", "UF_PHONE", "UF_PRICE_ON_MAP"));
while($arSect = $res->GetNext()) {
	if($arResult["SECTION"]["ID"] == $arSect["ID"]) {
		$arSect["CHECKED_ON_MAP"] = "Y";
	}
	if(strlen($arSect["UF_PRICE_ON_MAP"]) > 0) {
		$arSect["MAP_PRICE"] = $arSect["UF_PRICE_ON_MAP"];
	}
	$arResult["MAP_POINTS"][] = $arSect;
}
?>



<?$this->__component->SetResultCacheKeys(array("CACHED_TPL", "MAP_POINTS"));?>