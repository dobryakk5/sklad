$(document).ready(function() {
    
  const reviewsSwiper = document.querySelector('.reviews__swiper')

  if(reviewsSwiper) {
    const baseConfig = {
      spaceBetween: 40,
      pagination: {
        el: '.swiper-pagination_reviews',
        clickable: true,
      },
    }

    new Swiper(reviewsSwiper, {
      loop: true,

      breakpoints: {
        1400: {
          slidesPerView: 3,
          spaceBetween: baseConfig.spaceBetween,
          navigation: {
            nextEl: '.swiper-button-next_reviews',
            prevEl: '.swiper-button-prev_reviews',
          },
        },
        900: {
          slidesPerView: 3,
          ...baseConfig
        },
        640: {
          slidesPerView: 2,
          ...baseConfig
        },
        0: {
          slidesPerView: 1,
          ...baseConfig
        }
      }
    });
  }
});