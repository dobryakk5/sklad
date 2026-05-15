<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
global $APPLICATION;

if(isset($arResult["SECTION"]["NAME"])) {
    $APPLICATION->SetTitle($arResult["SECTION"]["NAME"]);
	$APPLICATION->AddChainItem($arResult["SECTION"]["NAME"], "");
}

if($arResult["SECTION"]["ID"] == 0) {
	if (Bitrix\Main\Loader::includeModule("iblock")) {
		Bitrix\Iblock\Component\Tools::process404(
			'Страница не найдена'
			,true
			,true
			,true
			,'/404.php'
		);
	}
}
$APPLICATION->SetPageProperty("ADRESS", $arResult["SECTION"]["UF_ADDRESS"]);

?>



<?
global $DinamicData;
$DinamicData = Array();
$APPLICATION->AddHeadScript("https://api-maps.yandex.ru/2.1/?apikey=412ef885-28cc-476a-9cfd-5169683d9db4&lang=ru_RU");
$DinamicData["MAP"] = '';
ob_start();  
?>
<script type="text/javascript">
    ymaps.ready(init);    
    function init(){ 
        var myMap = new ymaps.Map("map", {
            center: [55.76, 37.64],
            zoom: 10,
            controls: []
        });

        <?foreach($arResult["MAP_POINTS"] as $key=>$arPoint) {?>
			var CustomContentLayoutClass = ymaps.templateLayoutFactory.createClass(
				'<div class="price <?=(intval($arPoint["MAP_PRICE"])>0)?"":"hide"?>" style=" border-radius:5px; text-align:center; color:#ef5a54; font-weight:bold; background:#ffffff; margin-top: 15px;">' +

				'</div>'
			);		
		
            myPlacemark_<?=$key?> = new ymaps.Placemark([<?=$arPoint["UF_MAP"]?>], {
                hintContent: '<?=$arPoint["NAME"]?>',
                balloonContent: '<div><img src="<?=SITE_TEMPLATE_PATH?>/images/custom/map-logo-w.png" height="16px" style="margin-top: -5px;" /> <span style="font-size: 16px; font-weight: bold;"><?=$arPoint["NAME"]?></span></div>'+
                                '<div><?=$arPoint["UF_ADDRESS"]?></div>'+
                                '<div style="margin-top: 10px; font-weight: bold;">Режим работы:</div>'+
                                '<?if(strlen($arPoint["UF_RECEPTION"])>0) {?><div>Ресепшн: <?=$arPoint["UF_RECEPTION"]?></div><?}?>'+
                                '<?if(strlen($arPoint["UF_DOSTUP_TIME"])>0) {?><div>Доступ на склад: <?=$arPoint["UF_DOSTUP_TIME"]?></div><?}?>'+
                                '<?if(strlen($arPoint["UF_PHONE"])>0) { $phone = preg_replace('/[^\d+]/', '', $arPoint["UF_PHONE"]);?><div>Телефон: <a href="tel:<?=$phone?>" class="dark-color"><?=$arPoint["UF_PHONE"]?></a></div><?}?>'+
								'<a class="btn btn-default btn-xs" href="/rental_catalog/<?=$arPoint["CODE"]?>/" style="margin-top:10px;">Арендовать</a>',
				iconContent: '<?=$arPoint["MAP_PRICE"]?>',
			}, {
                // Опции.
                // Необходимо указать данный тип макета.
                iconLayout: 'default#imageWithContent',
                // Своё изображение иконки метки.
                iconImageHref: '<?=SITE_TEMPLATE_PATH?>/images/custom/map-logo-<?=($arPoint["CHECKED_ON_MAP"]=="Y")?"r":"w"?>.png',
                // Размеры метки.
                iconImageSize: [50, 50],
                // Смещение левого верхнего угла иконки относительно
                // её "ножки" (точки привязки).
                iconImageOffset: [-25, -25],
                // Смещение слоя с содержимым относительно слоя с картинкой.
				iconContentOffset: [55, 0],
				iconContentLayout: CustomContentLayoutClass,
				iconContentSize: [100, 50],
            });

            myMap.geoObjects.add(myPlacemark_<?=$key?>);
        <?}?>

        <?if(count($arResult["MAP_POINTS"] == 1)):?>
            myMap.setCenter(myMap.geoObjects.getBounds()[0], 12);
        <?else:?>
            myMap.setBounds(myMap.geoObjects.getBounds(), {useMapMargin: true, zoomMargin: 15});
        <?endif;?>
    }
