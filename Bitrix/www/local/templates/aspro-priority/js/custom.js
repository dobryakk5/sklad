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

$( document ).ready(function() {

    if(window.location.pathname == '/rental_catalog/') {
        setTimeout(function() {
            let ph_text = $('.inner-table-block.phones a').text();
            let ph_attr = $('.inner-table-block.phones a').attr('href');
            //console.log(ph_attr)
            let link = $('.find_storage_list .item .contacts .phone a');
            link.text(ph_text);
            link.attr('href', ph_attr);

        }, 1500);
    }

    const header = document.querySelector('.header_wrap')

    function handleScroll() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop

        if (scrollTop > 0) {
            header?.classList.add('scrolled')
        } else {
            header?.classList.remove('scrolled')
        }
    }

    window.addEventListener('scroll', handleScroll)

    handleScroll()

    // ===============================================
    // новые страницы
    // ===============================================
    const storageServicesActionClosed = document.querySelector(".storage-services__accordion-action_closed");
    const storageServicesActionOpened = document.querySelector(".storage-services__accordion-action_opened");

    const storageServicesAccordion = document.querySelector(".storage-services__accordion")
    const storageServicesAccordionDropwdown = document.querySelector(".storage-services__accordion-dropdown")

    storageServicesActionClosed?.addEventListener("click", () => {
        storageServicesActionClosed.style.display = 'none'
        storageServicesActionOpened.style.display = 'block'

        storageServicesAccordionDropwdown.classList.add("storage-services__accordion-dropdown_active")
        storageServicesAccordionDropwdown.style.maxHeight = storageServicesAccordionDropwdown.scrollHeight + "px";
    })

    storageServicesActionOpened?.addEventListener("click", () => {
        storageServicesActionOpened.style.display = 'none'
        storageServicesActionClosed.style.display = 'inline-block'

        storageServicesAccordionDropwdown.classList.remove("storage-services__accordion-dropdown_active")
        storageServicesAccordionDropwdown.style.maxHeight = null;
    })

    const popupActions = document.querySelectorAll("[data-popup-action]")

    let popupActive = null;

    if(popupActions?.length) {
        popupActions.forEach((action) => {
            action.addEventListener("click", (e) => {
            e.preventDefault()
            e.stopPropagation()

            const currentPopup = document.querySelector(`[data-popup="${action.dataset.popupAction}"]`)

            if(currentPopup.classList.contains('popup_active')) {
                window.removeEventListener("click", handleOutsideClick)
                window.removeEventListener("keyup", handleKeyPress)
            } else {
                window.addEventListener("click", handleOutsideClick)
                window.addEventListener("keyup", handleKeyPress)

                if (action.dataset.popupAction == 'qr-to-call' && document.querySelector('.phone_number')) {
                    let src = currentPopup.querySelector('.popup-qr__qr-code').getAttribute('src');
                    if (src.indexOf('#') !== -1) {
                        const roiphone = document.querySelector('.phone_number').innerText;
                        currentPopup.querySelector('.popup-qr__qr-code').setAttribute('src', src.replace('#', roiphone));
                    }
                }
            }

            currentPopup.classList.toggle("popup_active")

            popupActive = currentPopup
            })
        })
    }

    const closePopup = () => {
        popupActive.classList.remove("popup_active")

        window.removeEventListener("click", handleOutsideClick)
    }

    const handleOutsideClick = (e) => {
        if(e.target?.className === 'popup__wrapper') {
            closePopup()
        }
    }

    const handleKeyPress = (e) => {
        if(e.key === "Escape") {
            closePopup()
        }
    }

    const heroPhoneButton = document.querySelector(".hero-block__button");
    const heroHintButton = document.querySelector(".hero-block__hint");

    if (heroPhoneButton && heroHintButton && /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        const linkPhone = document.createElement("a")

        linkPhone.href = `tel:${heroPhoneButton.dataset.phone}`;
        linkPhone.className = heroPhoneButton.className;
        linkPhone.innerText = heroPhoneButton.innerText;

        heroHintButton.style.display = 'none'

        heroPhoneButton.parentNode.replaceChild(linkPhone, heroPhoneButton);
    }   

    const tooltips = document.querySelectorAll(".tooltip-new-ui")

    if(tooltips?.length) {
        tooltips.forEach((tooltip) => {
            tippy(tooltip, {
                content: `
                ${tooltip.dataset.tooltipContent}
                `,
                allowHTML: true,
            });

            tooltip.removeAttribute("data-tooltip-content")
        })
    }
});