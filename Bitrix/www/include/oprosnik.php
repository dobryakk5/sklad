<? $APPLICATION->IncludeComponent("custom:oprosnik", "modified", array(
    "COMPONENT_TEMPLATE" => ".default",
    "IBLOCK_ID" => "45",    // IBLOCK_ID
    "QUESTION_ID" => "118",    // ID вопроса
    "OPROSNIK_DATA" => "",    // OPROSNIK_DATA
    "CACHE_TYPE" => "A",    // Тип кеширования
    "CACHE_TIME" => "36000000",    // Время кеширования (сек.)
),
    false,
    array(
        "HIDE_ICONS" => "N"
    )
); ?>

<div class="oprosnik_form_from" style="display:none;">
    <? $APPLICATION->IncludeComponent(
        "bitrix:form",
        "formManagerOrder_3",
        array(
            "COMPONENT_TEMPLATE" => "formManagerOrder_3",
            "START_PAGE" => "new",
            "SHOW_LIST_PAGE" => "N",
            "SHOW_EDIT_PAGE" => "N",
            "SHOW_VIEW_PAGE" => "N",
            "SUCCESS_URL" => "",
            "WEB_FORM_ID" => "13",
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
            "AJAX_OPTION_ADDITIONAL" => "form_13",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "3600",
            "CHAIN_ITEM_TEXT" => "",
            "CHAIN_ITEM_LINK" => "",
            "VARIABLE_ALIASES" => array(
                "action" => "action",
            ),
            "SHOW_LICENCE" => "Y",
        ),
        false,
        array("HIDE_ICONS" => "Y")
    ); ?>
    <script>
        $(function () {
            var strt = $('.oprosnik_form_from');
            var intobox = $('.oprosnik_mainpage .form_block');
            if (intobox.length && strt.length) {
                strt.appendTo(intobox);
            }
        });
    </script>
</div>