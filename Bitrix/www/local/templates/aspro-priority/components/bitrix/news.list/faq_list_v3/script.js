$(document).ready(function() {
  const accs = document.querySelectorAll(".accordion")
  const accActions = document.querySelectorAll(".accordion__action");

  accActions.forEach((acc) => {
    acc.addEventListener('click', () => {
      const parent = acc.closest(".accordion")

      if(parent.classList.contains("accordion_active")) {
        parent.classList.remove("accordion_active")

        const panel = parent.querySelector(".accordion__dropdown");

        panel.style.maxHeight = null;

        return;
      } else {
        accs.forEach((acc) => {
          acc.classList.remove("accordion_active")

          const panel = acc.querySelector(".accordion__dropdown");

          panel.style.maxHeight = null;
        })

        const panel = parent.querySelector(".accordion__dropdown");

        if (panel.style.maxHeight) {
          panel.style.maxHeight = null;
          parent.classList.remove("accordion_active")
        } else {
          panel.style.maxHeight = panel.scrollHeight + "px";
          parent.classList.add("accordion_active")
        }
      }
    })
  })
});