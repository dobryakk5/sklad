<section class="price-rent-spb">
    <div class="card">
        <div class="card__inner">
            <div class="card__header">
                <h2 class="card__title">
                    <?php if ($arParams['CAPTION']): ?>
                        <?= html_entity_decode($arParams['CAPTION']) ?>
                    <?php else: ?>
                        <span class="text_accent">Стоимость</span> аренды бокса в Москве
                    <?php endif; ?>
                </h2>
            </div>
            <?php if ($arParams['TEXT']): ?>
                <?= html_entity_decode($arParams['TEXT']) ?>
            <?php else: ?>
                <div class="price-rent-spb__items">
                    <div class="price-rent-spb__info">
                        <p class="text_m text-parted_m">
                            Стоимость услуги зависит от <span class="text_accent">локации</span> склада в городе, <span class="text_accent">размера</span> помещения и его <span class="text_accent">расположения</span> внутри комплекса
                        </p>

                        <p class="text_m text-parted_m">
                            Чем меньше размер бокса, тем ниже арендная плата, но в более крупном помещении квадратный метр площади обойдется дешевле.
                        </p>

                        <p class="text_m text-parted_m">
                            Цена аренды самого маленького бокса (1 кв. м) – <span class="text_medium">от</span> <span class="text_accent">2350 </span><span class="text_medium">руб. в месяц</span>
                        </p>

                        <p class="text_m text-parted_m">
                            Вы оплачиваете аренду заранее в конце каждого календарного месяца.
                        </p>
                    </div>

                    <div class="price-rent-spb__card vrezka">
                        <p class="price-rent-spb__card-text text_m text_medium">
                            В стоимость аренды входит:
                        </p>

                        <ul class="vrezka-list list">
                            <li class="list__elem">
                                <p class="text_m">
                                    Парковка на территории складских комплексов
                                </p>
                            </li>

                            <li class="list__elem">
                                <p class="text_m">
                                    Оборудование для погрузки и выгрузки: тележки, рохли, штабелеры
                                </p>
                            </li>

                            <li class="list__elem">
                                <p class="text_m">
                                    Коммунальные платежи
                                </p>
                            </li>

                            <li class="list__elem">
                                <p class="text_m">
                                    WI-FI
                                </p>
                            </li>

                            <li class="list__elem text_m">
                                <p class="text_m">
                                    Компенсация в случае повреждения имущества (см.&nbsp;<a href="https://alfasklad.ru/pdfjs/web/?file=/upload/iblock/ae7/wr3mntp38pzmou74c2k5njvnorszyrob/Standartnye-usloviya-predostavleniya-v-arendu-_Boksov_-dlya-khraneniya-imushchestva-ot-05.12.2024.pdf&amp;roistat_visit=1694592" target="_blank" rel="noopener noreferrer">пункт&nbsp;5&nbsp;Оферты</a>)
                                </p>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="price-rent-spb__contact-us">
                    <img class="price-rent-spb__contact-us-icon" src="/images/icons/x60/manager_x60.svg" alt="manager">

                    <p class="text_m">
                        Свяжитесь с нами и <span class="text_accent">расскажите о составе своих вещей.</span> Мы <span class="text_accent">поможем подобрать</span> оптимальное помещение и <span class="text_accent">рассчитаем стоимость</span> аренды именно в Вашем случае.
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>