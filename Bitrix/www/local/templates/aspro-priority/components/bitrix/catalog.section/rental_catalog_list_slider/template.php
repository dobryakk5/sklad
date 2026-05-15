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

<?if($arParams["AJAX_LOAD"] != "Y") {?>
<div class="rental_catalog_slider">
	<div class="ajaxPreloader"></div>
	<div class="ajax_rentalCatalogListSlider">
<?}?>
		
		<div class="box_filter_slider">
			<div class="flexslider flexslider-init flexslider-direction-nav" id="slider">
				<ul class="slides">
					<?if(strlen($arParams["SIZE_FROM"]) > 0) {?>
						<li class="item">
							<a href="<?=$arResult["GALLERY_SQUARE_PICTURE"]["BIG"]?>" target="_blank" class="fancybox" data-fancybox-group="gallery_rental_catalog" title="">
								<img alt="" class="img-responsive" src="<?=$arResult["GALLERY_SQUARE_PICTURE"]["MEDIUM"]?>">								
							</a>
						</li>						
					<?}?>
					<?foreach($arResult["ITEMS"] as $key=>$arItem) {?>
						<?if(!empty($arItem["GALLERY"])) {?>
							<?foreach($arItem["GALLERY"] as $arPicture) {?>
								<li class="item">
									<a href="<?=$arPicture["BIG"]["src"]?>" target="_blank" class="fancybox" data-fancybox-group="gallery_rental_catalog" title="<?=$arPicture["DESCRIPTION"]?>">
										<img alt="" class="img-responsive" src="<?=$arPicture["MEDIUM"]["src"]?>">								
									</a>
								</li>
							<?}?>
						<?}?>
					<?}?>
				</ul>
			</div>
			
			<div class="thmb_wrap">
				<div class="thmb flexslider unstyled" id="carousel">
					<ul class="slides">
						<?if(strlen($arParams["SIZE_FROM"]) > 0) {?>
							<li class="blink">
								<img class="img-responsive inline" src="<?=$arResult["GALLERY_SQUARE_PICTURE"]["SMALL"]?>" />
							</li>									
						<?}?>					
						<?foreach($arResult["ITEMS"] as $key=>$arItem) {?>
							<?if(!empty($arItem["GALLERY"])) {?>
								<?foreach($arItem["GALLERY"] as $arPicture) {?>
									<li class="blink">
										<img class="img-responsive inline" src="<?=$arPicture["SMALL"]["src"]?>" />
									</li>						
								<?}?>
							<?}?>
						<?}?>
					</ul>
				</div>
			</div>			
		</div>

<?if($arParams["AJAX_LOAD"] != "Y") {?>
	</div>
</div>	
<?}?>