<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


<?$arResult["CACHED_TPL"] = preg_replace_callback(
    "/#SKLAD_LIST#/is".BX_UTF_PCRE_MODIFIER,
    create_function('$matches', 'ob_start();
    $GLOBALS["APPLICATION"]->IncludeComponent(
		"bitrix:catalog.section.list", 
		"sklad_list_right_col", 
		array(
			"ADD_SECTIONS_CHAIN" => "N",
			"CACHE_FILTER" => "N",
			"CACHE_GROUPS" => "N",
			"CACHE_TIME" => "36000000",
			"CACHE_TYPE" => "A",
			"COUNT_ELEMENTS" => "N",
			"FILTER_NAME" => "",
			"IBLOCK_ID" => "40",
			"IBLOCK_TYPE" => "aspro_priority_catalog",
			"SECTION_CODE" => $_REQUEST["SECTION_CODE"],
			"SECTION_FIELDS" => array(
				0 => "NAME",
				1 => "PICTURE",
				2 => "",
			),
			"SECTION_ID" => "",
			"SECTION_URL" => "#CODE#/",
			"SECTION_USER_FIELDS" => array(
				0 => "UF_ADDRESS",
				1 => "UF_RECEPTION",
				2 => "UF_DOSTUP_TIME",
				3 => "UF_PHONE",
				4 => "UF_MAP",
				5 => "UF_YMAP_RATING",
				6 => "UF_YMAP_VOTES",
				7 => "UF_YMAP_REVIEWS",
			),
			"SHOW_PARENT_NAME" => "Y",
			"TOP_DEPTH" => "1",
			"VIEW_MODE" => "LINE",
			"COMPONENT_TEMPLATE" => "sklad_list_right_col"
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

include $_SERVER['DOCUMENT_ROOT'] . '/include/seo/setDescription.php';
?>