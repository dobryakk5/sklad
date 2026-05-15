$(document).ready(function() {
    $('html').on('click', '.item-views .catalog_opener.mobile', function() {
		$(this).toggleClass('closed');
		$(this).parents('.item-views').find('.items').slideToggle();
	})
});