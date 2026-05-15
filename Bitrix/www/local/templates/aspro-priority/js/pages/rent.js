let PAGINATION_PAGE = 1;
const PAGINATION_PER_PAGE = 6;

const cardsRoom = document.querySelectorAll(".card-room")

const catalogButtonHide = document.querySelector(".catalog__button_hide")
const catalogButtonMore = document.querySelector(".catalog__button_more")

const hideCards = () => {
  cardsRoom.forEach((card, index) => {
    if(index >= PAGINATION_PAGE * PAGINATION_PER_PAGE) {
      card.style.display = 'none'
    } else {
      card.style.display = 'flex'
    }
  })
}

hideCards()

catalogButtonHide.addEventListener("click", (e) => {
  e.stopPropagation()
  e.preventDefault()

  PAGINATION_PAGE -= 1

  if(PAGINATION_PAGE === 1) {
    catalogButtonHide.style.display = 'none'
  } else {
    catalogButtonHide.style.display = 'block'
  }

  if(cardsRoom.length > PAGINATION_PAGE * PAGINATION_PER_PAGE) {
    catalogButtonMore.style.display = 'block'
  }

  hideCards()
})

catalogButtonMore.addEventListener("click", (e) => {
  e.stopPropagation()
  e.preventDefault()

  PAGINATION_PAGE += 1

  if(cardsRoom.length < PAGINATION_PAGE * PAGINATION_PER_PAGE) {
    catalogButtonMore.style.display = 'none'
  } else {
    catalogButtonMore.style.display = 'block'
  }

  if(PAGINATION_PAGE > 1) {
    catalogButtonHide.style.display = 'block'
  } else {
    catalogButtonHide.style.display = 'none'
  }

  hideCards()
})


const tabsShowed = document.querySelectorAll('[data-form-type]');
const formBlocks = document.querySelectorAll('[data-form-block]');

const formAllInputs = document.querySelectorAll(".input-box__input");

const payment = document.querySelector(".payment")

const catalogSorting = document.querySelector(".catalog__sorting");
const catalogSortingAction = catalogSorting?.querySelector(".catalog__sorting-action")
const catalogSortinCurrent = catalogSorting?.querySelector(".catalog__sorting-current")
const catalogSortingDropdown = catalogSorting?.querySelector(".catalog__sorting-dropdown")
const catalogSortingSortingTypes = catalogSorting?.querySelectorAll(".catalog__sorting-type")

const filtersTabs = document.querySelector(".choose-room").querySelectorAll(".form-rent__tabs-tab");

const filterTabActiveClass = "form-rent__tabs-tab_active"

const summary = document.querySelector(".summary");
const summaryCount = document.querySelector(".summary__count");
const summaryPrice = document.querySelector(".summary__price");
const summarySquare = document.querySelector(".summary__square");
const rentalPriceSum = document.getElementById("rental_price_sum")
const paymentTotalSum = document.getElementById("payment_total_sum")

const paymentTotalAmountDate = document.querySelector(".payment__total-amount-date");
const paymentPeriodDateLap = document.querySelector(".payment__period-date_lap")
const paymentPeriodDatePayment = document.querySelector(".payment__period-date_payment")

const cart = {};

if(formAllInputs) {
  formAllInputs.forEach((input) => {
    input.addEventListener("input", (e) => {
      e.stopPropagation()
      e.preventDefault()

      input.closest(".input-box").classList.remove("error")
      input.closest(".input-box").querySelector(".input-box__error").innerHTML = ''
    })
  })
}

if (tabsShowed) {
  tabsShowed.forEach((tab) => {
    tab.addEventListener("click", (e) => {
      e.stopPropagation()
      e.preventDefault()

      tabsShowed.forEach(tab => {
        tab.classList.remove(filterTabActiveClass)
      });

      formBlocks.forEach(form => {
        form.classList.remove('form-rent__row_active')

        const allInputs = form.querySelectorAll("input")
        const allTextareas = form.querySelectorAll("textarea")

        allInputs.forEach((input) => {
          if (input.type === 'checkbox') {
            input.checked = false
          } else {
            input.value = ''
          }
        })

        allTextareas.forEach((textarea) => {
          textarea.value = ''
        })
      });

      tab.classList.add(filterTabActiveClass)

      document.querySelector(`[data-form-block="${tab.getAttribute("data-form-type")}"]`).classList.add("form-rent__row_active")
    })
  })
}

if (summary && payment) {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        summary.classList.remove("summary_active")
      } else {
        if(summary.classList.contains("summary_has-products")) {
          summary.classList.add("summary_active")
        }
      }
    });
  });

  observer.observe(payment);
}

const toggleClassDropdown = () => {
  catalogSortingDropdown.classList.toggle("catalog__sorting-dropdown_active")
}

