<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?
$arResult["SECTION"]["UF_TISERS_GALLERY"] = array_diff($arResult["SECTION"]["UF_TISERS_GALLERY"], array(''));

$cnt = 0;
foreach($arResult["SECTION"]["UF_TISERS_GALLERY"] as $arPic) {
	$arResult["SECTION"]["GALLERY"][$cnt]["BIG"]["RESIZE"] = CFile::ResizeImageGet($arPic, array("width"=>1500, "height"=>1500), BX_RESIZE_IMAGE_PROPORTIONAL, false);
	$arResult["SECTION"]["GALLERY"][$cnt]["MEDIUM"]["RESIZE"] = CFile::ResizeImageGet($arPic, array("width"=>600, "height"=>450), BX_RESIZE_IMAGE_EXACT, false);
	$arResult["SECTION"]["GALLERY"][$cnt]["SMALL"]["RESIZE"] = CFile::ResizeImageGet($arPic, array("width"=>100, "height"=>100), BX_RESIZE_IMAGE_EXACT, false);
	$cnt++;
}


$arResult["SECTION"]["TISERS"] = Array();
if(count($arResult["SECTION"]["UF_TISERS_ITEMS"]) > 0) {
	$arSelect = Array("ID", "IBLOCK_ID", "NAME", "PREVIEW_PICTURE","PROPERTY_*");
	$arFilter = Array("IBLOCK_ID"=>42, "ACTIVE"=>"Y", "ID"=>$arResult["SECTION"]["UF_TISERS_ITEMS"]);
	$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, Array(), $arSelect);
	while($ob = $res->GetNextElement()) { 
		$arFields = $ob->GetFields();  
		$arProps = $ob->GetProperties();
		$arResult["SECTION"]["TISERS"][] = Array("ID"=>$arFields["ID"], "NAME"=>$arFields["NAME"], "PICTURE"=>CFile::GetPath($arFields["PREVIEW_PICTURE"]), "LINK"=>$arProps["LINK"]["VALUE"], "LINK_TEXT"=>$arProps["LINK"]["DESCRIPTION"]);
	}
}
?>