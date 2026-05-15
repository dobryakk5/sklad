<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
foreach($arResult["ITEMS"] as $key=>$arItem) {
	if(strlen($arItem["PREVIEW_PICTURE"]["ID"]) > 0) {
		$arResult["ITEMS"][$key]["GALLERY"][] = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"]["ID"], array("width"=>450, "height"=>350), BX_RESIZE_IMAGE_PROPORTIONAL, false); 
	}
	if(!empty($arItem["PROPERTIES"]["GALLERY"]["VALUE"]) > 0) {
		foreach($arItem["PROPERTIES"]["GALLERY"]["VALUE"] as $picID) {
			$arResult["ITEMS"][$key]["GALLERY"][] = CFile::ResizeImageGet($picID, array("width"=>450, "height"=>350), BX_RESIZE_IMAGE_PROPORTIONAL, false); 
		}
	}	
}
?>