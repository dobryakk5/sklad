<?
if ($arResult['ITEMS']) {
	foreach ($arResult["ITEMS"] as $key => $arItem) {
		if (strlen($arItem["PREVIEW_PICTURE"]["ID"]) > 0) {
			$arResult["ITEMS"][$key]["PREVIEW_PICTURE"]["RESIZE"] = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"]["ID"], array("width" => 297, "height" => 297), BX_RESIZE_IMAGE_EXACT, false);
		}
		if (strlen($arItem["DETAIL_PICTURE"]["ID"]) > 0) {
			$arResult["ITEMS"][$key]["DETAIL_PICTURE"]["RESIZE"] = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"]["ID"], array("width" => 297, "height" => 297), BX_RESIZE_IMAGE_EXACT, false);
		}
	}
}
