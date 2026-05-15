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
	<div class="rental_catalog selected_map_item">
		<div class="rental_catalog_list">		
			<?/*
			<div class="map_item_title">Выбранный на карте этажа бокс</div>
			*/?>
		
			<?foreach($arResult["ITEMS"] as $key=>$arItem) {?>
				<?
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));
				?>	
				<div class="catalog_item" id="<?=$this->GetEditAreaId($arItem["ID"])?>">
					<div class="row">
						<div class="col-md-2 col-xs-12">
							<div class="picture_item">
								<?if(intval($arItem["MIN_PRICE"]["DISCOUNT_DIFF"]) > 0) {?>
									<div class="discount_icon"><img src="<?=$templateFolder?>/images/discount_icon.png" /></div>
								<?}?>						
								<?if(!empty($arItem["GALLERY"])) {?>
									<div class="photogallery_slider_catalog_item detail">
										<div class="flexslider flexslider-init" id="slider_<?=$arItem["ID"]?>">
											<ul class="slides">
												<?foreach($arItem["GALLERY"] as $arPicture) {?>
													<li class="item">
														<a href="<?=$arPicture["BIG"]["src"]?>" target="_blank" class="fancybox" data-fancybox-group="gallery_<?=$arItem["ID"]?>" title="<?=$arPicture["DESCRIPTION"]?>">
															<img alt="" class="img-responsive" src="<?=$arPicture["MEDIUM"]["src"]?>">								
														</a>
													</li>
												<?}?>
											</ul>
										</div>
										<?if(count($arItem["GALLERY"]) > 1) {?>
											<div class="thmb_wrap">
												<div class="thmb flexslider unstyled" id="carousel_<?=$arItem["ID"]?>">
													<ul class="slides">
														<?foreach($arItem["GALLERY"] as $arPicture) {?>
															<li class="blink">
																<img class="img-responsive inline" src="<?=$arPicture["SMALL"]["src"]?>" />
															</li>
														<?}?>
													</ul>
												</div>
											</div>
										<?}?>
									</div>
									<div class="clearfix"></div>
									<script>
									$(document).ready(function() {

										$('.photogallery_slider_catalog_item #carousel_<?=$arItem["ID"]?>').flexslider({
											animation: 'slide',
											controlNav: false,
											animationLoop: false,
											slideshow: false,
											itemWidth: 64,
											itemMargin: 3,
											directionNav: false,
											touch: true,
											minItems: 2,
											maxItems: 8,
											asNavFor: '.photogallery_slider_catalog_item #slider_<?=$arItem["ID"]?>',
											start: function(){
												$('.photogallery_slider_catalog_item').height('auto');
												$('.photogallery_slider_catalog_item #carousel_<?=$arItem["ID"]?>').css({'width': 'auto', 'opacity': 1});
											}
										});

										$('.photogallery_slider_catalog_item #slider_<?=$arItem["ID"]?>').flexslider({
											animation: 'slide',
											directionNav: false,
											controlNav: false,
											animationLoop: false,
											slideshow: false,
											sync: '.photogallery_slider_catalog_item #carousel_<?=$arItem["ID"]?>',
										});

									});						
									</script>
								<?} else {?>
									<div class="no_pic thumbnail">
										<img src="<?=$templateFolder?>/images/no_pic.png" />
									</div>
								<?}?>
							</div>
						</div>
						<div class="col-md-5 col-xs-12">
							<?/*<div class="name"><?=$arItem["NAME"]?></div>
							<?if(!empty($arItem["DISPLAY_PROPERTIES"])) {?>
								<div class="props">
									<?foreach($arItem["DISPLAY_PROPERTIES"] as $arProp) {?>
										<?if(strlen($arProp["VALUE"]) > 0) {?>
											<?
											if($arProp["CODE"] == "FLOOR") {
												$arProp["VALUE"] = preg_replace("/[^0-9]/", "", $arProp["VALUE"]);
											}
											if($arProp["CODE"] == "SQUARE") {
												$arProp["VALUE"] = $arProp["VALUE"]." м<sup>2</sup>";
											}
											if($arProp["CODE"] == "VOLUME") {
												$arProp["VALUE"] = $arProp["VALUE"]." м<sup>3</sup>";
											}												
											?>
											<div class="prop"><span class="prop_name"><?=$arProp["NAME"]?>:</span> <span class="prop_val"><?=$arProp["VALUE"]?></span></div>
										<?}?>
									<?}?>
								</div>
							<?}*/?>
							<?if(!empty($arItem["DISPLAY_PROPERTIES"])) {
								if(strlen($arItem["DISPLAY_PROPERTIES"]['NAME_FOR_SITE']["VALUE"]) > 0 ) {?>
									<div class="name"><?=$arItem["DISPLAY_PROPERTIES"]['NAME_FOR_SITE']["VALUE"]?></div>
								<?}
								else {?>
									<div class="name"><?=$arItem["NAME"]?></div>
								<?}?>
								<div class="props">
									<?foreach($arItem["DISPLAY_PROPERTIES"] as $arProp) {
										if($arProp['ID'] != 495) {
											if(strlen($arProp["VALUE"]) > 0) {
													if($arProp["CODE"] == "FLOOR") {
														$arProp["VALUE"] = preg_replace("/[^0-9]/", "", $arProp["VALUE"]);
													}
													if($arProp["CODE"] == "SQUARE") {
														$arProp["VALUE"] = $arProp["VALUE"]." м<sup>2</sup>";
													}
													if($arProp["CODE"] == "VOLUME") {
														$arProp["VALUE"] = $arProp["VALUE"]." м<sup>3</sup>";
													}
													if($arProp["CODE"] == "DOORWAY_WIDTH"){
														$arProp["VALUE"] = $arProp["VALUE"]." см";
													}
												?>
												<div class="prop"><span class="prop_name"><?=$arProp["NAME"]?>:</span> <span class="prop_val"><?=$arProp["VALUE"]?></span></div>
											<?}
										}
									}?>
								</div>
							<?}
							else{?>
								<div class="name"><?=$arItem["NAME"]?></div>
							<?}?>

							<div class="text"><?=$arItem["PREVIEW_TEXT"]?></div>
							<?if(!empty($arItem["DISCOUNT"])) {?>
								<div class="discount">
									<?foreach($arItem["DISCOUNT"] as $arDiscount) {?>
										<div class="discount_item">
											<span class="icon-text grey s25"><i class="fa fa-gift"></i> <?=$arDiscount["NAME"]?></span>	
										</div>															
									<?}?>
								</div>
							<?}?>
						</div>
						<div class="col-md-3 col-xs-12">
							<div class="sklad_name">
								<a class="dark-color" href="/rental_catalog/<?=$arResult["SKLAD_LIST"][$arItem["IBLOCK_SECTION_ID"]]["CODE"]?>/"><?=$arResult["SKLAD_LIST"][$arItem["IBLOCK_SECTION_ID"]]["NAME"]?></a>
							</div>
							<?if(strlen($arResult["SKLAD_LIST"][$arItem["IBLOCK_SECTION_ID"]]["UF_ADDRESS"]) > 0) {?>
								<div class="sklad_address"><?=$arResult["SKLAD_LIST"][$arItem["IBLOCK_SECTION_ID"]]["UF_ADDRESS"]?></div>
							<?}?>
							<?if(strlen($arResult["SKLAD_LIST"][$arItem["IBLOCK_SECTION_ID"]]["UF_PHONE"]) > 0) {?>
								<?
								$phone = preg_replace('/[^\d+]/', '', $arResult["SKLAD_LIST"][$arItem["IBLOCK_SECTION_ID"]]["UF_PHONE"]);
								?>					
								<div class="sklad_phone">
									<a class="dark-color" href="tel:<?=$phone?>"><?=$arResult["SKLAD_LIST"][$arItem["IBLOCK_SECTION_ID"]]["UF_PHONE"]?></a>
								</div>
							<?}?>
							<?if(strlen($arResult["SKLAD_LIST"][$arItem["IBLOCK_SECTION_ID"]]["UF_DOSTUP_TIME"]) > 0) {?>
								<div class="sklad_dostup_time">
									<div class="lbl">Доступ на склад:</div>
									<div class="val"><?=$arResult["SKLAD_LIST"][$arItem["IBLOCK_SECTION_ID"]]["UF_DOSTUP_TIME"]?></div>
								</div>
							<?}?>
							<?if(strlen($arResult["SKLAD_LIST"][$arItem["IBLOCK_SECTION_ID"]]["UF_RECEPTION"]) > 0) {?>
								<div class="sklad_reception">
									<div class="lbl">Режим работы ресепшна:</div>
									<div class="val"><?=$arResult["SKLAD_LIST"][$arItem["IBLOCK_SECTION_ID"]]["UF_RECEPTION"]?></div>
								</div>
							<?}?>
						</div>
						<div class="col-md-2 col-xs-12">						
							<div class="status <?=($arItem["PROPERTIES"]["STATUS"]["VALUE_ENUM_ID"] == 346)?"opened":"closed"?>"><?=$arItem["PROPERTIES"]["STATUS"]["VALUE"]?></div>
							<div class="price"><?=$arItem["MIN_PRICE"]["PRINT_DISCOUNT_VALUE"]?>/<?=$arItem["ITEM_MEASURE"]["TITLE"]?></div>
							<?if(intval($arItem["MIN_PRICE"]["DISCOUNT_DIFF"]) > 0) {?>
								<div class="old_price"><?=$arItem["MIN_PRICE"]["PRINT_VALUE"]?>/<?=$arItem["ITEM_MEASURE"]["TITLE"]?></div>
							<?}?>
							
							<?if($arItem["PROPERTIES"]["STATUS"]["VALUE_ENUM_ID"] == 346) {?>									
								<div class="buy_block">
									<div class="months_label">Кол-во месяцев:</div>
									<div class="counter">
										<div class="wrap">
											<span class="minus ctrl">
												<svg width="11" height="1" viewBox="0 0 11 1">
													<rect width="11" height="1" rx="0.5" ry="0.5"/>
												</svg>
											</span>
											<div class="input"><input type="text" value="<?if(strlen($arParams["COUNT_MONTHS"])>0){echo $arParams["COUNT_MONTHS"];}else{echo "1";}?>" class="countMonths" data-price="<?=$arItem["MIN_PRICE"]["DISCOUNT_VALUE"]?>" /></div>
											<span class="plus ctrl">
												<svg width="11" height="11" viewBox="0 0 11 11">
													<path d="M1034.5,193H1030v4.5a0.5,0.5,0,0,1-1,0V193h-4.5a0.5,0.5,0,0,1,0-1h4.5v-4.5a0.5,0.5,0,0,1,1,0V192h4.5A0.5,0.5,0,0,1,1034.5,193Z" transform="translate(-1024 -187)"/>
												</svg>
											</span>
										</div>
									</div>
									<div class="sum">
										Сумма: <span class="val"><?=number_format($arItem["MIN_PRICE"]["DISCOUNT_VALUE"], 0, '', ' ')?></span> руб.
									</div>
								</div>
								<div class="buy_button">
									<a class="btn btn-default btn-xs add_to_cart" href="javascript:void(0);" data-product-id="<?=$arItem["ID"]?>">Арендовать</a>
									<span style="display:none;" class="add_to_cart_popup" data-event="jqm" data-param-product-id="<?=$arItem["ID"]?>" data-param-count="" data-param-type="box" data-name="box"></span>
								</div>
							<?}?>
						</div>				
					</div>
				</div>
			<?}?>
			
		</div>
	</div>	
<?}?>

