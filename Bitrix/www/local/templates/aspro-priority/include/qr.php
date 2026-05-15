<?php
global $arRegion;

$arBackParametrs = CPriority::GetBackParametrsValues(SITE_ID);

$phone = str_replace([' ', '-', '–', '(', ')'], '', $arRegion ? $arRegion['PHONES'][0] : $arBackParametrs['HEADER_PHONES_array_PHONE_VALUE_0']);
?>

<div class="qr-wrapper">
    <?php if ((date('Gi') > 830 && date('Gi') < 2030 && !$_GET['night']) || $_GET['day']): ?>
        <button class="hero-block__button button button_red" data-popup-action="qr-to-call" data-phone="<?= $phone ?>">
            Позвонить
        </button>
        <p class="hero-block__hint text_s text_grey-medium">
            При нажатии откроется QR-код<br>
            Наведите на него камеру телефона для быстрого набора номера
        </p>

        <div class="popup popup-qr" data-popup="qr-to-call">
            <div class="popup__wrapper">
                <div class="popup-qr__container">
                    <?php if (false): ?>
                        <img class="popup-qr__qr-code" src="<?= getQRphonePath($phone) ?>" alt="qr-code">
                    <?php else: ?>
                        <img class="popup-qr__qr-code" src="/ajax/qr.php?phone=#" alt="qr-code">
                    <?php endif; ?>

                    <p class="popup-qr__text text_s">
                        Наведите камеру телефона на<br>QR-код для быстрого набора номера
                    </p>

                    <button class="popup-qr__close" data-popup-action="qr-to-call">
                        <img src="/images/icons/close-medium.svg" alt="close">
                    </button>
                </div>
            </div>
        </div>
    <?php else: ?>
        <button class="hero-block__button button button_red" data-event="jqm" data-param-id="21" data-name="callback">
            Оставить контакт
        </button>
    <?php endif; ?>
</div>