$(document).ready(function() {
	$(".modal.modal_box_on_floor_map").appendTo("body");

	$('.modal.modal_box_on_floor_map').on('shown.bs.modal', function () {
		//синхронизируем ширину svg карты и картинки карты
		$('.map-floor .mapsvg svg').css({width: $('.map-floor .mapsvg img').width()});
		//показываем карту
		setTimeout(function(){
			$('.map-floor .mapsvg img').css({visibility: "visible"});
		}, 300);
	})

	$('.modal.modal_box_on_floor_map').on('hidden.bs.modal', function () {
		//скрываем карту
		$('.map-floor .mapsvg img').css({visibility: "hidden"});
	})

});