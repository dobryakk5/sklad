<? if (!$isIndex): ?><? CPriority::checkRestartBuffer(); ?>
<? endif; ?>
<? IncludeTemplateLangFile(__FILE__); ?>
<? global $arTheme, $isIndex, $is404, $isCatalog, $isServices; ?>
<? if (!$isIndex && !$isCatalog && !$isProjects): ?>
    <? if ($is404): ?>
        </div>
    <? else: ?>
        <? if (!$isMenu): ?>
            </div><? // class=col-md-12 col-sm-12 col-xs-12 content-md
                    ?>
        <? elseif ($isMenu && $arTheme["SIDE_MENU"]["VALUE"] == "LEFT" && !$isBlog): ?>
            </div><? // class=col-md-9 col-sm-9 col-xs-8 content-md
                    ?>
        <? elseif ($isMenu && ($arTheme["SIDE_MENU"]["VALUE"] == "RIGHT" || $isBlog)): ?>
            <? CPriority::get_banners_position('CONTENT_BOTTOM'); ?>
            </div><? // class=col-md-9 col-sm-9 col-xs-8 content-md
                    ?>
            <div class="col-md-3 col-sm-3 hidden-xs hidden-sm right-menu-md">
                <? CPriority::ShowPageType('left_block') ?>
            </div>
        <? endif; ?>
    <? endif; ?>
    </div><? // class=row
            ?>
    <? if ($APPLICATION->GetProperty("FULLWIDTH") !== 'Y'): ?>
        </div><? // class="maxwidth-theme
                ?>
    <? endif; ?>
<? elseif ($isIndex && $APPLICATION->GetCurPage() != '/index_test.php'): ?>
    <? //CPriority::ShowPageType('indexblocks'); 
    ?>
<? endif; ?>

</div><? // class=container
        ?>
<? CPriority::get_banners_position('FOOTER'); ?>

</div><? // class=main ?>

<?php if ($isIndex): ?>
    </div>  <? // class=new-ui-bg-grey ?>
<?php endif; ?>

</div><? // class=body ?>
<? CPriority::ShowPageType('footer', '', 'FOOTER_TYPE'); ?>
<div class="bx_areas">
    <? CPriority::ShowPageType('bottom_counter'); ?>
</div>

<script>
    /* document.addEventListener("DOMContentLoaded", function () {
        setTimeout(function () {

            document.querySelectorAll('a').forEach(link => {
                if (link.href.includes("https://wa.me/79855800640")) {
                    link.href = "https://widget.yourgood.app/#whatsapp";
                }
            });
        }, 1000);
    });*/
</script>


<?
/*CPriority::AddMeta(
		array(
			'og:image' => 'https://alfasklad.ru/logo.svg',
		)
	);*/
?>

