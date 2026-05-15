<?require_once $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php";?>
<?
CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");

if($_REQUEST["ACTION"] == "FILTER") {

	global $arrFilterRentalCatalog;
	if(strlen($_REQUEST["FLOOR_CODE"]) > 0) {
		$arrFilterRentalCatalog["PROPERTY_FLOOR_VALUE"] = preg_replace("/[^0-9]/", "", $_REQUEST["FLOOR_CODE"])." этаж";
	}
	if(strlen($_REQUEST["PROP_SIZE"]) > 0) {
		if(strlen($_REQUEST["SIZE_FROM"]) > 0) {
			$arrFilterRentalCatalog[">=PROPERTY_".$_REQUEST["PROP_SIZE"]] = floatval($_REQUEST["SIZE_FROM"]);
		}
		if(strlen($_REQUEST["SIZE_TO"]) > 0) {
			$arrFilterRentalCatalog["<=PROPERTY_".$_REQUEST["PROP_SIZE"]] = floatval($_REQUEST["SIZE_TO"]);
		}
	}
	if(strlen($_REQUEST["SKLAD_CODE"]) > 0) {
		$SKLAD_CODE = $_REQUEST["SKLAD_CODE"];
		$SHOW_ALL_WO_SECTION = "N";
	} else {
		$SKLAD_CODE = "";
		$SHOW_ALL_WO_SECTION = "Y";
	}
	if(strlen($_REQUEST["FILTERED_BOXES_LIST"]) > 0) {
		$arrFilterRentalCatalog["ID"] = explode(",", $_REQUEST["FILTERED_BOXES_LIST"]);
	}	
	$arrFilterRentalCatalog["PROPERTY_STATUS"] = BOX_STATUS_OPENED_ID;
	$arrFilterRentalCatalog["SECTION_GLOBAL_ACTIVE"] = "Y";
	
/*	//чекбокс "Показать только ячейки"
	if($_REQUEST["SHOW_ONLY_CELLS"] == "Y") {
		$arrFilterRentalCatalog["PROPERTY_OBJECT_TYPE"] = 339;
		
		setcookie("boxListOnlyCells", "Y", 0, SITE_DIR);
		$_COOKIE["boxListOnlyCells"] = "Y";
	} else {
		setcookie("boxListOnlyCells", "", 0, SITE_DIR);
		$_COOKIE["boxListOnlyCells"] = "";
	}*/

	
	if (strpos($_SERVER['HTTP_REFERER'], 'kladovki') !== false) {
		$arrFilterRentalCatalog["PROPERTY_OBJECT_TYPE"] = 413;
	} elseif (strpos($_SERVER['HTTP_REFERER'], 'konteynery') !== false) {
		$arrFilterRentalCatalog["PROPERTY_OBJECT_TYPE"] = 415;
	} elseif (strpos($_SERVER['HTTP_REFERER'], 'yacheyka') !== false) {
		$arrFilterRentalCatalog["PROPERTY_OBJECT_TYPE"] = 414;
	}

	switch ($_REQUEST["SHOW_ONLY_CELLS"]){
		case 'cells':
			$arrFilterRentalCatalog["PROPERTY_OBJECT_TYPE"] = 414;

			setcookie("boxListFilterOnly", "cells", 0, SITE_DIR);
			$_COOKIE["boxListFilterOnly"] = "cells";
			break;
		case 'containers':
			$arrFilterRentalCatalog["PROPERTY_OBJECT_TYPE"] = 415;

			setcookie("boxListFilterOnly", "containers", 0, SITE_DIR);
			$_COOKIE["boxListFilterOnly"] = "containers";
			break;
		case 'antresolbox':
			$arrFilterRentalCatalog["PROPERTY_OBJECT_TYPE"] = 413;

			setcookie("boxListFilterOnly", "antresolbox", 0, SITE_DIR);
			$_COOKIE["boxListFilterOnly"] = "antresolbox";
			break;
		case 'all':
		default:
		setcookie("boxListFilterOnly", "", 0, SITE_DIR);
		$_COOKIE["boxListFilterOnly"] = "";
			break;
	}

	
	//сортировка
	if(strlen($_COOKIE["boxListSort"]) > 0) {
		$BOX_LIST_SORT = $_COOKIE["boxListSort"];
	} else {
		$BOX_LIST_SORT = "catalog_PRICE_1";
	}
	if(strlen($_COOKIE["boxListOrder"]) > 0) {
		$BOX_LIST_ORDER = $_COOKIE["boxListOrder"];
	} else {
		$BOX_LIST_ORDER = "asc";
	}	
	?>
	<?$APPLICATION->IncludeComponent(
		"bitrix:catalog.section", 
		"rental_catalog_list", 
		array(
			"AJAX_LOAD" => "Y",
			"ACTION_VARIABLE" => "action",
			"ADD_PROPERTIES_TO_BASKET" => "Y",
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
			"DISPLAY_BOTTOM_PAGER" => "Y",
			"DISPLAY_COMPARE" => "N",
			"DISPLAY_TOP_PAGER" => "N",
			"ELEMENT_SORT_FIELD" => $BOX_LIST_SORT,
			"ELEMENT_SORT_FIELD2" => "ID",
			"ELEMENT_SORT_ORDER" => $BOX_LIST_ORDER,
			"ELEMENT_SORT_ORDER2" => "asc",
			"ENLARGE_PRODUCT" => "STRICT",
			"FILTER_NAME" => "arrFilterRentalCatalog",
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
			"PAGE_ELEMENT_COUNT" => "12",
			"PARTIAL_PRODUCT_PROPERTIES" => "N",
			"PRICE_CODE" => array(
				0 => "BASE",
			),
			"PRICE_VAT_INCLUDE" => "Y",
			"PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons",
			"PRODUCT_ID_VARIABLE" => "id",
			"PRODUCT_PROPS_VARIABLE" => "prop",
			"PRODUCT_QUANTITY_VARIABLE" => "quantity",
			"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false}]",
			"PRODUCT_SUBSCRIPTION" => "Y",
			"RCM_PROD_ID" => $_REQUEST["PRODUCT_ID"],
			"RCM_TYPE" => "personal",
			"SECTION_CODE" => $SKLAD_CODE,
			"SECTION_ID" => "",
			"SECTION_ID_VARIABLE" => "SECTION_ID",
			"SECTION_URL" => "",
			"SECTION_USER_FIELDS" => array(
				0 => "UF_ADDRESS",
				1 => "UF_PHONE",
				2 => "UF_DOSTUP_TIME",
				3 => "UF_RECEPTION",
			),
			"SEF_MODE" => "N",
			"SET_BROWSER_TITLE" => "N",
			"SET_LAST_MODIFIED" => "N",
			"SET_META_DESCRIPTION" => "N",
			"SET_META_KEYWORDS" => "N",
			"SET_STATUS_404" => "N",
			"SET_TITLE" => "N",
			"SHOW_404" => "N",
			"SHOW_ALL_WO_SECTION" => $SHOW_ALL_WO_SECTION,
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
			"COMPONENT_TEMPLATE" => "rental_catalog_list",
			"CUSTOM_FILTER" => "",
			"PROP_SIZE" => $_REQUEST["PROP_SIZE"],
		),
		false
	);?>
	<?
}


