<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Адреса складов хранения вещей в Москве от АльфаСклад. Охрана, видеонаблюдение и освещение в боксе. Круглосуточный доступ. Договор аренды.");
$APPLICATION->SetPageProperty("title", "Адреса складов хранения вещей компании Альфасклад");
$APPLICATION->SetTitle("Адреса складов хранения вещей");?>

<?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"EDIT_TEMPLATE" => "",
		"PATH" => "/include/topblock_find_storage.php"
	),
	false,
	Array("HIDE_ICONS" => "Y")
);?>
<?$APPLICATION->IncludeComponent(
	"bitrix:menu",
	"fixed_custom_menu",
	array(
		"ALLOW_MULTI_SELECT" => "N",
		"CHILD_MENU_TYPE" => "contentMenu",
		"DELAY" => "N",
		"MAX_LEVEL" => "1",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MENU_CACHE_TIME" => "3600",
		"MENU_CACHE_TYPE" => "A",
		"MENU_CACHE_USE_GROUPS" => "N",
		"ROOT_MENU_TYPE" => "contentMenu",
		"USE_EXT" => "Y",
		"COMPONENT_TEMPLATE" => "fixed_custom_menu"
	),
	false
);?>



<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section.list",
	"find_storage_list",
	array(
		"ADD_SECTIONS_CHAIN" => "N",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "N",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"COUNT_ELEMENTS" => "N",
		"FILTER_NAME" => "arRegionLinkUF",
		"IBLOCK_ID" => "40",
		"IBLOCK_TYPE" => "aspro_priority_catalog",
		"SECTION_CODE" => $_REQUEST["SECTION_CODE"],
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
			6 => "UF_PRICE_ON_MAP",
			7 => "",
		),
		"SHOW_PARENT_NAME" => "Y",
		"TOP_DEPTH" => "1",
		"VIEW_MODE" => "LINE",
		"COMPONENT_TEMPLATE" => "find_storage_list"
	),
	false
);?>

<br><br>
<?
global $arrFilterGalleryMainpage;
$arrFilterGalleryMainpage["!PROPERTY_MAINPAGE"] = false;
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"gallery_mainpage",
	array(
		"COMPONENT_TEMPLATE" => "gallery_mainpage",
		"IBLOCK_TYPE" => "aspro_priority_content",
		"IBLOCK_ID" => "43",
		"NEWS_COUNT" => "20",
		"SORT_BY1" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_BY2" => "ID",
		"SORT_ORDER2" => "DESC",
		"FILTER_NAME" => "arrFilterGalleryMainpage",
		"FIELD_CODE" => array(
			0 => "NAME",
			1 => "DETAIL_PICTURE",
			2 => "",
		),
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "N",
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
		"PARENT_SECTION_CODE" => "",
		"INCLUDE_SUBSECTIONS" => "N",
		"STRICT_SECTION_CHECK" => "N",
		"DISPLAY_DATE" => "N",
		"DISPLAY_NAME" => "N",
		"DISPLAY_PICTURE" => "N",
		"DISPLAY_PREVIEW_TEXT" => "N",
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
		"MESSAGE_404" => ""
	),
	false
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>