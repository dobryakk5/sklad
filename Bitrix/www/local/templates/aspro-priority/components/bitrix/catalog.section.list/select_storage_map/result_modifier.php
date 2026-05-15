<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?
foreach($arResult["SECTIONS"] as $key=>$arItem) {
	//получаем минимальную цену бокса на складе для карты
	$skladMapPrice = 0;

	if(strlen($arItem["UF_PRICE_ON_MAP"]) > 0) {
		$skladMapPrice = $arItem["UF_PRICE_ON_MAP"];
	}
	
	$arResult["SECTIONS"][$key]["MAP_PRICE"] = $skladMapPrice;
}
?>

<?$this->__component->SetResultCacheKeys(array("CACHED_TPL", "SECTIONS"));?>