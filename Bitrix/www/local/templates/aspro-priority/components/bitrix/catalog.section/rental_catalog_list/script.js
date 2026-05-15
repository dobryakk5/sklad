$(document).ready(function() {

	$('body').on('input', '.rental_catalog_list .catalog_item .buy_block .counter .input input', function() {
		MonthsCounter($(this));
		
		var price = parseInt($(this).attr('data-price'));
		var months = parseInt($(this).val());
		var summa = price * months;
		$(this).parents('.buy_block').find('.sum .val').text(summa.toLocaleString('ru'));
	})
	
	$('body').on('click', '.rental_catalog_list .catalog_item .buy_block .counter .ctrl', function() {
		MonthsCounter($(this));
		$(this).parents('.counter').find('.input input').trigger('input');		
	})


	$('body').on('click', '.catalog_item .buy_button .add_to_cart', function() {
		var btn = $(this);
		var PRODUCT_ID = $(this).attr('data-product-id');
		var COUNT = $(this).parents('.catalog_item').find('.buy_block .counter .input input').val();
		var PRODUCT_NAME = $(this).parents('.buy_button').find('.box-name').val();
		var PRODUCT_PRICE = $(this).parents('.buy_button').find('.box-price').val();
		var PRODUCT_CATEGORY = $(this).parents('.buy_button').find('.box-category').val();
		var PRODUCT_BRAND = $(this).parents('.buy_button').find('.box-brand').val();
		
		if($(btn).attr("data-ctrl") != "Y") {
			$(btn).attr("data-ctrl", "Y");
			$.post("/ajax/add_to_cart.php", {ACTION:"ADD_BOX", "PRODUCT_ID":PRODUCT_ID, "COUNT":COUNT}, function(data) {
				$(btn).attr("data-ctrl", "N");

				if (typeof updateSmallCart === 'function')
					updateSmallCart();
				
				//e-commerce
				dataLayer.push({
					"ecommerce": {
						"add": {
							"products": [
								{
									"id": PRODUCT_ID,
									"name": PRODUCT_NAME,
									"price": PRODUCT_PRICE,
									"brand": PRODUCT_BRAND,
									"category": PRODUCT_CATEGORY,
									"quantity": COUNT
								}
							]
						}
					}
				});				
				
				//redirect
				window.location.href = "/cart/";
				
				//$(btn).parents('.buy_button').find('.add_to_cart_popup').attr('data-param-count', COUNT).trigger('click');
				
				/*if(data.indexOf("ERROR_") != -1) {
					$(".error-bl").empty().append(data.replace("ERROR_", "")).show();
				}*/
			})		
		}
	})	

	$('body').on('click', '.catalog_item .buy_button .buy_one_click', function() {
		var btn = $(this);
		var COUNT = $(this).parents('.catalog_item').find('.buy_block .counter .input input').val();
					
		$(btn).parents('.buy_button').find('.buy_one_click_popup').attr('data-param-count', COUNT).trigger('click');								
	})

});


MonthsCounter = function(el) {	
    var elClass = $.trim(el.attr('class')),
        bClassMinus = (elClass.indexOf('minus') > -1),
        bClassPlus = (elClass.indexOf('plus') > -1),
        bClassCount = (elClass.indexOf('countMonths') > -1),
        counterInput = el.closest('.counter').find('input.countMonths'),
        counterInputValue = parseFloat($.trim(counterInput.val())),
		counterInputMaxCount = 12;	
		
    // class minus button
    if(bClassMinus){
        var counterInputValueNew = counterInputValue - 1;

        if(counterInputValueNew <= 0){
            counterInputValueNew = 1;
        }

        counterInput.val(counterInputValueNew);
    }
    // class plus button
    else if(bClassPlus){
        var counterInputValueNew = counterInputValue + 1;

        if(counterInputValueNew > counterInputMaxCount){
            counterInputValueNew = counterInputMaxCount;
        }

        counterInput.val(counterInputValueNew);
    }
    // class input
    else if(bClassCount){
        var counterInputValueNew = counterInputValue;

        if(counterInputValueNew <= 0 || isNaN(counterInputValueNew)){
            counterInputValueNew = 1;
        }
        el.val(counterInputValueNew);
    }
}