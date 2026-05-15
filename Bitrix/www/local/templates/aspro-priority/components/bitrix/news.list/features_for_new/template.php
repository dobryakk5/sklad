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
		<div class="tabs">
			<ul class="nav nav-tabs font_upper_md">
				<?foreach($arResult["TABS"] as $arSection) {?>
                    <?if($arSection["IS_SELECTED"]=="Y"):?>
                        <li class="shadow border <?=($arSection["IS_SELECTED"]=="Y")?'active':''?>"><a href="<?=$arSection["SECTION_PAGE_URL"]?>"><?=$arSection["NAME"]?></a></li>
                    <?endif?>
				<?}?>
			</ul>
		</div>
	
		<div class="features-for">
			<div class="row">
				<?foreach($arResult["ITEMS"] as $arItem) {?>
					<?
					$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
					$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
					?>			
					<div class="col-md-4 col-xs-12">
						<div class="item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
							<div class="image">
								<img src="<?=$arItem["PROPERTIES"]["ICON"]["RESIZE"]["src"]?>" />
							</div>
							<div class="text"><?=$arItem["NAME"]?></div>							
							<a class="link scroll" href="#id<?=$arItem["ID"]?>"></a>							
						</div>
					</div>
				<?}?>
			</div>
		</div>
	</div>
<?}?>