<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?
//получаем данные о складах(е)
$arResult["SKLAD_LIST"] = array();
if ($arResult["ID"] == 0) {
	$rsSect = CIBlockSection::GetList(array("sort" => "asc"), array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "DEPTH_LEVEL" => 1), false, array("ID", "IBLOCK_ID", "NAME", "CODE", "UF_ADDRESS", "UF_PHONE", "UF_DOSTUP_TIME", "UF_RECEPTION", "UF_LINK_REGION"));
	while ($arSect = $rsSect->GetNext()) {
		$arResult["SKLAD_LIST"][$arSect["ID"]] = $arSect;
	}
} else {
	$arResult["SKLAD_LIST"][$arResult["ID"]] = array(
		"ID" => $arResult["ID"],
		"CODE" => $arResult["CODE"],
		"NAME" => $arResult["NAME"],
		"UF_ADDRESS" => $arResult["UF_ADDRESS"],
		"UF_PHONE" => $arResult["UF_PHONE"],
		"UF_DOSTUP_TIME" => $arResult["UF_DOSTUP_TIME"],
		"UF_RECEPTION" => $arResult["UF_RECEPTION"],
		"UF_LINK_REGION" => $arResult["UF_LINK_REGION"],
	);
}

foreach ($arResult["ITEMS"] as $key => $arItem) {

	if (isset($arResult["SKLAD_LIST"][$arItem['IBLOCK_SECTION_ID']]['UF_LINK_REGION']) && $arResult["SKLAD_LIST"][$arItem['IBLOCK_SECTION_ID']]['UF_LINK_REGION'] != 1 && !empty($arResult["SKLAD_LIST"][$arItem['IBLOCK_SECTION_ID']]['UF_LINK_REGION'])) {
		unset($arResult["ITEMS"][$key]);
		continue;
	}

	if (strlen($arItem["PREVIEW_PICTURE"]["ID"]) > 0) {
		$arGallery = array();
		$arGallery["BIG"] = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"]["ID"], array("width" => 1500, "height" => 1500), BX_RESIZE_IMAGE_PROPORTIONAL, false);
		$arGallery["MEDIUM"] = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"]["ID"], array("width" => 350, "height" => 300), BX_RESIZE_IMAGE_EXACT, false);
		$arGallery["SMALL"] = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"]["ID"], array("width" => 100, "height" => 100), BX_RESIZE_IMAGE_EXACT, false);
		if (strlen($arItem["PREVIEW_PICTURE"]["DESCRIPTION"]) > 0) {
			$arGallery["DESCRIPTION"] = $arItem["PREVIEW_PICTURE"]["DESCRIPTION"];
		} else {
			$arGallery["DESCRIPTION"] = $arItem["NAME"];
		}

		$arResult["ITEMS"][$key]["GALLERY"][] = $arGallery;
	}
	if (!empty($arItem["PROPERTIES"]["GALLERY"]["VALUE"])) {
		foreach ($arItem["PROPERTIES"]["GALLERY"]["VALUE"] as $key2 => $arPic) {
			$arGallery = array();
			$arGallery["BIG"] = CFile::ResizeImageGet($arPic, array("width" => 1500, "height" => 1500), BX_RESIZE_IMAGE_PROPORTIONAL, false);
			$arGallery["MEDIUM"] = CFile::ResizeImageGet($arPic, array("width" => 350, "height" => 300), BX_RESIZE_IMAGE_EXACT, false);
			$arGallery["SMALL"] = CFile::ResizeImageGet($arPic, array("width" => 100, "height" => 100), BX_RESIZE_IMAGE_EXACT, false);
			if (strlen($arItem["PROPERTIES"]["GALLERY"]["DESCRIPTION"][$key2]) > 0) {
				$arGallery["DESCRIPTION"] = $arItem["PROPERTIES"]["GALLERY"]["DESCRIPTION"][$key2];
			} else {
				$arGallery["DESCRIPTION"] = $arItem["NAME"];
			}

			$arResult["ITEMS"][$key]["GALLERY"][] = $arGallery;
		}
	}

	//получаем данные о скидке
	$discounts = CCatalogDiscount::GetDiscountByProduct($arItem["ID"], $USER->GetUserGroupArray());
	if (!empty($discounts)) {
		foreach ($discounts as $arDiscount) {
			$arResult["ITEMS"][$key]["DISCOUNT"][] = $arDiscount;
		}
	}
}
?>

<? $this->__component->SetResultCacheKeys(array("CODE")); ?>
