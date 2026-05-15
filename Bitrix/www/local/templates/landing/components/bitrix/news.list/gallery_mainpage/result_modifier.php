<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
foreach($arResult["ITEMS"] as $key=>$arItem) {
	if(strlen($arItem["DETAIL_PICTURE"]["ID"]) > 0) {
		$arResult["ITEMS"][$key]["DETAIL_PICTURE"]["SMALL"]["RESIZE"] = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"]["ID"], array("width"=>400, "height"=>400), BX_RESIZE_IMAGE_EXACT, false);
		$arResult["ITEMS"][$key]["DETAIL_PICTURE"]["BIG"]["RESIZE"] = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"]["ID"], array("width"=>1500, "height"=>1500), BX_RESIZE_IMAGE_PROPORTIONAL, false);
	}
}
?>