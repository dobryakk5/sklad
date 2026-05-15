<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Page\Asset;

if (CModule::IncludeModule("aspro.priority"))
    $arThemeValues = CPriority::GetFrontParametrsValues(SITE_ID);

?>
<!DOCTYPE html>
<html lang="ru">
    <head>
    <? global $APPLICATION; ?>
    <? IncludeTemplateLangFile(__FILE__); ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="DirectTalk">
    <title><? $APPLICATION->ShowTitle() ?></title>
    <?$APPLICATION->ShowHead();?> 
    <?Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/jquery.min.js");?>
    <meta name="cmsmagazine" content="42110ccba65ec255508b288e5489835d"/>
    <? if (CModule::IncludeModule("aspro.priority")) {
        CPriority::Start(SITE_ID);
    } ?>
    <meta name="yandex-verification" content="24592a9984827a32"/>
<meta name="yandex-verification" content="fd515d0fff061eb2" />
    <!-- showhead   -->
    <?Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/css/main.min.css");?>

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
        (function(w,d,n,c){w.CalltouchDataObject=n;w[n]=function(){w[n]["callbacks"].push(arguments)};if(!w[n]["callbacks"]){w[n]["callbacks"]=[]}w[n]["loaded"]=false;if(typeof c!=="object"){c=[c]}w[n]["counters"]=c;for(var i=0;i<c.length;i+=1){p(c[i])}function p(cId){var a=d.getElementsByTagName("script")[0],s=d.createElement("script"),i=function(){a.parentNode.insertBefore(s,a)},m=typeof Array.prototype.find === 'function',n=m?"init-min.js":"init.js";s.type="text/javascript";s.async=true;s.src="https://mod.calltouch.ru/"+n+"?id="+cId;if(w.opera=="[object Opera]"){d.addEventListener("DOMContentLoaded",i,false)}else{i()}}})(window,document,"ct","6cvg7p2a");
    </script>
    <!-- calltouch -->
    <? $APPLICATION->AddHeadString('<script>BX.message(' . CUtil::PhpToJSObject($MESS, false) . ')</script>', true); ?>
</head>
<body class="mainPage">
<!-- Google Tag Manager (noscript) -->
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KPXHCP2"
            height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->
<div id="panel"><? /*$APPLICATION->ShowPanel();*/ ?></div>
<? CPriority::SetJSOptions(); ?>
<div class="preheader">
    <div class="container big-width">
        <div class="preheader-wrapper">
            <div class="city-col">
				<div class="city-wrap"><span class="from"><a href="https://alfasklad.ru/">Москва</a></span><span class="slash">/</span><span class="to"><a href-="https://spb.alfasklad.ru/">Санкт-Петербург</a></span>
                </div>
            </div>
            <div class="preheader-menu-col xl-down-hidden">
                <ul class="menu">
                    <li><a href="/about/">О компании</a></li>
                    <li><a href="#">Вакансии</a></li>
                    <li><a href="#">Партнерам</a></li>
                    <li><a href="/about/fotogalereya-skladov/">Фотогалерея</a></li>
                    <li><a href="/services/usluga-dostavka/">Доставка</a></li>
                    <li><a href="/find_storage/">Контакты</a></li>
                </ul>
            </div>
            <div class="phone-number-col"><a href="tel:+74951549846">+7 (495) 154-98-46</a></div>
        </div>
    </div>
    <div class="cabinet"><a href="/cabinet/">
        <picture class="icon-svg"><img src="<?=SITE_TEMPLATE_PATH ?>/img/cabinet.svg"/></picture>
        <span>Личный кабинет</span></a></div>
