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
        <div class="useblock_mainpage">
            <h1 class="style-h3" style="font-size: 26px; margin-bottom: 20px">Хранение вещей в Москве</h1>
            <div id="ajax_useblock_mainpage">
                <? } ?>

                <? if (strlen($arResult["SECTION"]["ID"]) > 0) { ?>
                    <div class="main_section">
                        <div class="row">
                            <?
                            $mainSect = array();
                            $showPic = false;
                            $picSrc = "";
                            foreach ($arResult["MAIN_SECTIONS"] as $arMainSection) {
                                if ($arMainSection["SELECTED"] == "Y") {
                                    $mainSect = $arMainSection;
                                    if (strlen($arMainSection["PICTURE"]["src"]) > 0) {
                                        $showPic = true;
                                        $picSrc = $arMainSection["PICTURE"]["src"];

                                        break;
                                    }
                                }
                            }
                            ?>
                            <div class="<?= ($showPic) ? "col-md-6 col-xs-12" : "col-md-12" ?>">
                                <div class="main_section_list">
                                    <? foreach ($arResult["MAIN_SECTIONS"] as $arMainSection) { ?>
                                        <div class="item <?= ($arMainSection["SELECTED"] == "Y") ? "active" : "" ?>"
                                             data-section-id="<?= $arMainSection["ID"] ?>">
                                            <? if (strlen($arMainSection["ICON"]["src"]) > 0) { ?>
                                                <div class="icon">
                                                    <img class="lazy" data-src="<?= $arMainSection["ICON"]["src"] ?>"
                                                         alt="<?= $arMainSection["NAME"] ?>"/>
                                                </div>
                                            <? } ?>
                                            <div class="name">
                                                <?= $arMainSection["NAME"] ?>
                                            </div>
                                        </div>
                                    <? } ?>
                                </div>
                                <? if (strlen($mainSect["TEXT"]) > 0) { ?>
                                    <div class="text">
                                        <?= $mainSect["TEXT"] ?>
                                    </div>
                                <? } ?>
                            </div>
                            <? if ($showPic) { ?>
                                <div class="col-md-6 col-xs-12">
                                    <div class="main_section_picture">
                                        <img class="lazy" data-src="<?= $picSrc ?>" alt="Хранение вещей для дома - фото"/>
                                    </div>
                                </div>
                            <? } ?>
                        </div>
                    </div>

                    <? if (count($arResult["SECTIONS"]) > 0) { ?>
                        <div class="child_sections">
                            <div class="row">
                                <? foreach ($arResult["SECTIONS"] as $arChildSection) { ?>
                                    <div class="col-md-4 col-xs-12">
                                        <div class="item">
                                            <a class="dark-color"
                                               href="<?= $arChildSection["SECTION_PAGE_URL"] ?>"><?= ($arChildSection["UF_ANCHOR"] != '') ? $arChildSection["UF_ANCHOR"] : $arChildSection["NAME"] ?></a>
                                        </div>
                                    </div>
                                <? } ?>
                            </div>
                        </div>
                    <? } ?>
                <? } ?>

                <? if ($arParams["AJAX_LOAD"] != "Y") { ?>
            </div>
        </div>
    </div>
</div>
<? } ?>
