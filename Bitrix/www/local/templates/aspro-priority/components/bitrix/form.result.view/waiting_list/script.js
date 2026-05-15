$(document).ready(function() {

	$('html').on('click', '.item.lk_table .table th .delete', function() {
        var btn = $(this);
		var ID = $(this).attr('data-id');
        
        if($(btn).attr("data-ctrl") != "Y") {
            $(btn).attr("data-ctrl", "Y");
            $.post($(btn).attr('data-action'), {ACTION:"DELETE", "ID":ID}, function(data){
                $(btn).attr("data-ctrl", "N");
				window.location.reload();
            });            
        }
	});

});