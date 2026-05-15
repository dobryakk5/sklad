<section class="how-rent">
    <div class="card">
        <div class="card__inner">
            <div class="card__header">
                <h2 class="card__title">
                    <?php if ($arParams['CAPTION']): ?>
                        <?= html_entity_decode($arParams['CAPTION']) ?>
                    <?php else: ?>
                        Как <span class="text_accent">арендовать</span> бокс
                    <?php endif; ?>
                </h2>
            </div>

            <?php if ($arParams['TEXT']): ?>
                <?= html_entity_decode($arParams['TEXT']) ?>
            <?php else: ?>
                <div class="how-rent__cards">
                    <div class="how-rent__card">
                        <img src="/images/icons/x60/phone_x60.svg" alt="">

                        <p class="how-rent__card-title text_l">
                            <span class="text_accent">Позвоните</span> нам
                        </p>

                        <p class="how-rent__card-text text_m">
                            Менеджеры АльфаСклада помогут подобрать оптимальный для Вас вариант помещения, сориентируют по стоимости и основным условиям сотрудничества.
                        </p>

                        <p class="how-rent__card-text text_m text-parted_m">
                            При желании через специалиста Вы можете забронировать бокс или оформить договор.
                        </p>
                    </div>

                    <div class="how-rent__card">
                        <img src="/images/icons/x60/manager_x60.svg" alt="">

                        <p class="how-rent__card-title text_l">
                            <span class="text_accent">Приезжайте</span> к нам
                        </p>

                        <p class="how-rent__card-text text_m">
                            Приезжайте к нам в рабочие часы менеджера: ежедневно с 8:30 до 20:30.
                        </p>

                        <p class="how-rent__card-text text_m text-parted_m">
                            Ждем Вас в комплексе для предварительного осмотра бокса или сразу для размещения предметов.
                        </p>

                        <p class="how-rent__card-text text_m text-parted_m">
                            На месте подпишем договор и предоставим доступ.
                        </p>
                    </div>

                    <div class="how-rent__card">
                        <img src="/images/icons/x60/site_x60.svg" alt="">

                        <p class="how-rent__card-title text_l">
                            <span class="text_accent">Арендуйте</span> бокс <span class="text_accent">на&nbsp;сайте</span>
                        </p>

                        <p class="how-rent__card-text text_m">
                            В разделе <a class="link_medium" href="./" target="_blank">«Арендовать онлайн»</a> выберите нужный склад и помещение, заполните данные и внесите оплату.
                        </p>

                        <p class="how-rent__card-text text_m text-parted_m">
                            Приезжайте в комплекс в любое время и разместите имущество.
                        </p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>