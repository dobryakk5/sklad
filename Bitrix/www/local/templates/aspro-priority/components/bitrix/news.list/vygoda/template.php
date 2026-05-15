<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<? if($arResult["ITEMS"]): ?>
<div class="maxwidth-theme">
    <div class="shadow-box offers-block">
        <h2 class="offers-block__title">
            Выгодные предложения
        </h2>

        <div class="flexslider flexslider-init flexslider-direction-nav" id="carousel_vygoda">
            <ul class="slides offers-block__list" itemscope itemtype="http://schema.org/ItemList">

                <? foreach ($arResult["ITEMS"] as $key => $arItem) { ?>
                    <?
                    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                    ?>


                    <li class="item offers-block__item " id="<?= $this->GetEditAreaId($arItem['ID']); ?>" itemscope
                        itemprop="itemListElement" itemtype="http://schema.org/Product">
                        <? if (!empty($arItem["PROPERTIES"]["HREF"]["VALUE"])){
                        ?>


                        <a href="<?= $arItem["PROPERTIES"]["HREF"]["VALUE"] ?>" itemprop="url">
                            <?
                            } ?>
                            <div class="offers-block__img">
                                <img itemprop="image" id="PICTURE_<?= $arItem["ID"] ?>"
                                     src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>"
                                     width="276" height="183" alt="<?= $arItem["PREVIEW_PICTURE"]["DESCRIPTION"] ?>">
                            </div>
                            <input type="hidden" id="PREVIEW_TEXT_<?= $arItem["ID"] ?>"
                                   NAME="PREVIEW_TEXT_<?= $arItem["ID"] ?>"
                                   value="<?= $arItem["PREVIEW_TEXT"] ?>">
                            <meta itemprop="description" content="<?= $arItem["PREVIEW_TEXT"] ?> - описание <?=$arItem["NAME"]?>">

                            <p itemprop="name" class="offers-block__location " id="NAME_<?= $arItem["ID"] ?>">
                                <?= $arItem["NAME"] ?>
                            </p>

                            <div style="display:none" itemscope itemprop="offers" itemtype="http://schema.org/Offer">
                                <div><span itemprop="price"><?= $arItem["PROPERTIES"]["PRICE_FROM"]["VALUE"] ?></span>
                                    руб.
                                </div>
                                <meta itemprop="priceCurrency" content="RUB">
                                <meta itemprop="availability" content="http://schema.org/InStock"/>
                            </div>


                            <div class="offers-block__square">
                                <p class="offers-block__square-text">
                                    Площадь
                                </p>
                                <p class="offers-block__square-value " id="PLOCHSAD_<?= $arItem["ID"] ?>">
                                    <?= $arItem["PROPERTIES"]["PLOCHSAD"]["VALUE"] ?>
                                </p>
                            </div>
                            <p class="offers-block__price " id="PRICE_FROM_<?= $arItem["ID"] ?>">
                                от <?= $arItem["PROPERTIES"]["PRICE_FROM"]["VALUE"] ?> руб.
                            </p>

                            <button onclick="setElementToForm(`<?= $arItem['ID'] ?>`);"
                                    class="btn btn-default offers-block__btn offers-open-modal">
                                Арендовать
                            </button> <? if (!empty($arItem["PROPERTIES"]["HREF"]["VALUE"])){
                            ?>
                        </a>
                    <? } ?>
                    </li>

                <? } ?>

            </ul>
        </div>
    </div>
</div>

