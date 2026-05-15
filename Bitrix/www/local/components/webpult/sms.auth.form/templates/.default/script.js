function ajaxForm(obForm, link) {
    BX.bind(obForm, 'submit', BX.proxy(function(e) {
        BX.PreventDefault(e);
        obForm.getElementsByClassName('error-msg')[0].innerHTML = '';

        let xhr = new XMLHttpRequest();
        xhr.open('POST', link);

        xhr.onload = function() {
            if (xhr.status != 200) {
                alert(`Ошибка ${xhr.status}: ${xhr.statusText}`);
            } else {
                var json = JSON.parse(xhr.responseText)

                if (! json.success) {
                    let errorStr = '';
                    for (let fieldKey in json.errors) {
                        errorStr += json.errors[fieldKey] + '<br>';
                    }

                    // Ошибки вывести в элемент с классом error-msg
                    obForm.getElementsByClassName('error-msg')[0].innerHTML = errorStr;
                } else {
                    // Показываем сообщение об успешной отправке
                    // popupSuccess()
                }
            }
        };

        xhr.onerror = function() {
            alert("Запрос не удался");
        };

        // Передаем все данные из формы
        xhr.send(new FormData(obForm));
    }, obForm, link));
}