$(document).ready(function() {
    
	$('html').on('click', '.cabinet_balance .fixed_sum_btn', function() {
		$('.cabinet_balance .fixed_sum_btn').addClass('btn-transparent');
		$(this).removeClass('btn-transparent');
		$('.cabinet_balance .add_balance').attr('data-product-id', $(this).attr('data-product-id'));
		$('.cabinet_balance .add_balance').removeClass('disabled');
		$('.cabinet_balance .custom_sum_container input').val('');
		$('.cabinet_balance .add_balance').removeAttr('data-custom-sum');
	})
	
	$('.cabinet_balance input[name=CONTRACT_NUMBER]').on('input', function() { 
		if ($('.cabinet_balance #find_conract_2').length) {
			if ($(this).val().length == 0) {
				$('.cabinet_balance #find_conract_2').addClass('disabled').removeClass('hidden');
				$('.cabinet_balance .add_balance').text('Пополнить').addClass('hidden');
				$('.cabinet_balance .custom_sum').val('').attr('disabled', 'disabled');
				$('.contract-info div').html('');
				$('.contract-info').addClass('hidden');
			} else {
				$('.cabinet_balance #find_conract_2').removeClass('disabled');
			}
		}
	});

	$('.cabinet_balance .custom_sum_container input').on('input', function() { 
		$('.cabinet_balance .fixed_sum_btn').addClass('btn-transparent');
		
		var val = parseFloat($(this).val().replace(',', '.').replace(' ', ''));
		
		if(val > 0) {
			$('.cabinet_balance .add_balance').attr('data-product-id', $(this).attr('data-product-id'));
			$('.cabinet_balance .add_balance').attr('data-custom-sum', val);
			$('.cabinet_balance .add_balance').removeClass('disabled');
			if ($('.cabinet_balance #find_conract_2').length)
				$('.cabinet_balance #find_conract_2').removeClass('disabled');
		} else {
			$('.cabinet_balance .add_balance').addClass('disabled');
			$('.cabinet_balance .add_balance').removeAttr('data-custom-sum');
			if ($('.cabinet_balance #find_conract_2').length) {
				$('.cabinet_balance #find_conract_2').addClass('disabled');
			}
		}
	});	
	
	$('.cabinet_balance .contract_container select').on('change', function() {
		var CONTRACT_ID = $(this).find(':selected').attr('data-contract-id');
		window.location = window.location.href.split('?')[0] + '?CONTRACT_ID=' + CONTRACT_ID;
	});	

	// if ($('.cabinet_balance .contract_container input').length > 0)
	// 	$('.cabinet_balance .contract_container input').inputmask('mask', {'mask': '9/9999' });
	
	var showError = function(text) {
		$('.cabinet_balance .sale-acounterror-block').text(text).removeClass('hidden');
	}

	var hideError = function(text) {
		$('.cabinet_balance .sale-acounterror-block').text('').addClass('hidden');
	}

	var findcontract = function(pr = false) {
		
		var search = $('.cabinet_balance input[name=CONTRACT_NUMBER]').val();

		if (search.length == 0) {
			return;
		}

		hideError();

		$.post("/local/templates/aspro-priority/components/bitrix/news.list/add_balance_free/ajax.php", {
			ACTION: "FIND_CONTRACT", 
			"CONTRACT_NUMBER": search, 
		}, function(data){
			if (data.success == 'Y') {

				if (data.name && data.last_name) {
					var info = 'Клиент: ' + data.name +  ' ' + (data.last_name.substr(0, 1)) + '.';	
				} else if (data.name && !data.last_name) {
					var info = 'Клиент: ' + data.name;
				} else if (!data.name && data.last_name) {
					var info = 'Клиент: ' + data.last_name;
				} else {
					var info = 'Клиент: -';
				}

				// if (data.phone) {
				// 	info += '<br>Телефон: ' + data.phone;
				// }

				if (info) {
					$('.contract-info div').html(info).removeClass('hidden');
					$('.contract-info').removeClass('hidden');
				}

				if (data.last_invoice_sum) {
					$('.cabinet_balance .custom_sum_container input').val(data.last_invoice_sum);
				} else {
					$('.cabinet_balance .custom_sum_container input').val('');
				}

				document.querySelector('.cabinet_balance .custom_sum_container input').dispatchEvent(new Event('input'));

				if (pr) {
					$('.cabinet_balance .add_balance').text('Подтвердить и пополнить');
				}

				$('.cabinet_balance #find_conract_2').addClass('hidden');
				$('.cabinet_balance .add_balance').removeClass('hidden');

				$('.cabinet_balance .custom_sum').removeAttr('disabled');

			} else {
				$('.contract-info div').html('');
				$('.contract-info').addClass('hidden');
				showError(data.error);
			}
		}, 'JSON');
	}

	$('html').on('click', '.cabinet_balance #find_conract', function() {
		findcontract();
		return false;
	});

	$('html').on('click', '.cabinet_balance #find_conract_2', function() {
		findcontract(true);
		return false;
	});

	$('html').on('click', '.cabinet_balance .add_balance', function() {
		var btn = $(this);
		var PRODUCT_ID = $(this).attr('data-product-id');
		var CUSTOM_SUM = $(this).attr('data-custom-sum');
		var CONTRACT_ID = $('.cabinet_balance .contract_container select').find(':selected').attr('data-contract-id');
		var CONTRACT_GUID = $('.cabinet_balance .contract_container select').find(':selected').attr('data-contract-guid');

		if ($('.cabinet_balance .contract_container select').length == 1) {
			var CONTRACT_NUMBER = $('.cabinet_balance .contract_container select').find(':selected').val();
		} else if ($('.cabinet_balance .contract_container input').length == 1) {
			var CONTRACT_NUMBER = $('.cabinet_balance .contract_container input').val();
		}
		
		var BOX_ID = $('.cabinet_balance .contract_container select').find(':selected').attr('data-box-id');
		
		if(PRODUCT_ID) {
			if($(btn).attr("data-ctrl") != "Y") {
				$(btn).attr("data-ctrl", "Y");
				
				hideError();

				$.post("/local/templates/aspro-priority/components/bitrix/news.list/add_balance_free/ajax.php", {
					ACTION:"ADD_BALANCE", 
					"PRODUCT_ID":PRODUCT_ID, 
					"CUSTOM_SUM":CUSTOM_SUM, 
					"CONTRACT_ID":CONTRACT_ID, 
					"CONTRACT_GUID":CONTRACT_GUID, 
					"CONTRACT_NUMBER":CONTRACT_NUMBER, 
					"BOX_ID":BOX_ID
				}, function(data){
					$(btn).attr("data-ctrl", "N");

					if (data && data.success == 'Y' && data.url) {
						window.location.href = data.url;
					} else if (data && data.success != 'Y' && data.error) {
						showError(data.error);
					}
				}, 'JSON');	
			}
		}
	});

	findcontract();
});