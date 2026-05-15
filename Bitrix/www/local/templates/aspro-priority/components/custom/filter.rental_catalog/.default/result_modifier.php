<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?
$arResult["SQUARE_TO"] = 15; //default
$arResult["VOLUME_TO"] = 15; //default

if (strlen($arParams["SKLAD_CODE"]) > 0) {

	$sect = CIBlockSection::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => STORAGES_CATALOG_IBLOCK, "CODE" => $arParams["SKLAD_CODE"]), false, $arSelect = array("UF_*"));
	$arResult["SECTION_UF"] = $sect->GetNext();

	//ищем максимальную площадь бокса на данном складе
	CModule::IncludeModule("iblock");
	$arSelect = array("ID", "IBLOCK_ID", "NAME", "PROPERTY_SQUARE");
	$arFilter = array("IBLOCK_ID" => STORAGES_CATALOG_IBLOCK, "ACTIVE" => "Y", "SECTION_CODE" => $arParams["SKLAD_CODE"], "PROPERTY_STATUS" => BOX_STATUS_OPENED_ID);
	$res = CIBlockElement::GetList(array("property_SQUARE" => "desc"), $arFilter, false, array("nTopCount" => 1), $arSelect);
	if ($ob = $res->GetNextElement()) {
		$arItem = $ob->GetFields();
		$arResult["SQUARE_TO"] = ceil($arItem["PROPERTY_SQUARE_VALUE"]);
	}
	if ($arResult["SQUARE_TO"] <= 1) {
		$arResult["SQUARE_TO"] = 2;
	}


	//ищем максимальный объем бокса на данном складе
	CModule::IncludeModule("iblock");
	$arSelect = array("ID", "IBLOCK_ID", "NAME", "PROPERTY_VOLUME");
	$arFilter = array("IBLOCK_ID" => STORAGES_CATALOG_IBLOCK, "ACTIVE" => "Y", "SECTION_CODE" => $arParams["SKLAD_CODE"], "PROPERTY_STATUS" => BOX_STATUS_OPENED_ID);
	$res = CIBlockElement::GetList(array("property_VOLUME" => "desc"), $arFilter, false, array("nTopCount" => 1), $arSelect);
	if ($ob = $res->GetNextElement()) {
		$arItem = $ob->GetFields();
		$arResult["VOLUME_TO"] = ceil($arItem["PROPERTY_VOLUME_VALUE"]);
	}
	if ($arResult["VOLUME_TO"] <= 1) {
		$arResult["VOLUME_TO"] = 2;
	}
} else {
	//ищем максимальную площадь бокса на всех складах
	CModule::IncludeModule("iblock");
	$arSelect = array("ID", "IBLOCK_ID", "NAME", "PROPERTY_SQUARE");
	$arFilter = array("IBLOCK_ID" => STORAGES_CATALOG_IBLOCK, "ACTIVE" => "Y", "SECTION_GLOBAL_ACTIVE" => "Y", "PROPERTY_STATUS" => BOX_STATUS_OPENED_ID);

	if ($APPLICATION->GetCurPage() == '/rental_catalog/konteynery/') {
		$arFilter['PROPERTY_OBJECT_TYPE'] = 415;
	}

	$res = CIBlockElement::GetList(array("property_SQUARE" => "desc"), $arFilter, false, array("nTopCount" => 1), $arSelect);
	if ($ob = $res->GetNextElement()) {
		$arItem = $ob->GetFields();
		$arResult["SQUARE_TO"] = ceil($arItem["PROPERTY_SQUARE_VALUE"]);
	}
	if ($arResult["SQUARE_TO"] <= 1) {
		$arResult["SQUARE_TO"] = 2;
	}


	//ищем максимальный объем бокса на всех складах
	CModule::IncludeModule("iblock");
	$arSelect = array("ID", "IBLOCK_ID", "NAME", "PROPERTY_VOLUME");
	$arFilter = array("IBLOCK_ID" => STORAGES_CATALOG_IBLOCK, "ACTIVE" => "Y", "SECTION_GLOBAL_ACTIVE" => "Y", "PROPERTY_STATUS" => BOX_STATUS_OPENED_ID);
	$res = CIBlockElement::GetList(array("property_VOLUME" => "desc"), $arFilter, false, array("nTopCount" => 1), $arSelect);
	if ($ob = $res->GetNextElement()) {
		$arItem = $ob->GetFields();
		$arResult["VOLUME_TO"] = ceil($arItem["PROPERTY_VOLUME_VALUE"]);
	}
	if ($arResult["VOLUME_TO"] <= 1) {
		$arResult["VOLUME_TO"] = 2;
	}
}
?>

<? $this->__component->SetResultCacheKeys(array("CACHED_TPL", "MAP_POINTS")); ?>