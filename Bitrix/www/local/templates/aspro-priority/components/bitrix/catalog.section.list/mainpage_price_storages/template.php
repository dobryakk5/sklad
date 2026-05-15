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


<? if (count($arResult["SECTIONS"]) > 0) { ?>
    <? if ($arParams["ONLY_MAP"] == "Y") { ?>
        #MAP#
    <? } else { ?>
        <? if ($arParams["AJAX_LOAD"] != "Y") { ?>
            <?
            if (empty($arParams["TITLE"])) {
                $arResult["TITLE"] = "Стоимость аренды бокса";
            } else {
                $arResult["TITLE"] = $arParams["TITLE"];

            }
            ?>

            <div class="maxwidth-theme">
            <div class="shadow-box">
            <div class="mainpage_price_storages">
            <h2 class="style-h3"><?= $arResult["TITLE"] ?></h2>
            <div class="link_title"><a href="/price/">Посмотреть прайс-лист</a></div>
            <div class="second_title">Сейчас
                свободно <?= num_decline($arResult["CNT_OPEN_STORAGES"], array("бокс", "бокса", "боксов")) ?> разных
                размеров
            </div>
            <div class="row">
            <div class="<?= ($arParams["HIDE_MAP"] != "Y") ? "col-md-6" : "col-md-12" ?> col-xs-12">
            <div class="size_block">
                <div class="block_label">Выберите размер:</div>
                <div class="sizes">
                    <div class="size_button active" data-square-from="1" data-square-to="3">1-3м<sup>2</sup></div>
                    <div class="size_button" data-square-from="3" data-square-to="5">3-5м<sup>2</sup></div>
                    <div class="size_button" data-square-from="5" data-square-to="7">5-7м<sup>2</sup></div>
                    <div class="size_button" data-square-from="7" data-square-to="10">7-10м<sup>2</sup></div>
                    <div class="size_button" data-square-from="10" data-square-to="15">10-15м<sup>2</sup></div>
                    <div class="size_button" data-square-from="15" data-square-to="100">от 15м<sup>2</sup></div>
                </div>
                <div class="text">Уточните у менеджера стоимость и наличие помещения</div>
            </div>
            <div class="sklad_list">
            <div class="ajaxPreloader"></div>
            <div class="ajax_skladList">
        <? } ?>

        <? foreach ($arResult["SECTIONS"] as $key => $arItem) { ?>
            <div class="sklad sklad_<?= $arItem["ID"] ?>">
                <div class="header">
                    <div class="info">
                        <div class="name"><a href="/rental_catalog/<?= $arItem["CODE"] ?>/"><?= $arItem["NAME"] ?></a></div>
                        <? if (strlen($arItem["UF_ADDRESS"]) > 0) { ?>
                            <div class="address"><?= $arItem["UF_ADDRESS"] ?></div>
                        <? } ?>
                    </div>

                    <? if ($arParams["HIDE_MAP"] == "Y") { ?>
                        <div class="closeopen">Развернуть</div>
                    <? } else { ?>
                        <div class="closeopen"><?= ($key >= 2) ? 'Развернуть' : 'Свернуть' ?></div>
                    <? } ?>
                </div>
                <div class="content <?= ($arParams["HIDE_MAP"] == "Y") ? "close_all" : "" ?>">
                    <div class="link_sklad"><a href="/rental_catalog/<?= $arItem["CODE"] ?>/">Подробнее о складе</a></div>
                    <table class="rental_catalog_data">
                        <? foreach ($arItem["FLOORS"] as $arFloor) { ?>
                            <tr>
                                <td class="floor"><?= $arFloor["NUMBER"] ?></td>
                                <td class="status"><?= ($arFloor["STATUS"] == "opened") ? "Свободен" : "Занят" ?></td>
                                <td class="price">
                                    <? if (strlen($arFloor["PRICE"]) > 0) { ?>
                                        от <span><?= number_format($arFloor["PRICE"], 0, '', ' ') ?> руб./месяц</span>
                                    <? } else { ?>
                                        <span> - </span>
                                    <? } ?>
                                </td>
                                <td class="button">
                                    <? if ($arFloor["STATUS"] == "opened") { ?>
                                        <a class="btn btn-default btn-xs"
                                           href="/rental_catalog/<?= $arItem["CODE"] ?>/?propSize=SQUARE&SQUARE_FROM=<?= $arParams["SQUARE_FROM"] ?>&SQUARE_TO=<?= ceil($arParams["SQUARE_TO"]) ?>">Выбрать
                                            и арендовать</a>
                                    <? } else { ?>
                                        <span class="btn btn-default btn-xs btn-transparent SHOW_FORM_REMINDER btn-wrap"
                                              data-event="jqm" data-param-webform-id="15"
                                              data-param-sklad-id="<?= $arItem["ID"] ?>"
                                              data-param-square-from="<?= $arParams["SQUARE_FROM"] ?>"
                                              data-param-square-to="<?= $arParams["SQUARE_TO"] ?>"
                                              data-param-type="webform"
                                              data-name="webform">Сообщить об освобождении</span>
                                    <? } ?>
                                </td>
                            </tr>
                        <? } ?>
                    </table>
                </div>
            </div>
        <? } ?>

        <? if ($arParams["AJAX_LOAD"] != "Y") { ?>
            </div>
            </div>
            </div>
            <? if ($arParams["HIDE_MAP"] != "Y") { ?>
                <div class="col-md-6 hidden-xs">
                    <? /*<div class="ajaxPreloader"></div>*/ ?>
                    <div class="ajax_map">
                        #MAP#
                    </div>
                    <? $APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        array(
                            "AREA_FILE_SHOW" => "file",
                            "AREA_FILE_SUFFIX" => "inc",
                            "EDIT_TEMPLATE" => "",
                            "PATH" => "/include/mainpage_price_storages_text.php"
                        ),
                        false,
                        array("HIDE_ICONS" => "Y")
                    ); ?>
                </div>
            <? } ?>
            </div>
            </div>
            </div>
            </div>
        <? } ?>
    <? } ?>
<? } ?>


<?
$this->__component->arResult["CACHED_TPL"] = @ob_get_contents();
ob_get_clean();
?>