<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use \Bitrix\Main;
\Bitrix\Main\Loader::includeModule('iblock');
?>

<?
$arResult["SECTION"]["TISERS"] = array();
if (count($arResult["SECTION"]["UF_TISERS_ITEMS"]) > 0) {
    $arSelect = array("ID", "IBLOCK_ID", "NAME", "PREVIEW_PICTURE", "PROPERTY_*");
    $arFilter = array("IBLOCK_ID" => 42, "ACTIVE" => "Y", "ID" => $arResult["SECTION"]["UF_TISERS_ITEMS"]);
    $res = CIBlockElement::GetList(array("SORT" => "ASC"), $arFilter, false, array(), $arSelect);
    while ($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();
        $arProps = $ob->GetProperties();
        $arResult["SECTION"]["TISERS"][] = array("ID" => $arFields["ID"], "NAME" => $arFields["NAME"], "PICTURE" => CFile::GetPath($arFields["PREVIEW_PICTURE"]), "LINK" => $arProps["LINK"]["VALUE"], "LINK_TEXT" => $arProps["LINK"]["DESCRIPTION"]);
    }
}
$this->__component->SetResultCacheKeys(array("CACHED_TPL"));


$entity = \Bitrix\Iblock\Model\Section::compileEntityByIblock($arParams["IBLOCK_ID"]);

$rsSection = $entity::getList(array(
    "filter" => array(
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "ID" => $arResult["SECTION"]["ID"],
        "ACTIVE" => "Y",
        "GLOBAL_ACTIVE" => "Y"
    ),
    "select" => array("NAME","UF_BANNER_TEXT","UF_TOPIMAGE_1","UF_TOPIMAGE_2","UF_ORDER_BTN","UF_QUESTION_BTN","UF_SHOWPRICES_BTN","UF_BLOCK_TITLE_1","UF_BLOCK_TITLE_2"),
));


while ($arSection = $rsSection->Fetch()){

	$ARTOPBANNERPROPS=$arSection;
}


$cp = $this->__component;
if (is_object($cp)) {
    $cp->arResult['TOP_BANNER'] = $ARTOPBANNERPROPS;
    $cp->SetResultCacheKeys(array('TOP_BANNER'));
}

