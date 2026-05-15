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

<?if(count($arResult["ITEMS"]) > 0) {?>
	<div class="calculator">
		<div class="row">
			<div class="col-md-8 col-xs-12">
				<div class="calculator_items_list">
					<div class="row slim">
						<?foreach($arResult["ITEMS"] as $arItem) {?>
							<div class="col-md-4 col-xs-6">
								<div class="item">
									<?if(strlen($arItem["PREVIEW_PICTURE"]["ID"]) > 0) {?>
										<div class="icon">
											<img width="32" height="32" src="<?=$arItem["PREVIEW_PICTURE"]["RESIZE"]["src"]?>" />
										</div>
									<?}?>
									<div class="name hidden-xs">
										<?if(strlen($arItem["PREVIEW_TEXT"]) > 0) {?>
											<?=$arItem["PREVIEW_TEXT"]?>
										<?} else {?>
											<?=$arItem["NAME"]?>
										<?}?>
									</div>
									<div class="counter">
										<input class="noscroll"  type="number" min="0" value="0" data-calc-item-id="<?=$arItem["ID"]?>" data-calc-item-square="<?=$arItem["PROPERTIES"]["SQUARE"]["VALUE"]?>" />
									</div>
								</div>
							</div>
						<?}?>				
											
					</div>
				</div>
				
				<div class="visible-xs mobile_slider">
					<div class="ajaxPreloader"></div>
					<div class="ajax_calculator_mobile_slider">#MOBILE_SLIDER#</div>
				</div>
				
				<div class="calculator_result_bottom_container">
					<div class="ajaxPreloader"></div>
					<div class="calculator_result_bottom greyline">
						<div class="row">
							<div class="col-md-8 col-xs-12">
								<div class="square_result big">
									Общая площадь бокса: <span><span class="val">0</span> м<sup>2</sup></span> (<span><span class="val_kub">0</span> м<sup>3</sup></span>)						
								</div>
								<div class="square_result_text">Приблизительные размеры помещения согласно вашей сборке в калькуляторе</div>
								<div class="sklad_list_container">									
									#SKLAD_LIST#
								</div>
							</div>
							<div class="col-md-4 col-xs-12">
								<div class="title">Характеристики бокса</div>
								<ul class="list">
									<?$APPLICATION->IncludeComponent(
										"bitrix:main.include",
										"",
										Array(
											"AREA_FILE_SHOW" => "file",
											"AREA_FILE_SUFFIX" => "inc",
											"EDIT_TEMPLATE" => "",
											"PATH" => "/include/calculator_box_specs_text.php"
										),
										false,
										Array("HIDE_ICONS"=>"Y")
									);?>								
								</ul>
								
								<?if(false):?>
								<div class="button" >
									<a class="btn btn-default btn-transparent scroll" href="#form_4">Оставить заявку</a>
								</div>
								<?endif?>
							</div>						
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-xs-12 hidden-xs">
				<div class="calculator_result">
					<div class="ajaxPreloader"></div>
					<div class="shadow-box">
						<h3>Ваш Бокс</h3>
						<div class="square_result small">
							Общая площадь бокса: <span><span class="val">0</span> м<sup>2</sup></span> (<span><span class="val_kub">0</span> м<sup>3</sup></span>)					
						</div>
						
						<div class="ajax_calculator_slider">#SLIDER#</div>
						
						<div class="selected_items_list" ss-container>
							<div class="scroll">

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