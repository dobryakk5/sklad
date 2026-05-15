<? if (!$isIndex): ?><? CPriority::checkRestartBuffer(); ?>
<? endif; ?>
<? IncludeTemplateLangFile(__FILE__); ?>
</div><? // class=row?>

</div><? // class=row?>

</div><? // class=container?>
<? CPriority::get_banners_position('FOOTER'); ?>
</div><? // class=main?>
</div><? // class=body?>
<? CPriority::ShowPageType('footer', '', 'FOOTER_TYPE'); ?>
<div class="bx_areas">
    <? CPriority::ShowPageType('bottom_counter'); ?>
</div>


<? /*CPriority::AddMeta(
		array(
			'og:image' => 'https://alfasklad.ru/logo.svg',
		)
	);*/ ?>

<? CPriority::SetMeta(); ?>
<? // CPriority::ShowPageType('basket_component'); ?>
<!--calltouch requests-->
<script>
    jQuery(document).on('click', 'form[name="aspro_priority_callback"] button[type="submit"]', function () {
        var m = jQuery(this).closest('form');
        var fio = m.find('#POPUP_FIO').val();
        var phone = m.find('#POPUP_PHONE').val();
        //var comment = 'Промо:' + jQuery('#POPUP_PROMO').val();
        var comment = 'Время звонка:' + m.find('#POPUP_TIME').val();
        var ct_site_id = '26167';
        var sub = 'Обратный звонок';
        var ct_data = {
            fio: fio,
            phoneNumber: phone,
            subject: sub,
            requestUrl: location.href,
            sessionId: window.call_value,
            comment: comment
        };
        if (!!phone && !!fio && !window.ct_snd_flag) {
            window.ct_snd_flag = 1;
            setTimeout(function () {
                window.ct_snd_flag = 0;
            }, 10000);
            jQuery.ajax({
                url: 'https://api.calltouch.ru/calls-service/RestAPI/requests/' + ct_site_id + '/register/',
                dataType: 'json', type: 'POST', data: ct_data, async: false
            });
        }
    });

    jQuery(document).on('click', 'form[name="formManagerOrder_2"] button[class*="btn"]', function () {
        var m = jQuery(this).closest('form');
        var fio = m.find('input[name="form_text_54"]').val();
        var phone = m.find('input[name="form_text_55"]').val();
        var mail = m.find('input[name="form_email_57"]').val();
        var comment = m.find('input[name="form_text_58"]').val();
        var ct_site_id = '26167';
        var sub = 'Форма Возможности и преимущества АльфаСклад';
        var ct_data = {
            fio: fio,
            phoneNumber: phone,
            email: mail,
            comment: comment,
            subject: sub,
            requestUrl: location.href,
            sessionId: window.call_value
        };
        if (!!phone && !!fio && !!mail && !window.ct_snd_flag) {
            window.ct_snd_flag = 1;
            setTimeout(function () {
                window.ct_snd_flag = 0;
            }, 10000);
            jQuery.ajax({
                url: 'https://api.calltouch.ru/calls-service/RestAPI/requests/' + ct_site_id + '/register/',
                dataType: 'json', type: 'POST', data: ct_data, async: false
            });
        }
    });

    jQuery(document).on('click', 'form[name="formManagerOrder_3"] button[class*="btn"]', function () {
        var m = jQuery(this).closest('form');
        var fio = m.find('input[name="form_text_60"]').val();
        var phone = m.find('input[name="form_text_61"]').val();
        var mail = m.find('input[name="form_email_63"]').val();
        var ct_site_id = '26167';
        var sub = 'Форма Отправить информацию менеджеру';
        var ct_data = {
            fio: fio,
            phoneNumber: phone,
            email: mail,
            subject: sub,
            requestUrl: location.href,
            sessionId: window.call_value
        };
        if (!!phone && !!fio && !window.ct_snd_flag) {
            window.ct_snd_flag = 1;
            setTimeout(function () {
                window.ct_snd_flag = 0;
            }, 10000);
            jQuery.ajax({
                url: 'https://api.calltouch.ru/calls-service/RestAPI/requests/' + ct_site_id + '/register/',
                dataType: 'json', type: 'POST', data: ct_data, async: false
            });
        }
    });

    jQuery(document).on('click', 'form[name="aspro_priority_question"] button[type="submit"]', function () {
        var m = jQuery(this).closest('form');
        var fio = m.find('#POPUP_NAME').val();
        var phone = m.find('#POPUP_PHONE').val();
        var mail = m.find('#POPUP_EMAIL').val();
        var comment = m.find('#POPUP_MESSAGE').val();
        var ct_site_id = '26167';
        var sub = 'Форма Задать вопрос';
        var ct_data = {
            fio: fio,
            phoneNumber: phone,
            email: mail,
            comment: comment,
            subject: sub,
            requestUrl: location.href,
            sessionId: window.call_value
        };
        if (!!phone && !!fio && !window.ct_snd_flag) {
            window.ct_snd_flag = 1;
            setTimeout(function () {
                window.ct_snd_flag = 0;
            }, 10000);
            jQuery.ajax({
                url: 'https://api.calltouch.ru/calls-service/RestAPI/requests/' + ct_site_id + '/register/',
                dataType: 'json', type: 'POST', data: ct_data, async: false
            });
        }
    });

    jQuery(document).on('click', 'form[name="aspro_priority_add_review"] button[type="submit"]', function () {
        var m = jQuery(this).closest('form');
        var fio = m.find('#POPUP_NAME').val();
        var comment = m.find('#POPUP_MESSAGE').val();
        var ct_site_id = '26167';
        var sub = 'Форма Оставьте свой отзыв';
        var ct_data = {
            fio: fio,
            comment: comment,
            subject: sub,
            requestUrl: location.href,
            sessionId: window.call_value
        };
        if (!!fio && !!comment) {
            jQuery.ajax({
                url: 'https://api.calltouch.ru/calls-service/RestAPI/requests/' + ct_site_id + '/register/',
                dataType: 'json', type: 'POST', data: ct_data, async: false
            });
        }
    });

    jQuery(document).on('click', 'form[name="formManagerOrder"] button[clas*="btn"]', function () {
        var m = jQuery(this).closest('form');
        var fio = m.find('input[name="form_text_52"]').val();
        var phone = m.find('input[name="form_text_53"]').val();
        var ct_site_id = '26167';
        var sub = 'Форма Заявка через менеджера';
        var ct_data = {
            fio: fio,
            phoneNumber: phone,
            subject: sub,
            requestUrl: location.href,
            sessionId: window.call_value
        };
        if (!!fio && !!phone && !window.ct_snd_flag) {
            window.ct_snd_flag = 1;
            setTimeout(function () {
                window.ct_snd_flag = 0;
            }, 10000);
            jQuery.ajax({
                url: 'https://api.calltouch.ru/calls-service/RestAPI/requests/' + ct_site_id + '/register/',
                dataType: 'json', type: 'POST', data: ct_data, async: false
            });
        }
    });

    jQuery(document).on('click', 'form[name="formManagerOrder_5"] button[type="submit"]', function () {
        var m = jQuery(this).closest('form');
        var fio = m.find('#POPUP_NAME').val();
        var phone = m.find('#POPUP_PHONE').val();
        var ct_site_id = '26167';
        var sub = 'Форма Сообщить об освобождении бокса';
        var ct_data = {
            fio: fio,
            phoneNumber: phone,
            subject: sub,
            requestUrl: location.href,
            sessionId: window.call_value
        };
        if (!!phone && !!fio && !window.ct_snd_flag) {
            window.ct_snd_flag = 1;
            setTimeout(function () {
                window.ct_snd_flag = 0;
            }, 10000);
            jQuery.ajax({
                url: 'https://api.calltouch.ru/calls-service/RestAPI/requests/' + ct_site_id + '/register/',
                dataType: 'json', type: 'POST', data: ct_data, async: false
            });
        }
    });

    jQuery(document).on('click', 'form[name="aspro_priority_order_services"] button[type="submit"]', function () {
        var m = jQuery(this).closest('form');
        var fio = m.find('#POPUP_NAME').val();
        var phone = m.find('#POPUP_PHONE').val();
        var mail = m.find('#POPUP_EMAIL').val();
        var comment = m.find('#POPUP_MESSAGE').val();
        var ct_site_id = '26167';
        var sub = 'Форма Заказать услугу';
        var ct_data = {
            fio: fio,
            phoneNumber: phone,
            email: mail,
            comment: comment,
            subject: sub,
            requestUrl: location.href,
            sessionId: window.call_value
        };
        if (!!phone && !!fio && !window.ct_snd_flag) {
            window.ct_snd_flag = 1;
            setTimeout(function () {
                window.ct_snd_flag = 0;
            }, 10000);
            jQuery.ajax({
                url: 'https://api.calltouch.ru/calls-service/RestAPI/requests/' + ct_site_id + '/register/',
                dataType: 'json', type: 'POST', data: ct_data, async: false
            });
        }
    });

    jQuery(document).on('click', 'form[name="formManagerOrder_4"] button[class*="btn"]', function () {
        var m = jQuery(this).closest('form');
        var fio = m.find('input[name="form_text_66"]').val();
        var phone = m.find('input[name="form_text_67"]').val();
        var mail = m.find('input[name="form_email_68"]').val();
        var ct_site_id = '26167';
        var sub = 'Форма Отправьте заявку прямо сейчас';
        var ct_data = {
            fio: fio,
            phoneNumber: phone,
            email: mail,
            subject: sub,
            requestUrl: location.href,
            sessionId: window.call_value
        };
        if (!!phone && !!fio && !!mail && !window.ct_snd_flag) {
            window.ct_snd_flag = 1;
            setTimeout(function () {
                window.ct_snd_flag = 0;
            }, 10000);
            jQuery.ajax({
                url: 'https://api.calltouch.ru/calls-service/RestAPI/requests/' + ct_site_id + '/register/',
                dataType: 'json', type: 'POST', data: ct_data, async: false
            });
        }
    });

    jQuery(document).on('click', 'form[name="webpult_request"] button[type="submit"]', function () {
        var form = jQuery(this).closest('form');
        var fio = form.find('#POPUP_NAME').val();
        var phone = form.find('#POPUP_PHONE').val();
        var ct_site_id = '26167';
        var comment = 'Время звонка:' + form.find('#POPUP_TIME2').val();
        var sub = 'Форма Расчет размера бокса';
        var ct_data = {
            fio: fio,
            phoneNumber: phone,
            subject: sub,
            comment: comment,
            requestUrl: location.href,
            sessionId: window.call_value
        };
        if (!!phone && !!fio && !window.ct_snd_flag) {
            window.ct_snd_flag = 1;
            setTimeout(function () {
                window.ct_snd_flag = 0;
            }, 10000);
            jQuery.ajax({
                url: 'https://api.calltouch.ru/calls-service/RestAPI/requests/' + ct_site_id + '/register/',
                dataType: 'json', type: 'POST', data: ct_data, async: false
            });
        }
    });

    jQuery(document).on('click', 'form[name="short_subscribe"] input[type="submit"]', function () {
        var form = jQuery(this).closest('form');
        var mail = form.find('#POPUP_EMAIL').val();
        var ct_site_id = '26167';
        var sub = 'Форма Подписка на рассылку, футер';
        var ct_data = {
            email: mail,
            subject: sub,
            requestUrl: location.href,
            sessionId: window.call_value
        };
        if (!!mail && !window.ct_snd_flag) {
            window.ct_snd_flag = 1;
            setTimeout(function () {
                window.ct_snd_flag = 0;
            }, 10000);
            jQuery.ajax({
                url: 'https://api.calltouch.ru/calls-service/RestAPI/requests/' + ct_site_id + '/register/',
                dataType: 'json', type: 'POST', data: ct_data, async: false
            });
        }
    });

    jQuery(document).on('click', 'form[name="form_storage"] button[type="submit"]', function () {
        var m = jQuery(this).closest('form');
        var fio = m.find('#POPUP_NAME').val();
        var phone = m.find('#POPUP_PHONE').val();
        var mail = m.find('#POPUP_EMAIL').val();
        var comment = m.find('#POPUP_MESSAGE').val();
        var ct_site_id = '26167';
        var sub = 'Форма Заказать хранение Под ключ';
        var ct_data = {
            fio: fio,
            phoneNumber: phone,
            email: mail,
            comment: comment,
            subject: sub,
            requestUrl: location.href,
            sessionId: window.call_value
        };
        if (!!phone && !!fio && !window.ct_snd_flag) {
            window.ct_snd_flag = 1;
            setTimeout(function () {
                window.ct_snd_flag = 0;
            }, 10000);
            jQuery.ajax({
                url: 'https://api.calltouch.ru/calls-service/RestAPI/requests/' + ct_site_id + '/register/',
                dataType: 'json', type: 'POST', data: ct_data, async: false
            });
        }
    });

    jQuery(document).on('mouseup touchend', 'form[name="ORDER_FORM"] #bx-soa-orderSave a, form[name="ORDER_FORM"] .bx-soa-cart-total-button-container a', function () {
        var form = jQuery(this).closest('form');
        var lastName = form.find('[name="ORDER_PROP_1"]').val();
        var firstName = form.find('[name="ORDER_PROP_2"]').val();
        var fio = (!!lastName && !!firstName) ? lastName + ' ' + firstName : form.find('[name="ORDER_PROP_16"]').val();
        var phone = form.find('[name="ORDER_PROP_7"], [name="ORDER_PROP_15"]').val();
        var email = form.find('[name="ORDER_PROP_8"], [name="ORDER_PROP_14"]').val();
        var comment = form.find('#orderDescription').val();
        var ct_site_id = '26167';
        var sub = 'Оформление заказа';
        var ct_data = {
            fio: fio,
            phoneNumber: phone,
            email: email,
            subject: sub,
            comment: comment,
            requestUrl: location.href,
            sessionId: window.call_value
        };
        var ct_valid = false;
        switch ($('[name="PERSON_TYPE"]:checked').val()) {
            case '1':
                ct_valid = !!firstName && !!lastName && !!phone && !!email && !!form.find('[name="ORDER_PROP_9"]').val() && !!form.find('[name="ORDER_PROP_10"]').val();
                break;
            case '2':
                ct_valid = !!form.find('[name="ORDER_PROP_11"]').val() && !!phone && !!email;
                break;
            default:
                console.log('unknown person type');
                break;
        }
        console.log(ct_data, ct_valid);
        if (ct_valid && window.ct_snd_flag != 1) {
            window.ct_snd_flag = 1;
            setTimeout(function () {
                window.ct_snd_flag = 0;
            }, 20000);
            jQuery.ajax({
                url: 'https://api.calltouch.ru/calls-service/RestAPI/requests/' + ct_site_id + '/register/',
                dataType: 'json', type: 'POST', data: ct_data, async: false
            });
        }
    });
