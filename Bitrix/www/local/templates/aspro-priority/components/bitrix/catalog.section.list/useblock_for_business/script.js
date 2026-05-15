$(document).ready(function() {

	$('html').on('click', '.for-business-use .use-items .button a', function() {
		$(this).addClass('hidden');
		$('.for-business-use .use-items .row.hidden').removeClass('hidden');
	});

});