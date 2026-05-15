function updateSmallCart() {
	$.post("/ajax/add_to_cart.php", {ACTION:"UPDATE_SMALL_CART"}, function(data) {
		$('.basket.fly .fly_forms .bx-basket').remove();
		$('.basket.fly .fly_forms').prepend(data);
	})	
}