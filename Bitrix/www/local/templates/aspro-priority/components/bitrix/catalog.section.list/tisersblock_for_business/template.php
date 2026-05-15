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
$showBlock = false;
if((strlen($arResult["SECTION"]["UF_TISERS_TITLE"]) > 0) or (strlen($arResult["SECTION"]["UF_TISERS_TEXT"]) > 0)) {
	$showBlock = true;
}

$showGallery = false;
if(!empty($arResult["SECTION"]["GALLERY"])) {
	$showGallery = true;
}
?>

<?if($showBlock) {?>
	<div class="maxwidth-theme">
		<div class="shadow-box">
			<div class="for-business-info">
				<div class="row">
					<div class="<?=($showGallery)?"col-md-8":"col-md-12"?> col-xs-12">
						<?if(strlen($arResult["SECTION"]["UF_TISERS_TITLE"]) > 0) {?>
							<h2 class="style-h3"><?=$arResult["SECTION"]["UF_TISERS_TITLE"]?></h2>
						<?}?>
						<?if(strlen($arResult["SECTION"]["UF_TISERS_TEXT"]) > 0) {?>
							<div class="text">
								<?=$arResult["SECTION"]["~UF_TISERS_TEXT"]?>
							</div>
						<?}?>
						<?if(count($arResult["SECTION"]["TISERS"]) > 0) {?>
							<div class="row">
								<?
								if(count($arResult["SECTION"]["TISERS"]) <= 2) {
									$tz_class = "col-md-12";
								} else {
									$tz_class = "col-md-6";
								}
								?>
								<?foreach($arResult["SECTION"]["TISERS"] as $arTiser) {?>
									<div class="<?=$tz_class?> col-xs-12">
										<div class="item">
											<div class="image">
												<img src="<?=$arTiser["PICTURE"]?>" />
											</div>
											<div class="title">
												<span class="name"><?=$arTiser["NAME"]?></span>
												<a class="link" href="<?=$arTiser["LINK"]?>"><?=$arTiser["LINK_TEXT"]?></a>
											</div>
										</div>
									</div>
								<?}?>
							</div>
						<?}?>
					</div>
					<?if($showGallery) {?>
						<div class="col-md-4 col-xs-12">
							<div class="photogallery_slider detail">
								<div class="flexslider flexslider-init flexslider-direction-nav" id="slider">
									<ul class="slides">
										<?foreach($arResult["SECTION"]["GALLERY"] as $arPicture) {?>
											<li class="item">
												<a href="<?=$arPicture["BIG"]["RESIZE"]["src"]?>" target="_blank" class="fancybox" data-fancybox-group="gallery">
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
						</div>
					<?}?>
				</div>
			</div>
		</div>
	</div>
<?}?>