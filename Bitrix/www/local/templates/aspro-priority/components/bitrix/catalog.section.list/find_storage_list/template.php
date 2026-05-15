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
	#MAP#
	<div class="row">
		<div class="col-md-12">
			<div class="find_storage_list">								
				<div class="items">
					<?foreach($arResult["SECTIONS"] as $arItem) {?>
						<?
						$arSectionButtons = CIBlock::GetPanelButtons($arItem['IBLOCK_ID'], 0, $arItem['ID'], array('SESSID' => false, 'CATALOG' => true));
						$this->AddEditAction($arItem['ID'], $arSectionButtons['edit']['edit_section']['ACTION_URL'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'SECTION_EDIT'));
						$this->AddDeleteAction($arItem['ID'], $arSectionButtons['edit']['delete_section']['ACTION_URL'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'SECTION_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
						?>
						<div class="item" id="<?=$arItem["CODE"]?>" itemscope itemtype="http://schema.org/LocalBusiness" >
							<div class="item-wrap" id="<?=$this->GetEditAreaId($arItem['ID'])?>">
								<div class="maxwidth-theme">
									<div class="row">
										<div class="col-md-9 col-xs-12">
											<div class="title"><a class="dark-color" href="/rental_catalog/<?=$arItem["CODE"]?>/" itemprop="name"><?=$arItem["NAME"]?></a></div>
											<?if(strlen($arItem["UF_ADDRESS"]) > 0) {?>
												<div class="address" itemprop="address" ><?=$arItem["UF_ADDRESS"]?></div>
											<?}?>
											<?if(strlen($arItem["DESCRIPTION"]) > 0) {?>
												<div class="description"><?=$arItem["DESCRIPTION"]?></div>
											<?}?>
											
											<?
											$show_work_time = false;
											if((strlen($arItem["UF_RECEPTION"]) > 0) or (strlen($arItem["UF_DOSTUP_TIME"]) > 0)) {
												$show_work_time = true;
											}
											?>
											<div class="contacts">
												<div class="row">
													<?if($show_work_time) {?>
														<div class="col-md-6 col-xs-12">
															<div class="work-time-block">
																<p class="work-time-label">Режим работы:</p>
																<?if(strlen($arItem["UF_RECEPTION"]) > 0) {?>
																	<p class="work-time">Ресепшн: <?=$arItem["UF_RECEPTION"]?></p>
																<?}?>
																<?if(strlen($arItem["UF_DOSTUP_TIME"]) > 0) {?>
																	<p class="work-time"  itemprop="openingHours"  >Доступ на склад: <?=$arItem["UF_DOSTUP_TIME"]?></p>
																<?}?>
															</div>														
														</div>
													<?}?>
													<div class="col-md-6 col-xs-12">
														<?if(strlen($arItem["UF_PHONE"]) > 0) {?>
															<?
															$phone = preg_replace('/[^\d+]/', '', $arItem["UF_PHONE"]);
															?>
															<div class="phone">
																<svg class="svg svg-phone mask" width="5" height="13" viewBox="0 0 5 13">
																	<path class="cls-phone" d="M785.738,193.457a22.174,22.174,0,0,0,1.136,2.041,0.62,0.62,0,0,1-.144.869l-0.3.3a0.908,0.908,0,0,1-.805.33,4.014,4.014,0,0,1-1.491-.274c-1.2-.679-1.657-2.35-1.9-3.664a13.4,13.4,0,0,1,.024-5.081c0.255-1.316.73-2.991,1.935-3.685a4.025,4.025,0,0,1,1.493-.288,0.888,0.888,0,0,1,.8.322l0.3,0.3a0.634,0.634,0,0,1,.113.875c-0.454.8-.788,1.37-1.132,2.045-0.143.28-.266,0.258-0.557,0.214l-0.468-.072a0.532,0.532,0,0,0-.7.366,8.047,8.047,0,0,0-.023,4.909,0.521,0.521,0,0,0,.7.358l0.468-.075c0.291-.048.4-0.066,0.555,0.207h0Z" transform="translate(-782 -184)"></path>
																</svg>															
																<a class="dark-color" itemprop="telephone" href="tel:<?=$phone?>"><?=$arItem["UF_PHONE"]?></a>
															</div>
														<?}?>
														<div class="button">
															<a class="btn btn-default btn-transparent" href="/rental_catalog/<?=$arItem["CODE"]?>/">Перейти на страницу склада</a>
														</div>
													</div>												
												</div>
											</div>	
										</div>
										<div class="col-md-3 col-xs-12">
											#RATING_SKLAD_<?=$arItem["ID"]?>#
										</div>
									</div>
								</div>
							</div>
						</div>
					<?}?>						
				</div>  				  							
			</div>
		</div>
	</div>
<?}?>


<?
$this->__component->arResult["CACHED_TPL"] = @ob_get_contents();
ob_get_clean();
?>