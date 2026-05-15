<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?
$arSquaresVariants = Array(1,1.5,2,3,4,5,6,7,8,9,10,11,12,13,14,15);

foreach($arResult["SECTIONS"] as $key=>$arItem) {
	//foreach($arSquaresVariants as $sq) {
	for($sq=1; $sq<=100; $sq=$sq+0.5) {
	
		$res = CIBlockElement::GetList(
			Array("catalog_PRICE_1"=>"asc"), 
			Array(
				"IBLOCK_ID"=>$arParams["IBLOCK_ID"], 
				"ACTIVE"=>"Y", 
				"IBLOCK_SECTION_ID"=>$arItem["ID"], 
				"PROPERTY_SQUARE"=>$sq,
				"PROPERTY_RENT_TYPE" => 340,
				"PROPERTY_STATUS" => BOX_STATUS_OPENED_ID
			), 
			false, 
			Array("nTopCount"=>1), 
			Array("ID", "IBLOCK_ID", "NAME")
		);
		if($ob = $res->GetNextElement()) {
			$arFields = $ob->GetFields();
			$price = intval($arFields["CATALOG_PRICE_1"]);
			$discountPrice = "";
			$arDiscounts = CCatalogDiscount::GetDiscountByProduct($arFields["ID"], $USER->GetUserGroupArray(), "N");
			if(is_array($arDiscounts) && sizeof($arDiscounts) > 0) {
				$discountPrice = CCatalogProduct::CountPriceWithDiscount($arFields["CATALOG_PRICE_1"], "RUB", $arDiscounts);
			}

			$arResult["SECTIONS"][$key]["BOX"]["ITEMS"][] = Array("SQUARE"=>$sq, "PRICE"=>$price, "DISCOUNT_PRICE"=>$discountPrice);
		}
		
		if($sq == 1) {
			$res = CIBlockElement::GetList(
				Array("catalog_PRICE_1"=>"asc"), 
				Array(
					"IBLOCK_ID"=>$arParams["IBLOCK_ID"], 
					"ACTIVE"=>"Y", 
					"IBLOCK_SECTION_ID"=>$arItem["ID"], 
					"PROPERTY_SQUARE"=>$sq,
					"PROPERTY_RENT_TYPE" => 339,
					"PROPERTY_STATUS" => BOX_STATUS_OPENED_ID
				), 
				false, 
				Array("nTopCount"=>1), 
				Array("ID", "IBLOCK_ID", "NAME")
			);
			if($ob = $res->GetNextElement()) {
				$arFields = $ob->GetFields();
				$price = intval($arFields["CATALOG_PRICE_1"]);
				$discountPrice = "";
				$arDiscounts = CCatalogDiscount::GetDiscountByProduct($arFields["ID"], $USER->GetUserGroupArray(), "N");
				if(is_array($arDiscounts) && sizeof($arDiscounts) > 0) {
					$discountPrice = CCatalogProduct::CountPriceWithDiscount($arFields["CATALOG_PRICE_1"], "RUB", $arDiscounts);
				}

				$arResult["SECTIONS"][$key]["CELL"]["ITEMS"][] = Array("SQUARE"=>$sq, "PRICE"=>$price, "DISCOUNT_PRICE"=>$discountPrice);
			}		
		}
		
	}
}
?>