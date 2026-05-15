(function (window) {

if (!window.JCCatalogSectionOnlyElement)
{
	window.JCCatalogSectionOnlyElement = function (arParams)
	{
		if (typeof arParams === 'object')
		{
			this.params = arParams;

			this.obProduct = null;
			this.set_quantity = 1;

			this.currentPriceMode = '';
			this.currentPrices = [];
			this.currentPriceSelected = 0;
			this.currentQuantityRanges = [];
			this.currentQuantityRangeSelected = 0;

			if (this.params.MESS)
			{
				this.mess = this.params.MESS;
			}

			this.init();
		}
	}
	window.JCCatalogSectionOnlyElement.prototype = {
		init: function()
		{
			var i = 0,
				j = 0,
				treeItems = null;

			this.obProduct = BX(this.params.ID);

			if(!!this.obProduct)
			{
				$(this.obProduct).find('.counter_wrapp .counter_block input').data('product', 'ob'+this.obProduct.id+'el');
				this.currentPriceMode = this.params.ITEM_PRICE_MODE;
				this.currentPrices = this.params.ITEM_PRICES;
				this.currentQuantityRanges = this.params.ITEM_QUANTITY_RANGES;
			}

		},

		setPriceAction: function()
		{
			this.set_quantity = this.params.MIN_QUANTITY_BUY;			
			if($(this.obProduct).find('input[name=quantity]').length)
				this.set_quantity = $(this.obProduct).find('input[name=quantity]').val();
			
			this.checkPriceRange(this.set_quantity);

			$(this.obProduct).find('.not_matrix').hide();
			$(this.obProduct).find('.with_matrix .price_value_block').html(getCurrentPrice(this.currentPrices[this.currentPriceSelected].PRICE, this.currentPrices[this.currentPriceSelected].CURRENCY, this.currentPrices[this.currentPriceSelected].PRINT_PRICE));

			if($(this.obProduct).find('.with_matrix .discount'))
			{
				$(this.obProduct).find('.with_matrix .discount').html(getCurrentPrice(this.currentPrices[this.currentPriceSelected].BASE_PRICE, this.currentPrices[this.currentPriceSelected].CURRENCY, this.currentPrices[this.currentPriceSelected].PRINT_BASE_PRICE));
			}

			if(this.params.SHOW_DISCOUNT_PERCENT_NUMBER == 'Y')
			{
				if(this.currentPrices[this.currentPriceSelected].PERCENT > 0 && this.currentPrices[this.currentPriceSelected].PERCENT < 100)
				{
					if(!$(this.obProduct).find('.with_matrix .sale_block .sale_wrapper .value').length)
						$('<div class="value"></div>').insertBefore($(this.obProduct).find('.with_matrix .sale_block .sale_wrapper .text'));

					$(this.obProduct).find('.with_matrix .sale_block .sale_wrapper .value').html('-<span>'+this.currentPrices[this.currentPriceSelected].PERCENT+'</span>%');
				}
				else
				{
					if($(this.obProduct).find('.with_matrix .sale_block .sale_wrapper .value').length)
						$(this.obProduct).find('.with_matrix .sale_block .sale_wrapper .value').remove();
				}
			}

			$(this.obProduct).find('.with_matrix .sale_block .text .values_wrapper').html(getCurrentPrice(this.currentPrices[this.currentPriceSelected].DISCOUNT, this.currentPrices[this.currentPriceSelected].CURRENCY, this.currentPrices[this.currentPriceSelected].PRINT_DISCOUNT));
			
			$(this.obProduct).find('.with_matrix').show();

			if(arNextOptions['THEME']['SHOW_TOTAL_SUMM'] == 'Y')
			{
				if(typeof this.currentPrices[this.currentPriceSelected] !== 'undefined')
					setPriceItem($(this.obProduct), this.set_quantity, this.currentPrices[this.currentPriceSelected].PRICE);
			}
		},

		checkPriceRange: function(quantity)
		{
			if (typeof quantity === 'undefined'|| this.currentPriceMode != 'Q')
				return;

			var range, found = false;
			
			for (var i in this.currentQuantityRanges)
			{
				if (this.currentQuantityRanges.hasOwnProperty(i))
				{
					range = this.currentQuantityRanges[i];

					if (
						parseInt(quantity) >= parseInt(range.SORT_FROM)
						&& (
							range.SORT_TO == 'INF'
							|| parseInt(quantity) <= parseInt(range.SORT_TO)
						)
					)
					{
						found = true;
						this.currentQuantityRangeSelected = range.HASH;
						break;
					}
				}
			}

			if (!found && (range = this.getMinPriceRange()))
			{
				this.currentQuantityRangeSelected = range.HASH;
			}

			for (var k in this.currentPrices)
			{
				if (this.currentPrices.hasOwnProperty(k))
				{
					if (this.currentPrices[k].QUANTITY_HASH == this.currentQuantityRangeSelected)
					{
						this.currentPriceSelected = k;
						break;
					}
				}
			}
		},

		getMinPriceRange: function()
		{
			var range;

			for (var i in this.currentQuantityRanges)
			{
				if (this.currentQuantityRanges.hasOwnProperty(i))
				{
					if (
						!range
						|| parseInt(this.currentQuantityRanges[i].SORT_FROM) < parseInt(range.SORT_FROM)
					)
					{
						range = this.currentQuantityRanges[i];
					}
				}
			}

			return range;
		}
	}
}
})(window);
$(document).on("click", ".counter_block:not(.basket) .plus", function(){
    if(!$(this).parents('.basket_wrapp').length){
        if($(this).parent().data("offers")!="Y"){
            var isDetailSKU2 = $(this).parents('.counter_block_wr').length;
            input = $(this).parents(".counter_block").find("input[type=text]")
            tmp_ratio = !isDetailSKU2 ? $(this).parents(".counter_wrapp").find(".to-cart").data('ratio') : $(this).parents('tr').first().find("td.buy .to-cart").data('ratio'),
                isDblQuantity = !isDetailSKU2 ? $(this).parents(".counter_wrapp").find(".to-cart").data('float_ratio') : $(this).parents('tr').first().find("td.buy .to-cart").data('float_ratio'),
                ratio=( isDblQuantity ? parseFloat(tmp_ratio) : parseInt(tmp_ratio, 10)),
                max_value='';
            currentValue = input.val();


            if(isDblQuantity)
                ratio = Math.round(ratio*arNextOptions.JS_ITEM_CLICK.precisionFactor)/arNextOptions.JS_ITEM_CLICK.precisionFactor;

            curValue = (isDblQuantity ? parseFloat(currentValue) : parseInt(currentValue, 10));

            curValue += ratio;
            if (isDblQuantity){
                curValue = Math.round(curValue*arNextOptions.JS_ITEM_CLICK.precisionFactor)/arNextOptions.JS_ITEM_CLICK.precisionFactor;
            }
            if(parseFloat($(this).data('max'))>0){
                if(input.val() < $(this).data('max')){
                    if(curValue<=$(this).data('max'))
                        input.val(curValue);

                    input.change();
                }
            }else{
                input.val(curValue);
                input.change();
            }
        }
    }
});

