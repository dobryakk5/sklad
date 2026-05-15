<?require_once $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php";?>
<?
CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");

$arRegionLinkUF = ['UF_LINK_REGION' => 1];

if($_REQUEST["ACTION"] == "UPDATE") {
	?>
	<?$APPLICATION->IncludeComponent(
		"bitrix:catalog.section.list", 
		"mainpage_price_storages", 
		array(
			"SQUARE_FROM" => $_REQUEST["SQUARE_FROM"],
			"SQUARE_TO" => $_REQUEST["SQUARE_TO"],
			"AJAX_LOAD" => "Y",
			"ADD_SECTIONS_CHAIN" => "N",
			"CACHE_FILTER" => "N",
			"CACHE_GROUPS" => "N",
			"CACHE_TIME" => "36000000",
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
				0 => "UF_PHOTOGALLERY",
				1 => "UF_ADDRESS",
				2 => "UF_RECEPTION",
				3 => "UF_DOSTUP_TIME",
				4 => "UF_PHONE",
				5 => "UF_MAP",
				6 => "UF_FLOORS",
				7 => "",
			),
			"SHOW_PARENT_NAME" => "Y",
			"TOP_DEPTH" => "1",
			"VIEW_MODE" => "LINE",
			"COMPONENT_TEMPLATE" => "mainpage_price_storages"
		),
		false
	);?>
	<?
}

/*
if($_REQUEST["ACTION"] == "MAP_UPDATE") {
	?>
	<?$APPLICATION->IncludeComponent(
		"bitrix:catalog.section.list", 
		"mainpage_price_storages", 
		array(
			"SQUARE_FROM" => $_REQUEST["SQUARE_FROM"],
			"SQUARE_TO" => $_REQUEST["SQUARE_TO"],
			"AJAX_LOAD" => "Y",
			"ONLY_MAP" =>"Y",
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
				0 => "UF_PHOTOGALLERY",
				1 => "UF_ADDRESS",
				2 => "UF_RECEPTION",
				3 => "UF_DOSTUP_TIME",
				4 => "UF_PHONE",
				5 => "UF_MAP",
				6 => "UF_FLOORS",
				7 => "UF_PRICE_ON_MAP",
				8 => "",
			),
			"SHOW_PARENT_NAME" => "Y",
			"TOP_DEPTH" => "1",
			"VIEW_MODE" => "LINE",
			"COMPONENT_TEMPLATE" => "mainpage_price_storages"
		),
		false
	);?>
	<?
}
*/

?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>