<div class="modal-overlay">
    <div class="offers-modal">
        <button type="button" class="offers-modal__close"></button>
        <div class="offers-modal__wrap">
            <div class="offers-modal__img">
                <img width="364" height="364" id="FORM_PREVIEW_PICTURE"
                     src="/bitrix/templates/aspro-priority/images/modal-img.jpg" alt="">
            </div>
            <div class="offers-modal__content">
                <p class="offers-modal__title">
                    Ваш заказ
                </p>
                <ul class="offers-modal__list">
                    <li class="offers-modal__item">
                        <p class="offers-modal__category">
                            Название
                        </p>
                        <p id="FORM_NAME" class="offers-modal__value">
                            Склад на Молодогвардейской
                        </p>
                    </li>
                    <li class="offers-modal__item">
                        <p class="offers-modal__category">
                            Адрес
                        </p>
                        <address id="FORM_PREVIEW_TEXT" class="offers-modal__value">
                            г. Москва, ул. Молодогвардейская, д. 61, стр. 3
                        </address>
                    </li>
                    <li class="offers-modal__item">
                        <p class="offers-modal__category">
                            Площадь
                        </p>
                        <p id="FORM_PROPERTY_PLOCHSAD" class="offers-modal__value">
                            5.92 м2
                        </p>
                    </li>
                    <li class="offers-modal__item">
                        <p class="offers-modal__category">
                            Цена
                        </p>
                        <div class="offers-modal__price">
                            <p id="FORM_PROPERTY_PRICE_FROM" class="offers-modal__price-value">
                                5 850 руб.
                            </p>
                            <p class="offers-modal__price-description">
                                за месяц
                            </p>
                        </div>
                    </li>
                </ul>
            </div>


            <form action="#" id="PREDLOSHENIYA_FORM" class="offers-modal__form" method="POST">
                <input type="hidden" name="ELEMENT_ID" id="FORM_ELEMENT_ID">
                <fieldset class="offers-modal__form-wrap">
                    <legeng class="offers-modal__form-legend">
                        Заполните ваши данные и мы вам перезвоним
                    </legeng>
                    <ul class="offers-modal__form-list">
                        <li class="offers-modal__form-item">
                            <input type="text" required class="offers-modal__form-input" name="NAME"
                                   placeholder="Ваше имя">
                            <label class="offers-modal__form-label">
                                Ваше имя<span class="span_red">*</span>
                            </label>
                        </li>
                        <li class="offers-modal__form-item">
                            <input type="tel" required class="offers-modal__form-input" name="PHONE"
                                   placeholder="Телефон">
                            <label for="" class="offers-modal__form-label">
                                Телефон<span class="span_red">*</span>
                            </label>
                        </li>
                        <li class="offers-modal__form-item">
                            <input type="text" required class="offers-modal__form-input" name="PROMO"
                                   placeholder="Промокод">
                            <label for="" class="offers-modal__form-label">
                                Промокод<span class="span_red">*</span>
                            </label>
                        </li>
                    </ul>
                    <div class="offers-modal__form-checkbox">
                        <label id="SOGLASIYE" class="offers-modal__checkbox">
                            <input type="checkbox" id="SOGLASIYE" name="SOGLASIYE">
                            <span class="offers-modal__checkmark"></span>
                        </label>
                        <p class="offers-modal__checkbox-text">
                            Я согласен на <a href="">обработку персональных данных</a>
                        </p>
                    </div>
                    <button type="submit" class="btn btn-default offers-modal__btn">
                        Отправить заявку
                    </button>
                </fieldset>
            </form>
        </div>
    </div>
</div>


<script>
    function setElementToForm(id) {

        var IMAGE = $("#PICTURE_" + id).attr("src")
        var NAME = $("#NAME_" + id).html()
        var PLOCHSAD = $("#PLOCHSAD_" + id).html()
        var PRICE_FROM = $("#PRICE_FROM_" + id).html()
        var PREVIEW_TEXT = $("#PREVIEW_TEXT_" + id).val()

        $("#FORM_PREVIEW_PICTURE").attr("src", IMAGE);
        $("#FORM_NAME").html(NAME);
        $("#FORM_PREVIEW_TEXT").html(PREVIEW_TEXT);

        $("#FORM_PROPERTY_PLOCHSAD").html(PLOCHSAD);
        $("#FORM_PROPERTY_PRICE_FROM").html(PRICE_FROM);
        $("#FORM_ELEMENT_ID").val(id);


    }

    $('#PREDLOSHENIYA_FORM').submit(function (e) {
        var $form = $(this);
        $.ajax({
            dataType: "JSON",
            type: "POST",
            url: '/ajax/predlosheniya.php',
            data: $form.serialize()
        }).done(function (answer) {
            if (answer.SUCSESS == "Y") {
                $form.html("<h2>Спасибо , ваша заявка принята!</h2>");
            } else {
                alert(answer.MESSAGE);
            }
        }).fail(function () {
            console.log('fail');
        });
        //отмена действия по умолчанию для кнопки submit
        e.preventDefault();
    });


    $(document).ready(function () {

        $('#carousel_vygoda').flexslider({
            animation: 'slide',
            controlNav: false,
            animationLoop: true,
            slideshow: true,
            itemWidth: 300,
            itemMargin: 25,
            directionNav: true,
            touch: true,
            minItems: 1,
            maxItems: 4,
            start: function () {
                $('.gallery_mainpage #carousel').height('auto');
                $('.gallery_mainpage #carousel').css({'width': 'auto', 'opacity': 1});
            }
        });

    });


</script>
<? endif; ?>