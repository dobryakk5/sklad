<?use Bitrix\Main\Page\Asset;?>
<footer class="footer">
    <div class="container">
        <div class="flex-block main-footerInfo">
            <div class="col col-1">
                <div class="section"><h3>Для дома</h3><a href="/features_for_personal/">Возможности и преимущества для физ.лиц</a></div>
                <div class="section"><h3>Для бизнеса</h3><a href="/features_for_business/">Возможности и преимущества для бизнеса</a></div>
                <div class="section"><h3>Мой заказ</h3></div>
            </div>
            <div class="col col-2"><h3>О Компании</h3>
                <ul class="list">
                    <li><a href="/about/kupim-ili-arenduem-sklad/">Купим или арендуем помещение</a></li>
                    <li><a href="/blog/">Полезная информация</a></li>
                    <li><a href="/news/">Новости</a></li>
                    <li><a href="/karta-saita/">Карта сайта</a></li>
                </ul>
            </div>
            <div class="col col-3">
                <ul class="big-list">
                    <li><a href="/rental_catalog/">Онлайн аренда</a></li>
                    <li><a href="/services/khraneniya-pod-klyuch/">Хранение “Под ключ”</a></li>
                    <li><a href="/services/usluga-dostavka/">Доставка</a></li>
                    <li><a href="/about/fotogalereya-skladov/">Фотогалерея</a></li>
                    <li><a href="/find_storage/">Контакты</a></li>
                    <li><a href="/cabinet/">Личный кабинет</a></li>
                </ul>
            </div>
            <div class="col col-4">
                <ul class="big-list">
                    <li><a href="/price/">Цены</a></li>
                    <li><a href="/promotions/">Акции</a></li>
                    <li><a href="/services/">Услуги</a></li>
                    <li><a href="/about/reviews/">Отзывы</a></li>
                    <li><a href="/about/documents/">Документы</a></li>
                    <li><a href="/news/mobilnoe-prilozhenie-alfasklad/">Мобильное приложение</a></li>
                </ul>
            </div>
            <div class="col col-5"><a class="btn btn-gray-with-icon" href="https://t.me/bestblinks"
                                      target="_blank"><span>Оставить заявку</span>
                <picture class="icon-svg"><img src="<?=SITE_TEMPLATE_PATH ?>/img/tg.svg"/></picture>
            </a>
                <div class="flex-block app-stores"><a href="#"><img class="item-image" src="<?=SITE_TEMPLATE_PATH ?>/img/google-play.jpg"
                                                                    alt="google-play"/></a><a href="#"><img
                        class="item-image" src="<?=SITE_TEMPLATE_PATH ?>/img/app-store.jpg" alt="app-store"/></a></div>
                <ul class="location">
                    <li class="phone"><a href="tel:+74951169783">
                        <picture class="icon-svg"><img src="<?=SITE_TEMPLATE_PATH ?>/img/phone.svg"/></picture>
                        <span>+7 (495) 116-97-83</span></a><a class="order-call" href="#">Заказать звонок</a></li>
                    <li class="watsup"><a href="https://wa.me/74951169783" target="_blank">
                        <picture class="icon-svg"><img src="<?=SITE_TEMPLATE_PATH ?>/img/wasap.svg"/></picture>
                        <span>написать в WhatsApp</span></a></li>
                    <li class="mail"><a href="mailto:info@alfasklad.ru" target="_blank">
                        <picture class="icon-svg"><img src="<?=SITE_TEMPLATE_PATH ?>/img/mail.svg"/></picture>
                        <span>info@alfasklad.ru</span></a></li>
                    <li><span class="address"><picture class="icon-svg"><img src="<?=SITE_TEMPLATE_PATH ?>/img/address.svg"/></picture><span>Москва, ул.Верхнелихоборская, д. 8А</span></span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="flex-block copy-policy">
            <div class="copyright">© Альфасклад 2010-2022</div>
            <a class="policy" href="">Политика конфиденциальности</a></div>
        <div class="flex-block socials">
            <div class="line"></div>
            <div class="icons-wrapper"><a href="#" target="_blank">
                <picture class="icon-svg"><img src="<?=SITE_TEMPLATE_PATH ?>/img/vk.svg"/></picture>
            </a><a href="#" target="_blank">
                <picture class="icon-svg"><img src="<?=SITE_TEMPLATE_PATH ?>/img/star.svg"/></picture>
            </a></div>
            <div class="line"></div>
        </div>
    </div>