$(document).on("click", ".counter_block:not(.basket) .minus", function(){
    if(!$(this).parents('.basket_wrapp').length){
        if($(this).parent().data("offers")!="Y"){
            var isDetailSKU2 = $(this).parents('.counter_block_wr').length;
            input = $(this).parents(".counter_block").find("input[type=text]")
            tmp_ratio = !isDetailSKU2 ? $(this).parents(".counter_wrapp").find(".to-cart").data('ratio') : $(this).parents('tr').first().find("td.buy .to-cart").data('ratio'),
                isDblQuantity = !isDetailSKU2 ? $(this).parents(".counter_wrapp").find(".to-cart").data('float_ratio') : $(this).parents('tr').first().find("td.buy .to-cart").data('float_ratio'),
                ratio=( isDblQuantity ? parseFloat(tmp_ratio) : parseInt(tmp_ratio, 10)),
                max_value='';
            currentValue = input.val();

            if(isDblQuantity)
                ratio = Math.round(ratio*arNextOptions.JS_ITEM_CLICK.precisionFactor)/arNextOptions.JS_ITEM_CLICK.precisionFactor;

            curValue = (isDblQuantity ? parseFloat(currentValue) : parseInt(currentValue, 10));

            curValue -= ratio;
            if (isDblQuantity){
                curValue = Math.round(curValue*arNextOptions.JS_ITEM_CLICK.precisionFactor)/arNextOptions.JS_ITEM_CLICK.precisionFactor;
            }

            if(parseFloat($(this).parents(".counter_block").find(".plus").data('max'))>0){
                if(currentValue > ratio){
                    if(curValue<ratio){
                        input.val(ratio);
                    }else{
                        input.val(curValue);
                    }
                    input.change();
                }
            }else{
                if(curValue > ratio){
                    input.val(curValue);
                }else{
                    if(ratio){
                        input.val(ratio);
                    }else if(currentValue > 1){
                        input.val(curValue);
                    }
                }
                input.change();
            }
        }
    }
});

