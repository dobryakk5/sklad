<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?
//кол-во свободных боксов
$arResult["CNT_OPEN_STORAGES"] = CIBlockElement::GetList(
	array(),
	array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y", "SECTION_GLOBAL_ACTIVE" => "Y", "PROPERTY_STATUS" => BOX_STATUS_OPENED_ID),
	array(),
	false,
	array("ID", "NAME")
);


foreach ($arResult["SECTIONS"] as $key => $arItem) {
	//получаем кол-во этажей на складе
	if (strlen($arItem["UF_FLOORS"]) > 0) {
		$rsUserProp = CUserFieldEnum::GetList(array(), array(
			"ID" => $arItem["UF_FLOORS"],
		));
		if ($arUserProp = $rsUserProp->GetNext()) {
			for ($floor = 1; $floor <= $arUserProp["VALUE"]; $floor++) {
				$arResult["SECTIONS"][$key]["FLOORS"][$floor] = array("NUMBER" => $floor . " этаж");
			}
		}
	}

	//проверяем, есть ли хотя бы 1 свободный бокс с заданной площадью на этаже
	foreach ($arResult["SECTIONS"][$key]["FLOORS"] as $k_fl => $floor) {
		$res = CIBlockElement::GetList(
			array("catalog_PRICE_1" => "asc"),
			array(
				"IBLOCK_ID" => $arParams["IBLOCK_ID"],
				"ACTIVE" => "Y",
				"PROPERTY_STATUS" => BOX_STATUS_OPENED_ID,
				"IBLOCK_SECTION_ID" => $arItem["ID"],
				"PROPERTY_FLOOR_VALUE" => $floor["NUMBER"],
				array(
					"LOGIC" => "AND",
					array(
						">=PROPERTY_SQUARE" => $arParams["SQUARE_FROM"],
						"<=PROPERTY_SQUARE" => $arParams["SQUARE_TO"]
					),
				),
			),
			false,
			array("nTopCount" => 1),
			array("ID", "IBLOCK_ID", "NAME")
		);
		if ($ob = $res->GetNextElement()) {
			$arFields = $ob->GetFields();
			$arDiscounts = CCatalogDiscount::GetDiscountByProduct($arFields["ID"], $USER->GetUserGroupArray(), "N");
			if (is_array($arDiscounts) && sizeof($arDiscounts) > 0) {
				$currentPrice = CCatalogProduct::CountPriceWithDiscount($arFields["CATALOG_PRICE_1"], "RUB", $arDiscounts);
			} else {
				$currentPrice = intval($arFields["CATALOG_PRICE_1"]);
			}

			$arResult["SECTIONS"][$key]["FLOORS"][$k_fl]["PRICE"] = $currentPrice;
			$arResult["SECTIONS"][$key]["FLOORS"][$k_fl]["STATUS"] = "opened";
		} else {
			$arResult["SECTIONS"][$key]["FLOORS"][$k_fl]["STATUS"] = "closed";

			//ищем минимальную цену занятых боксов на этаже 
			$res = CIBlockElement::GetList(
				array("catalog_PRICE_1" => "asc"),
				array(
					"IBLOCK_ID" => $arParams["IBLOCK_ID"],
					"ACTIVE" => "Y",
					"!PROPERTY_STATUS" => BOX_STATUS_OPENED_ID,
					"!PROPERTY_STATUS" => BOX_STATUS_DELETED_ID,
					"IBLOCK_SECTION_ID" => $arItem["ID"],
					"PROPERTY_FLOOR_VALUE" => $floor["NUMBER"],
					array(
						"LOGIC" => "AND",
						array(
							">=PROPERTY_SQUARE" => $arParams["SQUARE_FROM"],
							"<=PROPERTY_SQUARE" => $arParams["SQUARE_TO"]
						),
					),
				),
				false,
				array("nTopCount" => 1),
				array("ID", "IBLOCK_ID", "NAME")
			);
			if ($ob = $res->GetNextElement()) {
				$arFields = $ob->GetFields();
				$arDiscounts = CCatalogDiscount::GetDiscountByProduct($arFields["ID"], $USER->GetUserGroupArray(), "N");
				if (is_array($arDiscounts) && sizeof($arDiscounts) > 0) {
					$currentPrice = CCatalogProduct::CountPriceWithDiscount($arFields["CATALOG_PRICE_1"], "RUB", $arDiscounts);
				} else {
					$currentPrice = intval($arFields["CATALOG_PRICE_1"]);
				}

				$arResult["SECTIONS"][$key]["FLOORS"][$k_fl]["PRICE"] = $currentPrice;
			}
		}
	}

	//получаем минимальную цену бокса на складе для карты
	$skladMapPrice = 0;

	/*
	//прошлый вариант реализации
	foreach($arResult["SECTIONS"][$key]["FLOORS"] as $k_fl=>$floor) {
		if($floor["STATUS"] == "opened") {
			if($floor["PRICE"] > 0) {
				if($skladMapPrice == 0) {
					$skladMapPrice = $floor["PRICE"];
				} elseif($floor["PRICE"] < $skladMapPrice) {
					$skladMapPrice = $floor["PRICE"];
				}
			}
		}
	}
	if($skladMapPrice == 0) {
		foreach($arResult["SECTIONS"][$key]["FLOORS"] as $k_fl=>$floor) {
			if($floor["STATUS"] == "closed") {
				if($floor["PRICE"] > 0) {
					if($skladMapPrice == 0) {
						$skladMapPrice = $floor["PRICE"];
					} elseif($floor["PRICE"] < $skladMapPrice) {
						$skladMapPrice = $floor["PRICE"];
					}
				}
			}
		}	
	}
	*/

	if (strlen($arItem["UF_PRICE_ON_MAP"]) > 0) {
		$skladMapPrice = $arItem["UF_PRICE_ON_MAP"];
	}

	$arResult["SECTIONS"][$key]["MAP_PRICE"] = $skladMapPrice;
}
?>


<? $this->__component->SetResultCacheKeys(array("CACHED_TPL", "SECTIONS")); ?>