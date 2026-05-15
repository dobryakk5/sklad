<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?><!DOCTYPE html>
<?
if (CModule::IncludeModule("aspro.priority"))
    $arThemeValues = CPriority::GetFrontParametrsValues(SITE_ID);
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?= LANGUAGE_ID ?>" lang="<?= LANGUAGE_ID ?>"
      class="<?= ($_SESSION['SESS_INCLUDE_AREAS'] ? 'bx_editmode ' : '') ?><?= strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 7.0') ? 'ie ie7' : '' ?> <?= strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.0') ? 'ie ie8' : '' ?> <?= strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 7.0') ? 'ie ie9' : '' ?>">
<head>
    <script type="text/javascript">
        var IS_404=false;
    </script>
    <? global $APPLICATION;
    CJSCore::Init(array("jquery", "date"));
    ?>
    <? IncludeTemplateLangFile(__FILE__); ?>
    <title><? $APPLICATION->ShowTitle() ?></title>
    <? $APPLICATION->ShowMeta("viewport"); ?>
    <? $APPLICATION->ShowMeta("HandheldFriendly"); ?>
    <? $APPLICATION->ShowMeta("apple-mobile-web-app-capable", "yes"); ?>
    <? $APPLICATION->ShowMeta("apple-mobile-web-app-status-bar-style"); ?>
    <? $APPLICATION->ShowMeta("SKYPE_TOOLBAR"); ?>
    <meta name="cmsmagazine" content="42110ccba65ec255508b288e5489835d"/>
    <? if (CModule::IncludeModule("aspro.priority")) {
        CPriority::Start(SITE_ID);
    } ?>
    <meta name="yandex-verification" content="24592a9984827a32"/>
    <meta name="yandex-verification" content="fd515d0fff061eb2"/>
    <!-- showhead   -->
    <? $APPLICATION->ShowMeta("robots") ?>
    <? $APPLICATION->ShowCSS() ?>
    <? $APPLICATION->ShowHeadStrings() ?>
    <? $APPLICATION->ShowHeadScripts() ?>
    <? $APPLICATION->ShowMeta("title") ?>
    <? $APPLICATION->ShowMeta("description") ?>
    <? $APPLICATION->AddHeadScript("https://api-maps.yandex.ru/2.1/?lang=ru_RU"); ?>
    <? $APPLICATION->AddHeadScript("https://cdn.jsdelivr.net/npm/vanilla-lazyload@12.4.0/dist/lazyload.min.js"); ?>



    <!-- Global site tag (gtag.js) - Google Ads: 1015215436 -->
    <script async src="https://googletagmanager.com/gtag/js?id=AW-1015215436" data-skip-moving="true"></script>
    <script data-skip-moving="true">
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());
        gtag('config', 'AW-1015215436');
    </script>
    <!-- Google Tag Manager -->
    <script data-skip-moving="true">(function (w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start':
                    new Date().getTime(), event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-KPXHCP2');</script>
    <!-- End Google Tag Manager -->

    <? /*
 <!-- calltouch -->
<script async src="https://mod.calltouch.ru/init.js?id=6cvg7p2a" data-skip-moving="true"></script>
<!-- /calltouch -->
*/ ?>

    <!-- calltouch -->
    <script type="text/javascript">
        (function (w, d, n, c) {
            w.CalltouchDataObject = n;
            w[n] = function () {
                w[n]["callbacks"].push(arguments)
            };
            if (!w[n]["callbacks"]) {
                w[n]["callbacks"] = []
            }
            w[n]["loaded"] = false;
            if (typeof c !== "object") {
                c = [c]
            }
            w[n]["counters"] = c;
            for (var i = 0; i < c.length; i += 1) {
                p(c[i])
            }

            function p(cId) {
                var a = d.getElementsByTagName("script")[0], s = d.createElement("script"), i = function () {
                    a.parentNode.insertBefore(s, a)
                }, m = typeof Array.prototype.find === 'function', n = m ? "init-min.js" : "init.js";
                s.type = "text/javascript";
                s.async = true;
                s.src = "https://mod.calltouch.ru/" + n + "?id=" + cId;
                if (w.opera == "[object Opera]") {
                    d.addEventListener("DOMContentLoaded", i, false)
                } else {
                    i()
                }
            }
        })(window, document, "ct", "6cvg7p2a");
    </script>
    <!-- calltouch -->


    <? $APPLICATION->AddHeadString('<script>BX.message(' . CUtil::PhpToJSObject($MESS, false) . ')</script>', true); ?>
</head>

<body class="<?= CPriority::getConditionClass(); ?> mheader-v<?= $arThemeValues["HEADER_MOBILE"]; ?> footer-v<?= strtolower($arThemeValues['FOOTER_TYPE']); ?> fill_bg_<?= strtolower($arThemeValues['SHOW_BG_BLOCK']); ?> title-v<?= $arThemeValues["PAGE_TITLE"]; ?><?= ($arThemeValues['ORDER_VIEW'] == 'Y' && $arThemeValues['ORDER_BASKET_VIEW'] == 'HEADER' ? ' with_order' : '') ?><?= ($arThemeValues['CABINET'] == 'Y' ? ' with_cabinet' : '') ?><?= (intval($arThemeValues['HEADER_PHONES']) > 0 ? ' with_phones' : '') ?><?= ($arThemeValues['DECORATIVE_INDENTATION'] == 'Y' ? ' with_decorate' : '') ?> wheader_v<?= $arThemeValues['HEADER_TYPE'] ?><?= ($arThemeValues['ROUND_BUTTON'] == 'Y' ? ' round_button' : ''); ?><?= ($arThemeValues['PAGE_TITLE_POSITION'] == 'center' ? ' title_center' : ''); ?><?= (CSite::inDir(SITE_DIR . "index.php") ? ' in_index' : '') ?>">
<!-- Google Tag Manager (noscript) -->
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KPXHCP2"
            height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->
<div id="panel"><? $APPLICATION->ShowPanel(); ?></div>
<? if (!CModule::IncludeModule("aspro.priority")): ?>
    <? $APPLICATION->SetTitle(GetMessage("ERROR_INCLUDE_MODULE_PRIORITY_TITLE")); ?>
    <? $APPLICATION->IncludeFile(SITE_DIR . "include/error_include_module.php"); ?>
    <? die(); ?>
<? endif; ?>
<? CPriority::SetJSOptions(); ?>
<? global $arTheme; ?>
<? $arTheme = $APPLICATION->IncludeComponent("aspro:theme.priority", "", array(), false); ?>

<? include_once('defines.php'); ?>
<? CPriority::get_banners_position('TOP_HEADER'); ?>
<div class="cd-modal-bg"></div>
<?
$detect = new Mobile_Detect;
?>

<? CPriority::ShowPageType('mega_menu'); ?>
<div class="header_wrap  title-v<?= $arTheme["PAGE_TITLE"]["VALUE"]; ?><?= ($isIndex ? ' index' : '') ?>" id="part0">
    <? CPriority::ShowPageType('header', '', 'HEADER_TYPE'); ?>
</div>

<? CPriority::get_banners_position('TOP_UNDERHEADER'); ?>

<? if ($arTheme["TOP_MENU_FIXED"]["VALUE"] == 'Y' && 1 == 2): ?>
    <div id="headerfixed">
        <noindex>
            <? // CPriority::ShowPageType('header_fixed'); ?>
        </noindex>
    </div>
<? endif; ?>


<div class="body <?= ($isIndex ? 'index' : '') ?> hover_<?= $arTheme["HOVER_TYPE_IMG"]["VALUE"]; ?>">
    <div class="body_media"></div>

    <div role="main" class="main m-banner banner-<?= $arTheme["BANNER_WIDTH"]["VALUE"]; ?>">

        <div class="container">
            <div class="maxwidth-theme" >
                <div class="row">


<? CPriority::checkRestartBuffer(); ?>