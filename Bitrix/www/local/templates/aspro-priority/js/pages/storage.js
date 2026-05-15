// Мини утилки

const getIndex = (e) => {
  return e.get('target').properties.get('index')
}

const getAllElems = (className) => {
  return document.querySelectorAll(className)
}

const elemChangeClass = (elem, className, method) => {
  elem.classList[method](className)
}

window.addEventListener("DOMContentLoaded", () => {

  const heroBlockMap = document.querySelector(".hero-block__map")
  const activeAddress = JSON.parse(heroBlockMap.dataset.activeAddress)
  const addresses = JSON.parse(heroBlockMap.dataset.addresses)

  let mapMarkers = [];

  const init = () => {
    myMap = new ymaps.Map("map", {
      center: activeAddress,
      zoom: 12
    });

    createMarkersFromHTML();

    // Обработчик клика на карту
    myMap.events.add('click', () => {
      deactivateAllMarkers();
    });

    removeMapControls();
  }


  // Функция создания маркеров из данных в HTML
  const createMarkersFromHTML = () => {
    addresses.forEach((address, index) => {

      try {
        const isActiveAddress = activeAddress[0] === address.coordinate[0] && activeAddress[1] === address.coordinate[1]

        const customLayout = ymaps.templateLayoutFactory.createClass(
          `<div style="transform: translate(-50%, -100%); background-image: ${isActiveAddress ? 'url(/images/icons/marker.svg)' : 'url(/images/icons/marker_disabled.svg)'}; background-size: cover; width: ${isActiveAddress ? '40px' : '20px'}; height: ${isActiveAddress ? '50px' : '25px'}; cursor: pointer;"></div>`
        )

        const myPlacemark = new ymaps.Placemark(address.coordinate, {
          balloonContentBody: `
              <div class="map-marker__container" data-marker-index="${index}">
                <img class="map-marker__close" src="/images/icons/close.svg" />
                <div class="map-marker__info">
                  <p class="map-marker__text text_medium text_m">
                    ${address?.address_balloon ?? address.address}
                  </p>
                  <p class="map-marker__text text_m">
                    Доступ на склад: <b>${address.access}</b>
                  </p>
                  <p class="map-marker__text text_m">
                    Менеджер: <b>${address.manager_hours}</b>
                  </p>
                </div>
                ${!isActiveAddress ? '<a class="map-marker__link text_s" href="' + address.link + '">Подробнее о складе</a>' : ''}
              </div>
            `,
        }, {
          iconLayout: customLayout,
          hideIconOnBalloonOpen: false,
          balloonOffset: [80, 40],
          balloonCloseButton: false,
          iconShape: {
            type: 'Rectangle',
            coordinates: [[-10, -25], [10, 0]]
          }
        });

        // Сохраняем индекс в свойствах маркера для быстрого доступа
        myPlacemark.properties.set('index', index);

        if (isActiveAddress) {
          myPlacemark.properties.set("isActiveAddress", true)
        }

        myPlacemark.events.add('mouseenter', (e) => {
          const markerIndex = getIndex(e);
          const markerImage = getAllElems('[class*="-placemark-overlay"]')[markerIndex]
          const isActiveAddress = e.get('target').properties.get('isActiveAddress')

          e.get('target').options.set('iconImageHref', '/images/icons/marker.svg')

          if (!isActiveAddress) {
            elemChangeClass(markerImage, 'map-marker_scaled', 'add')
          }
        });

        // Обработчик уведения с маркера
        myPlacemark.events.add('mouseleave', (e) => {
          const markerIndex = getIndex(e);
          const markerImage = getAllElems('[class*="-placemark-overlay"]')[markerIndex]
          const isActiveAddress = e.get('target').properties.get('isActiveAddress')

          if (!markerImage.classList.contains("map-marker_scaled_open") && !isActiveAddress) {
            e.get('target').options.set('iconImageHref', '/images/icons/marker_disabled.svg')
          }

          if (!isActiveAddress) {
            elemChangeClass(markerImage, 'map-marker_scaled', 'remove')
          }
        });

        // Обработчик открытия балуна - добавляем обработчик для кнопки закрытия
        myPlacemark.events.add('balloonopen', (e) => {
          setTimeout(() => {
            deactivateAllMarkers(false);

            const isActiveAddress = e.get('target').properties.get('isActiveAddress')

            // Находим кнопку закрытия в открытом балуне
            const balloon = document.querySelector('[class*="-balloon-panel"]');

            const closeButton = balloon?.querySelector('.map-marker__close');

            myPlacemark.options.set('iconImageHref', '/images/icons/marker.svg')

            const markerImage = getAllElems('[class*="-placemark-overlay"]')[getIndex(e)]

            if (!isActiveAddress) {
              elemChangeClass(markerImage, 'map-marker_scaled_open', 'add')
            }

            if (closeButton) {
              // Удаляем старый обработчик (если был) и добавляем новый
              closeButton.replaceWith(closeButton.cloneNode(true));
              const newCloseButton = balloon.querySelector('.map-marker__close');

              newCloseButton.addEventListener('click', (event) => {
                event.stopPropagation();

                if (!isActiveAddress) {
                  elemChangeClass(markerImage, 'map-marker_scaled_open', 'remove')
                }

                deactivateAllMarkers();
              });
            }
          }, 50);
        });

        myPlacemark.events.add('balloonclose', (e) => {
          const markerImage = getAllElems('[class*="-placemark-overlay"]')[getIndex(e)]

          if (markerImage) {
            elemChangeClass(markerImage, 'map-marker_scaled_open', 'remove')
          }
        })

        myMap.geoObjects.add(myPlacemark);
        mapMarkers.push(myPlacemark);

      } catch (error) {
        console.error('Ошибка создания маркера для элемента', index, error);
      }
    });
  }

  // Функция удаления элементов управления картой
  const removeMapControls = () => {
    const controls = [
      'zoomControl',
      'searchControl',
      'trafficControl',
      'typeSelector',
      'fullscreenControl',
      'geolocationControl',
      'rulerControl',
    ];

    controls.forEach(control => myMap.controls.remove(control));
  }

  ymaps.ready(init);

  // Функция удаления класса у всех маркеров
  const removeAllMarkerClasses = (className) => {
    setTimeout(() => {
      const markerElements = getAllElems('[class*="-placemark-overlay"]');
      markerElements.forEach(markerElement => {
        elemChangeClass(markerElement, className, 'remove')
      });
    }, 10);
  }

  // Функция деактивации всех маркеров
  const deactivateAllMarkers = (isCloseBallon = true) => {
    // Убираем классы у всех маркеров
    removeAllMarkerClasses('map-marker_active');

    // Закрываем все балуны
    mapMarkers.forEach(marker => {
      const isActiveAddress = marker.properties.get('isActiveAddress')

      if (!isActiveAddress) {
        marker.options.set('iconImageHref', '/images/icons/marker_disabled.svg')
      }

      if (isCloseBallon) {
        marker?.balloon.close();
      }
    });
  }
})