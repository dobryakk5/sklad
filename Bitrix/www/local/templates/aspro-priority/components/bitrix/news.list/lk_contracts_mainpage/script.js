$(document).ready(function() { 

	//кнопка Оплатить
    $('html').on('click', '.add_invoice_to_cart', function() {
        var btn = $(this);
        var INVOICE_ID = $(this).attr('data-invoice-id');

		if($(btn).attr('data-ctrl') != 'Y') {
			$(btn).attr('data-ctrl', 'Y');
			
			$.post('/bitrix/templates/aspro-priority/components/bitrix/news.list/lk_contracts_mainpage/ajax.php', {ACTION:'PAY_INVOICE', 'INVOICE_ID':INVOICE_ID}, function(data){
				$(btn).attr('data-ctrl', 'N');

				if(data.indexOf('OK') != -1) {
					window.location.href = '/order/';
				}
			});    
		}

    })  

});