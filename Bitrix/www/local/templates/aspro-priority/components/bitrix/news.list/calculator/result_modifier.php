<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
foreach($arResult["ITEMS"] as $key=>$arItem) {
	if(strlen($arItem["PREVIEW_PICTURE"]["ID"]) > 0) {
		$arResult["ITEMS"][$key]["PREVIEW_PICTURE"]["RESIZE"] = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"]["ID"], array("width"=>40, "height"=>40), BX_RESIZE_IMAGE_PROPORTIONAL, false); 
	}
}
?>

<?$this->__component->SetResultCacheKeys(array("CACHED_TPL"));?>