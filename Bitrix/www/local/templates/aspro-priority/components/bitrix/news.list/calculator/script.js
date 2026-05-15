$(document).ready(function() {

	var timeoutID_sendAjaxData;

	$('html').on('input', '.calculator .calculator_items_list .item .counter input', function() {
		var calculatorData = {};
		$('.calculator .calculator_items_list .item .counter input').each(function(index) {
			calculatorData[index] = {
				"CALC_ITEM_ID": $(this).attr('data-calc-item-id'),
				"CALC_ITEM_SQUARE": $(this).attr('data-calc-item-square'),
				"CALC_ITEM_COUNT": $(this).val()
			}
		})
		
		$('.calculator .ajaxPreloader').show();
		clearInterval(timeoutID_sendAjaxData);
		timeoutID_sendAjaxData = setTimeout(function(){
			$.post("/local/templates/aspro-priority/components/bitrix/news.list/calculator/ajax.php", {ACTION:"UPDATE", "CALCULATOR_DATA":calculatorData}, function(data){
				
				var result = JSON.parse(data);
				
				//square
				$('.calculator .square_result .val').empty().append(result['TOTAL_SQUARE']);

				//volume
				$('.calculator .square_result .val_kub').empty().append(result['TOTAL_VOLUME']);					
				
				//selected_items_list
				$('.calculator .calculator_result .selected_items_list .scroll').empty().append(result['SELECTED_ITEMS']);
				var scrolBlockHeight = $('.calculator .calculator_result .selected_items_list .scroll').height();
				if(scrolBlockHeight <= 360) {
					$('.calculator .calculator_result .selected_items_list .ss-content').css({width: 'auto'});
				} else {
					$('.calculator .calculator_result .selected_items_list .ss-content').css({width: 'calc(100% + 18px)'});
				}
				
				//slider
				$('.calculator .ajax_calculator_slider').empty().append(result['SLIDER']);
				$('.box_filter_slider #carousel').flexslider({
					animation: 'slide',
					controlNav: false,
					animationLoop: false,
					slideshow: false,
					itemWidth: 86,
					itemMargin: 8,
					directionNav: false,
					touch: true,
					minItems: 2,
					maxItems: 8,
					asNavFor: '.box_filter_slider #slider',
					start: function(){
						$('.box_filter_slider').height('auto');
						$('.box_filter_slider #carousel').css({'width': 'auto', 'opacity': 1});
					}
				});
				$('.box_filter_slider #slider').flexslider({
					animation: 'slide',
					controlNav: false,
					animationLoop: false,
					slideshow: false,
					directionNav: true,
					sync: '.box_filter_slider #carousel',
				});
				
				//mobile_slider
				$('.calculator .ajax_calculator_mobile_slider').empty().append(result['MOBILE_SLIDER']);
				$('.box_filter_slider #mobile_carousel').flexslider({
					animation: 'slide',
					controlNav: false,
					animationLoop: false,
					slideshow: false,
					itemWidth: 86,
					itemMargin: 8,
					directionNav: false,
					touch: true,
					minItems: 2,
					maxItems: 8,
					asNavFor: '.box_filter_slider #mobile_slider',
					start: function(){
						$('.box_filter_slider').height('auto');
						$('.box_filter_slider #mobile_carousel').css({'width': 'auto', 'opacity': 1});
					}
				});
				$('.box_filter_slider #mobile_slider').flexslider({
					animation: 'slide',
					controlNav: false,
					animationLoop: false,
					slideshow: false,
					directionNav: true,
					sync: '.box_filter_slider #mobile_carousel',
				});				

				//sklad_list
				$('.calculator .calculator_result_bottom .sklad_list_container').empty().append(result['SKLAD_LIST']);
				var sklad_list = document.querySelector('.calculator .calculator_result_bottom .sklad_list_container .sklad_list');
				SimpleScrollbar.initEl(sklad_list);
								
				//preloader
				setTimeout(function(){
					$('.calculator .ajaxPreloader').hide();
				}, 500);				
			}); 
		}, 1000);				
	})
	
	$('html').on('click', '.calculator .calculator_result .selected_items_list .item .delete', function() {
		var itemId = $(this).attr('data-calc-item-id');
		$('.calculator .calculator_items_list .item .counter input[data-calc-item-id='+itemId+']').val(0).trigger('input');
	})
	
	$('html').on('click', '.calculator .calculator_result_bottom .sklad_list .sklad .checkbox_container', function() {		
		$('.calculator .calculator_result_bottom .sklad_list .sklad .checkbox_container input[type=checkbox]').prop('checked', false);
				
		if($(this).find('input[type=checkbox]').prop('checked')) {
			$(this).find('input[type=checkbox]').prop('checked', false);
		} else {
			$(this).find('input[type=checkbox]').prop('checked', true);    
		}

		var square_from = Math.ceil(parseFloat($('.calculator .calculator_result_bottom .square_result .val').text()));
		var sort = 'property_SQUARE';
		var order = 'asc';
		if(square_from < 1) {
			square_from = 1;
		}
		if(square_from > 15) {
			square_from = 1;
			order = 'desc';
		}		
		$('.calculator .calculator_result_bottom .sklad_list_container .button .btn').removeClass('disabled').attr('href', '/rental_catalog/'+$(this).find('input[type=checkbox]').attr('data-sklad-code')+'/?propSize=SQUARE&SQUARE_FROM='+square_from+'&sort='+sort+'&order='+order);
	})

	//клик по блоку с названием склада
	$('html').on('click', '.calculator .calculator_result_bottom .sklad_list .sklad .info ', function() {
		$(this).parents('.sklad').find('.checkbox_container').trigger('click');
	})	
});



document.addEventListener("wheel", function(event) {
	if (document.activeElement.type === "number" &&
		document.activeElement.classList.contains("noscroll")) {
		document.activeElement.blur();
	}
});