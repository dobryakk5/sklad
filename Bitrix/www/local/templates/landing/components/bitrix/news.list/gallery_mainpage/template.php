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

<?if(count($arResult["ITEMS"]) > 0) {?>
	<div class="gallery_mainpage">	
		<div class="flexslider flexslider-init flexslider-direction-nav" id="carousel">
			<ul class="slides">
				<?foreach($arResult["ITEMS"] as $arItem) {?>
					<li class="item">
						<a href="<?=$arItem["DETAIL_PICTURE"]["BIG"]["RESIZE"]["src"]?>" target="_blank" class="fancybox" data-fancybox-group="gallery1">
							<img alt="<?=$arItem["DETAIL_PICTURE"]["DESCRIPTION"]?>" class="img-responsive lazy" src="<?=$templateFolder?>/images/empty.jpg" data-src="<?=$arItem["DETAIL_PICTURE"]["SMALL"]["RESIZE"]["src"]?>">
						</a>
					</li>
				<?}?>
			</ul>
		</div>	
		
		<div class="button text-center">
			<a class="btn btn-default btn-xs btn-transparent" href="/about/fotogalereya-skladov/">Перейти в фотогалерею</a>
		</div>
	</div>
<?}?>