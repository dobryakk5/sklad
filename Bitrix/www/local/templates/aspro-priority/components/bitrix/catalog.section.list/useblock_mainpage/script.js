$(document).ready(function() {

	$('html').on('click', '.useblock_mainpage .main_section .main_section_list .item:not(.active)', function() {
		var btn = $(this);
		var SECTION_ID = $(this).attr('data-section-id');

        $.post("/local/templates/aspro-priority/components/bitrix/catalog.section.list/useblock_mainpage/ajax.php", {ACTION: "UPDATE", SECTION_ID: SECTION_ID}, function(data) {
            $('#ajax_useblock_mainpage').empty().append(data);

			var lazyLoadInstance = new LazyLoad({
				elements_selector: ".lazy"
			});
			lazyLoadInstance.update();
        }); 		
	});

});