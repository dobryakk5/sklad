$(document).ready(function() {

    $('html').on('click', '.storage_select_withmap .sklad_list .sklad .checkbox_container', function() {        
        $('.storage_select_withmap .sklad_list .sklad .checkbox_container input[type=checkbox]').prop('checked', false);
                
        if($(this).find('input[type=checkbox]').prop('checked')) {
            $(this).find('input[type=checkbox]').prop('checked', false);
        } else {
            $(this).find('input[type=checkbox]').prop('checked', true);    
        }

        $('.storage_select_withmap .button_select_storage .btn').removeClass('disabled').attr('href', '/rental_catalog/'+$(this).find('input[type=checkbox]').attr('data-sklad-code')+'/');
		
		var selectedSkladId = $(this).find('input[type=checkbox]').attr('data-sklad-id');
		
        $.post("/bitrix/templates/aspro-priority/components/bitrix/catalog.section.list/storage_select_withmap/ajax.php", {ACTION:"MAP_UPDATE", "SELECTED_SKLAD_ID":selectedSkladId}, function(data){
            $('.storage_select_withmap .ajax_map').empty().append(data);           
        });		
    })

    //клик по блоку с названием склада
    $('html').on('click', '.storage_select_withmap .sklad_list .sklad .info', function() {
        $(this).parents('.sklad').find('.checkbox_container').trigger('click');
    })

});