const handleOutsideDropdownClick = (e) => {
  if(!e.target.closest(".catalog__sorting")) {

    toggleClassDropdown()
    window.removeEventListener("click", handleOutsideDropdownClick)
  }
}

if(catalogSorting && catalogSortingAction && catalogSortingDropdown) {
  catalogSortingAction.addEventListener("click", (e) => {
    e.stopPropagation()
    e.preventDefault()

    toggleClassDropdown()

    if(catalogSortingDropdown.classList.contains("catalog__sorting-dropdown_active")) {
      window.addEventListener("click", handleOutsideDropdownClick)
    } else {
      window.removeEventListener("click", handleOutsideDropdownClick)
    }
  })

  // Возможно на будущее понадобится для сортировки

  // catalogSortingSortingTypes.forEach((type) => {
  //   type.addEventListener('click', (e) => {
  //     e.preventDefault()
  //     e.stopPropagation()

  //     catalogSortingSortingTypes.forEach((type) => {
  //       type.classList.remove("catalog__sorting-type_active")
  //     })

  //     type.classList.add("catalog__sorting-type_active")

  //     catalogSortinCurrent.innerHTML = type.innerHTML

  //     toggleClassDropdown()

  //     if(catalogSortingDropdown.classList.contains("catalog__sorting-dropdown_active")) {
  //       window.addEventListener("click", handleOutsideDropdownClick)
  //     } else {
  //       window.removeEventListener("click", handleOutsideDropdownClick)
  //     }
  //   })
  // })
}

filtersTabs.forEach((tab) => {
  tab.addEventListener("click", (e) => {
    e.preventDefault()
    e.stopPropagation()

    if(tab.classList.contains(filterTabActiveClass)) {
      tab.classList.remove(filterTabActiveClass)
    } else {
      tab.classList.add(filterTabActiveClass);
    }

    // дальнейшая логика с беком
  })
})

cardsRoom.forEach((card) => {
  card.addEventListener('click', () => {
    if(!card.classList.contains("card-room_active")) {
      card.classList.add("card-room_active")
    
      card.dataset.count = '1';

      card.querySelector(".card-room__chosen-button_plus").addEventListener("click", plusCount)
      card.querySelector(".card-room__chosen-button_minus").addEventListener("click", minusCount)
    }

    updateSummary()
    updatePayment(card)
  })
})

const updateSummary = () => {
  let price = 0;
  let square = 0;
  let count = 0;

  cardsRoom.forEach((card) => {
    const cardCount = +card.dataset.count
    const cardPrice = +card.dataset.price
    const cardSquare = +card.dataset.square

    if(cardCount) {
      price += +cardPrice * cardCount
      square += +cardSquare * cardCount
      count += cardCount
    }
  })

  if(price || square || count) {
    summary.classList.add("summary_active")
    summary.classList.add("summary_has-products")
  } else {
    summary.classList.remove("summary_active")
    summary.classList.remove("summary_has-products")
  }

  summaryCount.innerText = count
  summaryPrice.innerText = price
  summarySquare.innerText = square
  rentalPriceSum.innerText = price
  paymentTotalSum.innerHTML = price + 4000
}

const plusCount = (e) => {
  e.preventDefault();
  e.stopPropagation()

  const parent = e.target.closest('.card-room')
  const parentCount = +parent.dataset.count
  const counter = parent.querySelector('.card-room__chosen-count');

  if(parentCount === 99) {
    return
  } else {
    counter.innerText = parentCount + 1;
    parent.dataset.count = parentCount + 1
  }

  updatePayment(parent)
  updateSummary()
}

const minusCount = (e) => {
  e.preventDefault();
  e.stopPropagation()

  const parent = e.target.closest('.card-room')
  const parentCount = +parent.dataset.count
  const counter = parent.querySelector('.card-room__chosen-count');

  if(parentCount === 1) {
    parent.classList.remove("card-room_active")
    parent.dataset.count = '0';

    e.target.removeEventListener("click", plusCount)
    e.target.removeEventListener("click", minusCount)
  } else {
    counter.innerText = parentCount - 1;
    parent.dataset.count = parentCount - 1
  }

  updatePayment(parent)
  updateSummary()
}

const updatePayment = (card) => {
  const type = card.dataset.type;
  const count = +card.dataset.count;
  const name = card.querySelector(".card-room__name").innerText
  const width = card.querySelector(".card-room__plan-width").innerText
  const depth = card.querySelector(".card-room__plan-depth").innerText
  const height = card.querySelector(".card-room__plan-height").innerText
  const price = parseInt(card.querySelector(".card-room__price").textContent.trim().replace(/\s/g, ''))
  const address = document.querySelector(".addresses__list-item_active").innerText
  
  if(!(type in cart)) {
    cart[type] = {
      type,
      price,
      name,
      width,
      depth,
      height,
      count,
      address,
    }
  } else {
    cart[type].count = count
  }

  renderProducts()
}

