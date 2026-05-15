$(document).ready(function() {
	//проставляем чекбоксы у всех неоплачнных счетов
	var isAccessPay = false;
	$('.invoices_list .table tr td.selector .value input[type=checkbox]').each(function() {
		$(this).prop('checked', true);
		isAccessPay = true;
	})
	if(isAccessPay) {
		$('.invoices_list .buy_button').removeClass('disabled');
	}

	//выбор счетов для оплаты
    $('html').on('click', '.invoices_list .table tr td.selector .value', function() {        
        if($(this).find('input[type=checkbox]').prop('checked')) {
            $(this).find('input[type=checkbox]').prop('checked', false);
        } else {
            $(this).find('input[type=checkbox]').prop('checked', true);    
        }

		var isSelect = false;
		$('.invoices_list .table tr td.selector .value input[type=checkbox]').each(function() {
			if($(this).prop('checked')) {
				isSelect = true;
				return false;
			}
		})
		if(isSelect) {
			$('.invoices_list .buy_button').removeClass('disabled');
		} else {
			$('.invoices_list .buy_button').addClass('disabled');
		}
    }) 

	//кнопка Оплатить (общая)
    $('html').on('click', '.invoices_list .buy_button', function() {
        var btn = $(this);
        var INVOICES = [];
		$('.invoices_list .table tr td.selector .value input[type=checkbox]').each(function() {
			if($(this).prop('checked')) {
				INVOICES.push($(this).attr('data-invoice-id'));
			}
		})

        if(INVOICES.length != 0) {
            if($(btn).attr('data-ctrl') != 'Y') {
                $(btn).attr('data-ctrl', 'Y');
                
                $.post('/local/templates/aspro-priority/components/bitrix/news.list/lk_invoices_not_paid/ajax.php', {ACTION:'PAY_INVOICE', 'INVOICES':INVOICES}, function(data){
                    $(btn).attr('data-ctrl', 'N');

                    /*
                    if(data.indexOf('OK') != -1) {
                        window.location.href = '/order/';
                    }
                    */
                    
                    if (data) {
                        window.location.href = '/order/?ORDER_ID=' + data;
                    }
                });    
            }
        }
    })  
	
	//кнопка оплатить (отдельная для каждого счета)
    $('html').on('click', '.add_invoice_to_cart', function() {
        var btn = $(this);
		var INVOICES = [];
		INVOICES.push($(this).attr('data-invoice-id'));

        if($(btn).attr('data-ctrl') != 'Y') {
            $(btn).attr('data-ctrl', 'Y');
            
            $.post('/local/templates/aspro-priority/components/bitrix/news.list/lk_invoices_not_paid/ajax.php', {ACTION:'PAY_INVOICE', 'INVOICES':INVOICES}, function(data){
                $(btn).attr('data-ctrl', 'N');

                if (data) {
					window.location.href = '/order/?ORDER_ID=' + data;
				}
                /*
                if(data.indexOf('OK') != -1) {
                    window.location.href = '/order/';
                }
                */
            });    
        }

    })	

});