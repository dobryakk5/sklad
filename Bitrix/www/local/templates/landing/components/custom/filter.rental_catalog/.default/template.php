<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?
$this->setFrameMode(true);
ob_start();
?>

<?
$this->addExternalCss("https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.0/css/ion.rangeSlider.min.css");
$this->addExternalJS("https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.0/js/ion.rangeSlider.min.js");
?>

<?
if(strlen($arParams["PROP_SIZE"]) == 0) {
	$arParams["PROP_SIZE"] = "SQUARE";
}
?>

<div class="box_filter">
	<input class="sklad_code" type="hidden" value="<?=$arParams["SKLAD_CODE"]?>" />
	<input class="floor_code" type="hidden" value="<?=$arParams["FLOOR_CODE"]?>" />
	<input class="boxes_list" type="hidden" value="<?=$arParams["BOXES_LIST"]?>" />
	<div class="filter_block swipeignore">
		<div class="row">
			<div class="col-md-6 col-xs-12 size-box">
				<div class="title">
					Размер бокса
					<?if($arParams["SIMPLE_VIEW"] != "Y") {?>
						<select class="PROP_SIZE">
							<option value="SQUARE" <?=($arParams["PROP_SIZE"]=="SQUARE")?"selected":""?>>м&sup2;</option>
							<option value="VOLUME" <?=($arParams["PROP_SIZE"]=="VOLUME")?"selected":""?>>м&sup3;</option>
						</select>
					<?}?>
				</div>
				<div class="range">
					<input type="text" class="range_<?=$arParams["PROP_SIZE"]?>" name="range_<?=$arParams["PROP_SIZE"]?>" value="" />
				</div>
			</div>
			<div class="col-md-6 col-xs-12">
				<div class="title">Планируемый срок аренды</div>
				<div class="range">
					<input type="text" class="range_months" name="range_months" value="" />
				</div>						
			</div>					
		</div>
	</div>
<?
//echo '<pre>';
//print_r($arResult['SECTION_UF']['~UF_FILTER_ACTION']);
//echo '</pre>';
if ($arParams['DISCOUNT_ACTIVE'] == "Y"): ?>
	<div class="sale_block">
		<div class="row">
			<div class="col-md-6 col-sm-12">
				<div class="sale_title"><?= $arResult['SECTION_UF']['~UF_FILTER_ACTION'] ?></div>
			</div>
			<div class="col-md-6 col-sm-12">
				<div class="sale_buttons">
<? foreach ($arParams['DISCOUNT_MONTHS'] as $discountKey => $discountMonth): 
if (empty($discountMonth)) {
	continue;
}
?>
					<div class="sale_btn">
						<span class="icon-text s35 grey" data-from="<?= $discountMonth?>" data-to="36">
							<span class="styled_red">&nbsp;<?= $arParams['DISCOUNT_PERCENT'][$discountKey]?>%</span> - <?= $discountMonth?> мес.
						</span>
					</div>
<? endforeach; ?>
				</div>
			</div>			
		</div>
	</div>
<? endif; ?>
	<div class="start_price">
		Стоимость за данный размер бокса на этом складе: <span class="price">#SHOW_MIN_PRICE#</span>
	</div>
	<div class="buttons">
		<div class="row">
			<?/*
			<div class="col-md-6 col-xs-12">
				<?if($arParams["SIMPLE_VIEW"] == "Y") {?>
					<a class="btn btn-default btn-transparent simple_view_url" data-href="/rental_catalog/<?=$arParams["SKLAD_CODE"]?>/" href="/rental_catalog/<?=$arParams["SKLAD_CODE"]?>/">Перейти к выбору бокса</a>
				<?} else {?>
					<a class="btn btn-default btn-transparent" href="#">Выберите наилучшее расположение бокса</a> 
				<?}?>
			</div>
			<div class="col-md-1 col-xs-12">
				<div class="text-center ili">или</div>
			</div>	
			<div class="col-md-5 col-xs-12">
			*/?>
			
			<div class="col-md-6 col-xs-12">
				<?if($arParams["SIMPLE_VIEW"] == "Y") {?>
					<a class="btn btn-default btn-transparent simple_view_url" data-href="/rental_catalog/<?=$arParams["SKLAD_CODE"]?>/" href="/rental_catalog/<?=$arParams["SKLAD_CODE"]?>/">Перейти к выбору бокса</a>
				<?} else {?>
					<a class="btn btn-default btn-transparent scroll" href="#formManagerOrder_block">Арендуйте бокс через менеджера</a>
				<?}?>
			</div>
		</div>
	</div>
	<div class="buttons_hidden">
		<br><br>
		<a class="btn btn-default btn-transparent scroll" href="#formManagerOrder_block">Арендуйте бокс через менеджера</a>
	</div>
</div>

<script>
BX.message({
	PROP_SIZE: '<?=$arParams["PROP_SIZE"]?>',
	SQUARE_TO: '<?=$arResult["SQUARE_TO"]?>',
	VOLUME_TO: '<?=$arResult["VOLUME_TO"]?>'
});
</script>
<?
$this->__component->arResult["CACHED_TPL"] = @ob_get_contents();
ob_get_clean();
?>