<? CPriority::SetMeta(); ?>
<? CPriority::ShowPageType('search_title_component'); ?>
<? CPriority::ShowPageType('basket_component'); ?>
<? CPriority::AjaxAuth(); ?>
<!--calltouch requests-->
<script>
    jQuery(document).on('click', 'form[name="aspro_priority_callback"] button[type="submit"]', function() {
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
            setTimeout(function() {
                window.ct_snd_flag = 0;
            }, 10000);
            jQuery.ajax({
                url: 'https://api.calltouch.ru/calls-service/RestAPI/requests/' + ct_site_id + '/register/',
                dataType: 'json',
                type: 'POST',
                data: ct_data,
                async: false
            });
        }
    });

    jQuery(document).on('click', 'form[name="formManagerOrder_2"] button[class*="btn"]', function() {
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
            setTimeout(function() {
                window.ct_snd_flag = 0;
            }, 10000);
            jQuery.ajax({
                url: 'https://api.calltouch.ru/calls-service/RestAPI/requests/' + ct_site_id + '/register/',
                dataType: 'json',
                type: 'POST',
                data: ct_data,
                async: false
            });
        }
    });

    jQuery(document).on('click', 'form[name="formManagerOrder_3"] button[class*="btn"]', function() {
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
            setTimeout(function() {
                window.ct_snd_flag = 0;
            }, 10000);
            jQuery.ajax({
                url: 'https://api.calltouch.ru/calls-service/RestAPI/requests/' + ct_site_id + '/register/',
                dataType: 'json',
                type: 'POST',
                data: ct_data,
                async: false
            });
        }
    });

    jQuery(document).on('click', 'form[name="aspro_priority_question"] button[type="submit"]', function() {
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
            setTimeout(function() {
                window.ct_snd_flag = 0;
            }, 10000);
            jQuery.ajax({
                url: 'https://api.calltouch.ru/calls-service/RestAPI/requests/' + ct_site_id + '/register/',
                dataType: 'json',
                type: 'POST',
                data: ct_data,
                async: false
            });
        }
    });

    jQuery(document).on('click', 'form[name="aspro_priority_add_review"] button[type="submit"]', function() {
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
                dataType: 'json',
                type: 'POST',
                data: ct_data,
                async: false
            });
        }
    });

    jQuery(document).on('click', 'form[name="formManagerOrder"] button[clas*="btn"]', function() {
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
            setTimeout(function() {
                window.ct_snd_flag = 0;
            }, 10000);
            jQuery.ajax({
                url: 'https://api.calltouch.ru/calls-service/RestAPI/requests/' + ct_site_id + '/register/',
                dataType: 'json',
                type: 'POST',
                data: ct_data,
                async: false
            });
        }
    });

    jQuery(document).on('click', 'form[name="formManagerOrder_5"] button[type="submit"]', function() {
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
            setTimeout(function() {
                window.ct_snd_flag = 0;
            }, 10000);
            jQuery.ajax({
                url: 'https://api.calltouch.ru/calls-service/RestAPI/requests/' + ct_site_id + '/register/',
                dataType: 'json',
                type: 'POST',
                data: ct_data,
                async: false
            });
        }
    });

    jQuery(document).on('click', 'form[name="aspro_priority_order_services"] button[type="submit"]', function() {
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
            setTimeout(function() {
                window.ct_snd_flag = 0;
            }, 10000);
            jQuery.ajax({
                url: 'https://api.calltouch.ru/calls-service/RestAPI/requests/' + ct_site_id + '/register/',
                dataType: 'json',
                type: 'POST',
                data: ct_data,
                async: false
            });
        }
    });

    jQuery(document).on('click', 'form[name="formManagerOrder_4"] button[class*="btn"]', function() {
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
            setTimeout(function() {
                window.ct_snd_flag = 0;
            }, 10000);
            jQuery.ajax({
                url: 'https://api.calltouch.ru/calls-service/RestAPI/requests/' + ct_site_id + '/register/',
                dataType: 'json',
                type: 'POST',
                data: ct_data,
                async: false
            });
        }
    });

    jQuery(document).on('click', 'form[name="webpult_request"] button[type="submit"]', function() {
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
            setTimeout(function() {
                window.ct_snd_flag = 0;
            }, 10000);
            jQuery.ajax({
                url: 'https://api.calltouch.ru/calls-service/RestAPI/requests/' + ct_site_id + '/register/',
                dataType: 'json',
                type: 'POST',
                data: ct_data,
                async: false
            });
        }
    });

    jQuery(document).on('click', 'form[name="short_subscribe"] input[type="submit"]', function() {
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
            setTimeout(function() {
                window.ct_snd_flag = 0;
            }, 10000);
            jQuery.ajax({
                url: 'https://api.calltouch.ru/calls-service/RestAPI/requests/' + ct_site_id + '/register/',
                dataType: 'json',
                type: 'POST',
                data: ct_data,
                async: false
            });
        }
    });

    jQuery(document).on('click', 'form[name="form_storage"] button[type="submit"]', function() {
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
            setTimeout(function() {
                window.ct_snd_flag = 0;
            }, 10000);
            jQuery.ajax({
                url: 'https://api.calltouch.ru/calls-service/RestAPI/requests/' + ct_site_id + '/register/',
                dataType: 'json',
                type: 'POST',
                data: ct_data,
                async: false
            });
        }
    });

    jQuery(document).on('mouseup touchend', 'form[name="ORDER_FORM"] #bx-soa-orderSave a, form[name="ORDER_FORM"] .bx-soa-cart-total-button-container a', function() {
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
            setTimeout(function() {
                window.ct_snd_flag = 0;
            }, 20000);
            jQuery.ajax({
                url: 'https://api.calltouch.ru/calls-service/RestAPI/requests/' + ct_site_id + '/register/',
                dataType: 'json',
                type: 'POST',
                data: ct_data,
                async: false
            });
        }
    });
