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




<div class="topblock-backround" style="<?=(strlen($arResult["SECTION"]["BG_PICTURE"]) > 0) ? "background-image:url('".$arResult["SECTION"]["BG_PICTURE"]."')" : ""?>">
	<section class="page-top maxwidth-theme">
		<div class="row">
			<div class="col-md-12">
				<?
				$helper = new PHPInterface\ComponentHelper($component);
				$helper->deferredCall('ShowNavChain', array('corp'));
				?>
				<br>
				<div class="page-top-main">
					<h1 id="pagetitle"><?=$APPLICATION->GetTitle(false);?></h1>
				</div>
				<br>
				<?=$arResult["SECTION"]["DESCRIPTION"]?>
				<div class="button">
					<a class="order_button btn btn-default scroll" href="#tabs_formManagerOrder">Арендовать бокс</a>
				</div>

				<?if(count($arResult["SECTION"]["USE_TYPE"]) > 0) {?>
					<div class="row">
						<div class="col-md-12">
							<div class="topblock_find_storage">
											
								<div class="item-views within services-items type_5">
									<div class="items flexbox">
										<?
										$cnt = 0;
										$num = 0;
										?>
										<?foreach($arResult["SECTION"]["USE_TYPE"] as $arItem) {?>
											<?
											$num++;
											if($num > 3) {
												$num = 1;
											}	
											?>
											<?if(($cnt!=0) and ($cnt%3 == 0)) {?>
												</div>
												<div class="items flexbox">
											<?}?>
											
											<div class="item shadow border">
												<div class="wrap">																							
													<div class="body-info icon-<?=$arItem["XML_ID"]?>">																							
														<div class="title"><?=$arItem["VALUE"]?></div>
														<? if ($arItem["ITEMS"]): ?><div class="text"><?=num_decline(count($arItem["ITEMS"]), Array("статья", "статьи", "статей"));?></div><? endif; ?>
													</div>
													<a class="scroll filter_use_block" href="javascript:void(0);" data-usetype-id="<?=$arItem["ID"]?>" data-usetype-code="<?=$arItem["CODE"]?>"></a>
												</div>
											</div>
											<style>
												.topblock_find_storage .item-views.services-items.type_5 .items .item > .wrap .body-info.icon-<?=$arItem["XML_ID"]?>::before {
													background-image:url('<?=SITE_TEMPLATE_PATH?>/images/custom/icon-<?=$arItem["XML_ID"]?>.svg');
												}
												.topblock_find_storage .item-views.services-items.type_5 .items .item.active > .wrap .body-info.icon-<?=$arItem["XML_ID"]?>::before {
													background-image:url('<?=SITE_TEMPLATE_PATH?>/images/custom/icon-<?=$arItem["XML_ID"]?>-h.svg');
												}												
											</style>											
											
											<?
											$cnt++;
											?>
										<?}?>										
										
										<?if($num == 1) {?>
											<style>
												.topblock_find_storage .items.flexbox:last-child .item {width:99.75% !important;}
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
			</div>
		</div>
	</section>
</div>


<?
$helper->saveCache();
?>