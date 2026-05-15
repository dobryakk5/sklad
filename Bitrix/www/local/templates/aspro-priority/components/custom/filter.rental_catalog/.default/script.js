$(document).ready(function() {
	//изменение типа "размер бокса" 
	$('html').on('change', '.box_filter .filter_block select.PROP_SIZE', function() {
		window.location.href = window.location.pathname+'?propSize='+$(this).val();
	});

	var PROP_SIZE = BX.message('PROP_SIZE');
	var postfix_size = ' м<sup>2</sup>';
	if(PROP_SIZE == 'VOLUME') {
		postfix_size = ' м<sup>3</sup>';
	}
	
	// селектор выбора размера бокса
	var $range_size = $('.box_filter .filter_block .range_'+PROP_SIZE);

	grid_num = BX.message(PROP_SIZE+'_TO') - 1;

    $range_size.ionRangeSlider({
		skin: 'round',
		type: 'double',
		step: 0.5,
        min: 1,
        max: BX.message(PROP_SIZE+'_TO'),
        from: 1,
        to: BX.message(PROP_SIZE+'_TO'),
        grid: true,
		grid_num: grid_num,
		postfix: postfix_size,
    });
	var timeoutID_sendAjaxData;
    $range_size.on("change", function () {
        var $inp = $(this);
        // var size_to = parseInt($inp.data("to"));
        // var size_from = parseInt($inp.data("from"));
		var size_to = parseFloat($inp.data("to"));
		var size_from = parseFloat($inp.data("from"));
		var sklad_code = $('.box_filter .sklad_code').val();
		var floor_code = $('.box_filter .floor_code').val();
		var boxes_list = $('.box_filter .boxes_list').val();

		clearInterval(timeoutID_sendAjaxData);
		timeoutID_sendAjaxData = setTimeout(sendAjaxData, 1000, PROP_SIZE, size_from, size_to, sklad_code, floor_code, boxes_list, 1);	

		$('.rental_catalog .ajaxPreloader').show();
		$('.rental_catalog_slider .ajaxPreloader').show();
		$('.map-floor .mapsvg .ajaxPreloader').show();
		
		//формируем ссылку с кнопки "Перейти к выбору бокса" при упрощенном отображении
		var def_url = $('.box_filter .buttons .simple_view_url').attr('data-href');
		$('.box_filter .buttons .simple_view_url').attr('href', def_url+'?'+PROP_SIZE+'_FROM='+size_from+'&'+PROP_SIZE+'_TO='+size_to);
		
		//обновляем ссылки в блоке сортировки списка
		$('.sort-box-list .sort-item').each(function(index) {
			var href = $(this).attr('href');
			var param = '';
			var paramVal = '';
			
			param = PROP_SIZE+'_FROM';
			paramVal = size_from;
			href = updateUrlParameter(href, param, paramVal);

			param = PROP_SIZE+'_TO';
			paramVal = size_to;
			href = updateUrlParameter(href, param, paramVal);
			
			$(this).attr('href', href);			
		});		
    });		
	
	//Связь селектора выбора размера бокса и GET параметра SQUARE_FROM/VOLUME_FROM
	var range_size_instance = $range_size.data("ionRangeSlider");
	var size_from = parseInt(getUrlParameter(window.location.search.substring(1), PROP_SIZE+'_FROM'));
	var size_to = parseInt(getUrlParameter(window.location.search.substring(1), PROP_SIZE+'_TO'));
	if(size_from > 0) {
		range_size_instance.update({
			from: size_from,
		});		
	}
	if(size_to > 0) {
		range_size_instance.update({
			to: size_to,
		});		
	}	
	
	
	
	// селектор выбора месяцев
	var $range_months = $('.box_filter .filter_block .range_months');
    $range_months.ionRangeSlider({
		skin: 'round',
        min: 0,
        max: 12,
		from_min: 1,
        from: 1,
        grid: true,
		grid_num: 12,
		postfix: " мес.",		
    });
	// Связь селектора выбора месяцев и кнопок со скидками
    $range_months.on("change", function () {
        var $inp = $(this);
        var monthsCnt = parseInt($inp.data("from"));
		
		$('.box_filter .sale_block .sale_btn .icon-text').addClass('grey');
		$('.box_filter .sale_block .sale_btn').each(function(index) {
			var saleBtnFrom = parseInt($(this).find('.icon-text').attr('data-from'));
			var saleBtnTo = parseInt($(this).find('.icon-text').attr('data-to'));
			
			if((monthsCnt >= saleBtnFrom) && (monthsCnt <= saleBtnTo)) {
				$(this).children('.icon-text').removeClass('grey');
			}
		});
		
		$('.rental_catalog_list .buy_block .counter .input input').val($range_months.data('from')).trigger('input');
		
		//обновляем ссылки в блоке сортировки списка
		$('.sort-box-list .sort-item').each(function(index) {
			var href = $(this).attr('href');
			var param = '';
			var paramVal = '';
			
			param = 'MONTHS';
			paramVal = monthsCnt;
			href = updateUrlParameter(href, param, paramVal);
			
			$(this).attr('href', href);			
		});			
    });
	var range_months_instance = $range_months.data("ionRangeSlider");
    $('html').on('click', '.box_filter .sale_block .sale_btn .icon-text', function () {
        var monthsCnt = $(this).attr('data-from');   
        range_months_instance.update({
            from: monthsCnt,
        });
    });
	//Связь селектора выбора срока аренды и GET параметра MONTHS
	var monthsCnt = parseInt(getUrlParameter(window.location.search.substring(1), 'MONTHS'));
	if(monthsCnt > 0) {
		range_months_instance.update({
			from: monthsCnt,
		});		
	}
	
	//постраничка
	$('body').on('click', '.rental_catalog_list .pagination li a', function(e) {
		e.preventDefault();
		var pagenUrl = $(this).attr('href');		
		pagenUrl = pagenUrl.substr(pagenUrl.indexOf('?')+1);
		var pagen = parseInt(getUrlParameter(pagenUrl, 'PAGEN_1'));
		
		var sklad_code = $('.box_filter .sklad_code').val();
		var floor_code = $('.box_filter .floor_code').val();		
		var boxes_list = $('.box_filter .boxes_list').val();		
		sendAjaxData(PROP_SIZE, $range_size.data('from'), $range_size.data('to'), sklad_code, floor_code, boxes_list, pagen);
		$('html').animate({
			scrollTop: $(".rental_catalog").offset().top-200,
		}, 1000);		
	})
	
	
	//чекбокс "Показать только ячейки"
	$('body').on('click', '.only_cells .checkbox_container', function() {
		if($(this).find(":disabled").length){
			return;
		}
        // if($(this).find('input[type=checkbox]').prop('checked')) {
        //     $(this).find('input[type=checkbox]').prop('checked', false);
        // } else {
        //     $(this).find('input[type=checkbox]').prop('checked', true);
        // }
            $(this).find('input[type=radio]').prop('checked', true);

		var sklad_code = $('.box_filter .sklad_code').val();
		var floor_code = $('.box_filter .floor_code').val();		
		var boxes_list = $('.box_filter .boxes_list').val();		
		sendAjaxData(PROP_SIZE, $range_size.data('from'), $range_size.data('to'), sklad_code, floor_code, boxes_list, 1);
		$('.rental_catalog .ajaxPreloader').show();
		$('.rental_catalog_slider .ajaxPreloader').show();
		$('.map-floor .mapsvg .ajaxPreloader').show();		
	})	
	
	
	
	
	//ajax обновление данных о боксах
	function sendAjaxData(PROP_SIZE, size_from, size_to, sklad_code, floor_code, boxes_list, pagen) {
		//Проверяем чекбокс "Показать только ячейки"
		var SHOW_ONLY_CELLS = '';
		// if($('.only_cells .checkbox_container input[type=checkbox]').prop('checked')) {
		// 	SHOW_ONLY_CELLS = 'Y';
		// }

		SHOW_ONLY_CELLS = $('.only_cells .checkbox_container input[type=radio]:checked').val();


		$.post("/local/templates/aspro-priority/components/custom/filter.rental_catalog/.default/ajax.php", {ACTION:"FILTER", "SKLAD_CODE":sklad_code, "FLOOR_CODE":floor_code, "PROP_SIZE":PROP_SIZE, "SIZE_FROM":size_from, "SIZE_TO":size_to, "FILTERED_BOXES_LIST":boxes_list, "PAGEN_1":pagen, "SHOW_ONLY_CELLS":SHOW_ONLY_CELLS}, function(data){
			$('.ajax_rentalCatalogList').empty().append(data);
			$('.rental_catalog_list .buy_block .counter .input input').val($range_months.data('from')).trigger('input');
			setTimeout(function(){
				$('.rental_catalog .ajaxPreloader').hide();
			}, 500);			
		});
		$.post("/local/templates/aspro-priority/components/custom/filter.rental_catalog/.default/ajax.php", {ACTION:"UPDATE_MIN_PRICE", "SKLAD_CODE":sklad_code, "FLOOR_CODE":floor_code, "PROP_SIZE":PROP_SIZE, "SIZE_FROM":size_from, "SIZE_TO":size_to, "FILTERED_BOXES_LIST":boxes_list, "SHOW_ONLY_CELLS":SHOW_ONLY_CELLS}, function(data){
			$('.box_filter .start_price .price').empty().append(data);			
		});		
		$.post("/local/templates/aspro-priority/components/custom/filter.rental_catalog/.default/ajax.php", {ACTION:"UPDATE_SLIDER", "SKLAD_CODE":sklad_code, "FLOOR_CODE":floor_code, "PROP_SIZE":PROP_SIZE, "SIZE_FROM":size_from, "SIZE_TO":size_to, "FILTERED_BOXES_LIST":boxes_list, "SHOW_ONLY_CELLS":SHOW_ONLY_CELLS}, function(data){
			$('.ajax_rentalCatalogListSlider').empty().append(data);
			$('.box_filter_slider #carousel').flexslider({
				animation: 'slide',
				controlNav: false,
				animationLoop: false,
				slideshow: false,
				itemWidth: 100,
				itemMargin: 12,
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
			setTimeout(function(){
				$('.rental_catalog_slider .ajaxPreloader').hide();
			}, 500);			
		});	
		$.post("/local/templates/aspro-priority/components/custom/filter.rental_catalog/.default/ajax.php", {ACTION:"UPDATE_MAP", "SKLAD_CODE":sklad_code, "FLOOR_CODE":floor_code, "PROP_SIZE":PROP_SIZE, "SIZE_FROM":size_from, "SIZE_TO":size_to, "FILTERED_BOXES_LIST":boxes_list, "SHOW_ONLY_CELLS":SHOW_ONLY_CELLS}, function(data){
			$('.map-floor .mapsvg svg').empty().append(data);
			$('.map-floor .mapsvg').html($('.map-floor .mapsvg').html()); //fix svg
			$('.map-floor .ajax_load_map_item').empty();
			$('.map-floor .mapsvg .ajaxPreloader').hide();
		});		
	}	
});





var getUrlParameter = function getUrlParameter(sUrl, sParam) {
    var sPageURL = decodeURIComponent(sUrl),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};

var updateUrlParameter = function updateUrlParameter(url, param, paramVal) {
    var TheAnchor = null;
    var newAdditionalURL = "";
    var tempArray = url.split("?");
    var baseURL = tempArray[0];
    var additionalURL = tempArray[1];
    var temp = "";

    if (additionalURL) 
    {
        var tmpAnchor = additionalURL.split("#");
        var TheParams = tmpAnchor[0];
            TheAnchor = tmpAnchor[1];
        if(TheAnchor)
            additionalURL = TheParams;

        tempArray = additionalURL.split("&");

        for (var i=0; i<tempArray.length; i++)
        {
            if(tempArray[i].split('=')[0] != param)
            {
                newAdditionalURL += temp + tempArray[i];
                temp = "&";
            }
        }        
    }
    else
    {
        var tmpAnchor = baseURL.split("#");
        var TheParams = tmpAnchor[0];
            TheAnchor  = tmpAnchor[1];

        if(TheParams)
            baseURL = TheParams;
    }

    if(TheAnchor)
        paramVal += "#" + TheAnchor;

    var rows_txt = temp + "" + param + "=" + paramVal;
    return baseURL + "?" + newAdditionalURL + rows_txt;
}