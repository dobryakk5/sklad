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

<?
$arItem = $arResult["ITEMS"][0];

if(strlen($arItem["MIN_PRICE"]["PRINT_DISCOUNT_VALUE"]) > 0) {
	?>
	от <?=$arItem["MIN_PRICE"]["PRINT_DISCOUNT_VALUE"]?>/<?=$arItem["ITEM_MEASURE"]["TITLE"]?>
	<script>
	$(document).ready(function() {
		$('.box_filter .start_price').show();
		$('.box_filter .buttons').show();
		$('.box_filter .buttons_hidden').hide();
	});	
	</script>	
	<?
} else {
	echo " - ";
	?>
	<script>
	$(document).ready(function() {
		$('.box_filter .start_price').hide();
		$('.box_filter .buttons').hide();
		$('.box_filter .buttons_hidden').show();
	});	
	</script>
	<?
}
?>
