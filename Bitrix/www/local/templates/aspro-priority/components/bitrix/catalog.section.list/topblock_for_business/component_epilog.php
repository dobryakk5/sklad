<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
global $APPLICATION;

if(isset($arResult["SECTION"]["IPROPERTY_VALUES"]["SECTION_META_TITLE"])) {
	$APPLICATION->SetPageProperty("title", $arResult["SECTION"]["IPROPERTY_VALUES"]["SECTION_META_TITLE"]);
}
if(isset($arResult["SECTION"]["IPROPERTY_VALUES"]["SECTION_META_KEYWORDS"])) {
	$APPLICATION->SetPageProperty("keywords", $arResult["SECTION"]["IPROPERTY_VALUES"]["SECTION_META_KEYWORDS"]);
}
if(isset($arResult["SECTION"]["IPROPERTY_VALUES"]["SECTION_META_DESCRIPTION"])) {
	$APPLICATION->SetPageProperty("description", $arResult["SECTION"]["IPROPERTY_VALUES"]["SECTION_META_DESCRIPTION"]);
}
?>

<?
PHPInterface\ComponentHelper::handle($this);
?>