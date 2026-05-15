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

<div class="row">
	<div class="col-md-8 col-xs-12">
		<?if(count($arResult["SECTION"]["GALLERY"]) > 0) {?>
			<div class="photogallery_slider detail">
				<div class="flexslider flexslider-init flexslider-direction-nav" id="slider">
					<ul class="slides">
						<?foreach($arResult["SECTION"]["GALLERY"] as $arPicture) {?>
							<li class="item">
								<a href="<?=$arPicture["BIG"]["RESIZE"]["src"]?>" target="_blank" class="fancybox" data-fancybox-group="gallery" title="<?=$arPicture["DESCRIPTION"]?>">
									<img alt="" class="img-responsive" src="<?=$arPicture["MEDIUM"]["RESIZE"]["src"]?>">
									<span class="zoom">
										<?=CPriority::showIconSvg(SITE_TEMPLATE_PATH.'/images/include_svg/zoom.svg');?>
									</span>									
								</a>
							</li>
						<?}?>
					</ul>
				</div>
				<?if(count($arResult["SECTION"]["GALLERY"]) > 1) {?>
					<div class="thmb_wrap">
						<div class="thmb flexslider unstyled" id="carousel">
							<ul class="slides">
								<?foreach($arResult["SECTION"]["GALLERY"] as $arPicture) {?>
									<li class="blink">
										<img class="img-responsive inline" src="<?=$arPicture["SMALL"]["RESIZE"]["src"]?>" />
									</li>
								<?}?>
							</ul>
						</div>
					</div>
				<?}?>
			</div>
		<?} else {?>
			<div class="alert alert-warning">Фотографии не найдены</div>
		<?}?>
	</div>
	<div class="col-md-4 col-xs-12">
		<div class="photogallery_contacts_block">
			<?if(strlen($arResult["SECTION"]["UF_ADDRESS"]) > 0) {?>
				<p class="address"><?=$arResult["SECTION"]["UF_ADDRESS"]?></p>
			<?}?>
			<?if((strlen($arResult["SECTION"]["UF_RECEPTION"]) > 0) or (strlen($arResult["SECTION"]["UF_DOSTUP_TIME"]) > 0) or (strlen($arResult["SECTION"]["UF_PHONE"]) > 0)) {?>
				<div class="work-time-block">
					<p class="work-time-label">Режим работы:</p>
					<?if(strlen($arResult["SECTION"]["UF_RECEPTION"]) > 0) {?>
						<p class="work-time">Ресепшн: <?=$arResult["SECTION"]["UF_RECEPTION"]?></p>
					<?}?>
					<?if(strlen($arResult["SECTION"]["UF_DOSTUP_TIME"]) > 0) {?>
						<p class="work-time">Доступ на склад: <?=$arResult["SECTION"]["UF_DOSTUP_TIME"]?></p>
					<?}?>	
					<?if(strlen($arResult["SECTION"]["UF_PHONE"]) > 0) {?>
						<p class="work-time">Телефон: <?=$arResult["SECTION"]["UF_PHONE"]?></p>
					<?}?>	
				</div>
			<?}?>
			
			<?if(strlen($arResult["SECTION"]["UF_MAP"]) > 0) {?>
				#MAP#
			<?}?>

			<div class="buttons">
				<a class="btn btn-default" href="/rental_catalog/<?=$arResult["SECTION"]["CODE"]?>/"><span>Перейти на страницу склада</span></a>
			</div>	
		</div>
	</div>	
</div>


<?
$this->__component->arResult["CACHED_TPL"] = @ob_get_contents();
ob_get_clean();
?>