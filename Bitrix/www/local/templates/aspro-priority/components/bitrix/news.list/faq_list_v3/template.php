<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
?>

<section class="faq" itemscope itemtype="https://schema.org/FAQPage">
    <div class="card">
        <div class="card__inner">
            <div class="card__header">
                <h2 class="card__title">
                    Вопросы и <span class="text_accent">ответы</span>
                </h2>
            </div>

            <div class="faq__accordions">
                <?php foreach ($arResult['ITEMS'] as $k => $arItem): ?>
                    <div class="accordion" itemprop="mainEntity" itemscope itemtype="https://schema.org/Question">
                        <div class="accordion__action text_m">
                            <span class="text_accent"><?= $k + 1 ?>.</span>
                            <h3 itemprop="name" class="text_m"><?= $arItem['NAME'] ?></h3>
                            <img class="accordion__arrow" src="/images/icons/arrow-b.svg" alt="arrow-b">
                        </div>

                        <div class="accordion__dropdown" itemprop="acceptedAnswer" itemscope="" itemtype="http://schema.org/Answer">
                            <div itemprop="text" class="text-parted_m text_m">
                                <?= $arItem['PREVIEW_TEXT'] ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>