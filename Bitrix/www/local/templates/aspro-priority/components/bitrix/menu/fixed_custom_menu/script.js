$(document).ready(function() {
	var menuContent = $('.menu-content');
	var topMenu = $('#headerfixed');
	var menuContent_posX = menuContent.offset().top - topMenu.height();
	var menuContentBuffer = $('.menu-content-buffer');
	
	$(window).scroll(function () {
		if ($(window).width() > 991) {
			if ($(this).scrollTop() > menuContent_posX) {
				menuContent.addClass("fixed");
				menuContent.css({top: topMenu.height()});
				menuContentBuffer.css({height: menuContent.height()});
			} else {
				menuContent.removeClass("fixed");
				menuContent.css({top: 'auto'});
				menuContentBuffer.height(0);
			}
		}
	});	
	
	
	
	/**** Вычисление нужного размера пункта меню, чтобы вместить все в одну строку ****/
	var pageWidth = $('.menu-content .maxwidth-theme').width();
	var menuWidth = 0;
	var cntMenuItems = 0; //кол-во пунктов меню
	
	setTimeout(function() {
		$('.menu-content .menu-item').each(function(index) {
			menuWidth = menuWidth + $(this).width();
			cntMenuItems++;
		});
		
		if(menuWidth > pageWidth) {
			var overWidth = menuWidth - pageWidth;
			var correctWidth = Math.ceil( (overWidth/cntMenuItems)/2 );
			var currentPadding = parseInt($('.menu-content .menu-item .title a').css('padding-left'));
			
			$('.menu-content .menu-item .title a').css({paddingLeft: currentPadding - correctWidth, paddingRight: currentPadding - correctWidth});		
		}	  
	}, 500);	
	
});