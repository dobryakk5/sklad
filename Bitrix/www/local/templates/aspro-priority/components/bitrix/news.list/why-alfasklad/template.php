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
	<div class="maxwidth-theme">
		<div class="why-alfasklad">
			<h3>АльфаСклад это:</h3>
			<div class="row">
				<?foreach($arResult["ITEMS"] as $arItem) {?>
					<?
					$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
					$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
					?>			
					<div class="col-md-6 col-xs-12">
						<div class="item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
							<div class="image">
								<img src="<?=$arItem["PROPERTIES"]["ICON"]["RESIZE"]["src"]?>" />
							</div>
							<div class="text"><?=$arItem["NAME"]?></div>
							<?if(strlen($arItem["DETAIL_TEXT"]) > 0) {?>
								<a class="link" href="<?=$arItem["DETAIL_PAGE_URL"]?>"></a>
							<?}?>
						</div>
					</div>
				<?}?>
			</div>
		</div>
	</div>
<?}?>