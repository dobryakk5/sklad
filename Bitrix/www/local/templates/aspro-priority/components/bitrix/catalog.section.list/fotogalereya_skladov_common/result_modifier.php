<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?
foreach($arResult["SECTIONS"] as $key=>$arItem) {
	
	if(strlen($arResult["SECTIONS"][$key]["UF_PHOTOGALLERY"]) > 0) {
		$res = CIBlockElement::GetList(
			array("SORT"=>"ASC"),
			array("IBLOCK_ID"=>43, "SECTION_ID"=>$arResult["SECTIONS"][$key]["UF_PHOTOGALLERY"]),
			false,
			false,
			array("ID", "NAME", "DETAIL_PICTURE")
		);
		while($ob = $res->GetNextElement())
		{
			$arFields = $ob->GetFields();

			$arFields["PICTURE"]["RESIZE"]["SMALL"] = CFile::ResizeImageGet($arFields["DETAIL_PICTURE"], array("width"=>275, "height"=>140), BX_RESIZE_IMAGE_EXACT, false);
			$arFields["PICTURE"]["RESIZE"]["BIG"] = CFile::ResizeImageGet($arFields["DETAIL_PICTURE"], array("width"=>1500, "height"=>1500), BX_RESIZE_IMAGE_PROPORTIONAL, false);

			$arResult["SECTIONS"][$key]["PHOTOGALLERY"][] = $arFields;
		}
	}
}
//var_dump($arResult);
?>

<?$this->__component->SetResultCacheKeys(array("CACHED_TPL"));?>