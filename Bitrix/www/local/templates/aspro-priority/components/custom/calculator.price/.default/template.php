<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>

<?
$this->addExternalCss("/local/templates/aspro-priority/css/ion.rangeSlider.min.css");
$this->addExternalJS("/local/templates/aspro-priority/js/ion.rangeSlider.min.js");
?>

<!--div class="calc-price swipeignore">
    <div class="calc-price__head"></div>
    <div class="calc-price__subhead">Выберите складской комплекс:</div>
    <div class="calc-price__body">
        <div class="dropdown">
            <div class="dropdown__toggle">
                <div data-id="<?=$arResult["SKLAD"][0]["ID"]?>" class="dropdown__item"></div>
                <div class="dropdown__toggle-icon"><i class="fa fa-angle-down"></i></div>
            </div>
            <div class="dropdown__list">
</?foreach($arResult["SKLAD"] as $item):?>
</?
                    $metroColor = '';
                    if($item['COLORS_METRO']){
                        $metroColor = 'fill:' . $item['COLORS_METRO'];
                    }
                    ?>
<div data-id="</?=$item["ID"]?>" class="dropdown__item">
                        <div class="dropdown__item-left">
<div class="dropdown__item-icon" style="</?=$metroColor?>">
</?if($metroColor):?>
</?=CPriority::showIconSvg('/bitrix/components/custom/calculator.price/templates/.default/svg/metro.svg');?>
</?endif?>
                            </div>
<span class="dropdown__item-text"></?=$item["NAME"]?></span>
                        </div>
                        
</?if($item["PROMO"] == 1):?>
                            <div class="dropdown__item-promo">
                            <img src="/bitrix/templates/aspro-priority/components/bitrix/catalog.section/rental_catalog_list/images/discount_icon.png">
                            </div>
</?endif?>
                    </div>
</?endforeach?>
            </div>
            <div class="calc-price__sklad-full-info">
</?foreach($arResult["SKLAD"] as $item):?>
</?if($item['CALC_DESCR']):?>
<div data-id="</?=$item["ID"]?>" class="calc-price__sklad-full-info-item" </?= $item["ID"] == $arResult["SKLAD"][0]["ID"] ? '' : 'style="display:none;"'?>>
</?= htmlspecialcharsback($item['CALC_DESCR'])?>
                        </div>
			</?endif?>
			</?endforeach?>
            </div>
            <div class="calc-price__subhead">Выберите размер склада:</div>
            <input type="text" class="calc-price__range">
            <div class="calc-price__subhead">Цена в месяц:</div>
            <div class="calc-price__price-wrap">
                <div class="calc-price__price">
                    <span class="calc-price__price-value">1&nbsp;980</span>
                    <span class="calc-price__price-text">руб./мес.</span>
                    <div class="calc-price__small">Ставка включает страховку 120 руб.</div>
                    <div class="calc-price__sklad-info">
					</?foreach($arResult["SKLAD"] as $key => $item):?>
					</?if($item['SKLAD_INFO']):?>
<div data-id="</?=$item["ID"]?>" class="calc-price__sklad-info-item" </?= $item["ID"] == $arResult["SKLAD"][0]["ID"] ? '' : 'style="display:none;"'?>>
						</?=$item['SKLAD_INFO']?>
                                </div>
					</?endif?>
					</?endforeach?>
                    </div>
                </div>
                <div class="price__send-wrap">
                    <button class="calc-price__send btn btn-default" data-event="jqm" data-param-id="20" data-name="question">Узнать о наличии</button>
                </div>
            </div>
        </div>
    </div>
</div-->

<script>
BX.message({
	RANGE_VALUES: <?=json_encode($arResult["RANGE_VALUES"])?>,
	DATA: <?=json_encode($arResult["DATA"])?>,
});
</script>
