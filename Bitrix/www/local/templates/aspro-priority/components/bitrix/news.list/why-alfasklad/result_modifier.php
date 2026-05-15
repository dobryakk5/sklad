<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
foreach($arResult["ITEMS"] as $key=>$arItem) {
	if(strlen($arItem["PROPERTIES"]["ICON"]["VALUE"]) > 0) {
		$arResult["ITEMS"][$key]["PROPERTIES"]["ICON"]["RESIZE"] = CFile::ResizeImageGet($arItem["PROPERTIES"]["ICON"]["VALUE"], array("width"=>45, "height"=>45), BX_RESIZE_IMAGE_EXACT, false); 
	}	
}
?>