if($_REQUEST["ACTION"] == "UPDATE_SLIDER") {
			
	if(strlen($_REQUEST["SKLAD_CODE"]) > 0) {
		$SKLAD_CODE = $_REQUEST["SKLAD_CODE"];
	} else {
		$SKLAD_CODE = "";
	}
	?>
	<?$APPLICATION->IncludeComponent(
		"bitrix:catalog.section.list", 
		"fotogalereya_skladov_rental_catallog", 
		array(
			"ADD_SECTIONS_CHAIN" => "N",
			"CACHE_FILTER" => "N",
			"CACHE_GROUPS" => "N",
			"CACHE_TIME" => "36000",
			"CACHE_TYPE" => "A",
			"COMPONENT_TEMPLATE" => "fotogalereya_skladov_rental_catallog",
			"COUNT_ELEMENTS" => "N",
			"FILTER_NAME" => "",
			"IBLOCK_ID" => "40",
			"IBLOCK_TYPE" => "aspro_priority_catalog",
			"SECTION_CODE" => $SKLAD_CODE,
			"SECTION_FIELDS" => array(
				0 => "NAME",
				1 => "PICTURE",
				2 => "",
			),
			"SECTION_ID" => "",
			"SECTION_URL" => "",
			"SECTION_USER_FIELDS" => array(
				0 => "UF_PHOTOGALLERY",
				1 => "",
			),
			"SHOW_PARENT_NAME" => "Y",
			"TOP_DEPTH" => "2",
			"VIEW_MODE" => "LINE",
			"SIZE_FROM" => $_REQUEST["SIZE_FROM"]
		),
		false
	);?>
	<?
}


