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


CModule::IncludeModule("iblock");
$arResult["SECTION"]["FEATURES"] = Array();
/*foreach($arResult["SECTION"]["UF_FEATURES"] as $featureID) {
	$res = CIBlockElement::GetByID($featureID);
	if($arF = $res->GetNext()) {
		$arFeature["ID"] = $arF["ID"];
		$arFeature["NAME"] = $arF["NAME"];
		$arFeature["DETAIL_TEXT"] = $arF["DETAIL_TEXT"];
		$arFeature["DETAIL_PAGE_URL"] = $arF["DETAIL_PAGE_URL"];
		
		$resProp = CIBlockElement::GetProperty(38, $arF["ID"], "sort", "asc", array("CODE" => "ICON"));
		if($obProp = $resProp->GetNext()) {
			if(strlen($obProp["VALUE"]) > 0) {
				$arFeature["ICON"] = CFile::ResizeImageGet($obProp["VALUE"], array("width"=>45, "height"=>45), BX_RESIZE_IMAGE_EXACT, false); 
			}
		}
		
		$arResult["SECTION"]["FEATURES"][] = $arFeature;
	}
}*/
if(count($arResult["SECTION"]["UF_FEATURES"]) > 0) {
	$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DETAIL_TEXT", "DETAIL_PAGE_URL", "PROPERTY_*");
	$arFilter = Array("IBLOCK_ID"=>38, "ACTIVE"=>"Y", "ID"=>$arResult["SECTION"]["UF_FEATURES"]);
	$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, Array(), $arSelect);
	while($ob = $res->GetNextElement()) { 
		$arFields = $ob->GetFields();  
		
		$arFeature["ID"] = $arFields["ID"];
		$arFeature["NAME"] = $arFields["NAME"];
		$arFeature["DETAIL_TEXT"] = $arFields["DETAIL_TEXT"];
		$arFeature["DETAIL_PAGE_URL"] = $arFields["DETAIL_PAGE_URL"];
		
		$resProp = CIBlockElement::GetProperty(38, $arFields["ID"], "sort", "asc", array("CODE" => "ICON"));
		if($obProp = $resProp->GetNext()) {
			if(strlen($obProp["VALUE"]) > 0) {
				$arFeature["ICON"] = CFile::ResizeImageGet($obProp["VALUE"], array("width"=>45, "height"=>45), BX_RESIZE_IMAGE_EXACT, false); 
			}
		}		
		
		$arResult["SECTION"]["FEATURES"][] = $arFeature;
	}
}

//получаем адреса всех меток на Яндекс карту
//добавлен "ID" => $arResult["SECTION"]["ID"] для выборки только одной точки
$arResult["MAP_POINTS"] = Array();
$res = CIBlockSection::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>$arParams["IBLOCK_ID"], "ACTIVE"=>"Y", "DEPTH_LEVEL"=>1, "ID" => $arResult["SECTION"]["ID"]), false, Array("UF_MAP", "UF_ADDRESS", "UF_RECEPTION", "UF_RECEPTION_NAME", "UF_DOSTUP_TIME", "UF_PHONE", "UF_PRICE_ON_MAP"));
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