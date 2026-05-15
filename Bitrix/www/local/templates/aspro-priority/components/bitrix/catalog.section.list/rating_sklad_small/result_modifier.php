<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?
$arResult["DATA"] = Array();

if($arResult["SECTION"]["ID"] > 0) {
	//открыта детальная страница склада
	$arResult["DATA"]["ID"] = $arResult["SECTION"]["ID"];
	$arResult["DATA"]["RATING"] = $arResult["SECTION"]["UF_YMAP_RATING"];
	$arResult["DATA"]["VOTES"] = $arResult["SECTION"]["UF_YMAP_VOTES"];
	$arResult["DATA"]["REVIEWS"] = $arResult["SECTION"]["UF_YMAP_REVIEWS"];
	$arResult["DATA"]["CODE"] = $arResult["SECTION"]["CODE"];
	
	//получаем рандомный отзыв склада
	$arSelect = Array("ID", "ACTIVE_FROM", "PROPERTY_NAME", "PROPERTY_MESSAGE", "PROPERTY_RATING");
	$arFilter = Array("IBLOCK_ID"=>30, "ACTIVE"=>"Y", "PROPERTY_SKLAD"=>$arResult["DATA"]["ID"]);
	$res = CIBlockElement::GetList(Array("rand"=>"asc"), $arFilter, false, Array("nTopCount"=>1), $arSelect);
	if($ob = $res->GetNextElement()) {
		$arReview = $ob->GetFields();
		$arReview["ACTIVE_FROM"] = FormatDate("j F Y", MakeTimeStamp($arReview["ACTIVE_FROM"]));
		$arResult["DATA"]["REVIEW"] = $arReview;
	}	
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
	
	//получаем рандомный отзыв склада
	$arSelect = Array("ID", "ACTIVE_FROM", "PROPERTY_NAME", "PROPERTY_MESSAGE", "PROPERTY_RATING");
	$arFilter = Array("IBLOCK_ID"=>30, "ACTIVE"=>"Y", "PROPERTY_SKLAD"=>$arResult["DATA"]["ID"]);
	$res = CIBlockElement::GetList(Array("rand"=>"asc"), $arFilter, false, Array("nTopCount"=>1), $arSelect);
	if($ob = $res->GetNextElement()) {
		$arReview = $ob->GetFields();
		$arReview["ACTIVE_FROM"] = FormatDate("j F Y", MakeTimeStamp($arReview["ACTIVE_FROM"]));
		$arResult["DATA"]["REVIEW"] = $arReview;
	}	
}
?>