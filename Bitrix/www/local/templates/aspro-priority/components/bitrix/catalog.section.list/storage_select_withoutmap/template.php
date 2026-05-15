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


<?
$this->addExternalCss(SITE_TEMPLATE_PATH."/css/simple-scrollbar.css");
$this->addExternalJS(SITE_TEMPLATE_PATH."/js/simple-scrollbar.min.js");
?>

<?if(count($arResult["SECTIONS"]) > 0) {?>
	<!-- Модальное окно -->  
	<div class="modal fade" id="modalSkladList" tabindex="-1" role="dialog" aria-labelledby="modalSkladListLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="modalSkladListLabel">Выберите склад из списка</h4>
					</div>
				<div class="modal-body">				
					<div class="storage_select_withmap">
						<div class="row">
							<div class="col-md-12 col=xs-12">
								<div class="sklad_list" ss-container>
									<?foreach($arResult["SECTIONS"] as $arSklad) {?>
										<div class="sklad">
											<div class="info">
												<div class="name"><?=$arSklad["NAME"]?></div>
												<div class="address"><?=$arSklad["UF_ADDRESS"]?></div>
											</div>
											<div class="checkbox_container">
												<div class="value"><input type="checkbox" value="Y" data-sklad-code="<?=$arSklad["CODE"]?>" data-sklad-id="<?=$arSklad["ID"]?>" /><label></label></div>                                            
											</div>
										</div>
									<?}?>                                        
								</div>
								<div class="button button_select_storage">
									<a class="btn btn-default disabled" href="#">Перейти к выбору бокса</a>
								</div>				
							</div>			
						</div>
					</div>			
				</div>
			</div>
		</div>
	</div>
<?}?>

<?
$this->__component->arResult["CACHED_TPL"] = @ob_get_contents();
ob_get_clean();
?>