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


<?if(count($arResult["SECTIONS"]) > 0) {?>
	<div class="row">
		<div class="col-md-12 col-xs-12">
			<div class="photogallery_list_sections">
							
				<div class="item-views within services-items type_5">
					<div class="items flexbox">
						<?foreach($arResult["SECTIONS"] as $arItem) {?>
							<?
							$arSectionButtons = CIBlock::GetPanelButtons($arItem['IBLOCK_ID'], 0, $arItem['ID'], array('SESSID' => false, 'CATALOG' => true));
							$this->AddEditAction($arItem['ID'], $arSectionButtons['edit']['edit_section']['ACTION_URL'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'SECTION_EDIT'));
							$this->AddDeleteAction($arItem['ID'], $arSectionButtons['edit']['delete_section']['ACTION_URL'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'SECTION_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
							?>
							<div class="item shadow border" id="<?=$this->GetEditAreaId($arItem['ID'])?>">
								<div class="wrap">									
									<div class="image">
										<div class="wrap"><img src="<?=$arItem["PICTURE"]["RESIZE"]["src"]?>" title="<?=$arItem["NAME"]?>" class="img-responsive" /></div>
									</div>									
									
									<div class="body-info">										
										<div class="title"><a class="dark-color" href="<?=$arItem["SECTION_PAGE_URL"]?>"><?=$arItem["NAME"]?></a></div>										
										<div class="count_elements font_upper"><?=$arItem["PHOTOGALLERY_COUNT"]?> фотографий</div>
									</div>
									<a href="<?=$arItem["SECTION_PAGE_URL"]?>"></a>
								</div>
							</div>
						<?}?>
					</div>        
				</div>				
				
			</div>
		</div>
		<?/*
		<div class="col-md-3 col-xs-12">
			#SKLAD_LIST#
		</div>
		*/?>
	</div>
<?} else {?>
	Складов не найдено
<?}?>


<?
$this->__component->arResult["CACHED_TPL"] = @ob_get_contents();
ob_get_clean();
?>