$(document).ready(function() {

	$('html').on('click', '.topblock-backround .topblock_find_storage .item .filter_use_block', function() {
		$('.topblock-backround .topblock_find_storage .item').removeClass('active');
		$(this).parents('.item').addClass('active');
		
		//scroll
		var target = $('.for-business-use');
		if (target.length) {
			$('html,body').animate({
				scrollTop: target.offset().top-160
			}, 1000);
		}
				
		//ajax
		var USETYPE_CODE = $(this).attr('data-usetype-code');
		var USETYPE_ID = $(this).attr('data-usetype-id');
		$.post("/ajax/useTypeFilter.php", {ACTION: "USE_TYPE_FILTER", USETYPE_CODE: USETYPE_CODE, USETYPE_ID: USETYPE_ID}, function(data) {			
			$('#ajax_useTypeBlock').empty().append(data);
		});		
	});

});