</div>
<header class="header">
    <div class="container big-width">
        <div class="header-wrapper">
			<div class="logo-region"><a class="logo" href="/"><img class="item-image" src="<?=SITE_TEMPLATE_PATH ?>/img/logo.png" alt="logo"/>
                <div class="logo-desc"><span class="logo-title">Альфасклад</span><span
                        class="slogan"><span>заберём</span><span
                        class="strong">сохраним</span><span>вернём</span></span></div>
            </a></div>
            <div class="navbar-region">
                <nav class="menu xxxl-down-hidden">
                    <ul class="menu__list">
                        <li><a class="menu__link parent" href="/storage/">Хранение вещей</a><span
                                class="menu__arrow arrow"></span><span class="sub-menu__list"><ul><li><a
                                class="sub-menu__link" href="/storage/"><picture class="icon-svg"><img
                                src="<?=SITE_TEMPLATE_PATH ?>/img/menu1.svg"/></picture><span>Хранение вещей</span></a></li><li><a
                                class="sub-menu__link" href="/services/furnuture-storage/"><picture class="icon-svg"><img
                                src="<?=SITE_TEMPLATE_PATH ?>/img/menu2.svg"/></picture><span>Хранение мебели</span></a></li><li><a
                                class="sub-menu__link" href="#"><picture class="icon-svg"><img
                                src="<?=SITE_TEMPLATE_PATH ?>/img/menu3.svg"/></picture><span>Хранение детских вещей</span></a></li><li><a
                                class="sub-menu__link" href="#"><picture class="icon-svg"><img
                                src="<?=SITE_TEMPLATE_PATH ?>/img/menu4.svg"/></picture><span>Хранение бытовой техники</span></a></li><li><a
                                class="sub-menu__link" href="#"><picture class="icon-svg"><img
                                src="<?=SITE_TEMPLATE_PATH ?>/img/menu5.svg"/></picture><span>Временное хранение</span></a></li><li><a
                                class="sub-menu__link" href="#"><picture class="icon-svg"><img
                                src="<?=SITE_TEMPLATE_PATH ?>/img/menu6.svg"/></picture><span>Хранение шин</span></a></li><li><a
                                class="sub-menu__link" href="#"><picture class="icon-svg"><img
                                src="<?=SITE_TEMPLATE_PATH ?>/img/menu7.svg"/></picture><span>Хранение велосипеда</span></a></li><li><a
                                class="sub-menu__link" href="#"><picture class="icon-svg"><img
                                src="<?=SITE_TEMPLATE_PATH ?>/img/menu8.svg"/></picture><span>Хранение мотоцикла</span></a></li><li><a
                                class="sub-menu__link" href="#"><picture class="icon-svg"><img
                                src="<?=SITE_TEMPLATE_PATH ?>/img/menu9.svg"/></picture><span>Хранение самоката</span></a></li><li><a
                                class="sub-menu__link" href="#"><picture class="icon-svg"><img
                                src="<?=SITE_TEMPLATE_PATH ?>/img/menu10.svg"/></picture><span>Хранение лодки</span></a></li><li><a
                                class="sub-menu__link" href="#"><picture class="icon-svg"><img
                                src="<?=SITE_TEMPLATE_PATH ?>/img/menu11.svg"/></picture><span>На время ремонта</span></a></li><li><a
                                class="sub-menu__link" href="#"><picture class="icon-svg"><img
                                src="<?=SITE_TEMPLATE_PATH ?>/img/menu12.svg"/></picture><span>На время отпуска</span></a></li><li><a
                                class="sub-menu__link" href="#"><picture class="icon-svg"><img
                                src="<?=SITE_TEMPLATE_PATH ?>/img/menu13.svg"/></picture><span>Во время переезда</span></a></li><li><a
                                class="sub-menu__link" href="#"><picture class="icon-svg"><img
                                src="<?=SITE_TEMPLATE_PATH ?>/img/menu14.svg"/></picture><span>Сдать вещи на время аренды</span></a></li><li><a
                                class="sub-menu__link" href="#"><picture class="icon-svg"><img
                                src="<?=SITE_TEMPLATE_PATH ?>/img/menu15.svg"/></picture><span>Кладовка у дома</span></a></li><li><a
                                class="sub-menu__link" href="#"><picture class="icon-svg"><img
                                src="<?=SITE_TEMPLATE_PATH ?>/img/menu16.svg"/></picture><span>Хранение архивов</span></a></li><li><a
                                class="sub-menu__link" href="#"><picture class="icon-svg"><img
                                src="<?=SITE_TEMPLATE_PATH ?>/img/menu17.svg"/></picture><span>Для интернет магазина</span></a></li><li><a
                                class="sub-menu__link" href="#"><picture class="icon-svg"><img
                                src="<?=SITE_TEMPLATE_PATH ?>/img/menu18.svg"/></picture><span>С подъездом на автомобиле</span></a></li><li><a
                                class="sub-menu__link" href="#"><picture class="icon-svg"><img
                                src="<?=SITE_TEMPLATE_PATH ?>/img/menu19.svg"/></picture><span>Склад для бизнеса</span></a></li><li><a
                                class="sub-menu__link" href="#"><picture class="icon-svg"><img
                                src="<?=SITE_TEMPLATE_PATH ?>/img/menu20.svg"/></picture><span>Склад + Офис</span></a></li></ul></span></li>
                        <li><a class="menu__link" href="/services/khraneniya-pod-klyuch/">Облачное хранение</a></li>
                        <li><a class="menu__link" href="/price/">Тарифы</a></li>
                        <li><a class="menu__link" href="/services/">Доп.услуги</a></li>
                        <li><a class="menu__link" href="#">Сервис и магазин</a></li>
                    </ul>
                </nav>
            </div>
            <div class="header-burger xl-up-hidden"><span></span></div>
            <div class="mobile-menu hidden">
                <div class="btn-close">
                    <div class="crest"></div>
                </div>
                <div class="preheader-menu-col">
                    <ul class="menu">
                        <li><a href="#">О компании</a></li>
                        <li><a href="#">Вакансии</a></li>
                        <li><a href="#">Партнерам</a></li>
                        <li><a href="/about/fotogalereya-skladov/">Фотогалерея</a></li>
                        <li><a href="/services/usluga-dostavka/">Доставка</a></li>
                        <li><a href="/find_storage/">Контакты</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>