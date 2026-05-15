document.addEventListener("DOMContentLoaded", function(event) {

    let dataPrice = BX.message('DATA');
    let rangeEl = $('.calc-price__range');
    let priceEl = $('.calc-price__price-value');


    rangeEl.ionRangeSlider({
        values: BX.message('RANGE_VALUES'),
        skin: 'round',
        grid: true,
        grid_snap: true,
        onStart: function (data) {
         //   priceEl.html(dataPrice[data.from_value]["items"][$('.dropdown__toggle .dropdown__item').attr('data-id')]);
        },
        onChange: function (data) {
            priceEl.html(dataPrice[data.from_value]["items"][$('.dropdown__toggle .dropdown__item').attr('data-id')]);
            gridToggleVisible();
        },
    });

    gridToggleVisible();

    // кастомная функция изменения видимости элементов сетки (только целые, некратные 2)
    function gridToggleVisible(){
        $('.irs-grid-text').each(function( index ) {
            let text = parseFloat($(this).text().split(' ')[0]);
            let rule = text % 2 != 0 && Number.isInteger(text);
            if(document.documentElement.clientWidth < 600){
                rule = (index == 0 || text % 3 == 0 || index == $('.irs-grid-text').length - 1);
            }
            if(rule){
                $(this).css('visibility','visible');
            } else {
                $(this).hide();
            }
        });
    }

    $('.dropdown__toggle .dropdown__item').replaceWith($('.dropdown__list .dropdown__item:first-child').clone());

    $('.dropdown__toggle').click(function(){
        //смена видимости блока со списком
        $(this).closest('.dropdown').find('.dropdown__list').toggle();

        //смена иконки
        $(this).find('i.fa').toggleClass('fa-angle-down fa-angle-up');
    });

    $('.dropdown__list .dropdown__item').click(function(){
        let dropdown = $(this).closest('.dropdown');

        $('.dropdown__toggle .dropdown__item').replaceWith($(this).clone());

        $(this).closest('.dropdown__list').hide();
        dropdown.find('.dropdown__toggle i.fa').removeClass('fa-angle-up');
        dropdown.find('.dropdown__toggle i.fa').addClass('fa-angle-down');
    });

    //клик вне блока со списком
    $(document).mouseup(function (e){
        let dropdown = $('.dropdown');
		let dropdownList = $(".dropdown__list");

		if (dropdownList.is(":visible") && !$('.dropdown').is(e.target) && $('.dropdown').has(e.target).length === 0) {
            $('.dropdown__list').hide();
            dropdown.find('.dropdown__toggle i.fa').removeClass('fa-angle-up');
            dropdown.find('.dropdown__toggle i.fa').addClass('fa-angle-down');
		}
	});


    // Смена склада
    $('.calc-price .dropdown__item').click(function(){
        let el = $(this).closest('.calc-price').find(rangeEl);
        let instance = el.data("ionRangeSlider");

        priceEl.html(dataPrice[instance.result.from_value]["items"][$(this).attr('data-id')]);

        $(this).closest('.calc-price').find('.calc-price__sklad-info-item').hide();
        $(this).closest('.calc-price').find('.calc-price__sklad-info-item[data-id=' + $(this).attr('data-id') + ']').show();

        $(this).closest('.calc-price').find('.calc-price__sklad-full-info-item').hide();
        $(this).closest('.calc-price').find('.calc-price__sklad-full-info-item[data-id=' + $(this).attr('data-id') + ']').show();
    });

     // Вызов формы и вставка информации в поле ввода
     $('.calc-price__send').on('click', function(){

        let calc = $(this).closest('.calc-price');
        let el = calc.find(rangeEl);
        let instance = el.data("ionRangeSlider");
        let fieldText = calc.find('.dropdown__toggle .dropdown__item-text').text().trim() + ': ' + instance.result.from_value;
        $(this).attr('data-calc', fieldText);
        setTimeout(function() {
            $('#POPUP_NEED_PRODUCT').closest('.form-group').addClass('input-filed');
            $('#POPUP_NEED_PRODUCT').val(fieldText);
        }, 1000);
    })
});