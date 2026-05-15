<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");?><!DOCTYPE html>
<?
if (CModule::IncludeModule("aspro.priority"))
    $arThemeValues = CPriority::GetFrontParametrsValues(SITE_ID);
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?= LANGUAGE_ID ?>" lang="<?= LANGUAGE_ID ?>"
    class="<?= ($_SESSION['SESS_INCLUDE_AREAS'] ? 'bx_editmode ' : '') ?><?= strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 7.0') ? 'ie ie7' : '' ?> <?= strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.0') ? 'ie ie8' : '' ?> <?= strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 7.0') ? 'ie ie9' : '' ?>">

<head>

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
    <meta name="cmsmagazine" content="42110ccba65ec255508b288e5489835d" />
    <? if (CModule::IncludeModule("aspro.priority")) {
        CPriority::Start(SITE_ID);
    } ?>
    <meta name="yandex-verification" content="24592a9984827a32" />
    <meta name="yandex-verification" content="fd515d0fff061eb2" />
    <!-- showhead   -->
    <? $APPLICATION->ShowMeta("robots") ?>
    <? $APPLICATION->ShowCSS() ?>
    <? $APPLICATION->ShowHeadStrings() ?>
    <? $APPLICATION->ShowHeadScripts() ?>
    <? $APPLICATION->ShowMeta("title") ?>
    <? $APPLICATION->ShowMeta("description") ?>
    <? $APPLICATION->AddHeadScript("https://api-maps.yandex.ru/2.1/?lang=ru_RU"); ?>
    <? $APPLICATION->AddHeadScript("https://cdn.jsdelivr.net/npm/vanilla-lazyload@12.4.0/dist/lazyload.min.js"); ?>


