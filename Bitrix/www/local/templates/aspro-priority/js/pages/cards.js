const cardsRoom = document.querySelectorAll(".card-room")

const summary = document.querySelector(".summary");
const summaryCount = document.querySelector(".summary__count");
const summaryPrice = document.querySelector(".summary__price");
const summarySquare = document.querySelector(".summary__square");

cardsRoom.forEach((card) => {
  card.addEventListener('click', () => {
    if(!card.classList.contains("card-room_active")) {
      card.classList.add("card-room_active")
    
      card.dataset.count = '1';

      card.querySelector(".card-room__chosen-button_plus").addEventListener("click", plusCount)
      card.querySelector(".card-room__chosen-button_minus").addEventListener("click", minusCount)
    } else {

    }

    updateSummary()
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
  } else {
    summary.classList.remove("summary_active")
  }

  summaryCount.innerText = count
  summaryPrice.innerText = price
  summarySquare.innerText = square
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

  updateSummary()
}