<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
global $APPLICATION;
global $DinamicData;

$DinamicData = Array();

// $APPLICATION->AddHeadScript("https://api-maps.yandex.ru/2.1/?apikey=412ef885-28cc-476a-9cfd-5169683d9db4&lang=ru_RU");
$DinamicData["MAP"] = '';
ob_start();  
?>
<script type="text/javascript">
	function getYaMap(){
		var myMap = new ymaps.Map("map_3", {
			center: [55.76, 37.64],
			zoom: 9,
			controls: []
		});

		<?foreach($arResult["SECTIONS"] as $key=>$arPoint) {?>
			/*var CustomContentLayoutClass = ymaps.templateLayoutFactory.createClass(
				'<div class="price <?=(intval($arPoint["MAP_PRICE"])>0)?"":"hide"?>" style="border:1px solid #ef5a54; border-radius:5px; text-align:center; color:#ef5a54; font-weight:bold; background:#ffffff; margin-top: 15px;">' +
					'<span>{{ properties.iconContent }}</span>' +
				'</div>'
			);*/

        var CustomContentLayoutClass = ymaps.templateLayoutFactory.createClass(
            '<div class="price <?=(intval($arPoint["MAP_PRICE"])>0)?"":"hide"?>" style="border-radius:5px; text-align:center; color:#ef5a54; font-weight:bold; background:#ffffff; margin-top: 15px;">' +
            '</div>'
        );

        myPlacemark_<?=$key?> = new ymaps.Placemark([<?=$arPoint["UF_MAP"]?>], {
				hintContent: '<?=$arPoint["NAME"]?>',
				balloonContent: '<div><img src="<?=SITE_TEMPLATE_PATH?>/images/custom/map-logo-w.png" height="16px" style="margin-top: -5px;" /> <span style="font-size: 16px; font-weight: bold;"><?=$arPoint["NAME"]?></span></div>'+
								'<div><?=$arPoint["UF_ADDRESS"]?></div>'+
								'<div style="margin-top: 10px; font-weight: bold;">Режим работы:</div>'+
								'<?if(strlen($arPoint["UF_RECEPTION"])>0) {?><div><?=(strlen($arPoint["UF_RECEPTION_NAME"]) > 0 ? $arPoint["UF_RECEPTION_NAME"] : 'Ресепшн')?>: <?=$arPoint["UF_RECEPTION"]?></div><?}?>'+
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
		
		myMap.setBounds(myMap.geoObjects.getBounds(), {useMapMargin: true, zoomMargin: 10});



		$('.map_redraw a[data-toggle="tab"]').on('shown.bs.tab', function(e){
			myMap.setZoom(10);
		});
	}
	ymaps.ready(getYaMap);
	
</script>

<div id="map_3" style="width: 100%; height: 350px;"></div>



<?
$DinamicData["MAP"] .= @ob_get_contents();
ob_get_clean(); 
?>


<?$arResult["CACHED_TPL"] = preg_replace_callback(
    "/#MAP#/is".BX_UTF_PCRE_MODIFIER,
    function($matches) {ob_start();
    echo $GLOBALS["DinamicData"]["MAP"];   
    $retrunStr = @ob_get_contents();
    ob_get_clean();
    return $retrunStr;},
    $arResult["CACHED_TPL"]);
?>




<?// вывод
echo $arResult["CACHED_TPL"];
?>