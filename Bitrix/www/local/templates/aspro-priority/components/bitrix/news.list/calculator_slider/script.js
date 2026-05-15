$(document).ready(function() {

	$('.box_filter_slider #carousel').flexslider({
		animation: 'slide',
		controlNav: false,
		animationLoop: false,
		slideshow: false,
		itemWidth: 86,
		itemMargin: 8,
		directionNav: false,
		touch: true,
		minItems: 2,
		maxItems: 8,
		asNavFor: '.box_filter_slider #slider',
		start: function(){
			$('.box_filter_slider').height('auto');
			$('.box_filter_slider #carousel').css({'width': 'auto', 'opacity': 1});
		}
	});

	$('.box_filter_slider #slider').flexslider({
		animation: 'slide',
		controlNav: false,
		animationLoop: false,
		slideshow: false,
		directionNav: true,
		sync: '.box_filter_slider #carousel',
	});

});