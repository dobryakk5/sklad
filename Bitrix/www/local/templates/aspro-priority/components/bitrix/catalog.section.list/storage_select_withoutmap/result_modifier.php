<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?
if(strlen($arParams["SELECTED_SKLAD_ID"]) > 0) {
	foreach($arResult["SECTIONS"] as $key=>$arSklad) {
		if($arSklad["ID"] == $arParams["SELECTED_SKLAD_ID"]) {
			$arResult["SECTIONS"][$key]["CHECKED_ON_MAP"] = "Y";
		}
	}
}
?>

<?$this->__component->SetResultCacheKeys(array("CACHED_TPL", "SECTIONS"));?>