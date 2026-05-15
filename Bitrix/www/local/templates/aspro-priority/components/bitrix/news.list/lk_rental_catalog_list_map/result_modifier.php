<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
//Записываем в массив ID нашего бокса
$arFilteredBoxesId = Array();
foreach($arResult["ITEMS"] as $key=>$arItem) {
	$arResult["ITEMS"][$key]["CLASS_STATUS"] = "opened";
	$arFilteredBoxesId[] = $arItem["ID"];
}

//Получаем все остальные боксы этого склада и этого этажа
$arOtherItems = Array();
if((strlen($arParams["PARENT_SECTION_CODE"]) > 0) and (strlen($arParams["FLOOR_CODE"]) > 0)) {
	$res = CIBlockElement::GetList(Array("propertysort_STATUS"=>"desc"), Array("IBLOCK_ID"=>STORAGES_CATALOG_IBLOCK, "SECTION_CODE"=>$arParams["PARENT_SECTION_CODE"], "PROPERTY_FLOOR_VALUE"=>preg_replace("/[^0-9]/", "", $arParams["FLOOR_CODE"])." этаж", "!ID"=>$arFilteredBoxesId), false, Array(), Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_STATUS", "PROPERTY_MAP_COORDS"));
	while($ob = $res->GetNextElement()) {
		$arFields = $ob->GetFields();
		if(strlen($arFields["PROPERTY_MAP_COORDS_VALUE"]) > 0) {
			$arOtherItems[] = $arFields;
		}
	}
}

//Формируем итоговый массив объектов "MAP_ITEMS" для карты этажа
$arResult["MAP_ITEMS"] = Array();
foreach($arOtherItems as $item) {
	$classStatus = "closed";
	$arResult["MAP_ITEMS"][] = Array("ID"=>$item["ID"], "NAME"=>$item["NAME"], "CLASS_STATUS"=>$classStatus, "MAP_COORDS"=>$item["PROPERTY_MAP_COORDS_VALUE"]);
}
foreach($arResult["ITEMS"] as $arItem) {
	if(strlen($arItem["PROPERTIES"]["MAP_COORDS"]["VALUE"]) > 0) {
		$arResult["MAP_ITEMS"][] = Array("ID"=>$arItem["ID"], "NAME"=>$arItem["NAME"], "CLASS_STATUS"=>$arItem["CLASS_STATUS"], "MAP_COORDS"=>$arItem["PROPERTIES"]["MAP_COORDS"]["VALUE"]);
	}
}
?>
