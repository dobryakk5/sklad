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
	<div class="cart-banner">
		<?foreach($arResult["ITEMS"] as $arItem) {?>
			<?
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			?>
			<div class="banner" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
				<?if(strlen($arItem["PROPERTIES"]["LINK"]["VALUE"]) > 0) {?>
					<a href="<?=$arItem["PROPERTIES"]["LINK"]["VALUE"]?>" target="<?=(strlen($arItem["PROPERTIES"]["TARGET_BLANK"]["VALUE"])>0)?"_blank":""?>">
				<?}?>				
				<img src="<?=$arItem["DETAIL_PICTURE"]["SRC"]?>" />				
				<?if(strlen($arItem["PROPERTIES"]["LINK"]["VALUE"]) > 0) {?>
					</a>
				<?}?>				
			</div>
		<?}?>
	</div>
<?}?>



