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


<div class="features-for-list">
	<?
	$cnt_for_grey = 0;
	?>
	<?foreach($arResult["ITEMS"] as $key=>$arItem) {?>
		<?
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
		?>
		<?
		if($key%2 == 0) {
			$class = "pic-left";
		} else {
			$class = "pic-right";
		}
		
		$cnt_for_grey++;
		if($cnt_for_grey > 4) {
			$cnt_for_grey = 1;
		}
		$classGray = "";
		if(($cnt_for_grey == 1) or ($cnt_for_grey == 2)) {
			$classGray = "greyline";
		}
		
		?>
		<div class="item_block <?=$classGray?>" id="id<?=$arItem["ID"]?>">
			<div class="maxwidth-theme">
				<div class="item clearfix <?=$class?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">				
					<div class="image">								
						<img alt="" class="img-responsive" src="<?=$arItem["PREVIEW_PICTURE"]["RESIZE"]["src"]?>">				
					</div>
					<div class="text">
						<h2 class="style-h3"><?=$arItem["NAME"]?></h2>
						<?=$arItem["PREVIEW_TEXT"]?>
						
						<?if(strlen($arItem["DETAIL_TEXT"]) > 0) {?>
							<div class="button_block">
								<a class="callback-block btn btn-default btn-transparent" href="<?=$arItem["DETAIL_PAGE_URL"]?>">Подробнее</a>
							</div>
						<?}?>
					</div>				
				</div>
			</div>
		</div>
	<?}?>
</div>