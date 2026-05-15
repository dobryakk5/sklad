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

	if ($arItem['UF_WAREHOUSE_TYPE']) {
		foreach ($arItem['UF_WAREHOUSE_TYPE'] as $wt) {
			if (!isset($arResult['TYPES'][$wt])) {
				$rsTypes = CUserFieldEnum::GetList(array(), array(
					"ID" => $wt,
				));

				if ($arDistrict = $rsTypes->GetNext()) {
					$arResult['TYPES'][$wt] = ['value' => $arDistrict['VALUE'], 'code' => $arDistrict['XML_ID']];
				}
			}

			$arResult['SECTIONS_JSON'][$arResult['TYPES'][$wt]['code']][] = [
				'address' => $arItem['UF_ADDRESS'],
				// 'address' => $arItem['ID'] == 175 ? str_replace('паркинг,', 'паркинг,<br>', $arItem['UF_ADDRESS']) : $arItem['UF_ADDRESS'],
				'address_balloon' => $arItem['NAME'],
				'access' => $arItem["UF_DOSTUP_TIME"],
				'manager_hours' => $arItem['UF_RECEPTION'],
				'link' => '/rental_catalog/' . $arItem["CODE"] . '/',
				'coordinate' => explode(',', $arItem['UF_MAP']),
			];
		}
	}
}

?>


<? $this->__component->SetResultCacheKeys(array("CACHED_TPL", "SECTIONS")); ?>