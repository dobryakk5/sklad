<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>

<?
if(strlen($arResult["PREVIEW_PICTURE"]["ID"]) > 0) {
	$arResult["PREVIEW_PICTURE"]["RESIZE"] = CFile::ResizeImageGet($arResult["PREVIEW_PICTURE"]["ID"], array('width'=>80, 'height'=>80), BX_RESIZE_IMAGE_EXACT, false);
}

if(strlen($arResult["PROPERTIES"]["NAME_FOR_SITE"]["VALUE"]) > 0) {
	$arResult["NAME"] = $arResult["PROPERTIES"]["NAME_FOR_SITE"]["VALUE"];
}

//получаем данные о складе
if(strlen($arResult["IBLOCK_SECTION_ID"]) > 0) {
	$rsSect = CIBlockSection::GetList(array("sort"=>"asc"), Array("IBLOCK_ID"=>$arParams["IBLOCK_ID"], "DEPTH_LEVEL"=>1, "ID"=>$arResult["IBLOCK_SECTION_ID"]), false, Array("ID", "IBLOCK_ID", "NAME", "CODE", "UF_ADDRESS", "UF_PHONE", "UF_DOSTUP_TIME", "UF_RECEPTION"));
	if ($arSect = $rsSect->GetNext()) {
	   $arResult["SKLAD"] = $arSect;
	}
}
?>