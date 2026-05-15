<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?php

$arResult['DISTRICTS'] = [];
$arResult['SECTIONS_JSON'] = [];

foreach ($arResult["SECTIONS"] as $key => $arItem) {

	//получаем минимальную цену бокса на складе для карты
	$skladMapPrice = 0;

	if (strlen($arItem["UF_PRICE_ON_MAP"]) > 0) {
		$skladMapPrice = $arItem["UF_PRICE_ON_MAP"];
	}

	$arResult["SECTIONS"][$key]["MAP_PRICE"] = $skladMapPrice;

	if ($arItem['UF_DISTRICT'] && !isset($arResult['DISTRICTS'][$arItem['UF_DISTRICT']])) {
		$rsDistrict = CUserFieldEnum::GetList(array(), array(
			"ID" => $arItem['UF_DISTRICT'],
		));
		if ($arDistrict = $rsDistrict->GetNext()) {
			$arResult['DISTRICTS'][$arItem['UF_DISTRICT']] = $arDistrict['VALUE'];
		}
	}

	$arResult['SECTIONS_JSON'][] = [
		'address' => $arItem['UF_ADDRESS'],
		// 'address' => $arItem['ID'] == 175 ? str_replace('паркинг,', 'паркинг,<br>', $arItem['UF_ADDRESS']) : $arItem['UF_ADDRESS'],
		'address_balloon' => $arItem['NAME'],
		'access' => $arItem["UF_DOSTUP_TIME"],
		'manager_hours' => $arItem['UF_RECEPTION'],
		'link' => '/rental_catalog/' . $arItem["CODE"] . '/',
		'coordinate' => explode(',', $arItem['UF_MAP']),
		'region' => $arItem['UF_DISTRICT'] ? 'd' . $arItem['UF_DISTRICT'] : '',
	];
}

?>


<? $this->__component->SetResultCacheKeys(array("CACHED_TPL", "SECTIONS")); ?>