if($_REQUEST["ACTION"] == "UPDATE_MIN_PRICE") {

	global $arrFilterRentalCatalog3;
	if(strlen($_REQUEST["FLOOR_CODE"]) > 0) {
		$arrFilterRentalCatalog3["PROPERTY_FLOOR_VALUE"] = preg_replace("/[^0-9]/", "", $_REQUEST["FLOOR_CODE"])." этаж";
	}
	if(strlen($_REQUEST["PROP_SIZE"]) > 0) {
		if(strlen($_REQUEST["SIZE_FROM"]) > 0) {
			$arrFilterRentalCatalog3[">=PROPERTY_".$_REQUEST["PROP_SIZE"]] = floatval($_REQUEST["SIZE_FROM"]);
		}
		if(strlen($_REQUEST["SIZE_TO"]) > 0) {
			$arrFilterRentalCatalog3["<=PROPERTY_".$_REQUEST["PROP_SIZE"]] = floatval($_REQUEST["SIZE_TO"]);
		}
	}
	if(strlen($_REQUEST["SKLAD_CODE"]) > 0) {
		$SKLAD_CODE = $_REQUEST["SKLAD_CODE"];
		$SHOW_ALL_WO_SECTION = "N";
	} else {
		$SKLAD_CODE = "";
		$SHOW_ALL_WO_SECTION = "Y";
	}	
	if(strlen($_REQUEST["FILTERED_BOXES_LIST"]) > 0) {
		$arrFilterRentalCatalog3["ID"] = explode(",", $_REQUEST["FILTERED_BOXES_LIST"]);
	}	
	$arrFilterRentalCatalog3["PROPERTY_STATUS"] = BOX_STATUS_OPENED_ID;
	$arrFilterRentalCatalog3["SECTION_GLOBAL_ACTIVE"] = "Y";
	
	//чекбокс "Показать только ячейки"
/*	if($_REQUEST["SHOW_ONLY_CELLS"] == "Y") {
		$arrFilterRentalCatalog3["PROPERTY_OBJECT_TYPE"] = 339;
	}*/
	switch ($_REQUEST["SHOW_ONLY_CELLS"]){
		case 'cells':
			$arrFilterRentalCatalog3["PROPERTY_OBJECT_TYPE"] = 414;
			break;
		case 'containers':
			$arrFilterRentalCatalog3["PROPERTY_OBJECT_TYPE"] = 415;
			break;
		case 'antresolbox':
			$arrFilterRentalCatalog3["PROPERTY_OBJECT_TYPE"] = 413;
			break;
		case 'all':
		default:
			break;
	}



	?>
	<?$APPLICATION->IncludeComponent(
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
			"ELEMENT_SORT_FIELD2" => "property_".$_REQUEST["PROP_SIZE"],
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
			"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false}]",
			"PRODUCT_SUBSCRIPTION" => "Y",
			"RCM_PROD_ID" => $_REQUEST["PRODUCT_ID"],
			"RCM_TYPE" => "personal",
			"SECTION_CODE" => $SKLAD_CODE,
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
			"SHOW_ALL_WO_SECTION" => $SHOW_ALL_WO_SECTION,
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
		false
	);?>
	<?
}



