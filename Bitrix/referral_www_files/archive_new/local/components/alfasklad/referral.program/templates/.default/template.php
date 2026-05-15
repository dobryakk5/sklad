<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$refCode  = htmlspecialcharsbx($arResult["REF_CODE"]);
$refUrl   = htmlspecialcharsbx($arResult["REFERRAL_URL"]);
$status   = (string) ($arResult["STATUS"] ?? "PENDING");
$isActive = $status === "ACTIVE";

// Статистика из HL-блока
$referralCount  = (int)   ($arResult["REFERRAL_COUNT"] ?? 0);
$bonusAmount    = (float) ($arResult["BONUS_AMOUNT"]   ?? 0);
$friendsActive  = $arResult["FRIENDS_ACTIVE"];  // null пока не добавлено в HL
$bonusPlanned   = $arResult["BONUS_PLANNED"];   // null пока не добавлено в HL
?>

<div class="lk-referral-page">

    <h1 class="lk-referral-page-title">Реферальная программа</h1>

    <section class="lk-referral-hero">
        <div class="lk-referral-hero-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24">
                <path d="M4 5.5C4 4.67 4.67 4 5.5 4H10c1.1 0 2 .9 2 2v14c0-1.1-.9-2-2-2H5.5C4.67 18 4 17.33 4 16.5v-11Z"/>
                <path d="M20 5.5C20 4.67 19.33 4 18.5 4H14c-1.1 0-2 .9-2 2v14c0-1.1.9-2 2-2h4.5c.83 0 1.5-.67 1.5-1.5v-11Z"/>
            </svg>
        </div>

        <div class="lk-referral-hero-text">
            <h2>Описание программы</h2>
            <p>Узнайте, как работает реферальная программа, размеры бонусов и условия участия.</p>
        </div>

        <a href="/referral/" class="btn btn-default btn-lg lk-referral-hero-btn">Описание</a>
    </section>

    <?php if (!$isActive): ?>
    <!-- ── Статус: код ещё не присвоен ── -->
    <section class="lk-referral-card lk-referral-pending-card">
        <div class="lk-referral-pending-inner">
            <span class="lk-referral-pending-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M12 8v4"/><circle cx="12" cy="16" r=".5" fill="currentColor"/>
                </svg>
            </span>
            <div>
                <h2>Реферальный код не присвоен</h2>
                <p>Для создания кода обратитесь к менеджеру.</p>
            </div>
        </div>
    </section>

    <?php else: ?>
    <!-- ── Промокод ── -->
    <section class="lk-referral-card lk-referral-code-card">
        <h2>Ваш персональный промокод</h2>

        <div class="lk-referral-code-layout">
            <button
                class="lk-referral-code-box"
                type="button"
                data-copy="<?= $refUrl ?>"
                title="Скопировать ссылку"
            >
                <span class="lk-referral-tag-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24">
                        <path d="M20.6 13.2 13.2 20.6a2 2 0 0 1-2.8 0l-7-7A2 2 0 0 1 2.8 12.2L4 5.5A2 2 0 0 1 5.5 4l6.7-1.2a2 2 0 0 1 1.4.6l7 7a2 2 0 0 1 0 2.8Z"/>
                        <circle cx="8.5" cy="8.5" r="1.4"/>
                    </svg>
                </span>

                <span class="lk-referral-code-value"><?= $refCode ?></span>
            </button>

            <div class="lk-referral-code-description">
                Передайте этот промокод другу —<br>
                он получит скидку <?= htmlspecialcharsbx($arResult["DISCOUNT_TEXT"]) ?><br>
                <?= htmlspecialcharsbx($arResult["DISCOUNT_PERIOD"]) ?>,<br>
                а вы бонус за каждого клиента.
            </div>
        </div>

        <div class="lk-referral-copy-note" data-copy-note hidden>
            Ссылка скопирована
        </div>
    </section>

    <!-- ── Wallet ── -->
    <section class="lk-referral-card lk-referral-wallet-card">
        <div class="lk-referral-wallet-content">
            <div class="lk-referral-wallet-text">
                <h2>Сохраните промокод в Wallet</h2>
                <p>Добавьте карту в Apple Wallet или Google Wallet и делитесь ей в один клик.</p>

                <div class="lk-referral-wallet-buttons">
                    <a href="#" class="lk-referral-wallet-btn" onclick="return false;">
                        <span class="lk-referral-wallet-icon"></span>
                        <span>
                            <small>Добавить в</small>
                            Apple Wallet
                        </span>
                    </a>

                    <a href="#" class="lk-referral-wallet-btn" onclick="return false;">
                        <span class="lk-referral-wallet-icon lk-referral-wallet-icon-google"></span>
                        <span>
                            <small>Добавить в</small>
                            Google Wallet
                        </span>
                    </a>
                </div>
            </div>

            <div class="lk-referral-wallet-preview">
                <div class="lk-referral-wallet-pass">
                    <div class="lk-referral-wallet-logo">
                        <span class="lk-referral-wallet-logo-mark">▲</span>
                        <span>
                            АЛЬФАСКЛАД
                            <small>индивидуальное хранение</small>
                        </span>
                    </div>

                    <div class="lk-referral-wallet-pass-center">
                        <small>ПРОМОКОД</small>
                        <strong><?= $refCode ?></strong>
                    </div>

                    <div class="lk-referral-wallet-pass-footer">
                        Покажите этот код<br>
                        при заключении договора
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ── Статистика ── -->
    <section class="lk-referral-card lk-referral-stats-card">
        <h2>Ваша статистика</h2>

        <div class="lk-referral-stats-layout">
            <div class="lk-referral-stats-list">

                <div class="lk-referral-stat-item">
                    <span class="lk-referral-stat-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="M7.5 12a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                            <path d="M16.5 12a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                            <path d="M2.5 20c.5-3.4 2.4-5.1 5-5.1s4.5 1.7 5 5.1"/>
                            <path d="M11.5 20c.5-3.4 2.4-5.1 5-5.1s4.5 1.7 5 5.1"/>
                        </svg>
                    </span>
                    <strong><?= $referralCount ?></strong>
                    <span>друзей<br>привлечено</span>
                </div>

                <div class="lk-referral-stat-item">
                    <span class="lk-referral-stat-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"/>
                            <path d="M4.5 21c.7-4 3.3-6 7.5-6s6.8 2 7.5 6"/>
                        </svg>
                    </span>
                    <strong><?= $friendsActive !== null ? (int)$friendsActive : '—' ?></strong>
                    <span>арендуют</span>
                </div>

                <div class="lk-referral-stat-item">
                    <span class="lk-referral-stat-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="M9 9h4.2a2.8 2.8 0 1 1 0 5.6H9"/>
                            <path d="M9 14.6h6"/>
                            <path d="M9 17h3"/>
                            <path d="M9 7v12"/>
                        </svg>
                    </span>
                    <strong><?= number_format($bonusAmount, 0, '.', ' ') ?> ₽</strong>
                    <span>выплачено</span>
                </div>

                <div class="lk-referral-stat-item">
                    <span class="lk-referral-stat-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <rect x="4" y="5.5" width="16" height="14" rx="2"/>
                            <path d="M8 3.5v4"/>
                            <path d="M16 3.5v4"/>
                            <path d="M4 10h16"/>
                        </svg>
                    </span>
                    <strong><?= $bonusPlanned !== null ? number_format((float)$bonusPlanned, 0, '.', ' ') . ' ₽' : '—' ?></strong>
                    <span>будет выплачено<br>в ближайший год</span>
                </div>

            </div>

            <aside class="lk-referral-stats-note">
                <div class="lk-referral-infinity" aria-hidden="true">
                    <svg viewBox="0 0 40 24">
                        <path d="M11 6c4.5 0 7.3 12 12 12 3.3 0 6-2.7 6-6s-2.7-6-6-6c-4.7 0-7.5 12-12 12-3.3 0-6-2.7-6-6s2.7-6 6-6Z"/>
                    </svg>
                </div>

                <p>Бонус выплачивается сразу после подписания договора и далее ежегодно, пока действует ваш друг.</p>
                <strong>Чем дольше он хранит —<br>тем больше вы зарабатываете!</strong>
            </aside>
        </div>
    </section>

    <?php endif; ?>

</div>
