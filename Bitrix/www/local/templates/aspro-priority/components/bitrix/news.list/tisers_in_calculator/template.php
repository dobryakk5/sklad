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
	<div class="tisers-list">
		<div class="maxwidth-theme">
			<div class="row">
				<?foreach($arResult["ITEMS"] as $arTiser) {?>
					<div class="col-md-3 col-xs-12">
						<div class="item">
							<div class="image">
								<img src="<?=$arTiser["PREVIEW_PICTURE"]["SRC"]?>" />
							</div>
							<div class="title">
								<span class="name"><?=$arTiser["NAME"]?></span>
								<a class="link" href="<?=$arTiser["PROPERTIES"]["LINK"]["VALUE"]?>"><?=$arTiser["PROPERTIES"]["LINK"]["DESCRIPTION"]?></a>
							</div>
						</div>
					</div>
				<?}?>
			</div>		
		</div>
	</div>	
<?}?>
