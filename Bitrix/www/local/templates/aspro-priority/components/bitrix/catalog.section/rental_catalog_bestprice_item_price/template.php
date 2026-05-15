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
$this->setFrameMode(false);

$arItem = $arResult["ITEMS"][0];
if (strlen($arItem["MIN_PRICE"]["DISCOUNT_VALUE"]) > 0) {
    echo $arItem["MIN_PRICE"]["DISCOUNT_VALUE"];
} else {
    echo 0;
}
