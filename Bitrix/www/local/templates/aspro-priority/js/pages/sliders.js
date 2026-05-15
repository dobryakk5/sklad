const heroBlockSwiper = document.querySelector(".hero-block__swiper")

if(heroBlockSwiper) {
  const baseConfig = {
    spaceBetween: 12,
    centeredSlides: true,
    pagination: {
      el: '.swiper-pagination_hero-block',
      clickable: true,
    },
  }

  new Swiper(heroBlockSwiper, {
    loop: true,
    slideActiveClass: 'hero-block__slide_active',
    
    navigation: {
      nextEl: '.swiper-button-next_hero-block',
      prevEl: '.swiper-button-prev_hero-block',
    },

    
    breakpoints: {
      1280: {
        slidesPerView: 2,
        spaceBetween: 8,
      },

      800: {
        slidesPerView: 3.2,
        ...baseConfig
      },

      640: {
        slidesPerView: 2.2,
        ...baseConfig
      },

      0: {
        slidesPerView: 1.3,
        ...baseConfig
      },
    }
  })
}

const examplesSwiper = document.querySelector(".examples__swiper")

if(examplesSwiper) {
  const baseConfig = {
    spaceBetween: 8,
    pagination: {
      el: '.swiper-pagination_examples',
      clickable: true,
    },
  }

  new Swiper(examplesSwiper, {
    loop: true,
    
    breakpoints: {
      1280: {
        slidesPerView: 4,
        spaceBetween: baseConfig.spaceBetween,
        navigation: {
          nextEl: '.swiper-button-next_examples',
          prevEl: '.swiper-button-prev_examples',
        },
      },

      931: {
        slidesPerView: 2,
        ...baseConfig
      },

      500: {
        slidesPerView: 2,
        ...baseConfig
      },

      0: {
        slidesPerView: 1,
        ...baseConfig
      },
    }
  })
}

const storageGallerySwiper = document.querySelector('.storage-gallery__swiper')

if(storageGallerySwiper) {
  const baseConfig = {
    spaceBetween: 12,
    centeredSlides: true,
    pagination: {
      el: '.swiper-pagination_storage-gallery',
      clickable: true,
    },
  }

  new Swiper(storageGallerySwiper, {
    loop: true,
    slideActiveClass: 'storage-gallery__slide_active',

    breakpoints: {
      1280: {
        slidesPerView: 4,
        spaceBetween: baseConfig.spaceBetween,
        navigation: {
          nextEl: '.swiper-button-next_storage-gallery',
          prevEl: '.swiper-button-prev_storage-gallery',
        },
      },
      800: {
        slidesPerView: 3.2,
        ...baseConfig
      },
      640: {
        slidesPerView: 2.2,
        ...baseConfig
      },
      0: {
        slidesPerView: 1.1,
        ...baseConfig
      }
    }
  });
}

const defaultSwipers = document.querySelectorAll('.swiper-default')

if(defaultSwipers?.length) {
  defaultSwipers.forEach((elem) => {
    const parent = elem.closest("section")

    const baseConfig = {
      spaceBetween: 40,
      pagination: {
        el: parent.querySelector('.swiper-default-pagination'),
        clickable: true,
      },
    }

    new Swiper(elem, {
      loop: true,

      breakpoints: {
        1400: {
          slidesPerView: 3,
          spaceBetween: baseConfig.spaceBetween,
          navigation: {
            nextEl: parent.querySelector('.swiper-default-button-next'),
            prevEl: parent.querySelector('.swiper-default-button-prev'),
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
  })
}