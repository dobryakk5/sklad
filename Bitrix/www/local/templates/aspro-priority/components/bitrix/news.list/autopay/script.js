$(document).ready(function() {
    
	$('html').on('click', '.save_merchant, .forget_merchant', function () {

		var b = $(this);
		b.attr('disabled', 'disabled');

		$.ajax({
			type: "POST",
        	dataType: "json",
			url: "/local/templates/aspro-priority/components/bitrix/news.list/autopay/ajax.php", 
			data: {ACTION:"save_merchant"},
			success: function(data){
				if (data) {
					if (data.success == 'Y') {
						if (data.current == 0) {
							window.location.reload();
						} else {
							$('.cabinet_balance .add_balance').click();
						}
					} else {
						alert('Произошла ошибка при выполнении операции. Обратитесь к администрации');
						b.removeAttr('disabled');
					}
				} else {
					alert('Произошла ошибка при выполнении операции. Обратитесь к администрации');
					b.removeAttr('disabled');
				}
			},
			error: function(r) {
				b.removeAttr('disabled');
			}
		});

		return false;
	});

	$('html').on('click', '.link_card', function() {
		var b = $(this);
		b.attr('disabled', 'disabled');

		$.ajax({
			type: "POST",
        	dataType: "json",
			url: "/local/templates/aspro-priority/components/bitrix/news.list/autopay/ajax.php", 
			data: {ACTION:"link_card"},
			success: function(data){
				if (data) {
					if (data.success == 'Y' && data.url) {
						window.location.href = data.url;
					} else if (data.success == 'N' && data.error) {
						alert(data.error);
						b.removeAttr('disabled');
					} else {
						alert('Произошла ошибка при попытке подключения автоплатежа. Обратитесь к администрации');
						b.removeAttr('disabled');
					}
				} else {
					alert('Произошла ошибка при попытке отключения автоплатежа. Обратитесь к администрации');
					b.removeAttr('disabled');
				}
			},
			error: function(r) {
				b.removeAttr('disabled');
			}
		});

		return false;
	});
	
	$('html').on('click', '.plug_autopay', function() {
		var b = $(this);
		b.attr('disabled', 'disabled');

		$.ajax({
			type: "POST",
        	dataType: "json",
			url: "/local/templates/aspro-priority/components/bitrix/news.list/autopay/ajax.php", 
			data: {ACTION:"plug_autopay"},
			success: function(data){
				if (data) {
					if (data.success == 'Y' && data.url) {
						window.location.href = data.url;
					} else if (data.success == 'N' && data.error) {
						alert(data.error);
						b.removeAttr('disabled');
					} else {
						alert('Произошла ошибка при попытке подключения автоплатежа. Обратитесь к администрации');
						b.removeAttr('disabled');
					}
				} else {
					alert('Произошла ошибка при попытке отключения автоплатежа. Обратитесь к администрации');
					b.removeAttr('disabled');
				}
			},
			error: function(r) {
				b.removeAttr('disabled');
			}
		});

		return false;
	});

	$('html').on('click', '.unplug_autopay', function() {
		var b = $(this);
		b.attr('disabled', 'disabled');

		$.ajax({
			type: "POST",
        	dataType: "json",
			url: "/local/templates/aspro-priority/components/bitrix/news.list/autopay/ajax.php", 
			data: {ACTION:"unplug"},
			success: function(data){
				if (data) {
					if (data.success == 'Y') {
						window.location.reload();
					} else if (data.success == 'N' && data.error) {
						alert(data.error);
						b.removeAttr('disabled');
					} else {
						alert('Произошла ошибка при попытке отключения автоплатежа. Обратитесь к администрации');
						b.removeAttr('disabled');
					}
				} else {
					alert('Произошла ошибка при попытке отключения автоплатежа. Обратитесь к администрации');
					b.removeAttr('disabled');
				}
			},
			error: function(r) {
				b.removeAttr('disabled');
			}
		});

		return false;
	});
	
	$('html').on('click', '.unlink_card', function() {
		var b = $(this);
		b.attr('disabled', 'disabled');

		$.ajax({
			type: "POST",
        	dataType: "json",
			url: "/local/templates/aspro-priority/components/bitrix/news.list/autopay/ajax.php", 
			data: {ACTION:"unlink_card"},
			success: function(data){
				if (data) {
					if (data.success == 'Y') {
						window.location.reload();
					} else if (data.success == 'N' && data.error) {
						alert(data.error);
						b.removeAttr('disabled');
					} else {
						alert('Произошла ошибка при попытке отвязки карты. Обратитесь к администрации');
						b.removeAttr('disabled');
					}
				} else {
					alert('Произошла ошибка при попытке отвязки карты. Обратитесь к администрации');
					b.removeAttr('disabled');
				}
			},
			error: function(r) {
				b.removeAttr('disabled');
			}
		});

		return false;
	});
	
	$('html').on('click', '.autopay-accordion-toggle', function() {
		var b = $(this);
		b.hide();

		b.next('div').removeClass('hidden');

		return false;
	});

});