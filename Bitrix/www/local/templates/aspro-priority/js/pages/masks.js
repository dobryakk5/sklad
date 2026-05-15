const inputPassport = document.querySelectorAll(".input-passport")

inputPassport.forEach((input) => {
  IMask(input, {
    mask: '00 00  000000'
  });
})

const inputPhone = document.querySelectorAll(".input-phone")

inputPhone.forEach((input) => {
  IMask(input, {
    mask: '+{7} (000) 000-00-00'
  });
})

const inputInn = document.querySelectorAll(".input-inn")

inputInn.forEach((input) => {
  IMask(input, {
    mask: '0000 0000 0000'
  });
})