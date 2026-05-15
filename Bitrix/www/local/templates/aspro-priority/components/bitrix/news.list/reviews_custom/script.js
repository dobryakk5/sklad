$(document).ready(function() {

	$('html').on('click', '.reviews-block .pagination-block .ajax_load_reviews', function() {
		var btn = $(this);
		var PAGEN_1 = $(this).attr('data-page');
		var SECTION_ID = $(this).attr('data-section-id');
		var SKLAD_ID = $(this).attr('data-sklad-id');
		var NEWS_COUNT = $(this).attr('data-count');

		$.post("/local/templates/aspro-priority/components/bitrix/news.list/reviews_custom/ajax.php", {action:"LOAD_REVIEWS", PAGEN_1: PAGEN_1, SECTION_ID: SECTION_ID, SKLAD_ID:SKLAD_ID, NEWS_COUNT: NEWS_COUNT}, function(data) {
			$(btn).closest('.reviews-list').append(data);
			$(btn).remove();
		})
	})

});