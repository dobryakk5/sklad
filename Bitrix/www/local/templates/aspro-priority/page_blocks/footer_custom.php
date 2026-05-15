<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
global $arTheme;
$bPrintButton = (is_array($arTheme['PRINT_BUTTON']) && $arTheme['PRINT_BUTTON']['VALUE'] == 'Y' ? true : false);
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

<? $APPLICATION->IncludeComponent(
    "alfasklad:crosslinks",
    "",
    array(
        "AREA_FILE_SHOW" => "page",
        "AREA_FILE_SUFFIX" => "",
        "EDIT_TEMPLATE" => ""
    )
); ?>

<footer id="footer" class="footer-v1">
    <div class="footer_top">
        <div class="maxwidth-theme">
            <div class="visible-xs text-center">
                <a href="javascript:void(0);" class="btn btn-transparent showMobileFooterMenu">Навигация по сайту</a><br><br>
            </div>
            <div class="row">
                <div class="col-md-2 col-sm-3 mobileFooterMenu">
                    <div class="first_bottom_menu">
                        <? $APPLICATION->IncludeComponent(
                            "bitrix:menu",
                            "bottom",
                            array(
                                "ROOT_MENU_TYPE" => "bottom3",
                                "MENU_CACHE_TYPE" => "A",
                                "MENU_CACHE_TIME" => "3600000",
                                "MENU_CACHE_USE_GROUPS" => "N",
                                "MENU_CACHE_GET_VARS" => array(),
                                "MAX_LEVEL" => "2",
                                "CHILD_MENU_TYPE" => "bottom",
                                "USE_EXT" => "Y",
                                "DELAY" => "N",
                                "ALLOW_MULTI_SELECT" => "Y",
                                "COMPONENT_TEMPLATE" => "bottom"
                            ),
                            false
                        ); ?>

                        <div style="margin-top:30px;">
                            <? $APPLICATION->IncludeComponent(
                                "bitrix:menu",
                                "bottom",
                                array(
                                    "ROOT_MENU_TYPE" => "bottom2",
                                    "MENU_CACHE_TYPE" => "A",
                                    "MENU_CACHE_TIME" => "3600000",
                                    "MENU_CACHE_USE_GROUPS" => "N",
                                    "MENU_CACHE_GET_VARS" => array(),
                                    "MAX_LEVEL" => "2",
                                    "CHILD_MENU_TYPE" => "bottom",
                                    "USE_EXT" => "Y",
                                    "DELAY" => "N",
                                    "ALLOW_MULTI_SELECT" => "Y",
                                    "COMPONENT_TEMPLATE" => "bottom"
                                ),
                                false
                            ); ?>
                        </div>

                        <div style="margin-top:30px;">
                            <? $APPLICATION->IncludeComponent(
                                "bitrix:menu",
                                "bottom",
                                array(
                                    "ROOT_MENU_TYPE" => "bottom10",
                                    "MENU_CACHE_TYPE" => "A",
                                    "MENU_CACHE_TIME" => "3600000",
                                    "MENU_CACHE_USE_GROUPS" => "N",
                                    "MENU_CACHE_GET_VARS" => array(),
                                    "MAX_LEVEL" => "2",
                                    "CHILD_MENU_TYPE" => "bottom",
                                    "USE_EXT" => "Y",
                                    "DELAY" => "N",
                                    "ALLOW_MULTI_SELECT" => "Y",
                                    "COMPONENT_TEMPLATE" => "bottom"
                                ),
                                false
                            ); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-3 mobileFooterMenu">
                    <div class="second_bottom_menu">
                        <? $APPLICATION->IncludeComponent(
                            "bitrix:menu",
                            "bottom",
                            array(
                                "ROOT_MENU_TYPE" => "bottom1",
                                "MENU_CACHE_TYPE" => "A",
                                "MENU_CACHE_TIME" => "3600000",
                                "MENU_CACHE_USE_GROUPS" => "N",
                                "MENU_CACHE_GET_VARS" => array(),
                                "MAX_LEVEL" => "2",
                                "CHILD_MENU_TYPE" => "bottom",
                                "USE_EXT" => "N",
                                "DELAY" => "N",
                                "ALLOW_MULTI_SELECT" => "Y",
                                "COMPONENT_TEMPLATE" => "bottom"
                            ),
                            false
                        ); ?>
                    </div>
                </div>
                <div class="col-md-2 col-sm-3 mobileFooterMenu">
                    <div class="third_bottom_menu">
                        <? $APPLICATION->IncludeComponent(
                            "bitrix:menu",
                            "bottom2",
                            array(
                                "ROOT_MENU_TYPE" => "bottom4",
                                "MENU_CACHE_TYPE" => "A",
                                "MENU_CACHE_TIME" => "3600000",
                                "MENU_CACHE_USE_GROUPS" => "N",
                                "MENU_CACHE_GET_VARS" => array(),
                                "MAX_LEVEL" => "1",
                                "CHILD_MENU_TYPE" => "",
                                "USE_EXT" => "N",
                                "DELAY" => "N",
                                "ALLOW_MULTI_SELECT" => "Y"
                            ),
                            false
                        ); ?>
                    </div>
                </div>
                <div class="col-md-2 col-sm-3 mobileFooterMenu">
                    <div class="fourth_bottom_menu">
                        <? $APPLICATION->IncludeComponent(
                            "bitrix:menu",
                            "bottom2",
                            array(
                                "ROOT_MENU_TYPE" => "bottom5",
                                "MENU_CACHE_TYPE" => "A",
                                "MENU_CACHE_TIME" => "3600000",
                                "MENU_CACHE_USE_GROUPS" => "N",
                                "MENU_CACHE_GET_VARS" => array(),
                                "MAX_LEVEL" => "1",
                                "CHILD_MENU_TYPE" => "",
                                "USE_EXT" => "N",
                                "DELAY" => "N",
                                "ALLOW_MULTI_SELECT" => "Y"
                            ),
                            false
                        ); ?>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12 contact-block">
                    <div class="info">
                        <div class="row">
                            <? if (\Bitrix\Main\Loader::includeModule('subscribe')): ?>
                                <div class="col-md-12 col-sm-12">
                                    <div class="subscribe_button">
                                        <span class="btn" data-event="jqm" data-param-id="subscribe" data-param-type="subscribe" data-name="subscribe"><?= GetMessage('SUBSCRIBE_TITLE') ?><?= CPriority::showIconSvg(SITE_TEMPLATE_PATH . '/images/include_svg/subscribe.svg') ?></span>
                                    </div>
                                </div>
                            <? endif; ?>
                            <? if (false): ?>
                                <div class="col-md-12 col-sm-12">
                                    <div class="mobile-apps">
                                        <a href="https://play.google.com/store/apps/details?id=ru.app.alfasklad" target="_blank" class="mobile-apps__item">
                                            <img width="135" height="40" src="/images/google-play-badge.svg" alt="Приложение Альфасклад в Goolge Play">
                                        </a>
                                        <a href="https://apps.apple.com/ru/app/альфасклад/id1508117125" target="_blank" class="mobile-apps__item">
                                            <img width="135" height="40" src="/images/app-store-badge.svg" alt="Приложение Альфасклад в App Store">
                                        </a>
                                    </div>
                                </div>
                            <? endif; ?>
                            <div class="col-md-12 col-sm-12">
                                <div class="phone blocks">
                                    <div class="inline-block">
                                        <? CPriority::ShowHeaderPhones('white sm'); ?>
                                    </div>
                                    <? if (is_array($arTheme["CALLBACK_BUTTON_FOOTER"]) && $arTheme["CALLBACK_BUTTON_FOOTER"]["VALUE"] == "Y"): ?>
                                        <div class="inline-block callback_wrap">
                                            <span class="callback-block animate-load twosmallfont colored" data-event="jqm" data-param-id="<?= CPriority::getFormID("aspro_priority_callback"); ?>" data-name="callback"><?= GetMessage("S_CALLBACK") ?></span>
                                        </div>
                                    <? endif; ?>
                                </div>
                                <?php if (false): ?>
                                    <div class="whatsapp-block">
                                        <?php if (false): ?>
                                            <span class="whatsapp-info"><a href="https://wa.me/79855800640" target="_blank">написать в WhatsApp</a></span>
                                            <span class="whatsapp-icon"><a href="https://wa.me/79855800640" target="_blank"><img src="/upload/webpult/whatsapp-header.svg"></a></span>
                                        <?php endif; ?>
                                        <span class="whatsapp-info"><a href="https://widget.yourgood.app/#whatsapp" target="_blank">написать в WhatsApp</a></span>
                                        <span class="whatsapp-icon"><a href="https://widget.yourgood.app/#whatsapp" target="_blank"><img src="/upload/webpult/whatsapp-header.svg"></a></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <?= CPriority::showEmail('email blocks') ?>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <?= CPriority::showAddress('address blocks') ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row footer-vidgets-block">
                <div class="col-md-5 col-sm-12">
                    <div class="wrapper-reviews-vidgets">
                        <a href="https://yandex.ru/maps/org/alfasklad/1374078280/reviews/?ll=37.865463%2C55.779039&z=16" target="_blank" class="link-reviews">
                            <img src="/images/yandex-review-logo.png" alt="yandex review">
                        </a>
                        <div class="wrapper-item-reviews">
                            <a href="https://2gis.ru/moscow/firm/4504128908435654/tab/reviews?m=37.553983%2C55.853457%2F16.65" target="_blank" class="link-reviews">
                                <img src="/images/google-review-logo.png" alt="google review">
                            </a>
                            <a href="#" onclick="javascript: void(0);" target="_blank" class="link-reviews">
                                <img src="/images/otzovik-logo.png" alt="otzovik review">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="block-payments">
                        <p class="title">Принимаем к оплате</p>
                        <div class="wrapper-payments">
                            <a href="#" onclick="javascript: void(0);" target="_blank" class="link-payments"><img src="/images/sber-logo-v3.svg" alt="sber logo"></a>
                            <a href="#" onclick="javascript: void(0);" target="_blank" class="link-payments"><img src="/images/iomoney.svg" alt="iomoney logo"></a>
                            <a href="#" onclick="javascript: void(0);" target="_blank" class="link-payments"><img src="/images/sbp.svg" alt="spb logo"></a>
                            <a href="#" onclick="javascript: void(0);" target="_blank" class="link-payments"><img src="/images/mastercard.svg" alt="mastercard logo"></a>
                            <a href="#" onclick="javascript: void(0);" target="_blank" class="link-payments"><img src="/images/visa.svg" alt="visa logo"></a>
                            <a href="#" onclick="javascript: void(0);" target="_blank" class="link-payments"><img src="/images/sim.svg" alt="mir logo"></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    <div class="mobile-apps">
                        <a href="https://apps.apple.com/ru/app/альфасклад/id1508117125" target="_blank" class="mobile-apps__item">
                            <img width="135" height="40" src="/images/app-store-badge.svg" alt="Приложение Альфасклад в App Store">
                        </a>
                    </div>
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
                            <? $APPLICATION->IncludeFile(
                                SITE_DIR . "include/footer/confidentiality.php",
                                array(),
                                array(
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
                        <? $APPLICATION->IncludeFile(
                            SITE_DIR . "include/footer/copy.php",
                            array(),
                            array(
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
                        <? $APPLICATION->IncludeComponent(
                            "aspro:social.info.priority",
                            ".default",
                            array(
                                "CACHE_TYPE" => "A",
                                "CACHE_TIME" => "3600000",
                                "CACHE_GROUPS" => "N",
                                "COMPONENT_TEMPLATE" => ".default",
                                "SOCIAL_TITLE" => "Мы в соцсетях"
                            ),
                            false
                        ); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>