<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Склад хранения вещей на Молодогвардейской в Москве - арендовать от АльфаСклад");
$APPLICATION->SetPageProperty("description", "Склад на Молодогвардейской. АльфаСклад предлагает индивидуальные складские помещения от 1 до 60 кв., в разных районах Москвы, с круглосуточным доступом, охранной сигнализацией и системой пожарной безопасности. Без перерыва и выходных!");
$APPLICATION->SetTitle("Склад на Звенигородском шоссе");
?>

<? $APPLICATION->IncludeComponent(
    "bitrix:catalog.section.list",
    "find_storage_detail",
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
            1 => "DESCRIPTION",
            2 => "PICTURE",
            3 => "",
        ),
        "SECTION_ID" => "",
        "SECTION_URL" => "",
        "SECTION_USER_FIELDS" => array(
            0 => "UF_ADDRESS",
            1 => "UF_RECEPTION",
            2 => "UF_DOSTUP_TIME",
            3 => "UF_PHONE",
            4 => "UF_DESCR_DETAIL",
            5 => "UF_METRO",
            6 => "UF_BUS_STATION",
            7 => "UF_FEATURES",
            8 => "UF_DOP_INFO",
            9 => "UF_ARTICLES",
            10 => "UF_MAP",
            11 => "UF_PHOTOGALLERY",
            12 => "",
        ),
        "SHOW_PARENT_NAME" => "Y",
        "TOP_DEPTH" => "1",
        "VIEW_MODE" => "LINE",
        "COMPONENT_TEMPLATE" => "find_storage_detail"
    ),
    false
); ?>

<? $APPLICATION->IncludeComponent(
    "bitrix:catalog.section.list",
    "rental_catalog_detail",
    array(
        "ADD_SECTIONS_CHAIN" => "N",
        "CACHE_FILTER" => "N",
        "CACHE_GROUPS" => "N",
        "CACHE_TIME" => "36000",
        "CACHE_TYPE" => "A",
        "COMPONENT_TEMPLATE" => "rental_catalog_detail",
        "COUNT_ELEMENTS" => "N",
        "FILTER_NAME" => "",
        "FLOOR_CODE" => $_REQUEST["FLOOR_CODE"],
        "IBLOCK_ID" => "40",
        "IBLOCK_TYPE" => "aspro_priority_catalog",
        "SECTION_CODE" => $_REQUEST["SKLAD_CODE"],
        "SECTION_FIELDS" => array(0 => "NAME", 1 => "PICTURE", 2 => "",),
        "SECTION_ID" => "",
        "SECTION_URL" => "",
        "SECTION_USER_FIELDS" => array(0 => "UF_ADDRESS", 1 => "UF_RECEPTION", 2 => "UF_DOSTUP_TIME", 3 => "UF_PHONE", 4 => "UF_FLOORS", 5 => "UF_MAP_FLOOR_1", 6 => "UF_MAP_FLOOR_2", 7 => "UF_MAP_FLOOR_3", 8 => "UF_MAP_FLOOR_4", 9 => "UF_MAP_FLOOR_5", 10 => "UF_SEO_H1_RENTAL", 11=>"UF_CONTAINERS"),
        "SHOW_PARENT_NAME" => "Y",
        "TOP_DEPTH" => "2",
        "VIEW_MODE" => "LINE",
        "SHOW_MAP" => ($_REQUEST["SHOW_MAP"] == "Y") ? "Y" : "N",
    )
); ?>


<? $APPLICATION->IncludeComponent(
    "bitrix:main.include",
    "",
    array(
        "HIDE_STEPS" => "Y",
        "AREA_FILE_SHOW" => "file",
        "AREA_FILE_SUFFIX" => "inc",
        "EDIT_TEMPLATE" => "",
        "PATH" => "/include/box-filter-container.php"
    )
); ?>


<? $APPLICATION->IncludeComponent(
    "bitrix:main.include",
    "",
    array(
        "AREA_FILE_SHOW" => "file",
        "AREA_FILE_SUFFIX" => "inc",
        "EDIT_TEMPLATE" => "",
        "PATH" => "/include/sort_box_list.php"
    ),
    false,
    array("HIDE_ICONS" => "Y")
); ?>


