$(document).ready(function() {
    
	$('html').on('click', '.cabinet_balance .fixed_sum_btn', function() {
		$('.cabinet_balance .fixed_sum_btn').addClass('btn-transparent');
		$(this).removeClass('btn-transparent');
		$('.cabinet_balance .add_balance').attr('data-product-id', $(this).attr('data-product-id'));
		$('.cabinet_balance .add_balance').removeClass('disabled');
		$('.cabinet_balance .custom_sum_container input').val('');
		$('.cabinet_balance .add_balance').removeAttr('data-custom-sum');
	})
	
	$('.cabinet_balance .custom_sum_container input').on('input', function() { 
		$('.cabinet_balance .fixed_sum_btn').addClass('btn-transparent');
		
		var val = parseFloat($(this).val().replace(',', '.'));
		if(val > 0) {
			$('.cabinet_balance .add_balance').attr('data-product-id', $(this).attr('data-product-id'));
			$('.cabinet_balance .add_balance').attr('data-custom-sum', val);
			$('.cabinet_balance .add_balance').removeClass('disabled');

			if ($('.save_merchant'))
				$('.save_merchant').removeClass('disabled');
		} else {
			$('.cabinet_balance .add_balance').addClass('disabled');
			$('.cabinet_balance .add_balance').removeAttr('data-custom-sum');

			if ($('.save_merchant'))
				$('.save_merchant').addClass('disabled');
		}
	});	
	
	$('.cabinet_balance .contract_container select').on('change', function() {
		var CONTRACT_ID = $(this).find(':selected').attr('data-contract-id');
		window.location = window.location.href.split('?')[0] + '?CONTRACT_ID=' + CONTRACT_ID;
	});	
	
	$('html').on('click', '.cabinet_balance .add_balance', function() {
		var btn = $(this);
		var PRODUCT_ID = $(this).attr('data-product-id');
		var CUSTOM_SUM = $(this).attr('data-custom-sum');
		var CONTRACT_ID = $('.cabinet_balance .contract_container select').find(':selected').attr('data-contract-id');
		var CONTRACT_GUID = $('.cabinet_balance .contract_container select').find(':selected').attr('data-contract-guid');
		var CONTRACT_NUMBER = $('.cabinet_balance .contract_container select').find(':selected').val();
		var BOX_ID = $('.cabinet_balance .contract_container select').find(':selected').attr('data-box-id');
		
		if(PRODUCT_ID) {
			if($(btn).attr("data-ctrl") != "Y") {
				$(btn).attr("data-ctrl", "Y");
				
				$.post("/local/templates/aspro-priority/components/bitrix/news.list/add_balance/ajax.php", {ACTION:"ADD_BALANCE", "PRODUCT_ID":PRODUCT_ID, "CUSTOM_SUM":CUSTOM_SUM, "CONTRACT_ID":CONTRACT_ID, "CONTRACT_GUID":CONTRACT_GUID, "CONTRACT_NUMBER":CONTRACT_NUMBER, "BOX_ID":BOX_ID}, function(data){
					$(btn).attr("data-ctrl", "N");

					if (data && data != 'autopay') {
						window.location.href = '/order/?ORDER_ID=' + data;
					} else if (data && data == 'autopay') {
						window.location.reload();
					}

					// if(data.indexOf("OK") != -1) {
					// 	window.location.href = '/order/';
					// }
				});	
			}
		}
	})
});