<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?
foreach ($arResult["ITEMS"] as $key => $arItem) {
	if (strlen($arItem["DETAIL_PICTURE"]["ID"]) > 0) {
		$arResult["ITEMS"][$key]["BIG"] = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"], array("width" => 1500, "height" => 1500), BX_RESIZE_IMAGE_PROPORTIONAL, false);
		// $arResult["ITEMS"][$key]["MEDIUM"] = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"], array("width"=>800, "height"=>420), BX_RESIZE_IMAGE_EXACT, false);
		$arResult["ITEMS"][$key]["SMALL"] = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"], array("width" => 312, "height" => 252), BX_RESIZE_IMAGE_EXACT, false);
	}
}
?>