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



<div class="maxwidth-theme">
	<div class="shadow-box greyline">
		<div class="featuresblock-for-business">
			<h2 class="style-h3"><?=$arResult["NAME"]?></h2>
			<div class="row">
				<?if(count($arResult["FEATURES"]) > 0) {?>
					<div class="col-md-3 col-xs-12">
						<div class="features-for">
							<div class="items">
								<?foreach($arResult["FEATURES"] as $arItem) {?>
									<div class="item">
										<div class="image">
											<img class="lazy" data-src="<?=$arItem["ICON"]?>" alt="<?=$arItem["NAME"]?>" />
										</div>
										<div class="text">
											<?=$arItem["NAME"]?>
										</div>
										<?if(strlen($arItem["DETAIL_TEXT"]) > 0) {?>
											<a class="link" href="<?=$arItem["DETAIL_PAGE_URL"]?>"></a>
										<?}?>
									</div>
								<?}?>
							</div>
							<div class="button">
								<a class="btn btn-default" href="/features_for_business/">Посмотреть все возможности</a>
							</div>							
						</div>
					</div>
				<?}?>
				<div class="col-md-<?=(count($arResult["FEATURES"]) > 0)?"9":"12"?> col-xs-12 hidden-xs">
					<?if(strlen($arResult["PREVIEW_PICTURE"]["RESIZE"]["src"]) > 0) {?>
						<div class="features-pic thumbnail">
							<img class="lazy" data-src="<?=$arResult["PREVIEW_PICTURE"]["RESIZE"]["src"]?>" alt="АльфаСклад" />
						</div>					
					<?}?>
					<div class="featuresblock_form"></div>
				</div>				
			</div>
		</div>
	</div>
</div>