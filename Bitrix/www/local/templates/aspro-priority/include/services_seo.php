<?php /*$APPLICATION->AddHeadScript("/local/templates/aspro-priority/js/pages/script.js");*/ ?>

<section class="storage-services">
    <div class="card">
        <div class="card__inner">
            <div class="card__header">
                <h2 class="card__title text_grey-medium">
                    <?php if ($arParams['CAPTION']): ?>
                        <?= html_entity_decode($arParams['CAPTION']) ?>
                    <?php else: ?>
                        Услуги по хранению вещей
                    <?php endif; ?>
                </h2>
            </div>

            <?php if ($arParams['TIZER']): ?>
                <?= html_entity_decode($arParams['TIZER']) ?>
            <?php else: ?>
                <p class="storage-services__accordion-text text_m">
                    Арендовать бокс на складе индивидуального хранения или разместить там вещи на хранение – это удобный
                    способ сделать квартиру, дом или офис просторнее и комфортнее. Здесь Вы можете разместить мебель, бытовую
                    технику, спортивную экипировку, детские товары, сезонный инвентарь, оборудование, товары для продажи,
                    архив и многое другое. 
                </p>
                <p class="storage-services__accordion-text text_m">
                    Комплекс круглосуточно находится под охраной и видеонаблюдением, отапливается и вентилируется.

                    <span class="storage-services__accordion-action_closed text_m">
                        ...
                    </span>
                </p>
            <?php endif; ?>


            <div class="storage-services__accordion">
                <div class="storage-services__accordion-dropdown">
                    <?php if ($arParams['TEXT']): ?>
                        <?= html_entity_decode($arParams['TEXT']) ?>
                    <?php else: ?>
                        <p class="storage-services__accordion-subtitle text_l text_medium">
                            Как оценить объем вещей и правильно подобрать бокс для хранения
                        </p>

                        <p class="storage-services__accordion-text text_m text-parted_m">
                            Оценить, сколько у Вас вещей по объему и помещение какой площади Вам нужно для их хранения, может быть непростой задачей. Наши менеджеры отмечают, что изначальное представление многих людей относительно того, какой у них объем вещей в кубометрах и какая площадь нужна для их хранения, завышено. 
                        </p>

                        <p class="storage-services__accordion-text text_m text-parted_m">
                            Если у Вас нет уверенности в этом вопросе, проконсультируйтесь с нашими менеджерами, они Вам помогут учесть все нюансы, или воспользуйтесь калькулятором, дающим достаточно точную оценку. 
                        </p>

                        <p class="storage-services__accordion-text text_m text-parted_m">
                            Калькулятор автоматически рассчитает объем хранения и площадь бокса на основе состава Ваших вещей. Вам нужно только указать в готовом списке количество предметов, которое Вы собираетесь хранить.
                        </p>

                        <p class="storage-services__accordion-text text_m text-parted_m">
                            Если Вы планируете арендовать бокс онлайн, то калькулятор Вам будет особенно полезен, чтобы самостоятельно выбрать бокс нужного размера.
                        </p>

                        <p class="storage-services__accordion-text text_m">
                            Также, в зависимости от того, насколько часто Вы хотите увозить и привозить вещи во время хранения, Вам может быть удобнее по-разному организовать пространство в боксе и под один и тот же набор вещей может быть целесообразно арендовать боксы разной площади. 
                        </p>

                        <p class="storage-services__accordion-text text_m">
                            В зависимости от состава вещей, особенно в случае боксов небольшой площади, также может иметь значение форма бокса. Например, если Вы в составе небольшого объема вещей планируете хранить велосипеды или диван и Вам подходит бокс 2 кв. м, то Вам может быть удобнее выбрать бокс вытянутой формы, а не квадратный. 
                        </p>

                        <p class="storage-services__accordion-text text_m">
                            Если у Вас много вещей, они тяжелые или громоздкие и к тому же предполагается их частое перемещение,
                            Вам может быть удобнее использовать помещение на первом этаже, даже несмотря на более высокую
                            стоимость, или бокс на другом этаже, расположенный недалеко от подъемника.
                        </p>
                    <?php endif; ?>
                </div>

                <div class="storage-services__accordion-action_opened text_m text_anchor-normal">
                    Скрыть
                </div>
            </div>
        </div>
    </div>
</section>