<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?
global $APPLICATION;
global $DinamicData;

$DinamicData = array();
$DinamicData["REVIEWS"] = '';
//ob_start();
?>

<?
global $arrFilterReviewsMainpage;
$arrFilterReviewsMainpage["!PROPERTY_MAINPAGE"] = false;
?>
<? $APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "reviews_front_2",
    array(
        "COMPONENT_TEMPLATE" => "reviews_front_2",
        "IBLOCK_TYPE" => "aspro_priority_form",
        "IBLOCK_ID" => "30",
        "NEWS_COUNT" => "20",
        "SORT_BY1" => "ID",
        "SORT_ORDER1" => "DESC",
        "SORT_BY2" => "SORT",
        "SORT_ORDER2" => "DESC",
        "FILTER_NAME" => "arrFilterReviewsMainpage",
        "FIELD_CODE" => array(
            0 => "NAME",
            1 => "PREVIEW_PICTURE",
            2 => "",
        ),
        "PROPERTY_CODE" => array(
            0 => "NAME",
            1 => "POST",
            2 => "MESSAGE",
            3 => "RATING",
            4 => "",
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
        "PREVIEW_TRUNCATE_LEN" => "250",
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
        "SHOW_DETAIL_LINK" => "N",
        "TITLE" => "Отзывы наших клиентов",
        "SHOW_ALL_TITLE" => "",
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
); ?>

<?
//$DinamicData["REVIEWS"] .= @ob_get_contents();
//ob_get_clean();
?>
<? /*$arResult["CACHED_TPL"] = preg_replace_callback(
    "/#REVIEWS#/is" . BX_UTF_PCRE_MODIFIER,
    create_function('$matches', 'ob_start();
    echo $GLOBALS["DinamicData"]["REVIEWS"];   
    $retrunStr = @ob_get_contents();
    ob_get_clean();
    return $retrunStr;'),
    $arResult["CACHED_TPL"]);
?>


<? // вывод
echo $arResult["CACHED_TPL"];*/
?>