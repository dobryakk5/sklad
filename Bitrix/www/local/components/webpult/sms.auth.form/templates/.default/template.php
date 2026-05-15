<?
$APPLICATION->SetTitle('Зайти в Личный кабинет');
?>
<div class="auth_wrapp form-block sms_auth_block">
    <span class="jqmClose top-close fa fa-close"><?= CPriority::showIconSvg(SITE_TEMPLATE_PATH . '/images/include_svg/close.svg'); ?></span>

    <div class="wrap_md">
        <div class="main_info form form_get_sms">
            <div class="form-wr form-body">
                <form name="system_sms_auth_form" method="post">

                    <?= bitrix_sessid_post() ?>
                    <input type="hidden" name="form_type" value="request_sms">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group animated-labels">
                                <label for="USER_PHONE">Введите номер телефона <span class="required-star">*</span></label>
                                <div class="input">
                                    <input type="text" name="USER_PHONE" id="USER_PHONE" class="form-control phone required" maxlength="50" value="" autocomplete="on" tabindex="1"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="error-msg"></div>
                    <div class="but-r">
                        <div class="buttons clearfix">
                            <button type="submit" class="btn btn-default btn-lg pull-left" name="GET_CODE" value="" tabindex="4">Получить код</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="main_info form form_set_code">
            <div class="form-wr form-body">
                <form name="system_code_auth_form" method="post">
                    <?= bitrix_sessid_post() ?>
                    <input type="hidden" name="form_type" value="sms_code">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group animated-labels">
                                <label for="USER_PHONE_SMS_CODE">Введите код <span class="required-star">*</span></label>
                                <div class="input">
                                    <input type="text" name="USER_PHONE_SMS_CODE" id="USER_PHONE_SMS_CODE" class="form-control required" maxlength="50" value="" autocomplete="off" tabindex="1"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="error-msg"></div>
                    <div class="but-r">
                        <div class="buttons clearfix">
                            <button type="submit" class="btn btn-default btn-lg pull-left" name="LOGIN" value="" tabindex="4">Войти</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<style>
    .form_set_code {
        display: none;
    }
</style>
    <script>

        //ajaxForm(document.getElementsByName('system_sms_auth_form')[0], '<?//=$templateFolder?>///ajax.php')

var link = '<?=$templateFolder?>/ajax.php';
        obForm = document.getElementsByName('system_sms_auth_form')[0]
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
                        $('.form_get_sms').hide(300);
                        $('.form_set_code').show(500);
                    }
                }
            };

            xhr.onerror = function() {
                alert("Запрос не удался");
            };

            // Передаем все данные из формы
            xhr.send(new FormData(obForm));
        }, obForm, link));

        obForm2 = document.getElementsByName('system_code_auth_form')[0]
        BX.bind(obForm2, 'submit', BX.proxy(function(e) {
            BX.PreventDefault(e);
            obForm2.getElementsByClassName('error-msg')[0].innerHTML = '';

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
                        obForm2.getElementsByClassName('error-msg')[0].innerHTML = errorStr;
                    } else {
                        // Показываем сообщение об успешной отправке
                        // popupSuccess()
                        window.location.reload(true);
                    }
                }
            };

            xhr.onerror = function() {
                alert("Запрос не удался");
            };

            // Передаем все данные из формы
            xhr.send(new FormData(obForm2));
        }, obForm2, link));







        $(document).ready(function () {

            if (arPriorityOptions['THEME']['PHONE_MASK'].length) {
                var base_mask = arPriorityOptions['THEME']['PHONE_MASK'].replace(/(\d)/g, '_');
                $('.sms_auth_block form[name="system_sms_auth_form<?= $arResult["RND"] ?>"] input.phone').inputmask('mask', {'mask': arPriorityOptions['THEME']['PHONE_MASK'], 'showMaskOnHover': false});
                $('.sms_auth_block form[name="system_sms_auth_form<?= $arResult["RND"] ?>"] input.phone').blur(function () {
                    if ($(this).val() == base_mask || $(this).val() == '') {
                        if ($(this).hasClass('required')) {
                            $(this).parent().find('div.error').html(BX.message('JS_REQUIRED'));
                        }
                    }
                });
            }

            try {
                setTimeout(function () {
                    //USER_PHONE
                    if ($('#avtorization-form input[name=USER_PHONE]').length > 0) {
                        $('#avtorization-form input[name=USER_PHONE]').parents('.form-group').addClass('input-filed');
                    }
                }, 300);
            } catch (e) {
            }
        })
    </script>

</div>

