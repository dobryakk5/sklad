window.addEventListener("DOMContentLoaded", () => {
    const typesPremiseSwipers  = document.querySelectorAll('.types-premise__swiper')

    if(typesPremiseSwipers?.length) {
        typesPremiseSwipers.forEach((elem) => {
            console.log(elem.querySelector('.swiper-pagination_types-premise'))
            new Swiper(elem, {
            loop: true,
            breakpoints: {
                768: {
                enabled: false,
                simulateTouch: false,
                },
                0: {
                slidesPerView: 1,
                spaceBetween: 12,
                enabled: true,
                simulateTouch: true,
                pagination: {
                    el: elem.closest(".types-premise__item").querySelector('.swiper-pagination_types-premise'),
                    clickable: true,
                },
                },
            }
            });
        })
    }
});