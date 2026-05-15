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

<?if(count($arResult["GALLERY"]) > 0) {?>
	<div class="box_filter_slider">
		<div class="flexslider flexslider-init flexslider-direction-nav" id="slider">
			<ul class="slides">
				<?foreach($arResult["GALLERY"] as $arItem) {?>
					<li class="item">
						<a href="<?=$arItem["BIG"]["src"]?>" target="_blank" class="fancybox" data-fancybox-group="gallery_rental_catalog" title="<?=$arItem["DESCRIPTION"]?>">
							<img alt="" class="img-responsive" src="<?=$arItem["MEDIUM"]["src"]?>">								
						</a>
					</li>
				<?}?>
			</ul>
		</div>
		
		<div class="thmb_wrap">
			<div class="thmb flexslider unstyled" id="carousel">
				<ul class="slides">	
					<?foreach($arResult["GALLERY"] as $arItem) {?>
						<li class="blink">
							<img class="img-responsive inline" src="<?=$arItem["SMALL"]["src"]?>" />
						</li>
					<?}?>
				</ul>
			</div>
		</div>			
	</div>
<?}?>