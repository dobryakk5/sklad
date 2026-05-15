<section class="how-choose">
    <div class="card">
        <div class="card__inner">
            <div class="card__header">
                <h2 class="card__title">
                    <?php if ($arParams['CAPTION']): ?>
                        <?= html_entity_decode($arParams['CAPTION']) ?>
                    <?php else: ?>
                        Как <span class="text_accent">выбрать</span> складской бокс
                    <?php endif; ?>
                </h2>

                <!-- <p class="card__subtitle">
                    Нюансов, на которые важно обратить внимание во время выбора бокса, довольно много. Поэтому понять, какое помещение нужно для хранения Ваших вещей, может быть нелегкой задачей.
                </p> -->
            </div>

            <?php if ($arParams['TEXT']): ?>
                <?= html_entity_decode($arParams['TEXT']) ?>
            <?php else: ?>

                <p class="how-choose__text text_m text-parted_m">
                    Нюансов, на которые важно обратить внимание во время выбора бокса, довольно много. Поэтому понять, какое помещение нужно для&nbsp;хранения Ваших вещей, может быть нелегкой задачей.
                </p>
                <p class="how-choose__text text_m text-parted_m">
                    Наши менеджеры отмечают, что изначальное представление многих людей относительно того, какая площадь бокса им нужна, завышено. Стоит также учитывать следующие детали:
                </p>
                <div class="how-choose__cards">
                    <div class="how-choose__card vrezka">
                        <p class="how-choose__card-text text_s text-parted_s">
                            Организация пространства.
                            <br>
                            В&nbsp;зависимости от&nbsp;того, насколько часто Вы хотите увозить и&nbsp;привозить личные вещи, под&nbsp;один и&nbsp;тот же набор предметов целесообразно выбирать боксы разной площади.
                        </p>
                    </div>
                    <div class="how-choose__card vrezka">
                        <p class="how-choose__card-text text_s text-parted_s">
                            Квадратная или&nbsp;вытянутая форма помещения.
                            <br>
                            Зависит от&nbsp;набора вещей. Например, если в&nbsp;составе небольшого объема предметов Вы собираетесь хранить велосипеды или&nbsp;диван, оптимально выбрать бокс 2 м&sup2; вытянутой формы.
                        </p>
                    </div>
                    <div class="how-choose__card vrezka">
                        <p class="how-choose__card-text text_s text-parted_s">
                            Расположение бокса.
                            <br>
                            Если у&nbsp;Вас много тяжелых и&nbsp;громоздких предметов, их нужно часто перемещать, удобнее использовать помещение на&nbsp;первом этаже или&nbsp;расположенное недалеко от&nbsp;подъемника.
                        </p>
                    </div>
                </div>
                <div class="how-choose__blocks">
                    <div class="how-choose__block">
                        <img src="/images/icons/x60/manager_x60.svg" class="how-choose__block-icon" alt="">
                        <p class="how-choose__block-text text_m">
                            Если у Вас нет уверенности в&nbsp;том, помещение какой площади и&nbsp;формы Вам нужно, позвоните нашим менеджерам. Они помогут учесть все факторы во время подбора помещения для&nbsp;хранения вещей.
                        </p>
                    </div>
                    <div class="how-choose__block">
                        <img src="/images/icons/x60/site_x60.svg" class="how-choose__block-icon" alt="">
                        <p class="how-choose__block-text text_m">
                            Вы также можете воспользоваться <a class="link_medium" href="https://alfasklad.ru/price/">онлайн–калькулятором</a> для&nbsp;оценки подходящей площади бокса.
                        </p>
                    </div>
                </div>
                <br>
            <?php endif; ?>
        </div>
    </div>
</section>