// /home/bitrix/ext_www/spb.alfasklad.ru/local/templates/aspro-priority/components/bitrix/catalog.section.list/useblock_detail_businass/component_epilog.php 

<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?
global $APPLICATION;

if (isset($arResult["SECTION"]["NAME"])) {
    $APPLICATION->SetTitle($arResult["SECTION"]["NAME"]);
    $APPLICATION->AddChainItem($arResult["SECTION"]["NAME"], "");
}
if (isset($arResult["SECTION"]["IPROPERTY_VALUES"]["SECTION_META_TITLE"])) {
    $APPLICATION->SetPageProperty("title", $arResult["SECTION"]["IPROPERTY_VALUES"]["SECTION_META_TITLE"]);
}
if (isset($arResult["SECTION"]["IPROPERTY_VALUES"]["SECTION_META_KEYWORDS"])) {
    $APPLICATION->SetPageProperty("keywords", $arResult["SECTION"]["IPROPERTY_VALUES"]["SECTION_META_KEYWORDS"]);
}
if (isset($arResult["SECTION"]["IPROPERTY_VALUES"]["SECTION_META_DESCRIPTION"])) {
    $APPLICATION->SetPageProperty("description", $arResult["SECTION"]["IPROPERTY_VALUES"]["SECTION_META_DESCRIPTION"]);
}

if (!$this->__template) {
    $this->InitComponentTemplate();
}

if (in_array($arParams["UF_LINK_REGION"], $arResult["SECTION"]["UF_LINK_REGION"]) === false) {
    if (Bitrix\Main\Loader::includeModule("iblock")) {
        Bitrix\Iblock\Component\Tools::process404(
            'Страница не найдена'
            , true
            , true
            , true
            , '/404.php'
        );
    }
}


if ($arResult["SECTION"]["ID"] == 0) {
    if (Bitrix\Main\Loader::includeModule("iblock")) {
        Bitrix\Iblock\Component\Tools::process404(
            'Страница не найдена'
            , true
            , true
            , true
            , '/404.php'
        );
    }
}


if ($arResult["SECTION"]["IBLOCK_SECTION_ID"] != "70") {
    Bitrix\Iblock\Component\Tools::process404(
        'Страница не найдена'
        , true
        , true
        , true
        , '/404.php'
    );
}


