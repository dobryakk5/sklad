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

#ELEMENTS#

<?if(count($arResult["SECTION"]["TISERS"]) > 0) {?>
	<div class="tisers-list">
		<div class="maxwidth-theme">
			<div class="row">
				<?foreach($arResult["SECTION"]["TISERS"] as $arTiser) {?>
					<div class="col-md-3 col-xs-12">
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
		</div>
	</div>	
<?}?>

<?
$this->__component->arResult["CACHED_TPL"] = @ob_get_contents();
ob_get_clean();
?>