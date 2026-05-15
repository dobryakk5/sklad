(function () {
    document.addEventListener('click', function (event) {
        var item = event.target.closest('.referral-faq-item');

        if (!item) {
            return;
        }

        item.classList.toggle('is-open');
    });
})();
