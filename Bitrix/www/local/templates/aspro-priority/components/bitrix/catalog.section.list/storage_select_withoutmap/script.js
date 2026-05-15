$(document).ready(function() {

	$("#modalSkladList").appendTo("body");

	//обнуляем чекбоксы при загрузке страницы (фикс бага в хроме)
	$('.storage_select_withmap .sklad_list .sklad .checkbox_container input[type=checkbox]').prop('checked', false);

    $('html').on('click', '.storage_select_withmap .sklad_list .sklad .checkbox_container', function() {        
        $('.storage_select_withmap .sklad_list .sklad .checkbox_container input[type=checkbox]').prop('checked', false);
                
        if($(this).find('input[type=checkbox]').prop('checked')) {
            $(this).find('input[type=checkbox]').prop('checked', false);
        } else {
            $(this).find('input[type=checkbox]').prop('checked', true);    
        }

        $('.storage_select_withmap .button_select_storage .btn').removeClass('disabled').attr('href', '/rental_catalog/'+$(this).find('input[type=checkbox]').attr('data-sklad-code')+'/');
    })
	
	//клик по блоку с названием склада
	$('html').on('click', '.storage_select_withmap .sklad_list .sklad .info', function() {
		$(this).parents('.sklad').find('.checkbox_container').trigger('click');
	})	

});