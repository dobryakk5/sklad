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
	<div class="maxwidth-theme">
		<div class="shadow-box">
			<div class="storage_list_withmap">
				<h2>Адреса наших складов</h2>
				
				<div class="flexslider unstyled navigation-vcenter dark-nav" data-plugin-options='{"directionNav": true, "controlNav" :true, "animationLoop": true, "slideshow": false, "counts": [5, 2, 2, 1]}'>
					<ul class="slides items">
						<?foreach($arResult["SECTIONS"] as $arItem) {?>
							<li class="item">
								<div class="wrap">							
									<div class="title"><a class="dark-color" href="/find_storage/<?=$arItem["CODE"]?>/"><?=$arItem["NAME"]?></a></div>
									<?if(strlen($arItem["UF_ADDRESS"]) > 0) {?>
										<div class="address"><?=$arItem["UF_ADDRESS"]?></div>
									<?}?>
									<?if((strlen($arItem["UF_RECEPTION"]) > 0) or (strlen($arItem["UF_DOSTUP_TIME"]) > 0) or (strlen($arItem["UF_PHONE"]) > 0)) {?>
										<div class="contacts">
											Режим работы:<br>
											<?if(strlen($arItem["UF_RECEPTION"]) > 0) {?>
												Ресепшн: <?=$arItem["UF_RECEPTION"]?><br>
											<?}?>
											<?if(strlen($arItem["UF_DOSTUP_TIME"]) > 0) {?>
												Доступ на склад: <?=$arItem["UF_DOSTUP_TIME"]?><br>
											<?}?>
											<?if(strlen($arItem["UF_PHONE"]) > 0) {?>
												<?
												$phone = preg_replace('/[^\d+]/', '', $arItem["UF_PHONE"]);
												?>										
												Телефон: <a class="dark-color" href="tel:<?=$phone?>"><?=$arItem["UF_PHONE"]?></a>
											<?}?>
										</div>
									<?}?>
								</div>
							</li>
						<?}?>					
					</ul>
				</div>	
			</div>
		</div>
		<div class="map-shadow">
			#MAP#
		</div>
	</div>	
<?}?>


<?
$this->__component->arResult["CACHED_TPL"] = @ob_get_contents();
ob_get_clean();
?>