<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-VDSQLEDEP8"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-VDSQLEDEP8');
</script>



    <!-- Roistat Counter Start -->
    <? $APPLICATION->AddHeadString('<script data-skip-moving="true">
    (function(w, d, s, h, id) {
        w.roistatProjectId = id; w.roistatHost = h;
        var p = d.location.protocol == "https:" ? "https://" : "http://";
        var u = /^.*roistat_visit=[^;]+(.*)?$/.test(d.cookie) ? "/dist/module.js" : "/api/site/1.0/"+id+"/init?referrer="+encodeURIComponent(d.location.href);
        var js = d.createElement(s); js.charset="UTF-8"; js.async = 1; js.src = p+h+u; var js2 = d.getElementsByTagName(s)[0]; js2.parentNode.insertBefore(js, js2);
    })(window, document, \'script\', \'cloud.roistat.com\', \'fc14835b90acd0f0f3f314dae650122c\');
    </script>'); ?>
    <!-- Roistat Counter End -->

    <? /*
    <!-- calltouch -->
    <script async src="https://mod.calltouch.ru/init.js?id=6cvg7p2a" data-skip-moving="true"></script>
    <!-- /calltouch -->
    */ ?>

    <!-- calltouch -->
    <script type="text/javascript">
        (function(w, d, n, c) {
            w.CalltouchDataObject = n;
            w[n] = function() {
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
                var a = d.getElementsByTagName("script")[0],
                    s = d.createElement("script"),
                    i = function() {
                        a.parentNode.insertBefore(s, a)
                    },
                    m = typeof Array.prototype.find === 'function',
                    n = m ? "init-min.js" : "init.js";
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

    <? $APPLICATION->AddHeadString("<script data-skip-moving=\"true\">
        document.addEventListener(\"DOMContentLoaded\", function () {setTimeout(function(){ 
            document.querySelector('.phone_icon_wa').setAttribute('href', 'https://widget.yourgood.app/#telegram'); 
            var widget = document.createElement('script'); 
            widget.defer = true; widget.dataset.pfId = '293a6b11-edf6-4e96-bba6-58577477577d';
            widget.src = 'https://widget.yourgood.app/script/widget.js?id=293a6b11-edf6-4e96-bba6-58577477577d&now='+Date.now(); 
            document.head.appendChild(widget);
        }, 2000)});</script>"); ?>

    <? $APPLICATION->AddHeadString('<script>BX.message(' . CUtil::PhpToJSObject($MESS, false) . ')</script>', true); ?>
</head>
<!-- Varioqub experiments -->
<script type="text/javascript" data-skip-moving='true'>
    (function(e, x, pe, r, i, me, nt) {
        e[i] = e[i] || function() {
                (e[i].a = e[i].a || []).push(arguments)
            },
            me = x.createElement(pe), me.async = 1, me.src = r, nt = x.getElementsByTagName(pe)[0], me.addEventListener('error', function() {
                function cb(t) {
                    t = t[t.length - 1], 'function' == typeof t && t({
                        flags: {}
                    })
                };
                Array.isArray(e[i].a) && e[i].a.forEach(cb);
                e[i] = function() {
                    cb(arguments)
                }
            }), nt.parentNode.insertBefore(me, nt)
    })
    (window, document, 'script', 'https://abt.s3.yandex.net/expjs/latest/exp.js', 'ymab');
    ymab('metrika.50400436', 'init' /*, {clientFeatures}, {callback}*/ );
</script>

<body class="<?= CPriority::getConditionClass(); ?> mheader-v<?= $arThemeValues["HEADER_MOBILE"]; ?> footer-v<?= strtolower($arThemeValues['FOOTER_TYPE']); ?> fill_bg_<?= strtolower($arThemeValues['SHOW_BG_BLOCK']); ?> title-v<?= $arThemeValues["PAGE_TITLE"]; ?><?= ($arThemeValues['ORDER_VIEW'] == 'Y' && $arThemeValues['ORDER_BASKET_VIEW'] == 'HEADER' ? ' with_order' : '') ?><?= ($arThemeValues['CABINET'] == 'Y' ? ' with_cabinet' : '') ?><?= (intval($arThemeValues['HEADER_PHONES']) > 0 ? ' with_phones' : '') ?><?= ($arThemeValues['DECORATIVE_INDENTATION'] == 'Y' ? ' with_decorate' : '') ?> wheader_v<?= $arThemeValues['HEADER_TYPE'] ?><?= ($arThemeValues['ROUND_BUTTON'] == 'Y' ? ' round_button' : ''); ?><?= ($arThemeValues['PAGE_TITLE_POSITION'] == 'center' ? ' title_center' : ''); ?><?= (CSite::inDir(SITE_DIR . "index.php") ? ' in_index' : '') ?>">
    <!-- Google Tag Manager (noscript) -->
    <!-- <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KPXHCP2"
            height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript> -->
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

    <? $detect = new Mobile_Detect; ?>

    <?php if (!$detect->isMobile() || !$detect->isTablet()): ?>
        <? CPriority::ShowPageType('mega_menu'); ?>
        <div class="header_wrap visible-lg visible-md title-v<?= $arTheme["PAGE_TITLE"]["VALUE"]; ?><?= ($isIndex ? ' index' : '') ?><?= ($isNewIndex ? ' new-layout' : '') ?>">
            <? CPriority::ShowPageType('header', '', 'HEADER_TYPE'); ?>
        </div>
	
		<? CPriority::get_banners_position('TOP_UNDERHEADER'); ?>
		
        <? if ($arTheme["TOP_MENU_FIXED"]["VALUE"] == 'Y' && false): ?>
            <div id="headerfixed">
                <noindex>
                    <? CPriority::ShowPageType('header_fixed'); ?>
                </noindex>
            </div>
        <? endif; ?>
    <?php endif; ?>

    <?php if ($detect->isMobile() || $detect->isTablet() || 1): ?>
        <div id="mobileheader" class="">
            <? CPriority::ShowPageType('header_mobile'); ?>
            <div id="mobilemenu"
                class="<?= ($arTheme["HEADER_MOBILE_MENU_OPEN"]["VALUE"] == '1' ? 'leftside' : 'dropdown') ?><?= ($arTheme['HEADER_MOBILE_MENU_COLOR']['VALUE'] ? ' ' . $arTheme['HEADER_MOBILE_MENU_COLOR']['VALUE'] : '') ?>">
                <? CPriority::ShowPageType('header_mobile_menu'); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="body <?= ($isIndex ? 'index' : '') ?> hover_<?= $arTheme["HOVER_TYPE_IMG"]["VALUE"]; ?>">
        <div class="body_media"></div>

        <?php if ($isIndex): ?>
            <div class="new-ui-bg-grey">
            <?php endif; ?>

            <div role="main" class="main m-banner banner-<?= $arTheme["BANNER_WIDTH"]["VALUE"]; ?> new-ui">
                <? if (!$isIndex && !$is404 && !$isForm): ?>

                <? $APPLICATION->ShowViewContent('section_bnr_content'); ?>
                <? if ($APPLICATION->GetProperty("HIDETITLE") !== 'Y' && !defined('CUSTOMHIDETITLE')): ?>
                <!--title_content-->
                <? CPriority::ShowPageType('page_title'); ?>
                <!--end-title_content-->
                <? endif; ?>

                <? $APPLICATION->ShowViewContent('top_section_filter_content'); ?>
                <? endif; // if !$isIndex && !$is404 && !$isForm
                ?>

                <div class="container <?= ($isCabinet ? 'cabinte-page' : ''); ?> <?= ($isServicesKhraneniya ? 'khraneniya-pod-klyuch-page' : ''); ?>">
                    <? if (!$isIndex && !$isCatalog && !$isProjects): ?>
                    <? if ($APPLICATION->GetProperty("FULLWIDTH") !== 'Y' || $isServices): ?>
                    <div class="maxwidth-theme">
                        <? endif; ?>
                        <div class="row">
                            <? if ($is404): ?>
                            <div class="col-md-12 col-sm-12 col-xs-12 content-md">
                                <? else: ?>
                                <? if (!$isMenu): ?>
                                <div class="col-md-12 col-sm-12 col-xs-12 content-md">
                                    <? elseif ($isMenu && !$isServices && ($arTheme["SIDE_MENU"]["VALUE"] == "RIGHT" || $isBlog)): ?>
                                    <div class="col-md-9 col-sm-12 col-xs-12 content-md">
                                        <? CPriority::get_banners_position('CONTENT_TOP'); ?>
                                        <? elseif ($isMenu && !$isServices && $arTheme["SIDE_MENU"]["VALUE"] == "LEFT" && !$isBlog): ?>
                                        <div class="col-md-3 col-sm-3 hidden-xs hidden-sm left-menu-md">
                                            <? CPriority::ShowPageType('left_block') ?>
                                        </div>
                                        <div class="col-md-9 col-sm-12 col-xs-12 content-md">
                                            <? CPriority::get_banners_position('CONTENT_TOP'); ?>
                                            <? endif; ?>
                                            <? endif; ?>
                                            <? endif; ?>
                                            <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>