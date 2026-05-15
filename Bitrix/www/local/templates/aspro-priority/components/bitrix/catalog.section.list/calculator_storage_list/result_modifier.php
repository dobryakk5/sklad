<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
$arFilteredSections = Array();

foreach($arResult["SECTIONS"] as $k=>$arSection) {
	//проверяем, имеются ли свободные боксы с заданной площадью SQUARE_FROM
	$arSelect = Array("ID", "IBLOCK_ID", "NAME");
	$arFilter = Array("IBLOCK_ID"=>$arParams["IBLOCK_ID"], "ACTIVE"=>"Y", "IBLOCK_SECTION_ID"=>$arSection["ID"], "PROPERTY_STATUS"=>BOX_STATUS_OPENED_ID, ">=PROPERTY_SQUARE"=>$arParams["SQUARE_FROM"]);
	$res = CIBlockElement::GetList(Array("id"=>"asc"), $arFilter, false, Array("nTopCount"=>1), $arSelect);
	if($ob = $res->GetNextElement()) {
		$arFields = $ob->GetFields();
		
		$arFilteredSections[] = $arSection;
	}
}

$arResult["SECTIONS"] = $arFilteredSections;
?>