const renderProducts = () => {
  const container = document.querySelector('.payment__items');
  container.innerHTML = '';

  for (const product in cart) {
    for (let i = 1; i <= cart[product].count; i++) {
      const elementHTML = createProductElement(cart[product], i + 1);
      container.innerHTML = container.innerHTML += elementHTML
    }
  }

  const allPaymentItems = document.querySelectorAll(".payment__item")

  if(allPaymentItems?.length > 1) {
    const totalHTML = renderTotal()
    container.innerHTML = container.innerHTML += totalHTML
  }
}

const renderTotal = () => {
  return `
    <div class="payment__item payment__total">
      <p class="payment__total-title text_m">
        Итого:
      </p>

      <div class="payment__total-info">
        <div class="payment__total-info__row">
          <p class="payment__total-square text_m">
            Площадь <span class="text_accent">${summarySquare.innerText} м²</span>
          </p>

          <p class="payment__total-count text_m">
            <span class="text_accent">${summaryCount.innerText}</span> помещения
          </p>
        </div>

        <p class="payment__total-price text_l">
          <span class="text_accent">${summaryPrice.innerText}</span> <span class="text_medium">руб.</span> / мес.
        </p>
      </div>
    </div>
  `
}

const createProductElement = (product, index) => {
  return `
        <div class="payment__item payment__product" data-key="${product.type}-${index}">
          <div class="payment__product-address">
            <img class="payment__product-marker" src="/images/icons/marker.svg" alt="marker">

            <p class="payment__product-street text_m">
              ${product.address}
            </p>
          </div>

          <div class="payment__product-info">
            <div class="payment__product-info__row">
              <div class="payment__product-info__block">
                <p class="payment__product-name text_l">
                  Ячейка <span class="text_accent">1 м³</span>
                </p>

                <div class="payment__product-size">
                  <p class="payment__product-text_grey text_xs">
                    Ш
                  </p>

                  <p class="payment__product-footage text_xs text_accent">
                    ${product.width}
                  </p>

                  <img src="/images/icons/cross-grey.svg" alt="cross-grey">

                  <p class="payment__product-text_grey text_xs">
                    Г 
                  </p>

                  <p class="payment__product-footage text_xs text_accent">
                    ${product.depth}
                  </p>

                  <img src="/images/icons/cross-grey.svg" alt="cross-grey">

                  <p class="payment__product-text_grey text_xs">
                    В
                  </p>

                  <p class="payment__product-footage text_xs text_accent">
                    ${product.height}
                  </p>
                </div>
              </div>

              <p class="payment__product-number text_m">
                №000000
              </p>
            </div>

            <p class="payment__product-price text_l">
              <span class="text_accent">${product.price}</span> <span class="text_medium">руб.</span> / мес.
            </p>
          </div>

          <button class="payment__product-delete">
            <img src="/images/icons/trash.svg" alt="delete">
          </button>
        </div>
      `
}

const setDate = () => {  
  const dateFormatter = new Intl.DateTimeFormat('ru', {
    day: '2-digit',
    month: '2-digit', 
    year: 'numeric'
  });

  const today = new Date();
  const formatToday = dateFormatter.format(new Date());

  const todayPlus5 = new Date(today);

  todayPlus5.setDate(today.getDate() + 5);
  const formatTodayPlus5 = dateFormatter.format(todayPlus5);

  const lastDayOfMonth = new Date(todayPlus5.getFullYear(), todayPlus5.getMonth() + 1, 0);
  const formatLastDayOfMonth = dateFormatter.format(lastDayOfMonth)
  
  const todayParts = formatToday.split('.');
  const todayPlus5Parts = formatTodayPlus5.split('.');
  const lastDayParts = formatLastDayOfMonth.split('.');

  paymentTotalAmountDate.innerHTML = `с <span class="text_accent">${todayPlus5Parts[0]}</span>.${todayPlus5Parts[1]}.${todayPlus5Parts[2]} по <span class="text_accent">${lastDayParts[0]}</span>.${lastDayParts[1]}.${lastDayParts[2]}`;

  paymentPeriodDateLap.innerHTML = `c <span class="text_accent">${todayParts[0]}</span>.${todayParts[1]}.${todayParts[2]}`
  paymentPeriodDatePayment.innerHTML = `c <span class="text_accent">${todayPlus5Parts[0]}</span>.${todayPlus5Parts[1]}.${todayPlus5Parts[2]}`
}

setDate()

