<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
global $APPLICATION;
?>


<?
$GLOBALS["arParams"] = $arParams;
global $arrFilterRentalCatalogMap;
$arrFilterRentalCatalogMap["ID"] = $arParams["BOX_ID"];
$arrFilterRentalCatalogMap["ACTIVE"] = Array("Y", "N");
?>
<?$arResult["CACHED_TPL"] = preg_replace_callback(
    "/#BOXES#/is".BX_UTF_PCRE_MODIFIER,
    function($matches) {ob_start();
	$GLOBALS["APPLICATION"]->IncludeComponent(
		"bitrix:news.list", 
		"lk_rental_catalog_list_map", 
		array(
			"COMPONENT_TEMPLATE" => "lk_rental_catalog_list_map",
			"IBLOCK_TYPE" => "aspro_priority_catalog",
			"IBLOCK_ID" => "40",
			"NEWS_COUNT" => "999",
			"SORT_BY1" => "ACTIVE_FROM",
			"SORT_ORDER1" => "DESC",
			"SORT_BY2" => "SORT",
			"SORT_ORDER2" => "ASC",
			"FILTER_NAME" => "arrFilterRentalCatalogMap",
			"FIELD_CODE" => array(
				0 => "NAME",
				1 => "",
			),
			"PROPERTY_CODE" => array(
				0 => "MAP_COORDS",
				1 => "",
			),
			"CHECK_DATES" => "N",
			"DETAIL_URL" => "",
			"AJAX_MODE" => "N",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "Y",
			"AJAX_OPTION_HISTORY" => "N",
			"AJAX_OPTION_ADDITIONAL" => "",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "36000000",
			"CACHE_FILTER" => "N",
			"CACHE_GROUPS" => "Y",
			"PREVIEW_TRUNCATE_LEN" => "",
			"ACTIVE_DATE_FORMAT" => "d.m.Y",
			"SET_TITLE" => "N",
			"SET_BROWSER_TITLE" => "N",
			"SET_META_KEYWORDS" => "N",
			"SET_META_DESCRIPTION" => "N",
			"SET_LAST_MODIFIED" => "N",
			"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
			"ADD_SECTIONS_CHAIN" => "N",
			"HIDE_LINK_WHEN_NO_DETAIL" => "N",
			"PARENT_SECTION" => "",
			"PARENT_SECTION_CODE" => $GLOBALS["arParams"]["SECTION_CODE"],
			"INCLUDE_SUBSECTIONS" => "N",
			"STRICT_SECTION_CHECK" => "N",
			"DISPLAY_DATE" => "N",
			"DISPLAY_NAME" => "N",
			"DISPLAY_PICTURE" => "N",
			"DISPLAY_PREVIEW_TEXT" => "Y",
			"PAGER_TEMPLATE" => ".default",
			"DISPLAY_TOP_PAGER" => "N",
			"DISPLAY_BOTTOM_PAGER" => "N",
			"PAGER_TITLE" => "Новости",
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_DESC_NUMBERING" => "N",
			"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
			"PAGER_SHOW_ALL" => "N",
			"PAGER_BASE_LINK_ENABLE" => "N",
			"SET_STATUS_404" => "N",
			"SHOW_404" => "N",
			"MESSAGE_404" => "",
			"FLOOR_CODE" => $GLOBALS["arParams"]["FLOOR_CODE"],
		),
		false,
		Array("HIDE_ICONS"=>"Y")
	);	
    $retrunStr = @ob_get_contents();
    ob_get_clean();
    return $retrunStr;},
    $arResult["CACHED_TPL"]);
?>


<?// вывод
echo $arResult["CACHED_TPL"];
?>