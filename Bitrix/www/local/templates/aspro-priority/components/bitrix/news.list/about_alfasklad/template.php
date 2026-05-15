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


<div class="about-alfasklad-block">
	<?foreach($arResult["ITEMS"] as $key=>$arItem) {?>
		<?
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
		?>
		<?
		$class = "";
		if(!empty($arItem["GALLERY"])) {
			if(strlen($arItem["PREVIEW_TEXT"]) > 0) {
				if($key%2 == 0) {
					$class = "pic-right";
				} else {
					$class = "pic-left";
				}
			} else {
				$class = "pic-center";
			}
		}
		?>
		<div class="item clearfix <?=$class?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
			<?if(!empty($arItem["GALLERY"])) {?>
				<div class="image">
					<div class="flexslider front hover inside" data-plugin-options='{"animation":"slide", "animationLoop": true, "maxItems": 1, "controlNav": true, "directionNav": false}'>
						<ul class="slides">
							<?foreach($arItem["GALLERY"] as $picture) {?>
								<li>
									<img alt="" class="img-responsive" src="<?=$picture["src"]?>">
								</li>
							<?}?>
						</ul>
					</div>
				</div>
			<?}?>
			<?if(strlen($arItem["PREVIEW_TEXT"]) > 0) {?>
				<div class="text">
					<?if(strlen($arItem["PROPERTIES"]["HIDE_NAME"]["VALUE"]) == 0) {?>
						<h2 class="style-h3"><?=$arItem["NAME"]?></h2>
					<?}?>
					<?=$arItem["PREVIEW_TEXT"]?>
				</div>
			<?}?>
		</div>
	<?}?>
</div>