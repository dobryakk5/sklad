<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>

<? if (!empty($arResult["DATA"])) { ?>
    <div class="rating_small_block" itemscope itemtype="http://schema.org/Review">

        <div class="row">
            <div class="col-md-12">
                <div class="review_block">
                    <div class="head">
                        <div class="name"
                             itemprop="author"><?= $arResult["DATA"]["REVIEW"]["PROPERTY_NAME_VALUE"] ?></div>
                    </div>
                    <div class="rating-block">
                        <?
                        $ratingValue = ($arResult["DATA"]["REVIEW"]["PROPERTY_RATING_VALUE"] ? $arResult["DATA"]["REVIEW"]["PROPERTY_RATING_VALUE"] : 0);
                        ?>
                        <div>

                        </div>

                        <div class="rating_wrap clearfix" itemprop="reviewRating" itemscope
                             itemtype="http://schema.org/Rating">
                            <div class="rating current_<?= $ratingValue ?>">
                                <meta itemprop="worstRating" content="0">
                                <span style="display:none;" itemprop="ratingValue"><?= $ratingValue ?></span>
                                <span style="display:none;" itemprop="bestRating">5</span>
                            </div>
                        </div>
                    </div>
                    <div itemprop="reviewBody">
                        <?= $arResult["DATA"]["REVIEW"]["~PROPERTY_MESSAGE_VALUE"]["TEXT"] ?>
                    </div>
                    <div class="date" itemprop="datePublished"
                         datetime="<?= $arResult["DATA"]["REVIEW"]["ACTIVE_FROM"] ?>">
                        <?= $arResult["DATA"]["REVIEW"]["ACTIVE_FROM"] ?>
                    </div>
                </div>
            </div>


            <div style="display:none;" itemprop="itemReviewed" itemscope="" itemtype="https://schema.org/Organization">
                <div itemprop="name">АльфаСклад</div>
                <link itemprop="url" href="https://alfasklad.ru/">
                <span itemprop="telephone" href="tel:+7(495)292-45-23"></span>
                <div itemprop="address" itemscope="" itemtype="https://schema.org/PostalAddress">
                    <span itemprop="addressCountry">Россия</span>,
                    <span itemprop="addressLocality">Москва</span>,
                    <span itemprop="streetAddress">ул. Верхнелихоборская, д. 8А</span>
                </div>
            </div>


            <div class="col-md-12">
                <div class="buttons">
                    <?
                    $link = "/rental_catalog/";
                    if (strlen($arResult["DATA"]["CODE"]) > 0) {
                        $link = "/rental_catalog/" . $arResult["DATA"]["CODE"] . "/";
                    }
                    ?>
                    <a class="btn btn-default btn-xs btn-transparent" itemprop="url" href="<?= $link ?>">Все отзывы</a>
                    <span class="btn btn-default btn-xs btn-transparent" data-event="jqm" data-param-id="30"
                          data-name="add_review">Оставить отзыв</span>
                </div>
            </div>
        </div>
    </div>
<? } ?>