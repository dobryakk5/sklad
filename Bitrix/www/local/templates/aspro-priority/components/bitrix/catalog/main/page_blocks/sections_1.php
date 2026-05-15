<div class="row">
    <?if($arTheme["SIDE_MENU"]["VALUE"] == "RIGHT"):?>
    <div class="col-md-9 col-sm-12 col-xs-12 content-md">
        <?CPriority::get_banners_position('CONTENT_TOP');?>
        <?elseif($arTheme["SIDE_MENU"]["VALUE"] == "LEFT"):?>
        <div class="col-md-3 col-sm-3 hidden-xs hidden-sm left-menu-md">
            <?CPriority::ShowPageType('left_block')?>
        </div>
        <div class="col-md-9 col-sm-12 col-xs-12 content-md">
            <?CPriority::get_banners_position('CONTENT_TOP');?>
            <?endif;?>
            <div class="text_before_items col-xs-12">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    Array(
                        "AREA_FILE_SHOW" => "page",
                        "AREA_FILE_SUFFIX" => "inc",
                        "EDIT_TEMPLATE" => ""
                    )
                );?>
            </div>
            <?if(!empty($isSections)):?><div class="col-xs-12"><?endif?>
            <?$APPLICATION->IncludeComponent(
                "bitrix:catalog.section.list",
                "sections_list",
                Array(
                    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                    "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                    "COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
                    "TOP_DEPTH" => $arParams["SECTION_TOP_DEPTH"],
                    "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
                    "VIEW_MODE" => $arParams["SECTIONS_VIEW_MODE"],
                    "SHOW_PARENT_NAME" => $arParams["SECTIONS_SHOW_PARENT_NAME"],
                    "HIDE_SECTION_NAME" => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"]) ? $arParams["SECTIONS_HIDE_SECTION_NAME"] : "N"),
                    "ADD_SECTIONS_CHAIN" => (isset($arParams["ADD_SECTIONS_CHAIN"]) ? $arParams["ADD_SECTIONS_CHAIN"] : ''),
                    "SHOW_SECTIONS_LIST_PREVIEW" => $arParams["SHOW_SECTIONS_LIST_PREVIEW"],
                    "SECTIONS_LIST_PREVIEW_PROPERTY" => $arParams["SECTIONS_LIST_PREVIEW_PROPERTY"],
                    "SECTIONS_LIST_PREVIEW_DESCRIPTION" => $arParams["SECTIONS_LIST_PREVIEW_DESCRIPTION"],
                    "SHOW_SECTION_LIST_PICTURES" => $arParams["SHOW_SECTION_LIST_PICTURES"],
                    "DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
                    "FILTER_NAME" => "arSectionFilter",
                    "CACHE_FILTER" => "Y",
                    "CURRENT" => !empty($arResult['VARIABLES']['SECTION_ID']) ? $arResult['VARIABLES']['SECTION_ID'] : 0,
                ),
                $component
            );?>
            <?if(!empty($isSections)):?></div><?endif?>


            <?if($arTheme["SIDE_MENU"]["VALUE"] == "LEFT"):?>
            <?CPriority::get_banners_position('CONTENT_BOTTOM');?>
        </div><?// class=col-md-9 col-sm-9 col-xs-8 content-md?>
        <?elseif($arTheme["SIDE_MENU"]["VALUE"] == "RIGHT"):?>
        <?CPriority::get_banners_position('CONTENT_BOTTOM');?>
    </div><?// class=col-md-9 col-sm-9 col-xs-8 content-md?>
    <div class="col-md-3 col-sm-3 hidden-xs hidden-sm right-menu-md">
        <?CPriority::ShowPageType('left_block')?>
    </div>
<?endif;?>
</div>