// shot top banners start?>
<?php if ($arResult['TOP_BANNER']['UF_TOPIMAGE_1'] && $arResult['TOP_BANNER']['UF_BANNER_TEXT']) { ?>

    <? $this->__template->SetViewTarget("section_bnr_content"); ?>
    <section class="bannerbread">

        <div class="banners-content" style="margin-bottom: unset">

            <div class="maxwidth-banner"
                 style="background: url(<?= CFile::GetPath($arResult['TOP_BANNER']['UF_TOPIMAGE_1']); ?>) 50% 50% no-repeat;">

                <div class="row">

                    <div class="maxwidth-theme">
                        <div class="col-md-6 text animated delay06 duration08 item_block fadeInUp<?= ($bLightBanner ? ' light' : '') ?>">

                            <h1><?= $arResult["SECTION"]["NAME"] ?></h1>
                            <div class="intro-text">
                                <?= $arResult['TOP_BANNER']['UF_BANNER_TEXT']; ?>
                            </div>
                            <div class="buttons">


                                <? if ($arResult['TOP_BANNER']['UF_ORDER_BTN']): ?>
                                    <span>
                                                                                <span class="btn btn-default animate-load" title="Заказать доставку"
                                              data-event="jqm" data-param-webform-id="23" data-param-type="webform"
                                              data-name="webform">Заказать доставку</span></span>

                                <? endif; ?>

                                <? if ($arResult['TOP_BANNER']['UF_QUESTION_BTN']): ?>
                                    <span>
                                                                                        <span class="btn <?= ($arResult['PROPERTIES']['BUTTON2CLASS']['VALUE'] ? $arResult['PROPERTIES']['BUTTON2CLASS']['VALUE_XML_ID'] : 'btn-default white'); ?> btn-lg animate-load"
                                                  data-event="jqm"
                                                  data-param-id="20"
                                                  data-autoload-need_product="<?= $arResult["SECTION"]['NAME'] ?>"
                                                  data-name="question"><span>
                                                Задать вопрос
                                                </span></span>
                                                                                </span>
                                <? endif; ?>

                                <? if ($arResult['TOP_BANNER']['UF_SHOWPRICES_BTN']): ?>
                                    <span><a class="btn btn-default white btn-lg" style="margin-bottom:20px;"
                                             href="/storage/arenda-teplogo-sklada/"><span>Посмотреть цены</span></a></span>
                                <? endif; ?>

                            </div>
                        </div>

                        <? if ($arResult['TOP_BANNER']['UF_TOPIMAGE_2']) { ?>
                            <div class="col-md-6 hidden-xs hidden-sm img animated delay09 duration08 item_block fadeInRight">
                                <div class="inner">
                                    <img src="<?= CFile::GetPath($arResult['TOP_BANNER']['UF_TOPIMAGE_2']); ?>"
                                         alt="<?= $arResult["SECTION"]["NAME"]; ?>"
                                         title="<?= $arResult["SECTION"]["NAME"]; ?>"
                                         draggable="false">
                                </div>
                            </div>
                        <? } ?>
                    </div>
                </div>
            </div>
        </div>
        <? $APPLICATION->IncludeComponent(
            "bitrix:breadcrumb",
            "corp_new",
            array(
                "COMPONENT_TEMPLATE" => "corp",
                "PATH" => "",
                "SITE_ID" => "s1",
                "START_FROM" => "0"
            )
        ); ?>

    </section>
    <? $this->__template->EndViewTarget(); ?>
    <?
} else { ?>
    <? $this->__template->SetViewTarget("section_bnr_content"); ?>


    <section class="page-top maxwidth-theme">
        <div class="row">
            <div class="col-md-12">
                <? $APPLICATION->IncludeComponent(
                    "bitrix:breadcrumb",
                    "corp",
                    array(
                        "COMPONENT_TEMPLATE" => "corp",
                        "PATH" => "",
                        "SITE_ID" => "s1",
                        "START_FROM" => "0"
                    )
                ); ?> <br>
                <div class="page-top-main">
                    <h1 id="pagetitle"><? $APPLICATION->ShowTitle(false) ?></h1>
                </div>
            </div>
        </div>
    </section>

    <? $this->__template->EndViewTarget(); ?>

<? } ?>

<? // shot top banners end?>

