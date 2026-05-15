<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
<?php if ($arResult['links']): ?>

    <?php
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/swiper-bundle.min.js');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/css/swiper-bundle.min.css", true);
    ?>

    <section class="section-links-slider">
        <div class="maxwidth-theme">
            <h2 class="style-h3">Вам может быть интересно:</h2>
            <div class="wrapper-links-slider">
                <button class="swiper-button-prev">
                    <svg width="8" height="13" viewBox="0 0 8 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.22412 2.2455L2.86414 6.5555L7.19409 10.8335C7.36467 11.0238 7.456 11.2721 7.44922 11.5276C7.44243 11.783 7.33803 12.0262 7.15759 12.2071C6.97716 12.3881 6.73431 12.4931 6.47888 12.5006C6.22345 12.5081 5.97481 12.4175 5.78406 12.2475L0.734131 7.2625C0.54666 7.07497 0.441284 6.82066 0.441284 6.5555C0.441284 6.29033 0.54666 6.03602 0.734131 5.8485L5.81409 0.831499C5.90489 0.730212 6.01545 0.648509 6.13892 0.591402C6.26238 0.534295 6.39612 0.502988 6.5321 0.499376C6.66809 0.495764 6.80337 0.519933 6.92969 0.570406C7.05601 0.620879 7.17079 0.696585 7.26685 0.792909C7.3629 0.889233 7.43829 1.00415 7.4884 1.13062C7.53852 1.25708 7.56222 1.39243 7.55823 1.5284C7.55423 1.66437 7.52254 1.7981 7.46509 1.9214C7.40763 2.0447 7.32566 2.15498 7.22412 2.2455Z" fill="#333333" />
                    </svg>
                </button>
                <div class="swiper links-slider">

                    <div class="swiper-wrapper">
                        <?php foreach ($arResult['links'] as $l): ?>
                            <?php if ($l['url'] && $l['anchor']): ?>
                                <div class="swiper-slide">
                                    <a class='swiper-slide-link' href="<?= $l['url'] ?>"><?= $l['anchor'] ?></a>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <button class="swiper-button-next">
                    <svg width="8" height="13" viewBox="0 0 8 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.25092 7.18585L2.20087 12.1709C2.11007 12.2722 1.99963 12.3538 1.87616 12.4109C1.7527 12.4681 1.61884 12.4994 1.48285 12.503C1.34687 12.5066 1.21159 12.4824 1.08527 12.4319C0.95895 12.3815 0.844286 12.3058 0.748234 12.2094C0.652182 12.1131 0.576791 11.9982 0.526676 11.8717C0.476561 11.7453 0.452733 11.6099 0.45673 11.474C0.460727 11.338 0.492413 11.2043 0.54987 11.081C0.607326 10.9577 0.689415 10.8474 0.790958 10.7569L5.12091 6.47885L0.760929 2.16986C0.588224 1.97994 0.495181 1.73088 0.501042 1.47424C0.506902 1.2176 0.611229 0.973048 0.792423 0.791211C0.973618 0.609374 1.21782 0.504187 1.47443 0.497418C1.73104 0.49065 1.98044 0.582817 2.17096 0.754849L7.25092 5.77185C7.43839 5.95938 7.54377 6.21368 7.54377 6.47885C7.54377 6.74401 7.43839 6.99832 7.25092 7.18585Z" fill="#333333" />
                    </svg>
                </button>
            </div>
        </div>
    </section>

    <script>
        if (document.querySelectorAll('.links-slider').length > 0) {
            const swiper = new Swiper('.links-slider', {
                slidesPerView: 'auto',
                spaceBetween: 15,
                pagination: {
                    el: '.swiper-pagination',
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });
        }
    </script>
<?php endif; ?>