</script>
<!--calltouch requests-->

<?php
/*
<!-- Добавление кода от 17.10.2024 -->
<script>
(function () {
	console.log('Скрипт от 17.10.2024 Ларионов');
	
	function ChangeLinkWA() {
		this.text = "Хочу узнать подробнее о хранении вещей в АльфаСкладе. Номер обращения {wz_metric}";
		this.cookieSource = "roistat_visit";
	}
	
	ChangeLinkWA.prototype.editLink = function (url, id) {
		if (
			decodeURIComponent(url.split("text=")[1]) ===
			this.text.replace(/{wz_metric}/gi, id)
		)
		return;
		var regexNumberPhone = /\d+/;
		if (!regexNumberPhone.test(url)) return;
		var phone = url.match(regexNumberPhone)[0];
		var host = url.split(phone)[0];
		var newUrl =
			host === "https://wa.me/"
			? host.toString()+phone.toString()+"?text="+(this.text.replace(/{wz_metric}/gi, id))
			: host.toString()+phone.toString()+"&text="+(this.text.replace(/{wz_metric}/gi, id));
			return newUrl;
	};

	ChangeLinkWA.prototype.getCookie = function (name) {
		var cookie = document.cookie;
		var matches = cookie.match(
		new RegExp(
			"(?:^|; )"+(name.replace(/([.$?*|{}()[]\/+^])/g, "\\$1"))+"=([^;]*)"
		));
		return matches ? decodeURIComponent(matches[1]) : undefined;
	};

	ChangeLinkWA.prototype.censusLinks = function () {
		var links = document.querySelectorAll(
			'[href*="//wa.me"], [href*="//api.whatsapp.com/send"], [href*="//web.whatsapp.com/send"], [href^="whatsapp://send"]'
		);
		var id = this.getCookie(this.cookieSource);
		var that = this;
		links.forEach(function (link) {
			var newLink = that.editLink(link.href, id);
			if (newLink) link.href = newLink;
		});
	};

	window.addEventListener("DOMContentLoaded", function () {
		if (!(window.__wz_scripts && window.__wz_scripts.scriptsChangeLinkWA)) {
			if (!window.__wz_scripts) window.__wz_scripts = {};
			window.__wz_scripts.scriptsChangeLinkWA = new ChangeLinkWA();
			var interval = setInterval(function () {
				var id = window.__wz_scripts.scriptsChangeLinkWA.getCookie(
					window.__wz_scripts.scriptsChangeLinkWA.cookieSource
				);
				if (id) {
					clearInterval(interval);
					window.__wz_scripts.scriptsChangeLinkWA.censusLinks();
				}
			}, 200);
		}
	});
	console.log('Конец скрипта... Ларионов');
})();
</script>
*/
?>

<?
function isPageSpeedUserAgent()
{
    if (!isset($_SERVER['HTTP_USER_AGENT'])) {
        return false;
    }
    return preg_match('/Chrome-Lighthouse|Speed Insights|Google Page Speed Insights/i', $_SERVER['HTTP_USER_AGENT']);
}


if (!isPageSpeedUserAgent()) {
?>


<? } ?>

<div class="jsloadAssetsCss"></div>
<div class="jsloadAssetsJs"></div>

<? $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/css/custom-2.css"); ?>
<? $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/css/additional.css", true); ?>

<? if ($isIndex && false): ?>
    <div itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">
        <meta itemprop="ratingValue" content="4,9">
        <meta itemprop="reviewCount" content="452">
        <meta itemprop="worstRating" content="1">
        <meta itemprop="bestRating" content="5">
    </div>
<? endif; ?>
</body>

</html>