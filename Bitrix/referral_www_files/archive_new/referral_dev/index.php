<?php
$referralCssVersion = filemtime($_SERVER["DOCUMENT_ROOT"] . "/referral/style.css");
$referralJsVersion = file_exists($_SERVER["DOCUMENT_ROOT"] . "/referral/script.js")
    ? filemtime($_SERVER["DOCUMENT_ROOT"] . "/referral/script.js")
    : time();

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetPageProperty("title", "Реферальная программа АльфаСклад");
$APPLICATION->SetPageProperty("description", "Приведите друга в АльфаСклад и получите бонус за каждого нового клиента.");
?>

<link rel="stylesheet" href="/referral/style.css?v=<?= (int)$referralCssVersion ?>">
<script src="/referral/script.js?v=<?= (int)$referralJsVersion ?>"></script>

<div class="referral-page">

    <section class="referral-hero">
        <div class="referral-hero-inner">

            <div class="referral-hero-content">
                <h1>
                    Приведи друга —
                    <span>получи до 5000 ₽</span>
                    за каждого
                </h1>

                <p class="referral-hero-text">
                    Делитесь промокодом с друзьями и получайте бонус за каждого нового клиента на ваш «баланс»
                </p>

                <div class="referral-hero-benefits">
                    <div class="referral-hero-benefit">
                        <span class="referral-icon">
                            <svg viewBox="0 0 24 24">
                                <circle cx="12" cy="8" r="3.5"/>
                                <path d="M4.5 21c.7-4.2 3.2-6.3 7.5-6.3S18.8 16.8 19.5 21"/>
                            </svg>
                        </span>
                        <div>
                            <strong>Новому клиенту</strong>
                            <b>-33%</b>
                            <span>на первые<br>3 месяца</span>
                        </div>
                    </div>

                    <div class="referral-hero-benefit">
                        <span class="referral-icon">
                            <svg viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="9"/>
                                <path d="M9 8.5h4.2a2.8 2.8 0 1 1 0 5.6H9"/>
                                <path d="M9 14.1h6"/>
                                <path d="M9 17h3"/>
                                <path d="M9 6.5v12"/>
                            </svg>
                        </span>
                        <div>
                            <strong>Вам — за каждого друга</strong>
                            <b>до 5 000 ₽</b>
                        </div>
                    </div>

                    <div class="referral-hero-benefit">
                        <span class="referral-icon">
                            <svg viewBox="0 0 40 24">
                                <path d="M11 6c4.5 0 7.3 12 12 12 3.3 0 6-2.7 6-6s-2.7-6-6-6c-4.7 0-7.5 12-12 12-3.3 0-6-2.7-6-6s2.7-6 6-6Z"/>
                            </svg>
                        </span>
                        <div>
                            <strong>Бонус выплачивается</strong>
                            <span>сразу и далее ежегодно,<br>пока арендует ваш друг</span>
                        </div>
                    </div>
                </div>

                <a href="/cabinet/referral/" class="btn btn-default btn-lg referral-main-btn">
                    Участвовать в программе
                </a>
            </div>

            <div class="referral-hero-visual" aria-hidden="true">
                <svg class="referral-lightning-svg" viewBox="0 0 170 455" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <polygon points="68,0 170,0 119,178 158,178 47,455 80,237 23,237" fill="#f30612"/>
                </svg>

                <div class="referral-photo-card">
                    <img
                        src="/upload/referral/referral-hero.jpeg"
                        alt=""
                        class="referral-hero-image"
                        loading="eager"
                    >
                </div>
            </div>

        </div>
    </section>

    <section class="referral-section">
        <h2>Ваш бонус за каждого друга</h2>

        <div class="referral-bonus-grid">
            <div class="referral-bonus-card">
                <span class="referral-card-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M4 8.5 12 4l8 4.5-8 4.5L4 8.5Z"/>
                        <path d="M4 8.5V16l8 4 8-4V8.5"/>
                        <path d="M12 13v7"/>
                    </svg>
                </span>
                <div>
                    <strong>Мини-Кладовка</strong>
                    <span>ячейки и боксы до 2 м²</span>
                    <b>1 000 ₽</b>
                </div>
            </div>

            <div class="referral-bonus-card">
                <span class="referral-card-icon">
                    <svg viewBox="0 0 24 24">
                        <rect x="6" y="3" width="12" height="18" rx="1.5"/>
                        <path d="M9 7h6"/>
                        <path d="M9 10h6"/>
                        <path d="M9 13h4"/>
                    </svg>
                </span>
                <div>
                    <strong>Кладовка</strong>
                    <span>2,5 – 5,5 м²</span>
                    <b>2 000 ₽</b>
                </div>
            </div>

            <div class="referral-bonus-card">
                <span class="referral-card-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M3 7h11v9H3z"/>
                        <path d="M14 10h4l3 3v3h-7z"/>
                        <circle cx="7" cy="18" r="2"/>
                        <circle cx="17" cy="18" r="2"/>
                    </svg>
                </span>
                <div>
                    <strong>Переезд</strong>
                    <span>6 – 9,5 м²</span>
                    <b>3 000 ₽</b>
                </div>
            </div>

            <div class="referral-bonus-card">
                <span class="referral-card-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M3 11 12 4l9 7"/>
                        <path d="M5 10.5V20h14v-9.5"/>
                        <path d="M9 20v-6h6v6"/>
                    </svg>
                </span>
                <div>
                    <strong>Бизнес</strong>
                    <span>от 10 м²</span>
                    <b>5 000 ₽</b>
                </div>
            </div>
        </div>

        <div class="referral-info-note">
            <span>i</span>
            <p>
                Бонус выплачивается сразу после подписания договора и далее ежегодно,
                пока действует ваш друг. <strong>Сумма зачисляется на ваш “баланс”.</strong>
                Чем дольше он хранит — тем больше вы зарабатываете.
            </p>
        </div>
    </section>

    <section class="referral-section">
        <h2>Как это работает</h2>

        <div class="referral-steps">
            <div class="referral-step">
                <div class="referral-step-number">1</div>
                <span class="referral-step-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M20 12v8H4v-8"/>
                        <path d="M2 7h20v5H2z"/>
                        <path d="M12 7v13"/>
                        <path d="M12 7H8.5a2.5 2.5 0 1 1 2.5-2.5C11 6 12 7 12 7Z"/>
                        <path d="M12 7h3.5A2.5 2.5 0 1 0 13 4.5C13 6 12 7 12 7Z"/>
                    </svg>
                </span>
                <div>
                    <strong>Получите промокод</strong>
                    <p>Он уже доступен в вашем личном кабинете.</p>
                </div>
            </div>

            <div class="referral-step-arrow">→</div>

            <div class="referral-step">
                <div class="referral-step-number">2</div>
                <span class="referral-step-icon">
                    <svg viewBox="0 0 24 24">
                        <circle cx="12" cy="8" r="3"/>
                        <path d="M6 20c.5-3.5 2.5-5.2 6-5.2s5.5 1.7 6 5.2"/>
                        <path d="M18 8h3"/>
                        <path d="M19.5 6.5v3"/>
                    </svg>
                </span>
                <div>
                    <strong>Поделитесь с друзьями</strong>
                    <p>Отправьте промокод или ссылку удобным способом.</p>
                </div>
            </div>

            <div class="referral-step-arrow">→</div>

            <div class="referral-step">
                <div class="referral-step-number">3</div>
                <span class="referral-step-icon">
                    <svg viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M9 8.5h4.2a2.8 2.8 0 1 1 0 5.6H9"/>
                        <path d="M9 14.1h6"/>
                        <path d="M9 17h3"/>
                        <path d="M9 6.5v12"/>
                    </svg>
                </span>
                <div>
                    <strong>Получите бонус</strong>
                    <p>Мы начислим бонус после подписания договора и далее ежегодно.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="referral-section">
        <h2>Где делиться промокодом</h2>

        <div class="referral-share-grid">
            <div class="referral-share-card">
                <span class="referral-card-icon">
                    <svg viewBox="0 0 24 24">
                        <circle cx="12" cy="8" r="3.5"/>
                        <path d="M4.5 21c.7-4.2 3.2-6.3 7.5-6.3S18.8 16.8 19.5 21"/>
                    </svg>
                </span>
                <strong>Личные контакты</strong>
                <p>друзья, коллеги, знакомые</p>
            </div>

            <div class="referral-share-card">
                <span class="referral-card-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M3 11 12 4l9 7"/>
                        <path d="M5 10.5V20h14v-9.5"/>
                    </svg>
                </span>
                <strong>Домовые чаты и чаты ЖК</strong>
                <p>районные чаты</p>
            </div>

            <div class="referral-share-card">
                <span class="referral-card-icon">
                    <svg viewBox="0 0 24 24">
                        <rect x="5" y="7" width="14" height="12" rx="2"/>
                        <path d="M9 7V5h6v2"/>
                    </svg>
                </span>
                <strong>Рабочие чаты</strong>
                <p>коллеги и партнёры</p>
            </div>

            <div class="referral-share-card">
                <span class="referral-card-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M5 8h14v12H5z"/>
                        <path d="M7 8V5h10v3"/>
                        <path d="M8 12h8"/>
                    </svg>
                </span>
                <strong>Чаты предпринимателей</strong>
                <p>маркетплейсы и бизнес-чаты</p>
            </div>

            <div class="referral-share-card">
                <span class="referral-card-icon">
                    <svg viewBox="0 0 24 24">
                        <rect x="7" y="3" width="10" height="18" rx="2"/>
                        <path d="M10 18h4"/>
                    </svg>
                </span>
                <strong>Соцсети</strong>
                <p>посты, сторис, личные сообщения</p>
            </div>

            <div class="referral-share-card">
                <span class="referral-card-icon">
                    <svg viewBox="0 0 24 24">
                        <circle cx="12" cy="8" r="3.5"/>
                        <path d="M4.5 21c.7-4.2 3.2-6.3 7.5-6.3S18.8 16.8 19.5 21"/>
                    </svg>
                </span>
                <strong>Сообщества по интересам</strong>
                <p>тематические группы и форумы</p>
            </div>
        </div>
    </section>

    <section class="referral-section referral-faq-section">
        <h2>Ответы на вопросы</h2>

        <div class="referral-faq">
            <button class="referral-faq-item" type="button">
                <span>Как и когда выплачивается бонус?</span>
                <p>Бонус начисляется сразу после подписания договора и далее ежегодно, пока арендует ваш друг.</p>
                <i></i>
            </button>

            <button class="referral-faq-item" type="button">
                <span>Сколько можно заработать?</span>
                <p>Сумма бонуса зависит от типа аренды вашего друга. Чем больше друзей и чем дольше они хранят — тем больше вы зарабатываете.</p>
                <i></i>
            </button>

            <button class="referral-faq-item" type="button">
                <span>Есть ли ограничения?</span>
                <p>Нет, вы можете приглашать неограниченное количество друзей.</p>
                <i></i>
            </button>
        </div>
    </section>

</div>

<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
