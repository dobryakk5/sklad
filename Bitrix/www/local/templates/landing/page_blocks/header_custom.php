<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
global $arTheme, $arTheme, $arRegion, $USER;
$headerType = ($arTheme['HEADER_TYPE'] && !is_array($arTheme['HEADER_TYPE']) ? $arTheme['HEADER_TYPE'] : $arTheme['HEADER_TYPE']['VALUE']);
$bOrder = (isset($arTheme['ORDER_VIEW']['VALUE']) && $arTheme['ORDER_VIEW']['VALUE'] == 'Y' && $arTheme['ORDER_VIEW']['DEPENDENT_PARAMS']['ORDER_BASKET_VIEW']['VALUE'] == 'HEADER' || $arTheme['ORDER_VIEW'] == 'Y' && $arTheme['ORDER_BASKET_VIEW'] == 'HEADER' ? true : false);
$bCabinet = ($arTheme["CABINET"]["VALUE"] == 'Y' ? true : false);

if ($arRegion)
    $bPhone = ($arRegion['PHONES'] ? true : false);
else
    $bPhone = ((int)$arTheme['HEADER_PHONES'] ? true : false);

$logoClass = ($arTheme['COLORED_LOGO']['VALUE'] !== 'Y' ? '' : ' colored');
$fixedMenuClass = (is_array($arTheme['TOP_MENU_FIXED']) && $arTheme['TOP_MENU_FIXED']['VALUE'] == 'Y' || $arTheme['TOP_MENU_FIXED'] == 'Y' ? ' canfixed' : '');
$basketViewClass = (is_array($arTheme["ORDER_BASKET_VIEW"]) && $arTheme["ORDER_BASKET_VIEW"]["VALUE"] ? ' ' . strtolower($arTheme["ORDER_BASKET_VIEW"]["VALUE"]) : ' ' . strtolower($arTheme["ORDER_BASKET_VIEW"]));
?>

<header class="header-v<?= $headerType ?><?= $fixedMenuClass ?><?= $basketViewClass ?> block-phone sm" id="MENU">
    <div class="logo_and_menu-row white">
        <div class="logo-row">
            <div class="maxwidth-theme clearfix">
                <div class="row">
                    <div class="logo-block col-md-12 col-sm-12">
                        <div class="logo pull-left<?= $logoClass ?>">
                            <a style="" href="/">
                                <img style=" margin-right: 20px"
                                     src="/upload/CPriority/b35/guv308nx3nh9ugbbpt4m6252s40syyit/logo_site.png"
                                     alt="АльфаСклад">
                                <?=$APPLICATION->ShowViewContent("HEADER_IMAGE")?>
                            </a>
                        </div>
                        <div class="slogan ">
                            <div class="top-description">
                                <?=$APPLICATION->ShowViewContent("HEADER_TEXT")?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><? // class=logo-row?>
        <div class="menu-row appendDown bgcolored">
            <div class="maxwidth-theme">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-12">

                            <div class="menu-only catalog_icons_Y icons_position_ view_type_LINE count_menu_wide_count_3">
                                <nav class="mega-menu sliced">
                                    <div class="table-menu">
                                        <div class="marker-nav"></div>
                                        <table>
                                            <tbody>
                                            <tr>
                                                <td class="menu-item normal_dropdown dropdown"
                                                    style="visibility: visible;">
                                                    <div class="wrap">
                                                        <a class="font_xs dark-color dropdown-toggle" onclick="window.scrollTo(0, -200)">
                                                            <span>О нас</span>
                                                        </a>
                                                        <span class="tail"></span>
                                                    </div>
                                                </td>

                                                <td class="menu-item normal_dropdown dropdown"
                                                    style="visibility: visible;">
                                                    <div class="wrap">
                                                        <a class="font_xs dark-color dropdown-toggle"
                                                           href="#part1">
                                                            <span>Адреса складов</span>
                                                        </a>
                                                        <span class="tail"></span>
                                                    </div>
                                                </td>

                                                <td class="menu-item normal_dropdown dropdown"
                                                    style="visibility: visible;">
                                                    <div class="wrap">
                                                        <a class="font_xs dark-color dropdown-toggle" href="#part2">
                                                            <span>Оставить заявку</span>
                                                        </a>
                                                        <span class="tail"></span>
                                                    </div>
                                                </td>

                                                <td class="menu-item normal_dropdown" style="visibility: visible;">
                                                    <div class="wrap">
                                                        <a class="font_xs dark-color" href="#part3">
                                                            <span>Фотогалерея</span>
                                                        </a>
                                                    </div>
                                                </td>

                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="line-row visible-xs"></div>
</header>