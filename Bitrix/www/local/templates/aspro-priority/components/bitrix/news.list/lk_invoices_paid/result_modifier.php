<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
foreach($arResult["ITEMS"] as $key=>$arItem) {
	//получаем данные о боксе
	if(strlen($arItem["PROPERTIES"]["BOX"]["VALUE"]) > 0) {	
		$res = CIBlockElement::GetList(Array("ID"=>"ASC"), Array("IBLOCK_ID"=>STORAGES_CATALOG_IBLOCK, "ID"=>$arItem["PROPERTIES"]["BOX"]["VALUE"]), false, Array("nTopCount"=>1), Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_BOX_NUMBER"));
		if($ob = $res->GetNextElement()) {
			$arBoxFields = $ob->GetFields();
			$arResult["ITEMS"][$key]["BOX"] = $arBoxFields;
		}		
	}
	
	//получаем сумму счета
	\Bitrix\Main\Loader::includeModule("catalog");
	$rsPrice = \Bitrix\Catalog\PriceTable::getList(
		Array(
			"select" => Array("*"),
			"filter" => Array("=PRODUCT_ID" => $arItem["ID"], "CATALOG_GROUP_ID" => 1)
		)
	);	
	if($arInvoicePrice = $rsPrice->fetch()) {
		$arResult["ITEMS"][$key]["PRICE"] = FormatCurrency($arInvoicePrice["PRICE"], $arInvoicePrice["CURRENCY"]);
	}
}
?>