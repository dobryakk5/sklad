(function () {
    function copyText(text) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            return navigator.clipboard.writeText(text);
        }

        return new Promise(function (resolve, reject) {
            var input = document.createElement('input');
            input.value = text;
            input.setAttribute('readonly', 'readonly');
            input.style.position = 'absolute';
            input.style.left = '-9999px';

            document.body.appendChild(input);
            input.select();

            try {
                document.execCommand('copy');
                document.body.removeChild(input);
                resolve();
            } catch (e) {
                document.body.removeChild(input);
                reject(e);
            }
        });
    }

    document.addEventListener('click', function (event) {
        var button = event.target.closest('.lk-referral-code-box');

        if (!button) {
            return;
        }

        var text = button.getAttribute('data-copy');
        var note = document.querySelector('[data-copy-note]');

        if (!text) {
            return;
        }

        copyText(text).then(function () {
            if (note) {
                note.hidden = false;

                setTimeout(function () {
                    note.hidden = true;
                }, 2000);
            }
        });
    });
})();
