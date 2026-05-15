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
			<h2 class="style-h3">Возможности и преимущества АльфаСклад</h2>
			<div class="row">
				<?if(count($arResult["SECTION"]["FEATURES"]) > 0) {?>
					<div class="col-md-3 col-xs-12">
						<div class="features-for">
							<div class="items">
								<?foreach($arResult["SECTION"]["FEATURES"] as $arItem) {?>
									<div class="item">
										<div class="image">
											<img src="<?=$arItem["ICON"]?>" />
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
								<?
								if($arParams["SECTION_CODE"] == "storage") {
									$link = "/features_for_personal/";
								} else {
									$link = "/features_for_business/";
								}
								?>
								<a class="btn btn-default" href="<?=$link?>">Посмотреть все возможности</a>
							</div>							
						</div>
					</div>
				<?}?>
				<div class="col-md-<?=(count($arResult["SECTION"]["FEATURES"]) > 0)?"9":"12"?> col-xs-12">
					<?if(strlen($arResult["SECTION"]["FEATURES_PIC"]["src"]) > 0) {?>
						<div class="features-pic thumbnail">
							<img src="<?=$arResult["SECTION"]["FEATURES_PIC"]["src"]?>" />
						</div>
						<div class="featuresblock_form"></div>					
					<?}?>
				</div>				
			</div>
		</div>
	</div>
</div>