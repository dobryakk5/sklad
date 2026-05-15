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


<div class="tabs">
	<ul class="nav nav-tabs font_upper_md">
		<?foreach($arResult["SECTION"]["FLOORS"] as $arFloor) {?>
			<li class="shadow border <?=($arParams["FLOOR_CODE"]==$arFloor["CODE"])?"active":""?>"><a href="<?=$arFloor["URL"]?>"><?=$arFloor["NAME"]?></a></li>
		<?}?>
	</ul>
</div>


<?if(!empty($arResult["SECTION"]["MAP_FLOOR"])) {?>
	<div class="map-floor <?=($arParams["SHOW_MAP"] == "Y")?"":"hide"?>">
		<div class="map-floor-container">
			<div class="head-container">
				<div class="row">
					<div class="col-md-7 col-xs-12">
						<div class="title">
							Карта <?=$arResult["SECTION"]["MAP_FLOOR"]["FLOOR_NAME"]?> этажа: <?=$arResult["SECTION"]["NAME"]?>
						</div>
					</div>
					<div class="col-md-5 col-xs-12">
						<div class="status info">
							<?if($arResult["SECTION"]["MAP_FLOOR"]["OPENED_BOXES_COUNT"] > 0) {?>
								Свободно <?=$arResult["SECTION"]["MAP_FLOOR"]["OPENED_BOXES_SQUARE"]?> м<sup>2</sup>, <?=num_decline($arResult["SECTION"]["MAP_FLOOR"]["OPENED_BOXES_COUNT"], Array("бокс", "бокса", "боксов"));?>
							<?} else {?>
								Нет свободных боксов
							<?}?>
						</div>
						<?if($arResult["SECTION"]["MAP_FLOOR"]["OPENED_BOXES_COUNT"] > 0) {?>
							<div class="status opened filtered">
								Свободные боксы, подходящие под условия фильтра —
							</div>
							<div class="status opened">
								Свободные боксы, не подходящие под условия фильтра —
							</div>
						<?}?>
					</div>			
				</div>		
			</div>
			
			<div class="mapsvg">
				<div class="ajaxPreloader"></div>
				<svg viewBox="0 0 <?=$arResult["SECTION"]["MAP_FLOOR"]["PICTURE"]["WIDTH"]?> <?=$arResult["SECTION"]["MAP_FLOOR"]["PICTURE"]["HEIGHT"]?>">
					#BOXES#		
				</svg>
				<img src="<?=$arResult["SECTION"]["MAP_FLOOR"]["PICTURE"]["SRC"]?>" />
			</div>

			<div class="ajax_load_map_item"></div>
		</div>

		<div class="button">
			<span class="btn btn-default btn-transparent showHideMapFloor" data-status="hide">Показать схему боксов</span>
		</div>
	</div>
<?}?>


<?
$this->__component->arResult["CACHED_TPL"] = @ob_get_contents();
ob_get_clean();
?>