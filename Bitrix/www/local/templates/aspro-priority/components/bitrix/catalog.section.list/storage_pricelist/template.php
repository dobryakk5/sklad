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


<?if(count($arResult["SECTIONS"]) > 0) {?>
	<div class="storage_pricelist b-layout">
		<div class="cell large-12 bottom-red-border">
			<p class="b-price-text">
				Стоимость указана за месяц, включая НДС
			</p>
			<p class="b-price-text b-price-text--small">
				Что входит в стоимость:
			</p>
			<p class="cell large-12">
				<span class="b-price-stuff-item">Страховка</span> <span class="b-price-stuff-item">Доступ 24/7</span> <span class="b-price-stuff-item">Охрана</span> <span class="b-price-stuff-item">Электричество</span> <span class="b-price-stuff-item">Быстрый Интернет</span> <span class="b-price-stuff-item">Тележки</span>
			</p>
		</div>	
		<div class="row">
			<?foreach($arResult["SECTIONS"] as $key=>$arItem) {?>
				<?
				if(($key%2 == 0) and ($key != 0)) {
					?>
					</div><div class="row">
					<?
				}
				?>
				<div class="col-md-6 col-xs-12">
					<div class="item">
						<p class="b-price-table-title bottom--fat-red-border">
							<?//<b>ЗАО</b><br>?>
							Цена Складских помещений <b><?=$arItem["NAME"]?></b>
						</p>
						<?if(!empty($arItem["CELL"]["ITEMS"])) {?>
							<?
							//проверяем, есть ли товары со скидкой
							$isSaleItem = false;
							foreach($arItem["CELL"]["ITEMS"] as $arItemPrice) {
								if(strlen($arItemPrice["DISCOUNT_PRICE"]) > 0) {
									$isSaleItem = true;
									break;
								}
							}
							?>
							<table class="b-price <?=($isSaleItem)?'sale-col':''?>">
								<tbody>									
									<tr>
										<th>
											Камера хранения, ячейка
										</th>
										<th>
											Цена<br>(руб. в месяц)
										</th>
										<?if($isSaleItem) {?>
											<th>
												Цена со скидкой<br>(руб. в месяц)
											</th>										
										<?}?>
									</tr>
									<?foreach($arItem["CELL"]["ITEMS"] as $arItemPrice) {?>	
										<tr>
											<td>
												Ячейка <?=$arItemPrice["SQUARE"]?>м<sup>3</sup>
											</td>
											<td>
												<?=number_format($arItemPrice["PRICE"], 0, '', ' ')?>
											</td>
											<?if($isSaleItem) {?>
												<td>
													<?=number_format($arItemPrice["DISCOUNT_PRICE"], 0, '', ' ')?>
												</td>										
											<?}?>										
										</tr>
									<?}?>
								</tbody>
							</table>
						<?}?>
						<?if(!empty($arItem["BOX"]["ITEMS"])) {?>
							<?
							//проверяем, есть ли товары со скидкой
							$isSaleItem = false;
							foreach($arItem["BOX"]["ITEMS"] as $arItemPrice) {
								if(strlen($arItemPrice["DISCOUNT_PRICE"]) > 0) {
									$isSaleItem = true;
									break;
								}
							}
							?>						
							<table class="b-price <?=($isSaleItem)?'sale-col':''?>">
								<tbody>									
									<tr>
										<th>
											Высокий бокс, высота 3 м
										</th>
										<th>
											Цена<br>(руб. в месяц)
										</th>
										<?if($isSaleItem) {?>
											<th>
												Цена со скидкой<br>(руб. в месяц)
											</th>										
										<?}?>										
									</tr>
									<?foreach($arItem["BOX"]["ITEMS"] as $arItemPrice) {?>	
										<tr>
											<td>
												<?=$arItemPrice["SQUARE"]?> (м<sup>2</sup>)
											</td>
											<td>
												<?=number_format($arItemPrice["PRICE"], 0, '', ' ')?>
											</td>
											<?if($isSaleItem) {?>
												<td>
													<?=number_format($arItemPrice["DISCOUNT_PRICE"], 0, '', ' ')?>
												</td>										
											<?}?>											
										</tr>
									<?}?>
								</tbody>
							</table>
						<?}?>
						<?if((empty($arItem["CELL"]["ITEMS"])) and (empty($arItem["BOX"]["ITEMS"]))) {?>
							<p>Свободных боксов не найдено</p>
						<?}?>
					</div>
				</div>
			<?}?>
		</div>
	</div>
	
	<div class="cell large-12 b-price-phone b-price-phone--center">
		 Для брони свяжитесь с нашим менеджером: <a href="tel:+74951544098">+7 (495) 154-40-98</a>
	</div>
	<div class="cell large-12 b-price-phone b-price-phone--center b-price-phone--margin-30">
		 Или оставьте заявку в форме ниже:
	</div>	
<?}?>