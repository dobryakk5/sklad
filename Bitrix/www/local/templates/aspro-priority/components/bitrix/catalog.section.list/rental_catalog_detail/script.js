$(document).ready(function() {

	//свернуть/развернуть блок с картой этажа 
	$('html').on('click', '.map-floor .button .showHideMapFloor', function() {
		var status = $(this).attr('data-status');
		if(status == 'show') {
			$('.map-floor .map-floor-container').slideUp();
			$(this).attr('data-status', 'hide');
			$(this).text('Показать схему боксов');
		}
		if(status == 'hide') {
			$('.map-floor .map-floor-container').slideDown();
			$(this).attr('data-status', 'show');
			$(this).text('Скрыть схему боксов');
		}

		//синхронизируем ширину svg карты и картинки карты
		$('.map-floor .mapsvg svg').css({width: $('.map-floor .mapsvg img').width()});
	})
	
	
	//клик по боксу на карте этажа
    $('html').on('click', '.map-floor .mapsvg svg .part.opened', function() {
        var btn = $(this);
        var ITEM_ID = $(this).attr('data-id');
		var $range_months = $('.box_filter .filter_block .range_months');
        var COUNT = $range_months.data('from');
        
        if($(btn).attr('data-ctrl') != 'Y') {
            $(btn).attr('data-ctrl', 'Y');

            $.post('/bitrix/templates/aspro-priority/components/bitrix/catalog.section.list/rental_catalog_detail/ajax.php', {ACTION:"SHOW_MAP_ITEM", "ITEM_ID":ITEM_ID, "COUNT":COUNT}, function(data) {
                $(btn).attr('data-ctrl', 'N');

				$('.map-floor .ajax_load_map_item').empty().append(data);
				
				$('.map-floor .mapsvg svg .part').attr('data-selected', 'N');
				$(btn).attr('data-selected', 'Y');
				
				
				var destination = $('.map-floor .ajax_load_map_item').offset().top;
				$('html').animate({scrollTop: destination-100}, 500);			
            })       
        }
    })	

});