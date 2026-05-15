<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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


<?foreach($arResult["ITEMS"] as $arItem) {?>
	<div class="button">
		<?if(strlen($arItem["PROPERTIES"]["LINK"]["VALUE"]) > 0) {?>
			<a class="btn btn-transparent" href="<?=$arItem["PROPERTIES"]["LINK"]["VALUE"]?>"><?=$arItem["NAME"]?></a>			
		<?} elseif($arItem["ID"] == "9807") {?>
			<span class="btn btn-transparent animate-load" title="Заказать доставку" data-event="jqm" data-param-webform-id="23" data-param-type="webform" data-name="webform"><?=$arItem["NAME"]?></span>
		<?} else {?>
			<span class="btn btn-transparent animate-load" data-event="jqm" data-param-id="18" data-name="order_services" data-autoload-service="<?=$arItem["NAME"]?>" data-autoload-project="<?=$arItem["NAME"]?>"><?=$arItem["NAME"]?></span>
		<?}?>
	</div>
<?}?>