</footer>
<div class="underfooter">
    <div class="container">
        <div class="left-side">
            <div class="accordeon-socials">
                <div class="accorlink">
                    <picture class="icon-svg"><img src="<?=SITE_TEMPLATE_PATH ?>/img/message-colored.svg"/></picture>
                </div>
                <div class="panel"><a data-fancybox="modalCall" href="#modalCall">
                    <picture class="icon-svg"><img src="<?=SITE_TEMPLATE_PATH ?>/img/phone-arrow.svg"/></picture>
                    <span>Заказать звонок</span></a>
                    <a data-fancybox="modalSendMail" href="#modalSendMail">
                    <picture class="icon-svg"><img src="<?=SITE_TEMPLATE_PATH ?>/img/mail-inline.svg"/></picture>
                    <span>Отправить E-mail</span></a>
                    <a data-fancybox="modalReview" href="#modalReview">
                    <picture class="icon-svg"><img src="<?=SITE_TEMPLATE_PATH ?>/img/message.svg"/></picture>
                    <span>Задать вопрос</span></a>
                    <a id="btnModalOurContacts" href="#modalOurContacts">
                    <picture class="icon-svg"><img src="<?=SITE_TEMPLATE_PATH ?>/img/address2.svg"/></picture>
                    <span>Наши контакты</span></a></div>
            </div>
            <a class="phone" href="tel:+74951549846">+7 (495) 154-98-46</a></div>
        <div class="right-side"><a class="btn btn-primary little-one" href="#dGoogleMap">Рассчитать стоимость</a></div>
    </div>
    <div class="modalCall" id="modalCall" style="display:none">
        <form class="webform" action="" method="POST">
            <div class="form-header">
                <div class="text">
                    <div class="title">Обратный звонок</div>
                    <div class="description">Представьтесь, мы вам перезвоним.</div>
                </div>
            </div>
            <div class="form-body"><label class="input-label">
                    <div class="input-border"><input class="input-field" type="text" name="name" id="name"
                                                     placeholder="Ваше имя *" required></div>
                </label><label class="input-label">
                    <div class="input-border"><input class="input-field phone-number" type="text" name="phone-number"
                                                     id="phone-number" placeholder="Телефон *" required></div>
                </label><label class="input-label">
                    <div class="input-border"><input class="input-field" type="text" name="promo" id="promo"
                                                     placeholder="Промокод"></div>
                </label></div>
            <div class="form-footer"><label class="type-checkbox"><input class="input-field" type="checkbox"
                                                                         oninput="this.className = ''" name="agreement"
                                                                         value="Соглашение" required><span
                            class="input-text">Я согласен на обработку &nbsp;<a
                                href="https://alfasklad.ru/include/licenses_detail.php"
                                target="_blank">персональных данных</a></span></label>
                <button class="btn btn-primary" type="submit" id="sendCallBack" name="sendCallBack">Отправить</button>
            </div>
        </form>
    </div>
    <div class="modalSendMail" id="modalSendMail" style="display:none">
        <form class="webform" action="" method="POST">
            <div class="form-header">
                <div class="text">
                    <div class="title">Задать вопрос</div>
                    <div class="description">Наши менеджеры с вами свяжутся, чтобы ответить на ваш вопрос</div>
                </div>
            </div>
            <div class="form-body"><label class="input-label">
                    <div class="input-border"><input class="input-field" type="text" name="name" id="askName"
                                                     placeholder="Ваше имя *" required></div>
                </label><label class="input-label">
                    <div class="input-border"><input class="input-field phone-number" type="text" name="phone-number"
                                                     id="ask-phone-number" placeholder="Телефон *" required></div>
                </label><label class="input-label">
                    <div class="input-border"><input class="input-field" type="mail" name="email" id="email"
                                                     placeholder="E-mail"></div>
                </label><label class="input-label">
                    <div class="input-border"><input class="input-field" type="text" name="goods" id="goods"
                                                     placeholder="Интересующий товар/услуга"></div>
                </label><label class="input-label">
                    <div class="input-border"><input class="input-field" type="text" name="promo" id="ask-promo"
                                                     placeholder="Телефон *"></div>
                </label><label class="input-label">
                    <div class="input-border"><textarea class="textarea-field" rows="3" oninput="this.className = ''"
                                                        name="message"></textarea></div>
                </label></div>
            <div class="form-footer"><label class="type-checkbox"><input class="input-field" type="checkbox"
                                                                         oninput="this.className = ''" name="agreement"
                                                                         value="Соглашение" required><span
                            class="input-text">Я согласен на обработку &nbsp;<a
                                href="https://alfasklad.ru/include/licenses_detail.php"
                                target="_blank">персональных данных</a></span></label>
                <button class="btn btn-primary" type="submit" id="sendMessage" name="sendMessage">Отправить</button>
            </div>
        </form>
    </div>
    <div class="modalReview" id="modalReview" style="display:none">
        <form class="webform" action="" method="POST">
            <div class="form-header">
                <div class="text">
                    <div class="title">Задать вопрос</div>
                    <div class="description">Наши менеджеры с вами свяжутся, чтобы ответить на ваш вопрос</div>
                </div>
            </div>
            <div class="form-body"><label class="input-label">
                    <div class="input-border"><input class="input-field" type="text" name="name" id="reviewName"
                                                     placeholder="Ваше имя *" required></div>
                </label><label class="input-label">
                    <div class="input-border"><input class="input-field" type="text" name="company" id="company"
                                                     placeholder="Компания"></div>
                </label>
                <div class="add-input-wrapper">
                    <div class="type-file">
                        <div class="link-input innerInputs" id="innerInput-1">
                            <div class="field__wrapper"><input class="input-field input-files" type="file"
                                                               name="innerInputs[1]"><label class="field__file-wrapper">
                                    <div class="field__file-fake">Файл не выбран</div>
                                    <div class="field__file-button">Выбрать</div>
                                </label></div>
                            <button class="btn remove-element" type="button" onclick="remove(this)"
                                    data-target="innerInput" data-id="1"></button>
                        </div>
                    </div>
                    <button class="add-button" type="button" onclick="addGroup(this)" data-target="innerInput">ещё один
                        фаил
                    </button>
                </div>
                <label class="type-select">
                    <div class="input-border"><select class="form-select" id="stockSelect">
                            <option selected="selected" data-id="1" value="Склад хранения вещей на 1-м Верхнем переулке">
                                Склад хранения вещей на 1-м Верхнем переулке
                            </option>
                            <option data-id="2" value="Склад на ул. Наташи Ковшовой">Склад на ул. Наташи Ковшовой</option>
                            <option data-id="3" value="Склад на шоссе Энтузиастов/МКАД">Склад на шоссе Энтузиастов/МКАД
                            </option>
                            <option data-id="4" value="Склад на Звенигородском шоссе">Склад на Звенигородском шоссе</option>
                            <option data-id="5" value="Склад на Нагатинской">Склад на Нагатинской</option>
                            <option data-id="6" value="Склад на Молодогвардейской">Склад на Молодогвардейской</option>
                            <option data-id="7" value="Склад на Верхнелихоборской">Склад на Верхнелихоборской</option>
                        </select></div>
                </label><label class="input-label">
                    <div class="input-border"><textarea class="textarea-field" rows="3" oninput="this.className = ''"
                                                        name="review" required></textarea></div>
                </label></div>
            <div class="form-footer">
                <div data-sid="RATING">
                    <div class="rating_wrap clearfix">
                        <div class="rating"><span class="star" data-current_width="20" data-rating_value="1"
                                                  data-message="Очень плохо"></span><span class="star"
                                                                                          data-current_width="40"
                                                                                          data-rating_value="2"
                                                                                          data-message="Плохо"></span><span
                                    class="star" data-current_width="60" data-rating_value="3"
                                    data-message="Нормально"></span><span class="star" data-current_width="80"
                                                                          data-rating_value="4"
                                                                          data-message="Хорошо"></span><span class="star"
                                                                                                             data-current_width="100"
                                                                                                             data-rating_value="5"
                                                                                                             data-message="Отлично"></span><span
                                    class="stars_current" data-rating="0"></span></div>
                        <div class="rating_message" data-message="Без оценки">Без оценки</div>
                    </div>
                </div>
                <label class="type-checkbox"><input class="input-field" type="checkbox" oninput="this.className = ''"
                                                    name="agreement" value="Соглашение" required><span
                            class="input-text">Я согласен на &nbsp;<a
                                href="https://alfasklad.ru/include/licenses_detail.php" target="_blank">обработку персональных данных</a></span></label>
                <button class="btn btn-primary" type="submit" id="btnReview" name="btnReview">Отправить</button>
            </div>
        </form>
    </div>
    <div class="modalOurContacts" id="modalOurContacts" style="display:none">
        <div id="eGoogleMap" style="width: 100%; height: 100vh;"></div>
    </div>
</div>
<script src="<?=SITE_TEMPLATE_PATH?>/js/select2.min.js"></script>
<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;amp;apikey=0cd90886-624e-4dfa-9109-1c251a07e0f5" type="text/javascript"></script>
<script src="<?=SITE_TEMPLATE_PATH?>/js/slick.min.js"></script>
<script src="<?=SITE_TEMPLATE_PATH?>/js/jquery.fancybox.min.js"></script>
<script src="<?=SITE_TEMPLATE_PATH?>/js/rSlider.min.js"></script>
<script src="<?=SITE_TEMPLATE_PATH?>/js/jquery.maskedinput.min.js"></script>
<script src="<?=SITE_TEMPLATE_PATH?>/js/yandexmap.min.js"></script>
<script src="<?=SITE_TEMPLATE_PATH?>/js/main.js"></script>
</body>
</html>