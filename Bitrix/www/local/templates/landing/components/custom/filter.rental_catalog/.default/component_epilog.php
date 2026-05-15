<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
global $APPLICATION;
?>


<?
global $arrFilterRentalCatalog3;
if(strlen($_REQUEST["FLOOR_CODE"]) > 0) {
	$arrFilterRentalCatalog3["PROPERTY_FLOOR_VALUE"] = preg_replace("/[^0-9]/", "", $_REQUEST["FLOOR_CODE"])." этаж";
}
if(strlen($arParams["PROP_SIZE"]) > 0) {
	if(strlen($_REQUEST["SIZE_FROM"]) > 0) {
		$arrFilterRentalCatalog3[">=PROPERTY_".$arParams["PROP_SIZE"]] = intval($_REQUEST["SIZE_FROM"]);
	}
	if(strlen($_REQUEST["SIZE_TO"]) > 0) {
		$arrFilterRentalCatalog3["<=PROPERTY_".$arParams["PROP_SIZE"]] = intval($_REQUEST["SIZE_TO"]);
	}
	
	// не выводим боксы с нулевой площадью/объемом
	if(strlen($_REQUEST["SIZE_FROM"]) == 0) {
		$arrFilterRentalCatalog3[">=PROPERTY_".$arParams["PROP_SIZE"]] = 1;
	}
}

if(strlen($_REQUEST["SKLAD_CODE"]) > 0) {
	$GLOBALS["SKLAD_CODE"] = $_REQUEST["SKLAD_CODE"];
	$GLOBALS["SHOW_ALL_WO_SECTION"] = "N";
} else {
	$GLOBALS["SKLAD_CODE"] = "";
	$GLOBALS["SHOW_ALL_WO_SECTION"] = "Y";
}
$arrFilterRentalCatalog3["PROPERTY_STATUS"] = BOX_STATUS_OPENED_ID;
?>
<?$arResult["CACHED_TPL"] = preg_replace_callback(
    "/#SHOW_MIN_PRICE#/is".BX_UTF_PCRE_MODIFIER,
    create_function('$matches', 'ob_start();
    $GLOBALS["APPLICATION"]->IncludeComponent(
		"bitrix:catalog.section", 
		"rental_catalog_bestprice_item", 
		array(
			"AJAX_LOAD" => "Y",
			"ACTION_VARIABLE" => "action",
			"ADD_PROPERTIES_TO_BASKET" => "N",
			"ADD_SECTIONS_CHAIN" => "N",
			"ADD_TO_BASKET_ACTION" => "ADD",
			"AJAX_MODE" => "N",
			"AJAX_OPTION_ADDITIONAL" => "",
			"AJAX_OPTION_HISTORY" => "N",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "Y",
			"BACKGROUND_IMAGE" => "-",
			"BASKET_URL" => "/personal/basket.php",
			"BROWSER_TITLE" => "-",
			"CACHE_FILTER" => "N",
			"CACHE_GROUPS" => "Y",
			"CACHE_TIME" => "3600",
			"CACHE_TYPE" => "A",
			"COMPATIBLE_MODE" => "Y",
			"CONVERT_CURRENCY" => "N",
			"DETAIL_URL" => "",
			"DISABLE_INIT_JS_IN_COMPONENT" => "N",
			"DISPLAY_BOTTOM_PAGER" => "N",
			"DISPLAY_COMPARE" => "N",
			"DISPLAY_TOP_PAGER" => "N",
			"ELEMENT_SORT_FIELD" => "catalog_PRICE_1",
			"ELEMENT_SORT_FIELD2" => "property_SQUARE",
			"ELEMENT_SORT_ORDER" => "asc",
			"ELEMENT_SORT_ORDER2" => "asc",
			"ENLARGE_PRODUCT" => "STRICT",
			"FILTER_NAME" => "arrFilterRentalCatalog3",
			"HIDE_NOT_AVAILABLE" => "N",
			"HIDE_NOT_AVAILABLE_OFFERS" => "N",
			"IBLOCK_ID" => "40",
			"IBLOCK_TYPE" => "aspro_priority_catalog",
			"INCLUDE_SUBSECTIONS" => "Y",
			"LAZY_LOAD" => "N",
			"LINE_ELEMENT_COUNT" => "3",
			"LOAD_ON_SCROLL" => "N",
			"MESSAGE_404" => "",
			"MESS_BTN_ADD_TO_BASKET" => "В корзину",
			"MESS_BTN_BUY" => "Купить",
			"MESS_BTN_DETAIL" => "Подробнее",
			"MESS_BTN_SUBSCRIBE" => "Подписаться",
			"MESS_NOT_AVAILABLE" => "Нет в наличии",
			"META_DESCRIPTION" => "-",
			"META_KEYWORDS" => "-",
			"OFFERS_LIMIT" => "5",
			"PAGER_BASE_LINK_ENABLE" => "N",
			"PAGER_DESC_NUMBERING" => "N",
			"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
			"PAGER_SHOW_ALL" => "N",
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_TEMPLATE" => ".default",
			"PAGER_TITLE" => "Товары",
			"PAGE_ELEMENT_COUNT" => "1",
			"PARTIAL_PRODUCT_PROPERTIES" => "N",
			"PRICE_CODE" => array(
				0 => "BASE",
			),
			"PRICE_VAT_INCLUDE" => "Y",
			"PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons",
			"PRODUCT_ID_VARIABLE" => "id",
			"PRODUCT_PROPS_VARIABLE" => "prop",
			"PRODUCT_QUANTITY_VARIABLE" => "quantity",
			"PRODUCT_ROW_VARIANTS" => "",
			"PRODUCT_SUBSCRIPTION" => "Y",
			"RCM_PROD_ID" => "",
			"RCM_TYPE" => "personal",
			"SECTION_CODE" => $GLOBALS["SKLAD_CODE"],
			"SECTION_ID" => "",
			"SECTION_ID_VARIABLE" => "SECTION_ID",
			"SECTION_URL" => "",
			"SECTION_USER_FIELDS" => array(
				0 => "",
			),
			"SEF_MODE" => "N",
			"SET_BROWSER_TITLE" => "N",
			"SET_LAST_MODIFIED" => "N",
			"SET_META_DESCRIPTION" => "N",
			"SET_META_KEYWORDS" => "N",
			"SET_STATUS_404" => "N",
			"SET_TITLE" => "N",
			"SHOW_404" => "N",
			"SHOW_ALL_WO_SECTION" => $GLOBALS["SHOW_ALL_WO_SECTION"],
			"SHOW_CLOSE_POPUP" => "N",
			"SHOW_DISCOUNT_PERCENT" => "N",
			"SHOW_FROM_SECTION" => "N",
			"SHOW_MAX_QUANTITY" => "N",
			"SHOW_OLD_PRICE" => "N",
			"SHOW_PRICE_COUNT" => "1",
			"SHOW_SLIDER" => "Y",
			"TEMPLATE_THEME" => "blue",
			"USE_ENHANCED_ECOMMERCE" => "N",
			"USE_MAIN_ELEMENT_SECTION" => "N",
			"USE_PRICE_COUNT" => "N",
			"USE_PRODUCT_QUANTITY" => "N",
			"COMPONENT_TEMPLATE" => "rental_catalog_bestprice_item",
			"CUSTOM_FILTER" => ""
		),
		false,
		Array("HIDE_ICONS"=>"Y")
    );
    $retrunStr = @ob_get_contents();
    ob_get_clean();
    return $retrunStr;'),
    $arResult["CACHED_TPL"]);
?>


<?// вывод
echo $arResult["CACHED_TPL"];
?>