</script>            
<div id="map" style="width: 100%; height: 400px"></div>
<?
$DinamicData["MAP"] .= @ob_get_contents();
ob_get_clean(); 
?>
<?$arResult["CACHED_TPL"] = preg_replace_callback(
    "/#MAP#/is".BX_UTF_PCRE_MODIFIER,
    create_function('$matches', 'ob_start();
    echo $GLOBALS["DinamicData"]["MAP"];   
    $retrunStr = @ob_get_contents();
    ob_get_clean();
    return $retrunStr;'),
    $arResult["CACHED_TPL"]);
?>




<?
global $arrArticlesForSklad;
$arrArticlesForSklad["ID"] = $arResult["SECTION"]["UF_ARTICLES"];
?>
<?$arResult["CACHED_TPL"] = preg_replace_callback(
    "/#ARTICLES#/is".BX_UTF_PCRE_MODIFIER,
    create_function('$matches', 'ob_start();
    $GLOBALS["APPLICATION"]->IncludeComponent(
        "bitrix:news.list", 
        "news_list_4_v2", 
        array(
            "COMPONENT_TEMPLATE" => "news_list_4_v2",
            "IBLOCK_TYPE" => "aspro_priority_content",
            "IBLOCK_ID" => "33",
            "NEWS_COUNT" => "24",
            "SORT_BY1" => "ACTIVE_FROM",
            "SORT_ORDER1" => "DESC",
            "SORT_BY2" => "ID",
            "SORT_ORDER2" => "DESC",
            "FILTER_NAME" => "arrArticlesForSklad",
            "FIELD_CODE" => array(
                0 => "NAME",
                1 => "PREVIEW_PICTURE",
                2 => "DATE_ACTIVE_FROM",
            ),
            "PROPERTY_CODE" => array(
                0 => "",
                1 => "",
            ),
            "CHECK_DATES" => "Y",
            "DETAIL_URL" => "",
            "AJAX_MODE" => "N",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "AJAX_OPTION_HISTORY" => "N",
            "AJAX_OPTION_ADDITIONAL" => "",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "60",
            "CACHE_FILTER" => "N",
            "CACHE_GROUPS" => "N",
            "PREVIEW_TRUNCATE_LEN" => "",
            "ACTIVE_DATE_FORMAT" => "j F Y",
            "SET_TITLE" => "N",
            "SET_BROWSER_TITLE" => "N",
            "SET_META_KEYWORDS" => "N",
            "SET_META_DESCRIPTION" => "N",
            "SET_LAST_MODIFIED" => "N",
            "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
            "ADD_SECTIONS_CHAIN" => "N",
            "HIDE_LINK_WHEN_NO_DETAIL" => "N",
            "PARENT_SECTION" => "",
            "PARENT_SECTION_CODE" => "b-mainpage-news",
            "INCLUDE_SUBSECTIONS" => "N",
            "STRICT_SECTION_CHECK" => "N",
            "DISPLAY_DATE" => "N",
            "DISPLAY_NAME" => "N",
            "DISPLAY_PICTURE" => "N",
            "DISPLAY_PREVIEW_TEXT" => "N",
            "PAGER_TEMPLATE" => ".default",
            "DISPLAY_TOP_PAGER" => "N",
            "DISPLAY_BOTTOM_PAGER" => "N",
            "PAGER_TITLE" => "Новости",
            "PAGER_SHOW_ALWAYS" => "N",
            "PAGER_DESC_NUMBERING" => "N",
            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
            "PAGER_SHOW_ALL" => "N",
            "PAGER_BASE_LINK_ENABLE" => "N",
            "SET_STATUS_404" => "N",
            "SHOW_404" => "N",
            "MESSAGE_404" => ""
        ),
        false,
		Array("HIDE_ICONS"=>"Y")
    );
    $retrunStr = @ob_get_contents();
    ob_get_clean();
    return $retrunStr;'),
    $arResult["CACHED_TPL"]);
?>

<?
$GLOBALS["SKLAD_CODE"] = $arResult["SECTION"]["CODE"];
?>
<?$arResult["CACHED_TPL"] = preg_replace_callback(
	"/#BOX_FILTER#/is".BX_UTF_PCRE_MODIFIER,
	create_function('$matches', 'ob_start();
	$GLOBALS["APPLICATION"]->IncludeComponent(
		"custom:filter.rental_catalog", 
		".default", 
		array(
			"COMPONENT_TEMPLATE" => ".default",
			"SKLAD_CODE" => $GLOBALS["SKLAD_CODE"],
			"FLOOR_CODE" => "",
			"BOXES_LIST" => "",
			"SIMPLE_VIEW" => "Y"
		),
		false,
		Array("HIDE_ICONS"=>"Y")
	);
	$retrunStr = @ob_get_contents();
	ob_get_clean();
	return $retrunStr;'),
	$arResult["CACHED_TPL"]);
?>
<?$arResult["CACHED_TPL"] = preg_replace_callback(
	"/#BOX_FILTER_SLIDER#/is".BX_UTF_PCRE_MODIFIER,
	create_function('$matches', 'ob_start();
	$GLOBALS["APPLICATION"]->IncludeComponent(
		"bitrix:catalog.section.list", 
		"fotogalereya_skladov_rental_catallog", 
		array(
			"ADD_SECTIONS_CHAIN" => "N",
			"CACHE_FILTER" => "N",
			"CACHE_GROUPS" => "N",
			"CACHE_TIME" => "36000",
			"CACHE_TYPE" => "A",
			"COMPONENT_TEMPLATE" => "fotogalereya_skladov_rental_catallog",
			"COUNT_ELEMENTS" => "N",
			"FILTER_NAME" => "",
			"IBLOCK_ID" => "40",
			"IBLOCK_TYPE" => "aspro_priority_catalog",
			"SECTION_CODE" => $GLOBALS["SKLAD_CODE"],
			"SECTION_FIELDS" => array(
				0 => "NAME",
				1 => "PICTURE",
				2 => "",
			),
			"SECTION_ID" => "",
			"SECTION_URL" => "",
			"SECTION_USER_FIELDS" => array(
				0 => "UF_PHOTOGALLERY",
				1 => "",
			),
			"SHOW_PARENT_NAME" => "Y",
			"TOP_DEPTH" => "2",
			"VIEW_MODE" => "LINE",
			"SIZE_FROM" => "1"
		),
		false,
		Array("HIDE_ICONS"=>"Y")
	);
	$retrunStr = @ob_get_contents();
	ob_get_clean();
	return $retrunStr;'),
	$arResult["CACHED_TPL"]);
?>




<?// вывод
echo $arResult["CACHED_TPL"];
include $_SERVER['DOCUMENT_ROOT'] . '/include/seo/setDescription.php';



?>