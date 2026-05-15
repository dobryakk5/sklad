<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?
global $APPLICATION;
?>



<? $arResult["CACHED_TPL"] = preg_replace_callback(
	"/#SLIDER#/is" . BX_UTF_PCRE_MODIFIER,
	function ($matches) {
		ob_start();
		$GLOBALS["APPLICATION"]->IncludeComponent(
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
				"SECTION_CODE" => "",
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
				"SIZE_FROM" => "1",
				"SMALL_THMB" => "Y"
			),
			false,
			array("HIDE_ICONS" => "Y")
		);
		$retrunStr = @ob_get_contents();
		ob_get_clean();
		return $retrunStr;
	},
	$arResult["CACHED_TPL"]
);
?>

<? $arResult["CACHED_TPL"] = preg_replace_callback(
	"/#MOBILE_SLIDER#/is" . BX_UTF_PCRE_MODIFIER,
	function ($matches) {
		ob_start();
		$GLOBALS["APPLICATION"]->IncludeComponent(
			"bitrix:catalog.section.list",
			"fotogalereya_calculator_mobile",
			array(
				"ADD_SECTIONS_CHAIN" => "N",
				"CACHE_FILTER" => "N",
				"CACHE_GROUPS" => "N",
				"CACHE_TIME" => "36000",
				"CACHE_TYPE" => "A",
				"COMPONENT_TEMPLATE" => "fotogalereya_calculator_mobile",
				"COUNT_ELEMENTS" => "N",
				"FILTER_NAME" => "",
				"IBLOCK_ID" => "40",
				"IBLOCK_TYPE" => "aspro_priority_catalog",
				"SECTION_CODE" => "",
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
				"SIZE_FROM" => "1",
				"SMALL_THMB" => "Y"
			),
			false,
			array("HIDE_ICONS" => "Y")
		);
		$retrunStr = @ob_get_contents();
		ob_get_clean();
		return $retrunStr;
	},
	$arResult["CACHED_TPL"]
);
?>



<? $arResult["CACHED_TPL"] = preg_replace_callback(
	"/#SKLAD_LIST#/is" . BX_UTF_PCRE_MODIFIER,
	function ($matches) {
		ob_start();
		$GLOBALS["APPLICATION"]->IncludeComponent(
			"bitrix:catalog.section.list",
			"calculator_storage_list",
			array(
				"SQUARE_FROM" => "0",
				"ADD_SECTIONS_CHAIN" => "N",
				"CACHE_FILTER" => "N",
				"CACHE_GROUPS" => "N",
				"CACHE_TIME" => "36000",
				"CACHE_TYPE" => "A",
				"COUNT_ELEMENTS" => "N",
				"FILTER_NAME" => "arRegionLinkUF",
				"IBLOCK_ID" => "40",
				"IBLOCK_TYPE" => "aspro_priority_catalog",
				"SECTION_CODE" => "",
				"SECTION_FIELDS" => array(
					0 => "NAME",
					1 => "DESCRIPTION",
					2 => "",
				),
				"SECTION_ID" => "",
				"SECTION_URL" => "#CODE#/",
				"SECTION_USER_FIELDS" => array(
					0 => "UF_ADDRESS",
				),
				"SHOW_PARENT_NAME" => "Y",
				"TOP_DEPTH" => "1",
				"VIEW_MODE" => "LINE",
				"COMPONENT_TEMPLATE" => "calculator_storage_list"
			),
			false,
			array("HIDE_ICONS" => "Y")
		);
		$retrunStr = @ob_get_contents();
		ob_get_clean();
		return $retrunStr;
	},
	$arResult["CACHED_TPL"]
);
?>


<? // вывод
echo $arResult["CACHED_TPL"];
?>