if($_REQUEST["ACTION"] == "UPDATE_MAP") {
	if((strlen($_REQUEST["SKLAD_CODE"]) > 0) and (strlen($_REQUEST["FLOOR_CODE"]) > 0)) {
	
		global $arrFilterRentalCatalogMap;
		$arrFilterRentalCatalogMap["PROPERTY_FLOOR_VALUE"] = preg_replace("/[^0-9]/", "", $_REQUEST["FLOOR_CODE"])." этаж";
		
		if(strlen($_REQUEST["PROP_SIZE"]) > 0) {
			if(strlen($_REQUEST["SIZE_FROM"]) > 0) {
				$arrFilterRentalCatalogMap[">=PROPERTY_".$_REQUEST["PROP_SIZE"]] = floatval($_REQUEST["SIZE_FROM"]);
			}
			if(strlen($_REQUEST["SIZE_TO"]) > 0) {
				$arrFilterRentalCatalogMap["<=PROPERTY_".$_REQUEST["PROP_SIZE"]] = floatval($_REQUEST["SIZE_TO"]);
			}
		}
		
		if(strlen($_REQUEST["FILTERED_BOXES_LIST"]) > 0) {
			$arrFilterRentalCatalogMap["ID"] = explode(",", $_REQUEST["FILTERED_BOXES_LIST"]);
		}	
		$arrFilterRentalCatalogMap["PROPERTY_STATUS"] = BOX_STATUS_OPENED_ID;
		$arrFilterRentalCatalogMap["SECTION_GLOBAL_ACTIVE"] = "Y";	
		
		//чекбокс "Показать только ячейки"
/*		if($_REQUEST["SHOW_ONLY_CELLS"] == "Y") {
			$arrFilterRentalCatalogMap["PROPERTY_OBJECT_TYPE"] = 339;
		}*/
		switch ($_REQUEST["SHOW_ONLY_CELLS"]){
			case 'cells':
				$arrFilterRentalCatalogMap["PROPERTY_OBJECT_TYPE"] = 414;
				break;
			case 'containers':
				$arrFilterRentalCatalogMap["PROPERTY_OBJECT_TYPE"] = 415;
				break;
			case 'antresolbox':
				$arrFilterRentalCatalogMap["PROPERTY_OBJECT_TYPE"] = 413;
				break;
			case 'all':
			default:
				break;
		}
		?>
		<?$APPLICATION->IncludeComponent(
			"bitrix:catalog.section", 
			"rental_catalog_list_map", 
			array(
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
				"ELEMENT_SORT_FIELD" => "SORT",
				"ELEMENT_SORT_FIELD2" => "ID",
				"ELEMENT_SORT_ORDER" => "asc",
				"ELEMENT_SORT_ORDER2" => "asc",
				"ENLARGE_PRODUCT" => "STRICT",
				"FILTER_NAME" => "arrFilterRentalCatalogMap",
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
				"PAGE_ELEMENT_COUNT" => "9999",
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
				"SECTION_CODE" => $_REQUEST["SKLAD_CODE"],
				"SECTION_ID" => "",
				"SECTION_ID_VARIABLE" => "SECTION_ID",
				"SECTION_URL" => "",
				"SECTION_USER_FIELDS" => array(
					0 => "UF_ADDRESS",
					1 => "UF_PHONE",
					2 => "UF_DOSTUP_TIME",
					3 => "UF_RECEPTION",
				),
				"SEF_MODE" => "N",
				"SET_BROWSER_TITLE" => "N",
				"SET_LAST_MODIFIED" => "N",
				"SET_META_DESCRIPTION" => "N",
				"SET_META_KEYWORDS" => "N",
				"SET_STATUS_404" => "N",
				"SET_TITLE" => "N",
				"SHOW_404" => "N",
				"SHOW_ALL_WO_SECTION" => "N",
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
				"COMPONENT_TEMPLATE" => "rental_catalog_list_map",
				"CUSTOM_FILTER" => "",
				"PROP_SIZE" => "",
				"FLOOR_CODE" => $_REQUEST["FLOOR_CODE"],
			),
			false,
			Array("HIDE_ICONS"=>"Y")
		);?>
		<?
	}
}

?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>