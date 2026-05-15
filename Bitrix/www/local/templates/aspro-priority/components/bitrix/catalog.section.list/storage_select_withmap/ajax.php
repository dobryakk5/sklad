<?require_once $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php";?>
<?

if($_REQUEST["ACTION"] == "MAP_UPDATE") {
	?>
	<?$APPLICATION->IncludeComponent(
		"bitrix:catalog.section.list", 
		"storage_select_withmap", 
		array(
			"ONLY_MAP" => "Y",
			"SELECTED_SKLAD_ID" => $_REQUEST["SELECTED_SKLAD_ID"],
			"ADD_SECTIONS_CHAIN" => "N",
			"CACHE_FILTER" => "N",
			"CACHE_GROUPS" => "N",
			"CACHE_TIME" => "36000000",
			"CACHE_TYPE" => "A",
			"COUNT_ELEMENTS" => "N",
			"FILTER_NAME" => "",
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
				1 => "UF_RECEPTION",
				2 => "UF_DOSTUP_TIME",
				3 => "UF_PHONE",
				4 => "UF_MAP",
				5 => "UF_PHOTOGALLERY",
				6 => "UF_PRICE_ON_MAP",
				7 => "",
			),
			"SHOW_PARENT_NAME" => "Y",
			"TOP_DEPTH" => "1",
			"VIEW_MODE" => "LINE",
			"COMPONENT_TEMPLATE" => "storage_select_withmap"
		),
		false
	);?>
	<?
}

?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>