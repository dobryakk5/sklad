<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?
foreach($arResult["ITEMS"] as $key=>$arItem) {
    if(strlen($arItem["PREVIEW_PICTURE"]["ID"]) > 0) {
        $arResult["ITEMS"][$key]["PREVIEW_PICTURE"]["RESIZE"] = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"]["ID"], array("width"=>50, "height"=>50), BX_RESIZE_IMAGE_EXACT, false);
    }

	if(strlen($arItem["PROPERTIES"]["SKLAD"]["VALUE"]) > 0) {
		$res = CIBlockSection::GetByID($arItem["PROPERTIES"]["SKLAD"]["VALUE"]);
		if($arSklad = $res->GetNext()) {
			$arResult["ITEMS"][$key]["SKLAD"] = $arSklad;
		}
	}
}
?>