<? if (!$USER->isAdmin()) { ?>
    <style>
        .rental_catalog_list .buy_one_click {
            display: none;
        }
    </style>
<? } ?>


<?
global $arrFilterRentalCatalog;
if (strlen($_REQUEST["FLOOR_CODE"]) > 0) {
    $arrFilterRentalCatalog["PROPERTY_FLOOR_VALUE"] = preg_replace("/[^0-9]/", "", $_REQUEST["FLOOR_CODE"]) . " этаж";
}
$arrFilterRentalCatalog["PROPERTY_STATUS"] = BOX_STATUS_OPENED_ID;

global $BOX_LIST_propSize;
if (strlen($_REQUEST[$BOX_LIST_propSize . "_FROM"]) == 0) {
    $arrFilterRentalCatalog[">=PROPERTY_" . $BOX_LIST_propSize] = 1;
}
if ($_COOKIE["boxListOnlyCells"] == "Y") {
    $arrFilterRentalCatalog["PROPERTY_RENT_TYPE"] = 339;
}
?>
<? $APPLICATION->IncludeComponent(
    "bitrix:catalog.section",
    "rental_catalog_list",
    array(
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
        "BROWSER_TITLE" => "UF_SEO_TITLE_RENTAL",
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
        "META_DESCRIPTION" => "UF_SEO_DESCR_RENTAL",
        "META_KEYWORDS" => "UF_SEO_KEYWORDS_RENT",
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
        "SECTION_CODE" => $_REQUEST["SKLAD_CODE"],
        "SECTION_ID" => "",
        "SECTION_ID_VARIABLE" => "SECTION_ID",
        "SECTION_URL" => "",
        "SECTION_USER_FIELDS" => array(
            0 => "UF_ADDRESS",
            1 => "UF_RECEPTION",
            2 => "UF_DOSTUP_TIME",
            3 => "UF_PHONE",
            4 => "UF_TEXT_BLOCK",
        ),
        "SEF_MODE" => "N",
        "SET_BROWSER_TITLE" => "Y",
        "SET_LAST_MODIFIED" => "N",
        "SET_META_DESCRIPTION" => "Y",
        "SET_META_KEYWORDS" => "Y",
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
        "COMPONENT_TEMPLATE" => "rental_catalog_list",
        "CUSTOM_FILTER" => "",
        "PROP_SIZE" => $BOX_LIST_propSize
    ),
    false
); ?>

    <div class="find_storage_reviews">
        <p class="mt-30-xs style-h3">Отзывы о складском комплексе <? echo $APPLICATION->GetPageProperty("ADRESS");
            ?></p>


        <?
        $APPLICATION->IncludeComponent(
            "bitrix:catalog.section.list",
            "reviews_list",
            array(
                "ADD_SECTIONS_CHAIN" => "N",
                "CACHE_FILTER" => "N",
                "CACHE_GROUPS" => "N",
                "CACHE_TIME" => "36000000",
                "CACHE_TYPE" => "A",
                "COMPONENT_TEMPLATE" => "reviews_list",
                "COUNT_ELEMENTS" => "Y",
                "FILTER_NAME" => "",
                "IBLOCK_ID" => "30",
                "IBLOCK_TYPE" => "aspro_priority_form",
//                "SECTION_CODE" => $_REQUEST["SECTION_CODE"],
                "SECTION_FIELDS" => array(
                    0 => "NAME",
                    1 => "",
                ),
                "SECTION_ID" => "",
                "SECTION_URL" => "#CODE#/",
                "SECTION_USER_FIELDS" => array(
                    0 => "",
                    1 => "",
                ),
                "SHOW_PARENT_NAME" => "Y",
                "TOP_DEPTH" => "1",
                "VIEW_MODE" => "LINE",
                "NEWS_COUNT" => "3"
            ),
            false
        ); ?>
    </div>
    <p class="mt-30-xs style-h3">Вопросы и ответы</p>
