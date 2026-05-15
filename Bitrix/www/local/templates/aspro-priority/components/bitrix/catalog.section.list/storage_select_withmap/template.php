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

<?if($arParams["ONLY_MAP"] == "Y") {?>
	#MAP#
<?} else {?>
	<?
	$this->addExternalCss(SITE_TEMPLATE_PATH."/css/simple-scrollbar.css");
	$this->addExternalJS(SITE_TEMPLATE_PATH."/js/simple-scrollbar.min.js");
	?>

	<?if(count($arResult["SECTIONS"]) > 0) {?>
		<div class="maxwidth-theme">
			<div class="storage_select_withmap">
				<div class="row">
					<div class="col-md-5 col=xs-12">
						<h3>Выберите расположение склада</h3>
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
					<div class="col-md-7 col-xs-12 hidden-xs">
						<div class="ajax_map">
							#MAP#
						</div>
					</div>			
				</div>
			</div>
		</div>
	<?}?>
<?}?>

<?
$this->__component->arResult["CACHED_TPL"] = @ob_get_contents();
ob_get_clean();
?>