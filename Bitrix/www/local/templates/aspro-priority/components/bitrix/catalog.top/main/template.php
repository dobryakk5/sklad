<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<? $this->setFrameMode( true ); ?>
<?
$sliderID  = "specials_slider_wrapp_".$this->randString();
$notifyOption = COption::GetOptionString("sale", "subscribe_prod", "");
$arNotify = unserialize($notifyOption, ['allowed_classes' => false]);
?>
<?
$countmd = 4;
$countsm = 3;
$countxs = 2;
$countxs1 = 1;
$colmd = 3;
$colsm = 4;
$colxs = 6;
?>
<?if($arResult["ITEMS"]):?>
	<?foreach($arResult["ITEMS"] as $key => $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));
	$totalCount = CNext::GetTotalCount($arItem, $arParams);
	$arQuantityData = CNext::GetQuantityArray($totalCount);
	$arItem["FRONT_CATALOG"]="Y";

	$strMeasure='';
	if($arItem["OFFERS"]){
		$strMeasure=$arItem["MIN_PRICE"]["CATALOG_MEASURE_NAME"];
	}else{
		if (($arParams["SHOW_MEASURE"]=="Y")&&($arItem["CATALOG_MEASURE"])){
			$arMeasure = CCatalogMeasure::getList(array(), array("ID"=>$arItem["CATALOG_MEASURE"]), false, false, array())->GetNext();
			$strMeasure=$arMeasure["SYMBOL_RUS"];
		}
	}

	$elementName = ((isset($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) ? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] : $arItem['NAME']);
	?>
	<?$arAddToBasketData = CNext::GetAddToBasketArray($arItem, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], true);?>
	<li id="<?=$this->GetEditAreaId($arItem['ID']);?>" class="item-wrap col-md-<?=$colmd?> col-sm-<?=$colsm?> col-xs-<?=$colxs?>">
		<div class="item wti">
		<div class="inner-wrap">
			<div class="image">
                <div class="wrap">
                    <?if($arItem["PROPERTIES"]["HIT"]["VALUE"] || ($arParams["SALE_STIKER"] && $arItem["PROPERTIES"][$arParams["SALE_STIKER"]]["VALUE"])){?>
                        <div class="stickers">
                            <div class="stickers-wrapper">
                                <?if($arItem["PROPERTIES"]["HIT"]["VALUE"]):?>
                                    <?$prop = ($arParams["STIKERS_PROP"] ? $arParams["STIKERS_PROP"] : "HIT");?>
                                    <?foreach(CNext::GetItemStickers($arItem["PROPERTIES"][$prop]) as $arSticker):?>
                                        <div class="<?=$arSticker['CLASS']?>"><?=$arSticker['VALUE']?></div>
                                    <?endforeach;?>
                                <?endif;?>
                                <?if($arParams["SALE_STIKER"] && $arItem["PROPERTIES"][$arParams["SALE_STIKER"]]["VALUE"]){?>
                                    <div class="sticker_sale_text"><?=$arItem["PROPERTIES"][$arParams["SALE_STIKER"]]["VALUE"];?></div>
                                <?}?>
                            </div>
                        </div>
                    <?}?>
                    <?if($arParams["DISPLAY_WISH_BUTTONS"] != "N" || $arParams["DISPLAY_COMPARE"] == "Y"):?>
                        <div class="like_icons">
                            <?if($arAddToBasketData["CAN_BUY"] && empty($arItem["OFFERS"]) && $arParams["DISPLAY_WISH_BUTTONS"] != "N"):?>
                                <div class="wish_item_button" <?=($arAddToBasketData['CAN_BUY'] ? '' : 'style="display:none"');?>>
                                    <span title="<?=GetMessage('CATALOG_WISH')?>" class="wish_item to" data-item="<?=$arItem["ID"]?>"><i></i></span>
                                    <span title="<?=GetMessage('CATALOG_WISH_OUT')?>" class="wish_item in added" style="display: none;" data-item="<?=$arItem["ID"]?>"><i></i></span>
                                </div>
                            <?endif;?>
                            <?if($arParams["DISPLAY_COMPARE"] == "Y"):?>
                                <div class="compare_item_button">
                                    <span title="<?=GetMessage('CATALOG_COMPARE')?>" class="compare_item to" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arItem["ID"]?>" ><i></i></span>
                                    <span title="<?=GetMessage('CATALOG_COMPARE_OUT')?>" class="compare_item in added" style="display: none;" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arItem["ID"]?>"><i></i></span>
                                </div>
                            <?endif;?>
                        </div>
                    <?endif;?>
                    <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="blink-block">
                        <?
                        $a_alt = ($arItem["PREVIEW_PICTURE"] && strlen($arItem["PREVIEW_PICTURE"]['DESCRIPTION']) ? $arItem["PREVIEW_PICTURE"]['DESCRIPTION'] : ($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] : $arItem["NAME"] ));
                        $a_title = ($arItem["PREVIEW_PICTURE"] && strlen($arItem["PREVIEW_PICTURE"]['DESCRIPTION']) ? $arItem["PREVIEW_PICTURE"]['DESCRIPTION'] : ($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] : $arItem["NAME"] ));
                        ?>
                        <?if(!empty($arItem["PREVIEW_PICTURE"])):?>
                            <img class="img-responsive" src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$a_alt;?>" title="<?=$a_title;?>" />
                        <?elseif(!empty($arItem["DETAIL_PICTURE"])):?>
                            <?$img = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"], array("width" => 170, "height" => 170), BX_RESIZE_IMAGE_PROPORTIONAL, true );?>
                            <img class="img-responsive" src="<?=$img["src"]?>" alt="<?=$a_alt;?>" title="<?=$a_title;?>" />
                        <?else:?>
                            <img class="img-responsive" class="img-responsive" src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=$a_alt;?>" title="<?=$a_title;?>" />
                        <?endif;?>
                        <?if($fast_view_text_tmp = CNext::GetFrontParametrValue('EXPRESSION_FOR_FAST_VIEW'))
                            $fast_view_text = $fast_view_text_tmp;
                        else
                            $fast_view_text = GetMessage('FAST_VIEW');?>
                    </a>
                    <div class="fast_view_block" data-event="jqm" data-param-form_id="fast_view" data-param-iblock_id="<?=$arParams["IBLOCK_ID"];?>" data-param-id="<?=$arItem["ID"];?>" data-param-item_href="<?=urlencode($arItem["DETAIL_PAGE_URL"]);?>" data-name="fast_view"><?=$fast_view_text;?></div>
                </div>
			</div>
            <div class="text">
                <div class="cont">

                    <div class="title">
                        <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="dark-color"><span><?=$arItem['NAME']?></span></a>
                    </div>

                    <?if($arParams["SHOW_RATING"] == "Y"):?>
                        <div class="rating">
                            <?$APPLICATION->IncludeComponent(
                                "bitrix:iblock.vote",
                                "element_rating_front",
                                Array(
                                    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                                    "IBLOCK_ID" => $arItem["IBLOCK_ID"],
                                    "ELEMENT_ID" =>$arItem["ID"],
                                    "MAX_VOTE" => 5,
                                    "VOTE_NAMES" => array(),
                                    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                                    "DISPLAY_AS_RATING" => 'vote_avg'
                                ),
                                $component, array("HIDE_ICONS" =>"Y")
                            );?>
                        </div>
                    <?endif;?>

                    <div class="sa_block">
                        <?=$arQuantityData["HTML"];?>
                    </div>


                </div>

                <div class="foot">
                    <div class="slice_price">
                        <?// element price?>
                        <?if(strlen($arItem['DISPLAY_PROPERTIES']['PRICE']['VALUE'])):?>
                            <div class="price<?=($bOrderViewBasket ? '  inline' : '')?>" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                <div class="price_new">
                                    <span class="price_val">
                                    <?if($arItem["OFFERS"]):?>
                                        <?\Aspro\Functions\CAsproSku::showItemPrices($arParams, $arItem, $item_id, $min_price_id, array(), ($arParams["SHOW_DISCOUNT_PERCENT_NUMBER"] == "Y" ? "N" : "Y"));?>
                                    <?else:?>
                                        <?
                                        if(isset($arItem['PRICE_MATRIX']) && $arItem['PRICE_MATRIX']) // USE_PRICE_COUNT
                                        {?>
                                            <?if($arItem['ITEM_PRICE_MODE'] == 'Q' && count($arItem['PRICE_MATRIX']['ROWS']) > 1):?>
                                            <?=CNext::showPriceRangeTop($arItem, $arParams, GetMessage("CATALOG_ECONOMY"));?>
                                        <?endif;?>
                                            <?=CNext::showPriceMatrix($arItem, $arParams, $strMeasure, $arAddToBasketData);?>
                                            <?
                                        }
                                        elseif($arItem["PRICES"])
                                        {?>
                                            <?\Aspro\Functions\CAsproItem::showItemPrices($arParams, $arItem["PRICES"], $strMeasure, $min_price_id, ($arParams["SHOW_DISCOUNT_PERCENT_NUMBER"] == "Y" ? "N" : "Y"));?>
                                        <?}?>
                                    <?endif;?>
                                    </span>
                                </div>
                                <?if($arItem['DISPLAY_PROPERTIES']['PRICEOLD']['VALUE']):?>
                                    <div class="price_old">
                                        <span class="price_val"><?=$arItem['DISPLAY_PROPERTIES']['PRICEOLD']['VALUE']?></span>
                                    </div>
                                <?endif;?>
                            </div>
                        <?endif;?>
                    </div>

                    <div class="footer-button">
                        <?=$arAddToBasketData["HTML"]?>
                    </div>
                </div>
            </div>
		</div>
		</div>
	</li>
<?endforeach;?>
<?else:?>
	<div class="empty_items"></div>

<?endif;?>