</script>
<!--calltouch requests-->
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (m, e, t, r, i, k, a) {
        m[i] = m[i] || function () {
            (m[i].a = m[i].a || []).push(arguments)
        };
        m[i].l = 1 * new Date();
        for (var j = 0; j < document.scripts.length; j++) {
            if (document.scripts[j].src === r) {
                return;
            }
        }
        k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
    })
    (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

    ym(50400436, "init", {
        clickmap: true,
        trackLinks: true,
        accurateTrackBounce: true,
        webvisor: true,
        ecommerce: "dataLayer"
    });
</script>
<noscript>
    <div><img src="https://mc.yandex.ru/watch/50400436" style="position:absolute; left:-9999px;" alt=""/></div>
</noscript>
<!-- /Yandex.Metrika counter -->

<div class="jsloadAssetsCss"></div>
<div class="jsloadAssetsJs"></div>
<!--        <link rel="stylesheet" href="--><? //=SITE_TEMPLATE_PATH?><!--/_template__styles.css">-->
<!-- <link rel="stylesheet" href="<?= SITE_TEMPLATE_PATH ?>/css/responsive.min.css">
			<link rel="stylesheet" href="<?= SITE_TEMPLATE_PATH ?>/css/custom.css"> -->
<? $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/css/custom-2.css"); ?>
<? $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/css/additional.css", true); ?>
</body>
</html>