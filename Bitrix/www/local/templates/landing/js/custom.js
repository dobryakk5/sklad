/* Add here all your JS customizations */


$(function () {

    /******** fix for tabs url ********/
    var hash = window.location.hash;
    hash && $('.faq_list ul.nav a[href="' + hash + '"]').tab('show');

    $('.faq_list .nav-tabs a').click(function (e) {
        window.location.hash = this.hash;
    });
    /********************************/


    /******* scroll do yakorya *******/
    $('a.scroll[href*="#"]:not([href="#"])').click(function () {
        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
            if (target.length) {
                $('html,body').animate({
                    scrollTop: target.offset().top - 100
                }, 1000);
                return false;
            }
        }
    });
    /**************************************/


    /***** masked input *****/
    $(".form-managerOrder input[type='tel']").inputmask({"mask": "+7 (999) 999-99-99"});
    $(".form-managerOrder2 input[type='tel']").inputmask({"mask": "+7 (999) 999-99-99"});
    $(".form-managerOrder4 input[type='tel']").inputmask({"mask": "+7 (999) 999-99-99"});
    /*************************************/

    /********* basket page ***********/
    $('html').on('click', '#basket-item-table .box-more-info .item-price-info-open a', function () {
        $(this).parents('.box-more-info').find('.item-price-info').slideToggle();
    });
    /*********************/

    /********* mobile footer menu slideToggle ***********/
    $('html').on('click', '#footer .showMobileFooterMenu', function () {
        $('#footer .mobileFooterMenu').slideToggle();
    });
    /*********************/

    /************ forms input-date ************/
    $('html').on('click', '.form.popup .input .form-control.input-date, .form.popup .input .form-control.input-date', function () {
        $(this).parents('.input').find('.calendar-icon').trigger('click');
    })
    /*********************/


    /************ LazyLoad ************/
    var lazyLoadInstance = new LazyLoad({
        elements_selector: ".lazy"
    });
    lazyLoadInstance.update();
    /***********************/

});

function actualBasketCounter() {
    $.ajax({
        type: "POST",
        url: arPriorityOptions['SITE_DIR'] + "ajax/actualBasket.php",
        dataType: "json",
        success: function (data) {
            if (data.BASKET) {
                $('.basketSmall .count').text(Object.keys(data.BASKET).length);
            } else {
                $('.basketSmall .count').text(0);
            }

        }
    });
}

scrollToBlockCustom = function (block, of) {
    if (!of) of = 0;
    if ($(block).length) {
        var offset = $(block).offset().top;
        if (typeof ($(block).data('toggle')) != 'undefined')
            $(block).click();

        if (typeof ($(block).data('offset')) != 'undefined')
            offset += $(block).data('offset');
        $('body, html').animate({scrollTop: offset + of}, 500);
    }
}


$(document).ready(function () {
    $("#menu>li").on("click", function (event) {

        event.preventDefault();
        var id = $(this).attr('href'),
            top = $(id).offset().top;
        $('body,html').animate({scrollTop: top - 200}, 1500);
    });
});

window.addEventListener('DOMContentLoaded', () => {
    let overlay = document.querySelector('.modal-overlay'),
        close = document.querySelector('.offers-modal__close'),
        initModal = document.querySelectorAll('.offers-open-modal'),
        htmlBlock = document.querySelector('html');

    initModal.forEach(modalItem => {
        modalItem.addEventListener('click', (e) => {
            e.preventDefault();
            overlay.classList.add('active');
            htmlBlock.style.overflowY = "hidden";
        })
        close.addEventListener('click', (e) => {
            e.preventDefault();
            overlay.classList.remove('active');
            htmlBlock.style.overflowY = "auto";
        })
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                overlay.classList.remove('active');
                htmlBlock.style.overflowY = "auto";
            }
        })
    })

    let tourFrame = document.querySelector('.tour-frame');

    if (tourFrame && window.innerWidth < 768) {
        tourFrame.setAttribute('width', '400');
        tourFrame.setAttribute('height', '640');
    }
})


$(function () {
    let header = $('#mobileheader');
    if (header) {
        let hederHeight = header.height(); // вычисляем высоту шапки

        $(window).scroll(function () {
            if ($(this).scrollTop() > 1) {
                header.addClass('header_fixed');
                $('body').css({
                    'paddingTop': hederHeight + 'px' // делаем отступ у body, равный высоте шапки
                });
            } else {
                header.removeClass('header_fixed');
                $('body').css({
                    'paddingTop': 0 // удаляю отступ у body, равный высоте шапки
                })
            }
        });
    }
});

$(document).ready(function () {

    if (window.location.pathname == '/find_storage/') {
        setTimeout(function () {
            let ph_text = $('.inner-table-block.phones a').text();
            let ph_attr = $('.inner-table-block.phones a').attr('href');
            //console.log(ph_attr)
            let link = $('.find_storage_list .item .contacts .phone a');
            link.text(ph_text);
            link.attr('href', ph_attr);

        }, 1500);
    }

});

if (!IS_404) {
    window.onscroll = function () {
        checkMarginToTop();
    };
}

var nav = document.getElementById("MENU");

var sticky = nav.offsetTop;

function checkMarginToTop() {
    if (window.pageYOffset > sticky) {
        nav.classList.add("sticky");
    } else {
        nav.classList.remove("sticky");
    }
}