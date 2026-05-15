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

<?if(count($arResult["ITEMS"]) > 0) {?>
	<div class="myboxes_list">
		<?foreach($arResult["ITEMS"] as $arItem) {?>
			<div class="box-item">
				<div class="row">
					<?
					$isPic = false;
					if(strlen($arItem["BOX"]["PREVIEW_PICTURE"]) > 0) {
						$isPic = true;
					}
					?>
					<div class="col-md-3 col-xs-12 <?=($isPic?"":"hidden")?>">
						<div class="picture">
							<img src="<?=$arItem["BOX"]["PREVIEW_PICTURE_SRC"]?>" />
						</div>
					</div>
					<div class="<?=($isPic?"col-md-6":"col-md-12")?> col-xs-12">
						<div class="box_name"><?=$arItem["BOX"]["NAME"]?></div>
						<div class="box_props">
							<div class="row">
								<div class="col-md-12 col-xs-12">
									<b><?=$arItem["BOX"]["SKLAD"]["NAME"]?></b>
								</div>
								<div class="col-md-6 col-xs-12">
									<div class="name">Номер бокса:</div>
									<div class="value"><?=$arItem["BOX"]["PROPERTY_BOX_NUMBER_VALUE"]?></div>
								</div>
								<div class="col-md-6 col-xs-12">
									<div class="name">Площадь:</div>
									<div class="value"><?=$arItem["BOX"]["PROPERTY_SQUARE_VALUE"]?> м<sup>2</sup></div>
								</div>
								<div class="col-md-6 col-xs-12">
									<div class="name">Этаж:</div>
									<div class="value"><?=$arItem["BOX"]["PROPERTY_FLOOR_VALUE"]?></div>
								</div>
								<div class="col-md-6 col-xs-12">
									<div class="name">Объем:</div>
									<div class="value"><?=$arItem["BOX"]["PROPERTY_VOLUME_VALUE"]?> м<sup>3</sup></div>
								</div>								
							</div>
						</div>
						<?if($arParams["IS_CANCELED"] != "Y") {?>
							<div class="buttons">
								<div class="row">
									
									<div class="col-md-6 col-xs-12" style="display:none;">
										<a class="btn btn-default show_box_on_map" href="javascript:void(0);" data-toggle="modal" data-target="#modalFloorMap_<?=$arItem["BOX"]["ID"]?>">Посмотреть на карте</a>
									</div>
									
									<div class="col-md-6 col-xs-12">
										<a class="btn btn-default" href="/cabinet/myboxes/current/inventory-<?=$arItem["BOX"]["ID"]?>/">Опись вещей</a>
									</div>
									<?if(strlen($arItem["BOX"]["PROPERTY_VIDEO_LINK_VALUE"]) > 0) {?>
										<?if($arItem["BOX"]["PROPERTY_VIDEO_LINK_ACTIVE_VALUE"] == "Y") {?>
											<div class="col-md-6 col-xs-12">
												<a class="btn btn-default" href="<?=$arItem["BOX"]["PROPERTY_VIDEO_LINK_VALUE"]?>" target="_blank" rel="nofollow">Видеонаблюдение</a>
											</div>								
										<?}?>
									<?}?>									
								</div>
							</div>
							<div class="floor_map_container">
								#MODAL_FLOOR_MAP_<?=$arItem["BOX"]["ID"]?>#
							</div>
						<?}?>
						<div class="contracts_list lk_table">
							<table class="table">
								<tbody>								
									<tr>
										<td>Номер договора</td>
										<td><?=$arItem["PROPERTIES"]["NUMBER"]["VALUE"]?></td>
									</tr>
									<tr>									
										<td>Оплачено до</td>
										<td><?=$arItem["PROPERTIES"]["PAID_DATE_TO"]["VALUE"]?></td>
									</tr>
									<tr>
										<td>Баланс по договору</td>
										<td><?=FormatCurrency(intval($arItem["PROPERTIES"]["BALANCE"]["VALUE"])*-1, "RUB")?></td>						
									</tr>									
								</tbody>
							</table>
						</div>

						<?if( is_countable($arItem["INVOICES"]) && count($arItem["INVOICES"]) > 0) {?>
							<div class="invoices_list">
								<div class="row">
									<div class="col-md-6 col-xs-12">
										<div class="title">Неоплаченные счета:</div>
									</div>
									<?foreach($arItem["INVOICES"] as $arInvoice) {?>
										<div class="col-md-4 col-xs-12">
											<div class="invoice_link"><a href="/cabinet/docs/invoices/<?=$arInvoice["ID"]?>/"><?=$arInvoice["MONTH"]?></a></div>
										</div>	
									<?}?>
								</div>
							</div>
						<?}?>
					</div>
					<div class="col-md-3 col-xs-12">
						<div class="services_buttons">
							<div class="title">Дополнительные услуги</div>								
							#SERVICES_BUTTONS#						
						</div>
					</div>					
				</div>
			</div>
		<?}?>
        <?if($arParams['DISPLAY_BOTTOM_PAGER']) {?>
            <div class="pagination_nav">
                <?=$arResult['NAV_STRING']?>
            </div>
        <?}?>		
	</div>
<?} else {?>
	<p>Боксы не найдены</p>
<?}?>

<?
$this->__component->arResult["CACHED_TPL"] = @ob_get_contents();
ob_get_clean();
?>