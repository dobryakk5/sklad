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
    <div class="maxwidth-theme">
        <div class="shadow-box">
            <div class="storage_list_withmap">
                <h3>Адреса наших складов</h3>


            </div>
        </div>
        <div class="map-shadow">
            #MAP#
        </div>
    </div>
<? } ?>


<?
$this->__component->arResult["CACHED_TPL"] = @ob_get_contents();
ob_get_clean();
?>