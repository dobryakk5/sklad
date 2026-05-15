$( document ).ready(function() {
    //сохранение описи
    $('html').on('click', '.inventory .SAVE', function() {
        var btn = $(this);
	var inventory_id = $(btn).attr('data-inventory-id');
	var block_id = '#inv'+ inventory_id;

        var LOAD_DATA = new Object();
	LOAD_DATA['INVENTORY_ID'] = inventory_id;
	LOAD_DATA['DETAIL_TEXT'] = $(block_id).find('.DETAIL_TEXT').val();		
        
        if($(btn).attr("data-ctrl") != "Y") {
            $(btn).attr("data-ctrl", "Y");
            $.post($(this).attr('data-action'), {ACTION:"SAVE", "LOAD_DATA":LOAD_DATA}, function(data) {
                $(btn).attr("data-ctrl", "N");
                var myData = JSON.parse(data);
                if(myData['STATUS'] == 'OK') {
                    //window.location.reload();
                }
            })
        }
    });

});
