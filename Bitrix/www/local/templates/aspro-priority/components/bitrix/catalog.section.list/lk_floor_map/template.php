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
ob_start();
?>


<?if(!empty($arResult["SECTION"]["MAP_FLOOR"])) {?>
	<div class="map-floor <?=($arParams["SHOW_MAP"] == "Y")?"":"hide"?>">
		<div class="map-floor-container">
			<div class="mapsvg">
				<div class="ajaxPreloader"></div>
				<svg viewBox="0 0 <?=$arResult["SECTION"]["MAP_FLOOR"]["PICTURE"]["WIDTH"]?> <?=$arResult["SECTION"]["MAP_FLOOR"]["PICTURE"]["HEIGHT"]?>">
					#BOXES#		
				</svg>
				<img src="<?=$arResult["SECTION"]["MAP_FLOOR"]["PICTURE"]["SRC"]?>" />
			</div>

			<div class="ajax_load_map_item"></div>
		</div>
	</div>
<?}?>


<?
$this->__component->arResult["CACHED_TPL"] = @ob_get_contents();
ob_get_clean();
?>