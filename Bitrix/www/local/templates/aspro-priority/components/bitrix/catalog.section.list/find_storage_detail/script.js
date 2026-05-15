$(document).ready(function() {

	$('.photogallery_slider #carousel').flexslider({
		animation: 'slide',
		controlNav: false,
		animationLoop: false,
		slideshow: false,
		itemWidth: 100,
		itemMargin: 12,
		directionNav: false,
		touch: true,
		minItems: 2,
		maxItems: 8,
		asNavFor: '.photogallery_slider #slider',
		start: function(){
			$('.photogallery_slider').height('auto');
			$('.photogallery_slider #carousel').css({'width': 'auto', 'opacity': 1});
		}
	});

	$('.photogallery_slider #slider').flexslider({
		animation: 'slide',
		controlNav: true,
		animationLoop: false,
		slideshow: false,
		directionNav: true,
		sync: '.photogallery_slider #carousel',
	});

});