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
	<div class="skladlist_leftcol">
		<h3>Посмотреть склады на Яндекс.Картах</h3>
		<div class="items">
			<?foreach($arResult["SECTIONS"] as $arItem) {?>
				<div class="item">
					<div class="row">
						<div class="col-md-9 col-xs-8">
							<div class="name"><span class="link dark-color" data-event="jqm" data-param-id="map" data-name="map" data-param-type="map" data-iblock_id="40" data-element-id="<?=$arItem["ID"]?>" ><?=$arItem["NAME"]?></span></div>
							<?/*if($arItem["UF_YMAP_RATING"] > 0) {?>
								<div class="rt">
									<?
									$ratingValue = round($arItem["UF_YMAP_RATING"]);
									?>
									<div class="rating_wrap small">
										<div class="rating current_<?=$ratingValue?>">
											<span class="stars_current"></span>
										</div>
										<div class="rt_val"><?=$arItem["UF_YMAP_RATING"]?></div>
										<div class="votes_val"><?=num_decline($arItem["UF_YMAP_VOTES"], Array("оценка", "оценки", "оценок"))?></div>
									</div>
								</div>
							<?}*/?>
							<div class="address"><?=$arItem["UF_ADDRESS"]?></div>
							<div class="dostup-time"><?=$arItem["UF_DOSTUP_TIME"]?></div>
						</div>
						<div class="col-md-3 col-xs-4">
							<div class="image">
								<img src="<?=$arItem["PICTURE"]["RESIZE_SMALL"]["src"]?>" />
							</div>
						</div>
					</div>
				</div>
			<?}?>
		</div>
	</div>
<?}?>