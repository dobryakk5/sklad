<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
global $arTheme;
$bPrintButton = ($arTheme['PRINT_BUTTON']['VALUE'] == 'Y' ? true : false);
?>

<? $APPLICATION->IncludeComponent(
    "bitrix:main.include",
    "",
    array(
        "AREA_FILE_SHOW" => "page",
        "AREA_FILE_SUFFIX" => "inc_before_footer",
        "EDIT_TEMPLATE" => ""
    )
); ?>

<footer id="footer" class="footer-v1">
    <div class="footer_top">
        <div class="maxwidth-theme">

            <div class="row">
                <div class="col-md-12 col-sm-12 contact-block">
                    <p style="color:white;">
                        Услуга оказывается компанией "АльфаСклад"
                    </p>

                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12 col-sm-12 contact-block">
                    <p style="color:white;">
                        После отправления с Вами свяжется менеджер!
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="footer_bottom">
        <div class="maxwidth-theme">
            <div class="row">
                <div class="link_block col-md-4 col-sm-4 pull-right">
                    <? if ($bPrintButton): ?>
                        <div class="pull-right">
                            <?= CPriority::ShowPrintLink(); ?>
                        </div>
                    <? endif ?>
                    <div class="pull-right">
                        <div class="confidentiality">
                            <? $APPLICATION->IncludeFile(SITE_DIR . "include/footer/confidentiality.php", array(), array(
                                    "MODE" => "php",
                                    "NAME" => "�onfidentiality",
                                    "TEMPLATE" => "include_area.php",
                                )
                            ); ?>
                        </div>
                    </div>
                </div>
                <div class="copy-block col-md-4 col-sm-4 pull-right" style="margin-right:0;">
                    <? $APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        array(
                            "AREA_FILE_SHOW" => "file",
                            "AREA_FILE_SUFFIX" => "inc",
                            "EDIT_TEMPLATE" => "",
                            "PATH" => "/include/footer_content_block.php"
                        ),
                        false
                    ); ?>
                </div>
                <div class="copy-block col-md-4 col-sm-4 pull-left">
                    <div class="copy font_xs pull-left">
                        <? $APPLICATION->IncludeFile(SITE_DIR . "include/footer/copy.php", array(), array(
                                "MODE" => "php",
                                "NAME" => "Copyright",
                                "TEMPLATE" => "include_area.php",
                            )
                        ); ?>
                    </div>
                    <div id="bx-composite-banner" class="pull-left"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer_middle">
        <div class="maxwidth-theme">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="social-block">
                        <div class="social-icons">
                            <!-- noindex -->
                            <ul style="margin-top: 20px">

                            </ul>
                            <!-- /noindex -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
