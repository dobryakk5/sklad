<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
GLOBAL $USER;
$userID = $USER->GetID();

$arResult['STORE'] = [];
$boxFilter = [];
foreach ($arResult['ITEMS'] as $key => $arItem) {
	if (count($arItem["PROPERTIES"]["CRM_FILES"]["VALUE"]) > 0) {      
		$arResult['STORE'][$key] = $arItem['ID'];
	} else {
		$cFilter[] = $arItem["PROPERTIES"]["CONTRACT"]["VALUE"];
	}
}

if (count($cFilter) > 0) {
	$arSelect = Array("ID", "IBLOCK_ID", "NAME");
	$arFilter = Array("IBLOCK_ID"=>52, 'ID' => $cFilter, "PROPERTY_USER"=>$userID);
	$res = CIBlockElement::GetList(Array("ID"=>"ASC"), $arFilter, false, false, $arSelect);
	while ($ob = $res->GetNextElement()) {
		$arFields = $ob->GetFields();  
		$arProps = $ob->GetProperties(); 
		$arResult["BOX_INVENTORY"][$arFields['ID']]['BOX'] = $arProps['BOX']['VALUE'];
		$arResult["BOX_INVENTORY"][$arFields['ID']]['NAME'] = $arFields['NAME'];
	}
}
?>