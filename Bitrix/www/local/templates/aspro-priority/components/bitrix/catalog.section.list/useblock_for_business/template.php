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
?>

<? if ($arParams["AJAX_LOAD"] != "Y") { ?>
    <div class="maxwidth-theme">
    <div class="shadow-box">
    <div class="for-business-use">
    <h2 class="text-center">Что можно хранить в складском помещении</h2>
    <h3>Применение бокса</h3>
    <? if (strlen($arResult["SECTION"]["UF_USE_TEXT"]) > 0) { ?>
        <div class="text">
            <?= htmlspecialchars_decode($arResult["SECTION"]["UF_USE_TEXT"]) ?>
        </div>
    <? } ?>

    <div id="ajax_useTypeBlock">
<? } ?>
<? if (!empty($arResult["SECTIONS"])) { ?>
    <div class="use-items">
        <div class="row">
            <?
            $itemsCount = count($arResult["SECTIONS"]);
            $cnt = 0;
            ?>
            <? foreach ($arResult["SECTIONS"] as $arUseItem) {
            if (!empty($arUseItem["UF_HREF"])) {
                $arUseItem["SECTION_PAGE_URL"] = $arUseItem["UF_HREF"];
            }
            ?>
            <?
            $cnt++;
            if ($cnt == 7) {
            ?>
        </div>
        <div class="row hidden">
            <?
            }
            ?>
            <div class="col-md-4 col-xs-12">
                <div class="item">
                    <div class="image">
                        <img src="<?= $arUseItem["PICTURE"]["RESIZE"]["src"] ?>"/>
                    </div>
                    <div class="title dark-color">
                        <?= $arUseItem["NAME"] ?>
                    </div>
                    <a class="link" href="<?= $arUseItem["SECTION_PAGE_URL"] ?>"></a>
                </div>
            </div>
            <? } ?>
        </div>
        <div class="button text-center <?= ($itemsCount <= 6) ? "hidden" : "" ?>">
            <a class="order_button btn btn-default" href="javascript:void(0);">Показать еще</a>
        </div>
    </div>
<? } else { ?>
    <p>Элементы не найдены</p>
<? } ?>
<? if ($arParams["AJAX_LOAD"] != "Y") { ?>
    </div>

    </div>
    </div>
    </div>
<? } ?>