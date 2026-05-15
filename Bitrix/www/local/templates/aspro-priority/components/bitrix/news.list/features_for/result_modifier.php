<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
foreach($arResult["ITEMS"] as $key=>$arItem) {
	if(strlen($arItem["PROPERTIES"]["ICON"]["VALUE"]) > 0) {
		$arResult["ITEMS"][$key]["PROPERTIES"]["ICON"]["RESIZE"] = CFile::ResizeImageGet($arItem["PROPERTIES"]["ICON"]["VALUE"], array("width"=>45, "height"=>45), BX_RESIZE_IMAGE_EXACT, false); 
	}	
}

//получаем все активные разделы 1-го уровня для табов
$arResult["TABS"] = Array();
$arFilter = array("IBLOCK_ID"=>$arParams["IBLOCK_ID"], "DEPTH_LEVEL"=>1, "ACTIVE"=>"Y");
$rsSect = CIBlockSection::GetList(array("sort"=>"asc"), $arFilter);
while($arSect = $rsSect->GetNext()) {
	if($arResult["SECTION"]["PATH"][0]["ID"] == $arSect["ID"]) {
		$arSect["IS_SELECTED"] = "Y";
	}
	$arResult["TABS"][] = $arSect;
}
?>