<?
global $arrElementsFilter;
$arrElementsFilter["SECTION_ID"] = $arResult["SECTION"]["ID"];
?>
<? $arResult["CACHED_TPL"] = preg_replace_callback(
    "/#ELEMENTS#/is" . BX_UTF_PCRE_MODIFIER,
    create_function('$matches', 'ob_start();
    $GLOBALS["APPLICATION"]->IncludeComponent(
        "bitrix:news.list", 
        "useblock_detail", 
        array(
            "COMPONENT_TEMPLATE" => "useblock_detail",
            "IBLOCK_TYPE" => "aspro_priority_content",
            "IBLOCK_ID" => "41",
            "NEWS_COUNT" => "30",
            "SORT_BY1" => "SORT",
            "SORT_ORDER1" => "ASC",
            "SORT_BY2" => "ID",
            "SORT_ORDER2" => "ASC",
            "FILTER_NAME" => "arrElementsFilter",
            "FIELD_CODE" => array(
                0 => "NAME",
                1 => "PREVIEW_PICTURE",
                2 => "PREVIEW_TEXT",
            ),
            "PROPERTY_CODE" => array(
                0 => "HIDE_TITLE",
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
            "CACHE_TIME" => "60",
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
            "PARENT_SECTION" => "",
            "PARENT_SECTION_CODE" => "b-mainpage-news",
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
        false,
                Array("HIDE_ICONS"=>"Y")
    );
    $retrunStr = @ob_get_contents();
    ob_get_clean();
    return $retrunStr;'),
    $arResult["CACHED_TPL"]);
?>


<? // вывод
echo $arResult["CACHED_TPL"];
?>


<div class="maxwidth-theme">


    <? $APPLICATION->IncludeComponent(
        "bitrix:form",
        "formManagerOrder_4",
        array(
            "COMPONENT_TEMPLATE" => "formManagerOrder_4",
            "START_PAGE" => "new",
            "TITLE" => $arResult["TOP_BANNER"]["UF_BLOCK_TITLE_1"],
            "SHOW_LIST_PAGE" => "N",
            "SHOW_EDIT_PAGE" => "N",
            "SHOW_VIEW_PAGE" => "N",
            "SUCCESS_URL" => "",
            "WEB_FORM_ID" => "14",
            "RESULT_ID" => $_REQUEST["RESULT_ID"],
            "SHOW_ANSWER_VALUE" => "N",
            "SHOW_ADDITIONAL" => "N",
            "SHOW_STATUS" => "N",
            "EDIT_ADDITIONAL" => "N",
            "EDIT_STATUS" => "N",
            "NOT_SHOW_FILTER" => array(
                0 => "",
                1 => "",
            ),
            "NOT_SHOW_TABLE" => array(
                0 => "",
                1 => "",
            ),
            "IGNORE_CUSTOM_TEMPLATE" => "N",
            "USE_EXTENDED_ERRORS" => "N",
            "SEF_MODE" => "N",
            "AJAX_MODE" => "Y",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "AJAX_OPTION_HISTORY" => "N",
            "AJAX_OPTION_ADDITIONAL" => "form_14",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "3600",
            "CHAIN_ITEM_TEXT" => "",
            "CHAIN_ITEM_LINK" => "",
            "SHOW_LICENCE" => "Y",
            "VARIABLE_ALIASES" => array(
                "action" => "action",
            )
        ),
        false,
        array("HIDE_ICONS" => "Y")
    ); ?>

</div>


<div class="maxwidth-theme" style="margin-top: 30px">


    <? $APPLICATION->IncludeComponent(
        "bitrix:main.include",
        "",
        array(
            "AREA_FILE_SHOW" => "file",
            "AREA_FILE_SUFFIX" => "inc",
            "EDIT_TEMPLATE" => "",
            "PATH" => "/include/rental_catalog-nav-block.php"
        ),
        false,
        array("HIDE_ICONS" => "Y")
    ); ?>


    <? $APPLICATION->IncludeComponent("bitrix:catalog.section.list", "select_storage_map", array(
        "ADD_SECTIONS_CHAIN" => "N",    // Включать раздел в цепочку навигации
        "CACHE_FILTER" => "N",    // Кешировать при установленном фильтре
        "CACHE_GROUPS" => "N",    // Учитывать права доступа
        "CACHE_TIME" => "36000000",    // Время кеширования (сек.)
        "CACHE_TYPE" => "A",    // Тип кеширования
        "COUNT_ELEMENTS" => "N",    // Показывать количество элементов в разделе
        "FILTER_NAME" => "",    // Имя массива со значениями фильтра разделов
        "IBLOCK_ID" => "40",    // Инфоблок
        "IBLOCK_TYPE" => "aspro_priority_catalog",    // Тип инфоблока
        "SECTION_CODE" => "",    // Код раздела
        "SECTION_FIELDS" => array(    // Поля разделов
            0 => "NAME",
            1 => "DESCRIPTION",
            2 => "",
        ),
        "SECTION_ID" => "",    // ID раздела
        "SECTION_URL" => "#CODE#/",    // URL, ведущий на страницу с содержимым раздела
        "SECTION_USER_FIELDS" => array(    // Свойства разделов
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
        "TOP_DEPTH" => "1",    // Максимальная отображаемая глубина разделов
        "VIEW_MODE" => "LINE",
        "COMPONENT_TEMPLATE" => "find_storage_list"
    ),
        false
    ); ?>

    <? $APPLICATION->IncludeComponent(
        "bitrix:catalog.section.list",
        "storage_select_withoutmap",
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
                6 => "",
            ),
            "SHOW_PARENT_NAME" => "Y",
            "TOP_DEPTH" => "1",
            "VIEW_MODE" => "LINE",
            "COMPONENT_TEMPLATE" => "storage_select_withoutmap"
        ),
        false
    ); ?>


</div>


<div class="maxwidth-theme" style="margin-top: 30px">

    <? $APPLICATION->IncludeComponent(
        "bitrix:catalog.section.list",
        "mainpage_price_storages",
        array(
            "TITLE" => $arResult["TOP_BANNER"]["UF_BLOCK_TITLE_2"],
            "SQUARE_FROM" => "1",
            "SQUARE_TO" => "3",
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
            "HIDE_MAP" => "Y",
            "COMPONENT_TEMPLATE" => "mainpage_price_storages"
        ),
        false
    ); ?>
</div>