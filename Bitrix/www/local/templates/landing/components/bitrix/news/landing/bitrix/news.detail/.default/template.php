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
    <section class="page-top maxwidth-theme " id="part1">
        <div class="shadow-box">
            <div class="useblock_mainpage">
                <div class="item-views company front company_scroll type_1">
                    <div class="company-block">
                        <div class="row">

                            <div class="item col-md-6" style="height: 466px;">
                                <div class="text" style="padding-top: unset">
                                    <div class="h2"><?= $arResult["NAME"] ?></div>
                                    <br>
                                    <div class="preview-text"><?= html_entity_decode($arResult["DETAIL_TEXT"]) ?></div>

                                    <div class="buttons" style="display:none;">
                                <span>
									<span class="btn btn-default btn-transparent animate-load" data-event="jqm"
                                          data-param-id="20" data-name="question">Начать сотрудничество</span>
								</span>
                                    </div>
                                </div>
                            </div>
                            <div class="item col-md-6" style="height: 466px;">
                                <div class="image lazy"
                                     data-bg="url(<?= $arResult["DETAIL_PICTURE"]["SRC"] ?>)"
                                     style="background-size: cover; background-repeat: no-repeat; background-position: 50% 0%; background-image: url(&quot;<?= $arResult["DETAIL_PICTURE"]["SRC"] ?>&quot;);"
                                     data-was-processed="true">
                                </div>
                            </div>

                            <div style="clear: both;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php
$this->SetViewTarget("HEADER_IMAGE");
?>
    <img style="" src="<?= $arResult["PREVIEW_PICTURE"]["SRC"] ?>"
         alt="<?= $arResult["NAME"] ?>">
<?php
$this->EndViewTarget();

$this->SetViewTarget("HEADER_TEXT");
?>
<?= $arResult["PREVIEW_TEXT"] ?>
<?php
$this->EndViewTarget();
$this->SetViewTarget("FORM");
?>
<?= html_entity_decode($arResult["PROPERTIES"]["FORM"]["VALUE"]) ?>
<?php
$this->EndViewTarget();
$APPLICATION->BLOCKS=$arResult["PROPERTIES"]["BLOCKS"]["VALUE"];