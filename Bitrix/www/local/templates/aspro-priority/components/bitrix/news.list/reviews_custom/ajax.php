<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>

<?
global $arrFilterReviewsList;
if(strlen($_REQUEST["SKLAD_ID"]) > 0) {
	$arrFilterReviewsList["PROPERTY_SKLAD"] = $_REQUEST["SKLAD_ID"];
}
?>

<?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"reviews_custom", 
	array(
		"AJAX_LOAD" => "Y",
		"COMPONENT_TEMPLATE" => "reviews_custom",
		"IBLOCK_TYPE" => "aspro_priority_form",
		"IBLOCK_ID" => "30",
		"NEWS_COUNT" => $_REQUEST["NEWS_COUNT"],
		"SORT_BY1" => !empty($_COOKIE["reviewsSort"]) ? $_COOKIE["reviewsSort"] : "active_from",
		"SORT_ORDER1" => !empty($_COOKIE["reviewsOrder"]) ? $_COOKIE["reviewsOrder"] : "desc",
		"SORT_BY2" => "SORT",
		"SORT_ORDER2" => "ASC",
		"FILTER_NAME" => "arrFilterReviewsList",
		"FIELD_CODE" => array(
			0 => "NAME",
			1 => "",
		),
		"PROPERTY_CODE" => array(
			0 => "NAME",
			1 => "POST",
			2 => "VIDEO",
			3 => "MESSAGE",
			4 => "RATING",
			5 => "",
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
		"ACTIVE_DATE_FORMAT" => "j F Y",
		"SET_TITLE" => "N",
		"SET_BROWSER_TITLE" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_LAST_MODIFIED" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"PARENT_SECTION" => $_REQUEST["SECTION_ID"],
		"PARENT_SECTION_CODE" => "",
		"INCLUDE_SUBSECTIONS" => "N",
		"STRICT_SECTION_CHECK" => "N",
		"PAGER_TEMPLATE" => ".default",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
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
	false,
	Array("HIDE_ICONS"=>"Y")
);?>