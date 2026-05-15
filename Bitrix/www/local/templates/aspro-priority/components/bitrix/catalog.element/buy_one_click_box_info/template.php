<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */
?>

<?
$fullBoxName = $arResult["NAME"]." [".$arResult["PROPERTIES"]["BOX_NUMBER"]["VALUE"]."], ".$arResult["SKLAD"]["NAME"].", ".$arResult["MIN_PRICE"]["PRINT_DISCOUNT_VALUE"]."/".$arResult["ITEM_MEASURE"]["TITLE"];
?>
<input type="hidden" name="FULL_BOX_NAME" value="<?=$fullBoxName?>" />

<div class="box_added_to_cart buy_one_click">
	<div class="basket_wrap">
		<div class="items_wrap">
			<div class="items">
				<div class="item">
					<div class="wrap clearfix">
						<div class="image">					
							<img class="img-responsive mCS_img_loaded" src="<?=$arResult["PREVIEW_PICTURE"]["RESIZE"]["src"]?>" alt="<?=$arResult["NAME"]?>" title="<?=$arResult["NAME"]?>">					
						</div>
						<div class="body-info">
							<div class="description">
								<div class="name">
									<?=$arResult["NAME"]?>
								</div>
								<div class="sklad">
									<?=$arResult["SKLAD"]["NAME"]?>
								</div>
								<div class="price">
									<?=$arResult["MIN_PRICE"]["PRINT_DISCOUNT_VALUE"]?>/<?=$arResult["ITEM_MEASURE"]["TITLE"]?>
								</div>						
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>				
	</div>
</div>
