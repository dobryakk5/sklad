$(document).ready(function() {
    // warehouseAddresses.js
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

    // Глобальные переменные
    let myMap;
    let mapMarkers = [];

    const addressTabs = getAllElems(".addresses__tab");

    let activeTabs = [];

    let customLayout = null;

    const init = () => {

    if (myMap) {
        myMap.geoObjects.removeAll();
        mapMarkers = []
    } else {
        myMap = new ymaps.Map("map", {
        center: [55.7408233, 37.6174994],
        zoom: 10,
        });
    }

    // Создаем маркеры из данных в HTML
    createMarkersFromHTML();

    // Инициализируем обработчики для списка адресов
    initAddressList();

    // Обработчик клика на карту
    myMap.events.add('click', () => {
        deactivateAllMarkers();
        deactivateAllAddressItems();
    });

    // Убираем элементы управления
    removeMapControls();
    }

    // Функция создания маркеров из данных в HTML
    const createMarkersFromHTML = () => {
    const addressItems = getAllElems('.addresses__list-item[data-info][data-active="1"]');

    addressItems.forEach((item, index) => {

        try {
        const markerData = JSON.parse(item.getAttribute('data-info'));

        if (!customLayout) {
            customLayout = ymaps.templateLayoutFactory.createClass(
            '<div style="transform: translate(-50%, -100%); background-image: url(/images/icons/marker.svg); background-size: cover; width: 20px; height: 25px; cursor: pointer;"></div>'
            )
        }

        const myPlacemark = new ymaps.Placemark(markerData.coordinate, {
            balloonContentBody: `
            <div class="map-marker__container" data-marker-index="${index}">
                <img class="map-marker__close" src="/images/icons/close.svg" />
                <div class="map-marker__info">
                <p class="map-marker__text text_medium text_m">
                    ${markerData?.address_balloon ?? markerData.address}
                </p>
                <p class="map-marker__text text_m">
                    Доступ на склад: <b>${markerData.access}</b>
                </p>
                <p class="map-marker__text text_m">
                    Менеджер: <b>${markerData.manager_hours}</b>
                </p>
                </div>
                <a class="map-marker__link text_s" href="${markerData.link}">
                Подробнее о складе
                </a>
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

        // Обработчик клика на маркер
        myPlacemark.events.add('click', () => {
            deactivateAllAddressItems();
        });

        // Обработчик наведения на маркер
        myPlacemark.events.add('mouseenter', (e) => {
            const markerIndex = getIndex(e);
            const markerImage = getAllElems('[class*="-placemark-overlay"]')[markerIndex]
            elemChangeClass(markerImage, 'map-marker_scaled', 'add')
            highlightAddressItem(markerIndex);
        });

        // Обработчик уведения с маркера
        myPlacemark.events.add('mouseleave', (e) => {
            const markerIndex = getIndex(e);
            const markerImage = getAllElems('[class*="-placemark-overlay"]')[markerIndex]
            elemChangeClass(markerImage, 'map-marker_scaled', 'remove')
            unhighlightAddressItem(markerIndex);
        });

        // Обработчик открытия балуна - добавляем обработчик для кнопки закрытия
        myPlacemark.events.add('balloonopen', (e) => {
            setTimeout(() => {
            // Находим кнопку закрытия в открытом балуне
            const balloon = document.querySelector('[class*="-balloon"]');
            const closeButton = balloon?.querySelector('.map-marker__close');

            const markerImage = getAllElems('[class*="-placemark-overlay"]')[getIndex(e)]
            elemChangeClass(markerImage, 'map-marker_scaled_open', 'add')

            getAllElems('.addresses__list-item[data-active="1"]')[getIndex(e)].classList.add("addresses__list-item_active")

            if (closeButton) {
                // Удаляем старый обработчик (если был) и добавляем новый
                closeButton.replaceWith(closeButton.cloneNode(true));
                const newCloseButton = balloon.querySelector('.map-marker__close');

                newCloseButton.addEventListener('click', (event) => {
                elemChangeClass(markerImage, 'map-marker_scaled_open', 'remove')
                event.stopPropagation();
                getAllElems('.addresses__list-item[data-active="1"]')[getIndex(e)].classList.remove("addresses__list-item_active")
                deactivateAllMarkers();
                });
            }
            }, 50);
        });

        myPlacemark.events.add('balloonclose', (e) => {
            const markerImage = getAllElems('[class*="-placemark-overlay"]')[getIndex(e)]

            if (markerImage) {
            elemChangeClass(markerImage, 'map-marker_scaled_open', 'remove')
            getAllElems('.addresses__list-item[data-active="1"]')[getIndex(e)].classList.remove("addresses__list-item_active")
            }
        })

        myMap.geoObjects.add(myPlacemark);
        mapMarkers.push(myPlacemark);

        } catch (error) {
        console.error('Ошибка создания маркера для элемента', index, error);
        }
    });
    }

    const handleAddressClick = (e) => {
    e.preventDefault()
    e.stopPropagation()

    const parent = e.target.closest(".addresses__list-item")
    mapMarkers[parent.dataset.index]?.balloon.open();
    deactivateAllAddressItems();

    parent.classList.add("addresses__list-item_active")

    const info = JSON.parse(parent.dataset.info)

    myMap.setCenter(info.coordinate)
    }

    // Функция для инициализации списка адресов
    const initAddressList = () => {
    const addressItems = getAllElems('.addresses__list-item[data-active="1"');

    addressItems.forEach((item, index) => {
        item.dataset.index = index
        item.addEventListener('click', handleAddressClick);
    });
    }

    // Функция добавления класса маркеру
    const addMarkerClass = (marker, className) => {
    setTimeout(() => {
        const element = getMarkerElement(marker);
        elemChangeClass(element, className, 'add')
    }, 10);
    }

    // Функция удаления класса у конкретного маркера
    const removeMarkerClass = (marker, className) => {
    setTimeout(() => {
        const element = getMarkerElement(marker);
        elemChangeClass(element, className, 'remove')
    }, 10);
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

    // Функция получения HTML-элемента маркера
    const getMarkerElement = (marker) => {
    try {
        const overlay = marker.getOverlay();
        const element = overlay?.getElement?.();

        // Ищем SVG элемент внутри overlay - это и есть сама иконка
        return element?.querySelector('svg') || element?.querySelector('img') || element;
    } catch (error) {
        return null;
    }
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

    // Функция деактивации всех элементов списка адресов
    const deactivateAllAddressItems = () => {
    getAllElems('.addresses__list-item').forEach(item => {
        elemChangeClass(item, 'addresses__list-item_active', 'remove')
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
        'routeButtonControl'
    ];

    controls.forEach(control => myMap.controls.remove(control));
    }

    const highlightAddressItem = (index) => {
    elemChangeClass(getAllElems('.addresses__list-item[data-active="1"]')[index], 'addresses__list-item_highlighted', 'add')
    }

    // Функция для снятия подсветки с элемента списка адресов
    const unhighlightAddressItem = (index) => {
    elemChangeClass(getAllElems('.addresses__list-item[data-active="1"]')[index], 'addresses__list-item_highlighted', 'remove')
    }

    const formatAddress = (address) => {
    return address.replace(
        /(–\s?\d+)/g, // Ищем "– число" (среднее тире, (пробел или без него) и одна или больше цифр)
        '<span class="no-wrap">$1</span>' // Оборачиваем найденное в span
    );
    }

    const addressesList = document.querySelector(".addresses__list")

    if (addressesList) {
    const addresses = addressesList.dataset.addresses

    const formattedAddresses = JSON.parse(addresses);

    formattedAddresses.forEach((address, index) => {
        const divListItem = document.createElement("div")
        divListItem.dataset.index = index;
        divListItem.dataset.info = JSON.stringify(address);
        divListItem.dataset.region = address.region;
        divListItem.dataset.active = '1'
        divListItem.className = "addresses__list-item"

        const addressHTML = `
            <img class="addresses__list-icon" src="/images/icons/marker.svg" alt="marker">

            <p class="addresses__list-text text_m">
            ${formatAddress(address.address)}
            </p>
        `

        divListItem.innerHTML = addressHTML

        divListItem.addEventListener('mouseenter', (e) => {
        const index = e.target.dataset.index
        const markerImage = getAllElems('[class*="-placemark-overlay"]')[index]
        elemChangeClass(markerImage, 'map-marker_scaled', 'add')
        });

        divListItem.addEventListener('mouseleave', (e) => {
        const index = e.target.dataset.index
        const markerImage = getAllElems('[class*="-placemark-overlay"]')[index]
        elemChangeClass(markerImage, 'map-marker_scaled', 'remove')
        });

        addressesList.appendChild(divListItem)
    })
    }

    if (addressTabs) {
    addressTabs.forEach((tab) => {
        tab.addEventListener('click', (e) => {
        e.preventDefault()
        e.stopPropagation()

        const isAll = tab.dataset.region === 'all'

        if (isAll) {
            addressTabs.forEach((tab) => {
            elemChangeClass(tab, 'addresses__tab_active', 'remove')
            })
        } else {
            const isAllActive = document.querySelector('.addresses__tab_active[data-region="all"');

            if (isAllActive) {
            elemChangeClass(isAllActive, 'addresses__tab_active', 'remove')
            }
        }

        elemChangeClass(tab, 'addresses__tab_active', 'add')

        const addresses = addressesList.querySelectorAll(".addresses__list-item")

        if (isAll) {
            activeTabs = [];
        } else {
            if (!activeTabs.includes(tab.dataset.region)) {
            activeTabs.push(tab.dataset.region)
            } else {
            if (activeTabs.length === 1) {
                return
            }

            elemChangeClass(tab, 'addresses__tab_active', 'remove')

            activeTabs = activeTabs.filter((el) => el !== tab.dataset.region)
            }
        }

        addresses.forEach((address) => {
            elemChangeClass(address, 'addresses__list-item_active', 'remove')

            const parseAddress = JSON.parse(address.dataset.info);

            if (activeTabs.includes(parseAddress.region) || isAll) {
            address.style.display = 'flex'
            address.dataset.active = 1;
            } else {
            address.style.display = 'none'
            address.dataset.active = 0;
            }
        })

        init()
        })
    })
    }

    ymaps.ready(init);
});