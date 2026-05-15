<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
?>

<section class="types-premise">
    <div class="card">
        <div class="card__inner">
            <div class="card__header">
                <h2 class="card__title">
                    <?php if ($arParams['HEADER']): ?>
                        <?= htmlspecialchars_decode($arParams['HEADER']); ?>
                    <?php else: ?>
                        <span class="text_accent">Типы</span> помещений
                    <?php endif; ?>
                </h2>

                <p class="card__subtitle text_l">
                    На наших складах доступны несколько типов помещений для аренды:
                </p>
            </div>

            <div class="types-premise__items">
                <?php foreach ($arResult['ITEMS'] as $k => $arItem): /*pre($arItem);*/ ?>
                    <div class="types-premise__item">
                        <p class="types-premise__item-title text_l text_accent">
                            <?= $arItem['NAME'] ?><?php if ($arItem['PROPERTIES']['CAPTION_POPUP']['VALUE']): ?>&nbsp;<span class="types-premise__item-title__tooltip tooltip-new-ui text_grey-light" data-tooltip-content="<?= $arItem['PROPERTIES']['CAPTION_POPUP']['VALUE'] ?>">ⓘ</span><?php endif; ?>
                        </p>

                        <div class="types-premise__item-characteristics">
                            <?php if ($arItem['PROPERTIES']['SQUARE_FROM']['VALUE'] || $arItem['PROPERTIES']['SQUARE_TO']['VALUE'] || $arItem['PROPERTIES']['SQUARE_ENUM']['VALUE']): ?>
                                <p class="text_m">
                                    Площадь
                                    <?php if ($arItem['PROPERTIES']['SQUARE_FROM']['VALUE'] && !$arItem['PROPERTIES']['SQUARE_TO']['VALUE']): ?>
                                        <span class="text_accent value-in-square"><?= $arItem['PROPERTIES']['SQUARE_FROM']['VALUE'] ?> м</span>
                                    <?php elseif (!$arItem['PROPERTIES']['SQUARE_FROM']['VALUE'] && $arItem['PROPERTIES']['SQUARE_TO']['VALUE']): ?>
                                        <span class="text_accent value-in-square"><?= $arItem['PROPERTIES']['SQUARE_TO']['VALUE'] ?> м</span>
                                    <?php elseif ($arItem['PROPERTIES']['SQUARE_FROM']['VALUE'] && $arItem['PROPERTIES']['SQUARE_TO']['VALUE']): ?>
                                        от <span class="text_accent"><?= $arItem['PROPERTIES']['SQUARE_FROM']['VALUE'] ?></span> до <span class="text_accent value-in-square"><?= $arItem['PROPERTIES']['SQUARE_TO']['VALUE'] ?>&nbsp;м</span>
                                    <?php elseif ($arItem['PROPERTIES']['SQUARE_ENUM']['VALUE']): ?>
                                        <?php foreach ($arItem['PROPERTIES']['SQUARE_ENUM']['VALUE'] as $k => $s): ?>
                                            <?php if ($k < count($arItem['PROPERTIES']['SQUARE_ENUM']['VALUE']) - 1): ?><span class="text_accent"><?= $s ?></span><?php endif; ?><?php if (count($arItem['PROPERTIES']['SQUARE_ENUM']['VALUE']) > 2 && $k < count($arItem['PROPERTIES']['SQUARE_ENUM']['VALUE']) - 2): ?>,<?php endif; ?>
                                                <?php if ($k == count($arItem['PROPERTIES']['SQUARE_ENUM']['VALUE']) - 1): ?>
                                                    и <span class="text_accent value-in-square"><?= $s ?> м</span>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                </p>
                            <?php endif; ?>

                            <?php if ($arItem['PROPERTIES']['HEIGHT_FROM']['VALUE'] || $arItem['PROPERTIES']['HEIGHT_TO']['VALUE']): ?>
                                <p class="text_m">
                                    Высота
                                    <?php if ($arItem['PROPERTIES']['HEIGHT_FROM']['VALUE'] && !$arItem['PROPERTIES']['HEIGHT_TO']['VALUE']): ?>
                                        <span class="text_accent"><?= $arItem['PROPERTIES']['HEIGHT_FROM']['VALUE'] ?> м</span>
                                    <?php elseif (!$arItem['PROPERTIES']['HEIGHT_FROM']['VALUE'] && $arItem['PROPERTIES']['HEIGHT_TO']['VALUE']): ?>
                                        <span class="text_accent"><?= $arItem['PROPERTIES']['HEIGHT_TO']['VALUE'] ?> м</span>
                                    <?php elseif ($arItem['PROPERTIES']['HEIGHT_FROM']['VALUE'] && $arItem['PROPERTIES']['HEIGHT_TO']['VALUE']): ?>
                                        от <span class="text_accent"><?= $arItem['PROPERTIES']['HEIGHT_FROM']['VALUE'] ?></span> до <span class="text_accent"><?= $arItem['PROPERTIES']['HEIGHT_TO']['VALUE'] ?>&nbsp;м</span>
                                    <?php endif; ?>
                                </p>
                            <?php endif; ?>

                            <?php if ($arItem['PROPERTIES']['STANDART']['VALUE']): ?>
                                <p class="text_m">
                                    Стандартный объем <span class="text_accent value-in-cube"><?= $arItem['PROPERTIES']['STANDART']['VALUE'] ?> м</span>
                                </p>
                            <?php endif; ?>
                        </div>

                        <div class="swiper types-premise__swiper">
                            <div class="swiper-wrapper">
                                <?php if (strlen($arItem["PREVIEW_PICTURE"]["RESIZE"]["src"]) > 0): ?>
                                    <div class="swiper-slide types-premise__slide">
                                        <img class="types-premise__image" width="297" height="297" src="<?= $arItem["PREVIEW_PICTURE"]["RESIZE"]["src"] ?>" alt="cell">
                                    </div>
                                <?php endif; ?>
                                <?php if (strlen($arItem["DETAIL_PICTURE"]["RESIZE"]["src"]) > 0): ?>
                                    <div class="swiper-slide types-premise__slide">
                                        <img class="types-premise__image" width="297" height="297" src="<?= $arItem["DETAIL_PICTURE"]["RESIZE"]["src"] ?>" alt="cell">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="swiper-pagination swiper-pagination_custom swiper-pagination_types-premise"></div>

                        <?= $arItem['PREVIEW_TEXT'] ?>

                    </div>
                <?php endforeach; ?>

            </div>

            <p class="types-premise__hint text_m">
                <?php if ($arParams['FOOTER']): ?>
                    <?= htmlspecialchars_decode($arParams['FOOTER']); ?>
                <?php else: ?>
                    На некоторых складах Вы также можете арендовать <span class="text_accent">офисы</span>.
                <?php endif; ?>
            </p>
        </div>
    </div>
</section>