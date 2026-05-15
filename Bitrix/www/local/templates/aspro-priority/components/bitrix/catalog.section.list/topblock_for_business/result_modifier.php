<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?
if(strlen($arResult["SECTION"]["PICTURE"]) > 0) {
	$arResult["SECTION"]["BG_PICTURE"] = CFile::GetPath($arResult["SECTION"]["PICTURE"]);
}


// направления применения боксов
$useTypeCode = "";
if($arResult["SECTION"]["CODE"] == "for_business") {
	//если для бизнеса
	$useTypeCode = "UF_USE_TYPE_B";
} elseif($arResult["SECTION"]["CODE"] == "storage") {
	//если для дома
	$useTypeCode = "UF_USE_TYPE_P";
}

if(strlen($useTypeCode) > 0) {
	$rsUseTypes = CUserFieldEnum::GetList(array("SORT"=>"ASC"), array("USER_FIELD_NAME" => $useTypeCode));
	while($arUseType = $rsUseTypes->GetNext()) {
		$arUseType["CODE"] = $useTypeCode;
		$arResult["SECTION"]["USE_TYPE"][] = $arUseType;
	}
}

global ${$arParams["FILTER_NAME"]};
foreach($arResult["SECTION"]["USE_TYPE"] as $k=>$arUseType) {
    $arFilter = array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y", ">LEFT_MARGIN" => $arResult["SECTION"]["LEFT_MARGIN"], "<RIGHT_MARGIN" => $arResult["SECTION"]["RIGHT_MARGIN"], "DEPTH_LEVEL" => 2, $useTypeCode => $arUseType["ID"]);

    if(!empty(${$arParams["FILTER_NAME"]}['UF_LINK_REGION'])){
        $arFilter['UF_LINK_REGION']=${$arParams["FILTER_NAME"]}['UF_LINK_REGION'];
    }
    $rsSect = CIBlockSection::GetList(array("SORT" => "ASC"), $arFilter);
    while ($arSect = $rsSect->GetNext()) {
        $arResult["SECTION"]["USE_TYPE"][$k]["ITEMS"][] = $arSect;
    }
}
?>