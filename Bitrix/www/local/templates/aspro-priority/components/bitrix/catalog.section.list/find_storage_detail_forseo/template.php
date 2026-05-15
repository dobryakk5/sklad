<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
ob_start();
?>
<div class="find_storage_detail">
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <? if (strlen($arResult["SECTION"]["UF_DESCR_DETAIL"]) > 0) { ?>
                <p class="descr"><?= $arResult["SECTION"]["UF_DESCR_DETAIL"] ?></p>
            <? } ?>
            <? if ((strlen($arResult["SECTION"]["UF_ADDRESS"]) > 0) or (strlen($arResult["SECTION"]["UF_PHONE"]) > 0)) { ?>
                <p class="address">
                    <? if (strlen($arResult["SECTION"]["UF_ADDRESS"]) > 0) { ?>
                        <span><?= $arResult["SECTION"]["UF_ADDRESS"] ?></span>
                    <? } ?>
                    <? if (strlen($arResult["SECTION"]["UF_PHONE"]) > 0) { ?>
                        <a href="tel:<?= preg_replace('/\D+/', '', $arResult["SECTION"]["UF_PHONE"]) ?>"><?= $arResult["SECTION"]["UF_PHONE"] ?></a>
                    <? } ?>
                </p>
            <? } ?>
            <div class="row">
                <? if (strlen($arResult["SECTION"]["UF_DOSTUP_TIME"]) > 0) { ?>
                    <div class="col-md-6 col-xs-12">
                        <div class="work-time">
                            Доступ на склад:<br>
                            <span><?= $arResult["SECTION"]["UF_DOSTUP_TIME"] ?></span>
                        </div>
                    </div>
                <? } ?>
                <? if (strlen($arResult["SECTION"]["UF_RECEPTION"]) > 0) { ?>
                    <div class="col-md-6 col-xs-12">
                        <div class="work-time">
                            <? if (strlen($arResult["SECTION"]["UF_ONLINE_MANAGER"]) > 0): ?>
                                Менеджер на связи:
                            <?php else: ?>
                                Режим работы
                                <? if (strlen($arResult["SECTION"]["UF_RECEPTION_NAME"]) > 0) { ?>
                                    <?= mb_strtolower($arResult["SECTION"]["UF_RECEPTION_NAME"]) ?>а
                                <? } else { ?>
                                    ресепшна:
                                <? } ?>
                            <?php endif; ?>
                            <br>
                            <span><?= $arResult["SECTION"]["UF_RECEPTION"] ?></span>
                        </div>
                    </div>
                <? } ?>
            </div>

            <? if ((!empty($arResult["SECTION"]["UF_METRO"])) or (!empty($arResult["SECTION"]["UF_BUS_STATION"]))) { ?>
                <div class="metro">
                    <div class="row">
                        <? if (!empty($arResult["SECTION"]["UF_METRO"])) { ?>
                            <div class="col-md-6 col-xs-12">
                                <div class="work-time">
                                    Ближайшие станции метро:<br>
                                    <? foreach ($arResult["SECTION"]["UF_METRO"] as $val) { ?>
                                        <span><?= $val ?></span>
                                    <? } ?>
                                </div>
                            </div>
                        <? } ?>
                        <? if (!empty($arResult["SECTION"]["UF_BUS_STATION"])) { ?>
                            <div class="col-md-6 col-xs-12">
                                <div class="work-time">
                                    Остановки общественного транспорта:<br>
                                    <? foreach ($arResult["SECTION"]["UF_BUS_STATION"] as $val) { ?>
                                        <span><?= $val ?></span>
                                    <? } ?>
                                </div>
                            </div>
                        <? } ?>
                    </div>
                </div>
            <? } ?>

            <?php /*
                <div class="buttons">
                    <a class="btn btn-default scroll" href="#formManagerOrder_block">Арендовать через менеджера</a>
                </div> */
            ?>

        </div>
        <div class="col-md-6 col-xs-12">
            <? if (strlen($arResult["SECTION"]["UF_MAP"]) > 0) { ?>
                #MAP#
            <? } ?>
        </div>
    </div>
    <div class="features">

        <p>
            <?= $arResult["SECTION"]["DESCRIPTION"] ?>
        </p>

    </div>

    <div class="features">
        <div class="row">
            <? if (!empty($arResult["SECTION"]["FEATURES"])) { ?>
                <div class="col-md-5 col-xs-12">
                    <p class="style-h3">Преимущества АльфаСклад</p>

                    <div class="features-list">
                        <? foreach ($arResult["SECTION"]["FEATURES"] as $arItem) { ?>
                            <div class="item">
                                <div class="image">
                                    <img src="<?= $arItem["ICON"]["src"] ?>" />
                                </div>
                                <div class="text"><?= $arItem["NAME"] ?></div>
                                <? if (strlen($arItem["DETAIL_TEXT"]) > 0) { ?>
                                    <a class="link" href="<?= $arItem["DETAIL_PAGE_URL"] ?>"></a>
                                <? } ?>
                            </div>
                        <? } ?>
                    </div>
                </div>
            <? } ?>

            <div class="col-md-7 col-xs-12">
                <p class="style-h3">Фотогалерея</p>

                <? if (count($arResult["SECTION"]["GALLERY"]) > 0) { ?>
                    <div class="photogallery_slider detail">
                        <div class="flexslider flexslider-init flexslider-direction-nav" id="slider">
                            <ul class="slides">
                                <? foreach ($arResult["SECTION"]["GALLERY"] as $arPicture) { ?>
                                    <li class="item">
                                        <a href="<?= $arPicture["BIG"]["RESIZE"]["src"] ?>" target="_blank"
                                            class="fancybox" data-fancybox-group="gallery"
                                            title="<?= $arPicture["DESCRIPTION"] ?>">
                                            <img alt="" class="img-responsive"
                                                src="<?= $arPicture["MEDIUM"]["RESIZE"]["src"] ?>">
                                            <span class="zoom">
                                                <?= CPriority::showIconSvg(SITE_TEMPLATE_PATH . '/images/include_svg/zoom.svg'); ?>
                                            </span>
                                        </a>
                                    </li>
                                <? } ?>
                            </ul>
                        </div>
                        <? if (count($arResult["SECTION"]["GALLERY"]) > 1) { ?>
                            <div class="thmb_wrap">
                                <div class="thmb flexslider unstyled" id="carousel">
                                    <ul class="slides">
                                        <? foreach ($arResult["SECTION"]["GALLERY"] as $arPicture) { ?>
                                            <li class="blink">
                                                <img class="img-responsive inline"
                                                    src="<?= $arPicture["SMALL"]["RESIZE"]["src"] ?>" />
                                            </li>
                                        <? } ?>
                                    </ul>
                                </div>
                            </div>
                        <? } ?>
                    </div>
                <? } ?>
            </div>
        </div>
    </div>

    <? if (false): ?>
        <div class="box_filter_main_container">
            <div class="row">
                <div class="col-md-8 col-xs-12">
                    <h2 class="style-h3">Стоимость аренды</h2>
                    #BOX_FILTER#
                </div>
                <div class="col-md-4 col-xs-12 hidden-xs">
                    #BOX_FILTER_SLIDER#
                </div>
            </div>
        </div>
    <? endif ?>


    <? /*
        <div class="useful_info">
            <h3>Дополнительная информация о ваших возможностях и услугах</h3>
            <? if (strlen($arResult["SECTION"]["UF_DOP_INFO"]) > 0) { ?>
                <div class="text">
                    <?= $arResult["SECTION"]["UF_DOP_INFO"] ?>
                </div>
            <? } ?>
            <? if (!empty($arResult["SECTION"]["UF_ARTICLES"])) { ?>
                #ARTICLES#
            <? } ?>
        </div>
         */ ?>

</div>


<?
$this->__component->arResult["CACHED_TPL"] = @ob_get_contents();
ob_get_clean();
?>