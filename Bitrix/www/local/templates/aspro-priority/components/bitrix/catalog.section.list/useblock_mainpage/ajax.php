<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?
if($_REQUEST["ACTION"] == "UPDATE") {
	if(strlen($_REQUEST["SECTION_ID"]) > 0) {
		$APPLICATION->IncludeComponent(
			"bitrix:catalog.section.list", 
			"useblock_mainpage", 
			array(
				"AJAX_LOAD" => "Y",
				"ADD_SECTIONS_CHAIN" => "N",
				"CACHE_FILTER" => "N",
				"CACHE_GROUPS" => "N",
				"CACHE_TIME" => "36000000",
				"CACHE_TYPE" => "A",
				"COUNT_ELEMENTS" => "N",
				"FILTER_NAME" => "",
				"IBLOCK_ID" => "41",
				"IBLOCK_TYPE" => "aspro_priority_content",
				"SECTION_CODE" => "",
				"SECTION_FIELDS" => array(
					0 => "NAME",
					1 => "",
				),
				"SECTION_ID" => $_REQUEST["SECTION_ID"],
				"SECTION_URL" => "/#SECTION_CODE_PATH#/",
				"SECTION_USER_FIELDS" => array(
					0 => "UF_TEXT_MAINPAGE",
					1 => "UF_PICTURE_MAINPAGE",
					2 => "UF_ICON_MAINPAGE",
					3 => "UF_ANCHOR",
				),
				"SHOW_PARENT_NAME" => "Y",
				"TOP_DEPTH" => "2",
				"VIEW_MODE" => "LINE",
				"COMPONENT_TEMPLATE" => "useblock_mainpage"
			),
			false
		);
	}
}
?>