$(document).ready(function() {

	$('.gallery_mainpage #carousel').flexslider({
		animation: 'slide',
		controlNav: false,
		animationLoop: true,
		slideshow: true,
		itemWidth: 200,
		itemMargin: 12,
		directionNav: true,
		touch: true,
		minItems: 2,
		maxItems: 8,
		start: function(){
			$('.gallery_mainpage #carousel').height('auto');
			$('.gallery_mainpage #carousel').css({'width': 'auto', 'opacity': 1});
		}
	});

});