<?
$APPLICATION->IncludeComponent(
    "aspro:form.priority", "request",
    Array(
        "IBLOCK_TYPE" => "aspro_priority_form",
        "IBLOCK_ID" => 66,
        "AJAX_MODE" => "Y",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "N",
        "AJAX_OPTION_HISTORY" => "N",
        "SHOW_LICENCE" => $arTheme["SHOW_LICENCE"],
        "LICENCE_TEXT" => $arTheme["LICENCE_TEXT"],
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "3605",
        "AJAX_OPTION_ADDITIONAL" => "",
        //"IS_PLACEHOLDER" => "Y",
        "SUCCESS_MESSAGE" => $successMessage,
        "SEND_BUTTON_NAME" => "Отправить",
        "SEND_BUTTON_CLASS" => "btn btn-default",
        "DISPLAY_CLOSE_BUTTON" => "N",
        "POPUP" => "Y",
        "CLOSE_BUTTON_NAME" => "Закрыть",
        "CLOSE_BUTTON_CLASS" => "jqmClose btn btn-default bottom-close"
    )
);?>