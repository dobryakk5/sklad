$(document).ready(function(){
	$('.item-views.company.front .company-block>.row>.item').sliceHeight();

	$('html').on('click', '.company-block .show_more_text', function() {
		$(this).css('visibility', 'hidden');
		$('.company-block .more_text').slideDown();
	})
});