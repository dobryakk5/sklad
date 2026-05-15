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
	<?if($arParams["SMALL_THMB"] != "Y") {?>
		<div class="ajaxPreloader"></div>
	<?}?>
	<div class="ajax_rentalCatalogListSlider">
<?}?>
		
		<div class="box_filter_slider">
			<div class="flexslider flexslider-init flexslider-direction-nav" id="slider">
				<ul class="slides">
					<?if(strlen($arParams["SIZE_FROM"]) > 0) {?>
						<?if(file_exists($_SERVER["DOCUMENT_ROOT"].$arResult["GALLERY_SQUARE_PICTURE"]["BIG"])) {?>
							<li class="item">
								<a href="<?=$arResult["GALLERY_SQUARE_PICTURE"]["BIG"]?>" target="_blank" class="fancybox" data-fancybox-group="gallery_rental_catalog" title="">
									<img width="347" height="248" alt="" class="img-responsive" src="<?=$arResult["GALLERY_SQUARE_PICTURE"]["MEDIUM"]?>">								
								</a>
							</li>
						<?}?>
					<?}?>					
					<?if(!empty($arResult["GALLERY"])) {?>
						<?foreach($arResult["GALLERY"] as $arPicture) {?>
							<li class="item">
								<a href="<?=$arPicture["BIG"]["src"]?>" target="_blank" class="fancybox" data-fancybox-group="gallery_rental_catalog" title="<?=$arPicture["DESCRIPTION"]?>">
									<img width="347" height="248" alt="" class="img-responsive" src="<?=$arPicture["MEDIUM"]["src"]?>">								
								</a>
							</li>
						<?}?>
					<?}?>					
				</ul>
			</div>

			<?
			$thmb_width = 100;
			if($arParams["SMALL_THMB"] == "Y") {
				$thmb_width = 86;
			}
			?>

			<div class="thmb_wrap">
				<div class="thmb flexslider unstyled" id="carousel" data-thmb-width="<?=$thmb_width?>">
					<ul class="slides">
						<?if(strlen($arParams["SIZE_FROM"]) > 0) {?>
							<?if(file_exists($_SERVER["DOCUMENT_ROOT"].$arResult["GALLERY_SQUARE_PICTURE"]["BIG"])) {?>
								<li class="blink">
									<img width="86" height="86" class="img-responsive inline" src="<?=$arResult["GALLERY_SQUARE_PICTURE"]["SMALL"]?>" />
								</li>
							<?}?>
						<?}?>										
						<?if(!empty($arResult["GALLERY"])) {?>
							<?foreach($arResult["GALLERY"] as $arPicture) {?>
								<li class="blink">
									<img width="86" height="86" class="img-responsive inline" src="<?=$arPicture["SMALL"]["src"]?>" />
								</li>						
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