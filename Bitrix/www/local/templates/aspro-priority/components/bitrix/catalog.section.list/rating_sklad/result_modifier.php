<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?
$arResult["DATA"] = Array();

if($arResult["SECTION"]["ID"] > 0) {
	//открыта детальная страница склада
	$arResult["DATA"]["ID"] = $arResult["SECTION"]["ID"];
	$arResult["DATA"]["RATING"] = $arResult["SECTION"]["UF_YMAP_RATING"];
	$arResult["DATA"]["VOTES"] = $arResult["SECTION"]["UF_YMAP_VOTES"];
	$arResult["DATA"]["REVIEWS"] = $arResult["SECTION"]["UF_YMAP_REVIEWS"];
} else {
	//открыта страница со списком
	$maxRating = 0;
	$keySection = 0;
	foreach($arResult["SECTIONS"] as $key=>$arSection) {
		if($arSection["UF_YMAP_RATING"] > $maxRating) {
			$maxRating = $arSection["UF_YMAP_RATING"];
			$keySection = $key;
		}
	}
	
	$arResult["DATA"]["ID"] = $arResult["SECTIONS"][$keySection]["ID"];
	$arResult["DATA"]["RATING"] = $arResult["SECTIONS"][$keySection]["UF_YMAP_RATING"];
	$arResult["DATA"]["VOTES"] = $arResult["SECTIONS"][$keySection]["UF_YMAP_VOTES"];
	$arResult["DATA"]["REVIEWS"] = $arResult["SECTIONS"][$keySection]["UF_YMAP_REVIEWS"];	
}
?>