<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?

$filter = Array("IBLOCK_ID"=>$arParams["IBLOCK_ID"], "DEPTH_LEVEL"=>1, "ACTIVE"=>"Y", 'UF_LINK_REGION'=>'1');
//$filter = array_merge($filter, $GLOBALS['arRegionLinkUF']);
$arResult["MAIN_SECTIONS"] = Array();
$rsMainSect = CIBlockSection::GetList(Array("sort"=>"asc"), $filter, false, Array("ID", "IBLOCK_ID", "NAME", "CODE", "UF_ICON_MAINPAGE", "UF_PICTURE_MAINPAGE", "UF_TEXT_MAINPAGE","UF_HREF", "UF_LINK_REGION"));
while($arMainSect = $rsMainSect->GetNext()) {
    $arS = Array();
    $arS["ID"] = $arMainSect["ID"];
    $arS["NAME"] = $arMainSect["NAME"];
    $arS["CODE"] = $arMainSect["CODE"];
    if(strlen($arMainSect["UF_ICON_MAINPAGE"]) > 0) {
        $arS["ICON"] = CFile::ResizeImageGet($arMainSect["UF_ICON_MAINPAGE"], array("width"=>45, "height"=>45), BX_RESIZE_IMAGE_PROPORTIONAL, false);
    }
    if(strlen($arMainSect["UF_PICTURE_MAINPAGE"]) > 0) {
        $arS["PICTURE"] = CFile::ResizeImageGet($arMainSect["UF_PICTURE_MAINPAGE"], array("width"=>700, "height"=>370), BX_RESIZE_IMAGE_EXACT, false);
    }
    $arS["TEXT"] = $arMainSect["UF_TEXT_MAINPAGE"];
    if($arMainSect["ID"] == $arResult["SECTION"]["ID"]) {
        $arS["SELECTED"] = "Y";
    }

    $arResult["MAIN_SECTIONS"][] = $arS;
}
?>