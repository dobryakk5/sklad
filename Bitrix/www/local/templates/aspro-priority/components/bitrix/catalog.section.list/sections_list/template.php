<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<?use \Bitrix\Main\Localization\Loc;?>
<?//pr($arResult)?>
<?if($arResult["SECTIONS"]){?>
<div class="item-views within services-items type_5">
	<div class="catalog_opener mobile"><span>Продукция<span class="arrow"></span></span></div>
	<div class="items flexbox">
		<?foreach( $arResult["SECTIONS"] as $arItems ){
			$this->AddEditAction($arItems['ID'], $arItems['EDIT_LINK'], CIBlock::GetArrayByID($arItems["IBLOCK_ID"], "SECTION_EDIT"));
			$this->AddDeleteAction($arItems['ID'], $arItems['DELETE_LINK'], CIBlock::GetArrayByID($arItems["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_SECTION_DELETE_CONFIRM')));

			$bImage = '';
			$arSectionImage = '';
			$imageSectionSrc = '';

			if($arItems["PICTURE"]["SRC"]){
				$bImage = !empty($arItems["PICTURE"]["SRC"]);
				$imageSectionSrc = $arItems["PICTURE"]["SRC"];
			}
		?>

			<div class="item shadow border<?=($imageSectionSrc ? '' : ' wti')?> <?=$arParams['IMAGE_CATALOG_POSITION'];?> <?=($arParams['CURRENT'] == $arItems['ID']) ? 'active' : ''?>" id="<?=$this->GetEditAreaId($arItems['ID'])?>">
				<div class="wrap">

					<?if($imageSectionSrc):?>
						<div class="image">
							<div class="wrap"><img src="<?=$imageSectionSrc?>" alt="<?=( $arItems['PICTURE']['ALT'] ? $arItems['PICTURE']['ALT'] : $arItems['NAME']);?>" title="<?=( $arItems['PICTURE']['TITLE'] ? $arItems['PICTURE']['TITLE'] : $arItems['NAME']);?>" class="img-responsive" /></div>
						</div>
					<?endif;?>

					<div class="body-info">
						<?// section name?>
						<div class="title"><a class="dark-color" href="<?=$arItems['SECTION_PAGE_URL']?>"><?=$arItems['NAME']?></a></div>
						<div class="count_elements font_upper"><?=CPriority::Vail($arItems['ELEMENT_CNT'], array(Loc::getMessage('COUNT_ELEMENTS_TITLE'), Loc::getMessage('COUNT_ELEMENTS_TITLE_2'), Loc::getMessage('COUNT_ELEMENTS_TITLE_3')));?></div>
					</div>
					<a href="<?=$arItems['SECTION_PAGE_URL']?>"></a>
				</div>
			</div>



		<?}?>
	</div>
</div>
<?}?>