$(document).on("change", ".counter_block input[type=text]", function(e){
    if(!$(this).parents('.basket_wrapp').length){
        var val = $(this).val(),
            tmp_ratio = $(this).parents(".counter_wrapp").find(".to-cart").data('ratio') ? $(this).parents(".counter_wrapp").find(".to-cart").data('ratio') : $(this).parents('tr').first().find("td.buy .to-cart").data('ratio'),
            isDblQuantity = $(this).parents(".counter_wrapp").find(".to-cart").data('float_ratio') ? $(this).parents(".counter_wrapp").find(".to-cart").data('float_ratio') : $(this).parents('tr').first().find("td.buy .to-cart").data('float_ratio'),
            ratio=( isDblQuantity ? parseFloat(tmp_ratio) : parseInt(tmp_ratio, 10)),
            diff = val % ratio;

        if(isDblQuantity)
        {
            ratio = Math.round(ratio*arNextOptions.JS_ITEM_CLICK.precisionFactor)/arNextOptions.JS_ITEM_CLICK.precisionFactor;
            if(Math.round(diff*arNextOptions.JS_ITEM_CLICK.precisionFactor)/arNextOptions.JS_ITEM_CLICK.precisionFactor == ratio)
                diff = 0;
        }

        if($(this).hasClass('focus'))
        {
            intCount = Math.round(
                Math.round(val * arNextOptions.JS_ITEM_CLICK.precisionFactor / ratio) / arNextOptions.JS_ITEM_CLICK.precisionFactor
            ) || 1;
            val = (intCount <= 1 ? ratio : intCount * ratio);
            // val -= diff;
            val = Math.round(val*arNextOptions.JS_ITEM_CLICK.precisionFactor)/arNextOptions.JS_ITEM_CLICK.precisionFactor;
        }

        if(parseFloat($(this).parents(".counter_block").find(".plus").data('max'))>0){
            if(val>parseFloat($(this).parents(".counter_block").find(".plus").data('max'))){
                val=parseFloat($(this).parents(".counter_block").find(".plus").data('max'));
                // val -= (val % ratio);
            }
        }
        if(val<ratio){
            val=ratio;
        }else if(!parseFloat(val)){
            val=1;
        }

        $(this).parents('.counter_block').parent().parent().find('.to-cart').attr('data-quantity', val);
        $(this).parents('.counter_block').parent().parent().find('.one_click').attr('data-quantity', val);
        $(this).val(val);

        var eventdata = {type: 'change', params: {id: $(this), value: val}};
        BX.onCustomEvent('onCounterProductAction', [eventdata]);
    }
});