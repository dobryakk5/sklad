<div class="what-keep">
    <div class="card">
        <div class="card__inner">
            <div class="card__header">
                <h2 class="card__title">
                    <?php if ($arParams['CAPTION']): ?>
                        <?= html_entity_decode($arParams['CAPTION']) ?>
                    <?php else: ?>
                        <span class="text_accent">Что можно хранить</span> в индивидуальных боксах
                    <?php endif; ?>
                </h2>
            </div>

            <?php if ($arParams['TEXT']): ?>
                <?= html_entity_decode($arParams['TEXT']) ?>
            <?php else: ?>
                <div class="what-keep__blocks">
                    <div class="what-keep__block">
                        <p class="what-keep__text text_m">
                            У нас можно хранить любые предметы, кроме представляющих опасность для людей, имущества других клиентов и помещений складского комплекса (см. пункт 2.7 Оферты), например:
                        </p>

                        <ul class="what-keep__list">
                            <li class="what-keep__list-elem">
                                <img class="what-keep__list-icon" src="/images/icons/cross-border-red.svg" alt="cross red">

                                <p class="what-keep__list-text text_m">
                                    огнеопасныx, взрывчатыx, токсичныx, сильно пахнущиx веществ;
                                </p>
                            </li>

                            <li class="what-keep__list-elem">
                                <img class="what-keep__list-icon" src="/images/icons/cross-border-red.svg" alt="cross red">

                                <p class="what-keep__list-text text_m">
                                    незаконныx веществ;
                                </p>
                            </li>

                            <li class="what-keep__list-elem">
                                <img class="what-keep__list-icon" src="/images/icons/cross-border-red.svg" alt="cross red">

                                <p class="what-keep__list-text text_m">
                                    очень тяжелых предметов, которые создают нагрузку на пол, превышающую допустимую <span>(см. пункт 2.10 Оферты)</span>.
                                </p>
                            </li>
                        </ul>
                    </div>

                    <div class="what-keep__block">
                        <div class="what-keep__card vrezka">
                            <p class="what-keep__text text_m">
                                Для хранения предметов, устойчивых к перепаду температур, мы предлагаем аренду контейнера, что даст Вам существенную экономию.
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>