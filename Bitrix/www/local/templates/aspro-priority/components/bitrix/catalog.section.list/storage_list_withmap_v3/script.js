$(document).ready(function() {
    // room.js
    
    const MAP_ROOM_CENTER = [55.7908233, 37.6174994]

    let customLayout = null;

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

    const heroBlockTabActiveClass = 'hero-block__tab_active'

    const mapTabs = document.querySelectorAll(".hero-block__tab");

    const heroBlockMap = document.querySelector(".hero-block__map")
    const heroBlockMapInfo = JSON.parse(heroBlockMap.dataset.info)
    const heroBlockLeft = document.querySelector(".hero-block__block_left")

    const mapMarkers = [];
    let roomMap = null;

    let activeTabs = [];

    mapTabs.forEach((tab) => {
    tab.addEventListener("click", handleClickTab)
    })

    function handleClickTab({ target }) {
    const type = target.dataset.type

    if (activeTabs.includes(type)) {
        if (activeTabs.length === 1) {
        return
        }
        activeTabs = activeTabs.filter((tab) => tab !== type)
        target.classList.remove(heroBlockTabActiveClass)
    } else {
        target.classList.add(heroBlockTabActiveClass)

        activeTabs.push(type)
    }

    roomMap.geoObjects.removeAll();

    if (type === 'all') {
        mapTabs.forEach((tab) => {
        if (tab.dataset.type !== 'all') {
            tab.classList.remove(heroBlockTabActiveClass)
        }

        activeTabs = []
        })

        createMarkersFromHTML()
    } else {
        document.querySelector('[data-type="all"]').classList.remove(heroBlockTabActiveClass)

        const currentRegions = {};

        activeTabs.forEach((tab) => {
        currentRegions[tab] = heroBlockMapInfo[tab]
        })

        createMarkersFromHTML(currentRegions)
    }

    roomMap.setCenter(MAP_ROOM_CENTER)
    }

    const init = () => {
    roomMap = new ymaps.Map("map", {
        center: MAP_ROOM_CENTER,
        zoom: 10
    });

    createMarkersFromHTML();

    // Обработчик клика на карту
    roomMap.events.add('click', () => {
        deactivateAllMarkers();
    });

    removeMapControls();
    }


    // Функция создания маркеров из данных в HTML
    const createMarkersFromHTML = (categoriesAddresses = heroBlockMapInfo) => {
    const uniqueAddresses = []

    Object.keys(categoriesAddresses).forEach((category) => {
        categoriesAddresses[category]?.forEach((currentAddress) => {
        const isDuplicate = uniqueAddresses.find((existingAddress) => existingAddress.coordinate[0] === currentAddress.coordinate[0] && existingAddress.coordinate[1] === currentAddress.coordinate[1])

        if (!isDuplicate) {
            uniqueAddresses.push(currentAddress)
        }
        })
    })

    uniqueAddresses.forEach((address, index) => {
        try {
        if (!customLayout) {
            customLayout = ymaps.templateLayoutFactory.createClass(
            '<div style="transform: translate(-50%, -100%); background-image: url(/images/icons/marker.svg); background-size: cover; width: 20px; height: 25px; cursor: pointer;"></div>'
            )
        }

        const placemark = new ymaps.Placemark(address.coordinate, {
            balloonContentBody: `
                <div class="map-marker__container">
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
                <a class="map-marker__link text_s" href="${address.link}">
                    Подробнее о складе
                </a>
                </div>
            `,
        }, {
            iconLayout: customLayout,
            hideIconOnBalloonOpen: false,
            balloonOffset: [80, 40],
            balloonCloseButton: false,
            balloonPanelMaxMapArea: 0,
            iconShape: {
            type: 'Rectangle',
            coordinates: [[-10, -25], [10, 0]]
            }
        });

        // Сохраняем индекс в свойствах маркера для быстрого доступа
        placemark.properties.set('index', index);

        // Обработчик наведения на маркер
        placemark.events.add('mouseenter', (e) => {
            const markerIndex = getIndex(e);
            const markerImage = getAllElems('[class*="-placemark-overlay"]')[markerIndex]
            elemChangeClass(markerImage, 'map-marker_scaled', 'add')
        });

        // Обработчик уведения с маркера
        placemark.events.add('mouseleave', (e) => {
            const markerIndex = getIndex(e);
            const markerImage = getAllElems('[class*="-placemark-overlay"]')[markerIndex]
            elemChangeClass(markerImage, 'map-marker_scaled', 'remove')
        });

        // Обработчик открытия балуна - добавляем обработчик для кнопки закрытия
        placemark.events.add('balloonopen', (e) => {
            setTimeout(() => {
            // Находим кнопку закрытия в открытом балуне
            const balloon = document.querySelector('[class*="-balloon"]');
            const closeButton = balloon?.querySelector('.map-marker__close');

            const markerImage = getAllElems('[class*="-placemark-overlay"]')[getIndex(e)]
            elemChangeClass(markerImage, 'map-marker_scaled_open', 'add')

            if (closeButton) {
                // Удаляем старый обработчик (если был) и добавляем новый
                closeButton.replaceWith(closeButton.cloneNode(true));
                const newCloseButton = balloon.querySelector('.map-marker__close');

                newCloseButton.addEventListener('click', (event) => {
                elemChangeClass(markerImage, 'map-marker_scaled_open', 'remove')
                event.stopPropagation();
                deactivateAllMarkers();
                });
            }
            }, 50);
        });

        placemark.events.add('balloonclose', (e) => {
            const markerImage = getAllElems('[class*="-placemark-overlay"]')[getIndex(e)]

            if (markerImage) {
            elemChangeClass(markerImage, 'map-marker_scaled_open', 'remove')
            }
        })

        roomMap.geoObjects.add(placemark);
        mapMarkers.push(placemark);


        } catch (error) {
        console.error('Ошибка создания маркера для элемента', error);
        }
    })

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

    controls.forEach(control => roomMap.controls.remove(control));
    }

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
    const deactivateAllMarkers = () => {
    // Убираем классы у всех маркеров
    removeAllMarkerClasses('map-marker_active');

    // Закрываем все балуны
    mapMarkers.forEach(marker => {
        marker?.balloon.close();
    });
    }

    ymaps.ready(init);
});