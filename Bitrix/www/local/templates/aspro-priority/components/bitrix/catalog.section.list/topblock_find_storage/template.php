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


<?if(count($arResult["SECTIONS"]) > 0) {?>
	<div class="row">
		<div class="col-md-12">
			<div class="topblock_find_storage">
							
				<div class="item-views within services-items type_5">
					<div class="items flexbox">
						<?
						$cnt = 0;
						$num = 0;
						?>
						<?foreach($arResult["SECTIONS"] as $arItem) {?>
							<?
							$num++;
							if($num > 3) {
								$num = 1;
							}							
							?>
							<?
							$arSectionButtons = CIBlock::GetPanelButtons($arItem['IBLOCK_ID'], 0, $arItem['ID'], array('SESSID' => false, 'CATALOG' => true));
							$this->AddEditAction($arItem['ID'], $arSectionButtons['edit']['edit_section']['ACTION_URL'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'SECTION_EDIT'));
							$this->AddDeleteAction($arItem['ID'], $arSectionButtons['edit']['delete_section']['ACTION_URL'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'SECTION_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
							?>
							<?if(($cnt!=0) and ($cnt%3 == 0)) {?>
								</div>
								<div class="items flexbox">
							<?}?>
							
							<div class="item shadow border" id="<?=$this->GetEditAreaId($arItem['ID'])?>">
								<div class="wrap">																							
									<div class="body-info">
										<svg width="17" height="22" viewBox="0 0 17 22">
											<path class="cls-1" d="M1755.96,608.018l0.01,0.012c-5.72,6.178-5.97,7.97-5.97,7.97h-1c-0.55-1.678-4.09-5.867-5.47-7.453A8.5,8.5,0,1,1,1755.96,608.018ZM1749.5,596a6.5,6.5,0,0,0-6.5,6.5,6.418,6.418,0,0,0,1.02,3.464L1744,606c0.08,0.065,1.11,1.545,3.06,3.754a15.174,15.174,0,0,1,2.35,3.246h0.15a13.294,13.294,0,0,1,2.41-3.25A32.028,32.028,0,0,0,1755,606l-0.02-.036A6.418,6.418,0,0,0,1756,602.5,6.5,6.5,0,0,0,1749.5,596Zm0,11a4.5,4.5,0,1,1,4.5-4.5A4.5,4.5,0,0,1,1749.5,607Zm0-7a2.5,2.5,0,1,0,2.5,2.5A2.5,2.5,0,0,0,1749.5,600Z" transform="translate(-1741 -594)"></path>
										</svg>										
										<div class="title"><?=$arItem["NAME"]?></div>										
									</div>
									<a class="scroll" href="#<?=$arItem["CODE"]?>"></a>
								</div>
							</div>
							
							<?
							$cnt++;
							?>
						<?}?>
						
						<?if($num == 1) {?>
							<style>
								.topblock_find_storage .items.flexbox:last-child .item {width:100% !important;}
							</style>
						<?}?>
						<?if($num == 2) {?>
							<style>
								.topblock_find_storage .items.flexbox:last-child .item {width:49.9% !important;}
							</style>
						<?}?>						
					</div>        
				</div>				
				
			</div>
		</div>
	</div>
<?}?>