<? $APPLICATION->IncludeComponent(
    "bitrix:news",
    "faq",
    array(
        "IBLOCK_TYPE" => "aspro_priority_content",
        "IBLOCK_ID" => "9",
        "NEWS_COUNT" => "50",
        "USE_SEARCH" => "N",
        "USE_RSS" => "N",
        "USE_RATING" => "N",
        "USE_CATEGORIES" => "N",
        "USE_FILTER" => "N",
        "SORT_BY1" => "SORT",
        "SORT_ORDER1" => "ASC",
        "SORT_BY2" => "ID",
        "SORT_ORDER2" => "DESC",
        "CHECK_DATES" => "Y",
        "SEF_MODE" => "Y",
        "SEF_FOLDER" => "/about/voprosi_i_otveti/",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "AJAX_OPTION_HISTORY" => "N",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "100000",
        "CACHE_FILTER" => "N",
        "CACHE_GROUPS" => "N",
        "SET_TITLE" => "N",
        "SET_STATUS_404" => "N",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "ADD_SECTIONS_CHAIN" => "N",
        "USE_PERMISSIONS" => "N",
        "PREVIEW_TRUNCATE_LEN" => "",
        "LIST_ACTIVE_DATE_FORMAT" => "d.m.Y",
        "LIST_FIELD_CODE" => array(
            0 => "PREVIEW_TEXT",
            1 => "PREVIEW_PICTURE",
            2 => "",
        ),
        "LIST_PROPERTY_CODE" => array(
            0 => "TITLE_BUTTON",
            1 => "LINK_BUTTON",
            2 => "",
        ),
        "HIDE_LINK_WHEN_NO_DETAIL" => "Y",
        "DISPLAY_NAME" => "Y",
        "META_KEYWORDS" => "-",
        "META_DESCRIPTION" => "-",
        "BROWSER_TITLE" => "-",
        "DETAIL_ACTIVE_DATE_FORMAT" => "d.m.Y",
        "DETAIL_FIELD_CODE" => array(
            0 => "",
            1 => "",
        ),
        "DETAIL_PROPERTY_CODE" => array(
            0 => "TITLE_BUTTON",
            1 => "LINK_BUTTON",
            2 => "",
        ),
        "DETAIL_DISPLAY_TOP_PAGER" => "N",
        "DETAIL_DISPLAY_BOTTOM_PAGER" => "Y",
        "DETAIL_PAGER_TITLE" => "Страница",
        "DETAIL_PAGER_TEMPLATE" => "",
        "DETAIL_PAGER_SHOW_ALL" => "Y",
        "PAGER_TEMPLATE" => ".default",
        "DISPLAY_TOP_PAGER" => "N",
        "DISPLAY_BOTTOM_PAGER" => "Y",
        "PAGER_TITLE" => "Новости",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL" => "N",
        "VIEW_TYPE" => "accordion",
        "SHOW_TABS" => "Y",
        "SHOW_SECTION_PREVIEW_DESCRIPTION" => "Y",
        "SHOW_SECTION_NAME" => "N",
        "USE_SHARE" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "USE_REVIEW" => "N",
        "ADD_ELEMENT_CHAIN" => "N",
        "SHOW_DETAIL_LINK" => "Y",
        "COUNT_IN_LINE" => "3",
        "IMAGE_POSITION" => "left",
        "COMPONENT_TEMPLATE" => "faq",
        "SECTION_ELEMENTS_TYPE_VIEW" => "list_elements_custom",
        "ELEMENT_TYPE_VIEW" => "element_1",
        "SET_LAST_MODIFIED" => "N",
        "STRICT_SECTION_CHECK" => "N",
        "SHOW_ASK_QUESTION_BLOCK" => "Y",
        "S_ASK_QUESTION" => "",
        "DETAIL_SET_CANONICAL_URL" => "N",
        "PAGER_BASE_LINK_ENABLE" => "N",
        "SHOW_404" => "N",
        "FILE_404" => "",
        "SEF_URL_TEMPLATES" => array(
            "news" => "",
            "section" => "",
            "detail" => "",
        )
    ),
    false
); ?>


<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>