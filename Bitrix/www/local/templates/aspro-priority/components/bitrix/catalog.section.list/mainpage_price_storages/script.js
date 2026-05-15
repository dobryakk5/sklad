$(document).ready(function() {
	$('html').on('click', '.mainpage_price_storages .sklad_list .sklad .closeopen', function() {
		$(this).parents('.sklad').find('.content').slideToggle();
		var text = $(this).text();
		if(text == 'Свернуть') {
			$(this).text('Развернуть');
		} else {
			$(this).text('Свернуть');
		}
	})

	$('html').on('click', '.mainpage_price_storages .size_block .sizes .size_button', function() {
		$('.mainpage_price_storages .size_block .sizes .size_button').removeClass('active');
		$(this).addClass('active');

		var square_from = $(this).attr('data-square-from');
		var square_to = $(this).attr('data-square-to');
		$('.mainpage_price_storages .ajaxPreloader').show();

        $.post("/local/templates/aspro-priority/components/bitrix/catalog.section.list/mainpage_price_storages/ajax.php", {ACTION:"UPDATE", "SQUARE_FROM":square_from, "SQUARE_TO":square_to}, function(data){
            $('.mainpage_price_storages .sklad_list .ajax_skladList').empty().append(data);       
            setTimeout(function(){
                $('.mainpage_price_storages .ajaxPreloader').hide();
            }, 500);            
        });
        /*$.post("/bitrix/templates/aspro-priority/components/bitrix/catalog.section.list/mainpage_price_storages/ajax.php", {ACTION:"MAP_UPDATE", "SQUARE_FROM":square_from, "SQUARE_TO":square_to}, function(data){
            $('.mainpage_price_storages .ajax_map').empty().append(data);           
        });*/
	})
});