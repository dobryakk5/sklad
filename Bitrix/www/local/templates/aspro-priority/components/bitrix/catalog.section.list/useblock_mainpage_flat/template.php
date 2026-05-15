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

<?php if (strlen($arResult["SECTION"]["ID"]) > 0): ?>
    <div class="main_section">

        <div class="style-h3"><?= $arResult["SECTION"]["NAME"] ?></div>

        <div class="row">
            <?
            $mainSect = array();
            foreach ($arResult["MAIN_SECTIONS"] as $arMainSection) {
                if ($arMainSection["SELECTED"] == "Y") {
                    $mainSect = $arMainSection;
                    break;
                }
            }
            ?>
            <div class="col-md-12">
                <? if (strlen($mainSect["TEXT"]) > 0) { ?>
                    <div class="text">
                        <?= $mainSect["TEXT"] ?>
                    </div>
                <? } ?>
            </div>
        </div>
    </div>

    <? if (count($arResult["SECTIONS"]) > 0) { ?>
        <div class="child_sections">
            <div class="row">
                <? foreach ($arResult["SECTIONS"] as $arChildSection) { ?>
                    <div class="col-md-6 col-xs-12">
                        <div class="item">
                            <a class="dark-color"
                                href="<?= $arChildSection["SECTION_PAGE_URL"] ?>"><?= ($arChildSection["UF_ANCHOR"] != '') ? $arChildSection["UF_ANCHOR"] : $arChildSection["NAME"] ?></a>
                        </div>
                    </div>
                <? } ?>
            </div>
        </div>
    <? } ?>

<?php endif; ?>