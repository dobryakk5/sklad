$(document).ready(function() {
	$('html').on('click', '.select_storage_map .select_steps .step .link.map, .rental_catalog-nav-block .nav-item.bron .link.map', function() {
		$('.select_storage_map #map').addClass('active');
		setTimeout(function(){
			$('.select_storage_map #map').removeClass('active');
		}, 700);
	})
});