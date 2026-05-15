<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<? $this->setFrameMode(true); ?>
<?

use \Bitrix\Main\Localization\Loc; ?>


<? if ($arResult['ITEMS']): ?>
    <?
    $arParams['PREVIEW_TRUNCATE_LEN'] = 10000000000;
    $bRating = (in_array('RATING', $arParams['PROPERTY_CODE']) ? true : false);
    ?>

    <div class="item-views greyline reviews_items front_items reviews_scroll">
        <?
        $qntyItems = count($arResult['ITEMS']);

        global $arTheme;
        $slideshowSpeed = (isset($arTheme['PARTNERSBANNER_SLIDESSHOWSPEED']['VALUE']) && $arTheme['PARTNERSBANNER_SLIDESSHOWSPEED']['VALUE'] ? abs(intval($arTheme['PARTNERSBANNER_SLIDESSHOWSPEED']['VALUE'])) : abs(intval($arTheme['PARTNERSBANNER_SLIDESSHOWSPEED'])));
        $animationSpeed = (isset($arTheme['PARTNERSBANNER_ANIMATIONSPEED']['VALUE']) && $arTheme['PARTNERSBANNER_ANIMATIONSPEED']['VALUE'] ? abs(intval($arTheme['PARTNERSBANNER_ANIMATIONSPEED']['VALUE'])) : abs(intval($arTheme['PARTNERSBANNER_ANIMATIONSPEED'])));
        $bAnimation = (bool)$slideshowSpeed;
        ?>
        <? if ($arParams['PAGER_SHOW_ALL']): ?>
            <a class="show_all pull-right"
                href="<?= str_replace('#SITE' . '_DIR#', SITE_DIR, $arResult['LIST_PAGE_URL']) ?>"><span><?= (strlen($arParams['SHOW_ALL_TITLE']) ? $arParams['SHOW_ALL_TITLE'] : GetMessage('S_TO_SHOW_ALL_REVIEWS')) ?></span></a>
        <? endif; ?>
        <h2
            style="margin-left: 30px; margin-bottom: 50px;">Отзывы наших клиентов</h2>
        <div class="sss unstyled row navigation-vcenter dark-nav wsmooth rewslider"
            data-plugin-options='{"smoothHeight": true, "directionNav": true, "controlNav": false, "slideshow": false, <?= ($slideshowSpeed >= 0 ? '"slideshowSpeed": ' . $slideshowSpeed . ',' : '') ?> <?= ($animationSpeed >= 0 ? '"animationSpeed": ' . $animationSpeed . ',' : '') ?> "counts": [3, 2, 1]}'>
            <script>
                $('.sss').flexslider({
                    animation: 'slide',
                    controlNav: false,
                    animationLoop: true,
                    slideshow: false,
                    itemWidth: 250,
                    itemMargin: 35,
                    directionNav: true,
                    touch: true,
                    minItems: 1,
                    maxItems: 3,
                    customsizes: "Y"

                });
            </script>
            <ul class="slides items ">
                <? foreach ($arResult['ITEMS'] as $i => $arItem): ?>
                    <?
                    // edit/add/delete buttons for edit mode
                    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
                    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => Loc::getMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                    // use detail link?
                    $bDetailLink = $arParams['SHOW_DETAIL_LINK'] != 'N' && (!strlen($arItem['DETAIL_TEXT']) ? ($arParams['HIDE_LINK_WHEN_NO_DETAIL'] !== 'Y' && $arParams['HIDE_LINK_WHEN_NO_DETAIL'] != 1) : true);
                    $name = (isset($arItem['DISPLAY_PROPERTIES']['NAME']) && strlen($arItem['DISPLAY_PROPERTIES']['NAME']['VALUE']) ? $arItem['DISPLAY_PROPERTIES']['NAME']['VALUE'] : '');
                    $post = (isset($arItem['DISPLAY_PROPERTIES']['POST']) && strlen($arItem['DISPLAY_PROPERTIES']['POST']['VALUE']) ? $arItem['DISPLAY_PROPERTIES']['POST']['VALUE'] : '');
                    $review = (isset($arItem['DISPLAY_PROPERTIES']['MESSAGE']) && strlen($arItem['DISPLAY_PROPERTIES']['MESSAGE']['VALUE']['TEXT']) ? CPriority::truncateLengthText($arItem['DISPLAY_PROPERTIES']['MESSAGE']['~VALUE']['TEXT'], $arParams['PREVIEW_TRUNCATE_LEN']) : '');
                    $bLogo = false;

                    // preview image
                    $bImage = strlen($arItem['FIELDS']['PREVIEW_PICTURE']['SRC']);
                    $arImage = ($bImage ? CFile::ResizeImageGet($arItem['FIELDS']['PREVIEW_PICTURE']['ID'], array('width' => 80, 'height' => 10000), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true) : array());
                    $imageSrc = ($bImage ? $arImage['src'] : '');

                    if (!$imageSrc && strlen($arItem['FIELDS']['DETAIL_PICTURE']['SRC'])) {
                        $bImage = strlen($arItem['FIELDS']['DETAIL_PICTURE']['SRC']);
                        $arImage = ($bImage ? CFile::ResizeImageGet($arItem['FIELDS']['DETAIL_PICTURE']['ID'], array('width' => 90, 'height' => 10000), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true) : array());
                        $imageSrc = ($bImage ? $arImage['src'] : '');
                        $bLogo = ($imageSrc ? true : false);
                    }
                    ?>
                    <li class="col-md-12">
                        <div class="item   clearfix<?= ($bImage ? '' : ' wti') ?><?= ($bLogo ? ' wlogo' : '') ?>"
                            itemscope itemtype="http://schema.org/Review"
                            id="<?= $this->GetEditAreaId($arItem['ID']) ?>">
                            <link itemprop="url" href="https://alfasklad.ru/">
                            <div style="display:none;" itemprop="itemReviewed" itemscope
                                itemtype="https://schema.org/Organization">
                                <div itemprop="name">АльфаСклад</div>
                                <link itemprop="url" href="https://alfasklad.ru/">
                                <span itemprop="telephone" href="tel:+7(495)292-45-23"></span>
                                <div itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
                                    <span itemprop="addressCountry">RU</span>,
                                    <span itemprop="addressLocality">Москва</span>,
                                    <span itemprop="streetAddress">ул. Верхнелихоборская, д. 8А</span>
                                    <meta itemprop="postalCode" content="127106">
                                </div>
                            </div>

                            <div class="question clearfix" style="display:inline-block;">
                                <div class="right_block pull-right clearfix">
                                    <? if ($bImage && $arImage['src']): ?>
                                        <div class="image  <?= ($bImage ? '' : 'wpi') ?>">
                                            <img data-src="<?= $arImage['src'] ?>"
                                                alt="<?= ($arItem['PREVIEW_PICTURE']['ALT'] ? $arItem['PREVIEW_PICTURE']['ALT'] : $arItem['NAME']); ?>"
                                                title="<?= ($arItem['PREVIEW_PICTURE']['TITLE'] ? $arItem['PREVIEW_PICTURE']['TITLE'] : $arItem['NAME']); ?>"
                                                class="img-responsive  lazy" />
                                        </div>
                                    <? endif; ?>
                                    <div class="body-info">
                                        <? if ($post && false): ?>
                                            <div class="post font_upper"><?= $post ?></div>
                                        <? endif; ?>

                                        <div class="title-wrapper<?= ($bRating ? ' wrating' : '') ?> <?= ($bHasSocProps ? 'bottom-props' : ''); ?>">
                                            <? if ($name): ?>
                                                <div class="title" itemprop="author" itemscope itemtype="https://schema.org/Person"><?= $name ?>
                                                    <meta itemprop="name" content="<?= $name ?>">
                                                </div>
                                                <div class="reviews__item-logo">
                                                    <a href="https://yandex.ru/maps/org/alfasklad/176142151824/?ll=37.558831%2C55.853350&z=16" target="_blank" rel="nofollow">
                                                        <img width="103" height="22"
                                                            src="/local/templates/aspro-priority/images/yandex_maps.svg"
                                                            alt="">
                                                    </a>
                                                </div>
                                            <? endif ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="left_block">
                                    <div class="body-info">
                                        <div class="top-wrapper">
                                            <? if ($bRating): ?>
                                                <?
                                                $ratingValue = ($arItem['DISPLAY_PROPERTIES']['RATING']['VALUE'] ? $arItem['DISPLAY_PROPERTIES']['RATING']['VALUE'] : 0);
                                                ?>
                                                <div class="rating_wrap clearfix">
                                                    <div class="rating-total" itemprop="reviewRating" itemscope
                                                        itemtype="http://schema.org/Rating">
                                                        <meta itemprop="worstRating" content="0">
                                                        <span style="display:none;" itemprop="ratingValue">5</span>
                                                        <span style="display:none;" itemprop="bestRating">5</span>
                                                        <span class="rating-item"></span>
                                                        <span class="rating-item"></span>
                                                        <span class="rating-item"></span>
                                                        <span class="rating-item"></span>
                                                        <span class="rating-item"></span>

                                                    </div>
                                                    <!-- <div class="rating current_<? //=$ratingValue
                                                                                    ?>" title="<? //=GetMessage('RATING_MESSAGE_'.$ratingValue)
                                                                                                ?>">
															<span class="stars_current "></span>
														</div> -->
                                                </div>
                                            <? endif ?>
                                            <? /* if (isset($arItem['DISPLAY_ACTIVE_FROM']) && strlen($arItem['DISPLAY_ACTIVE_FROM'])): ?>
                                                <div itemprop="datePublished"
                                                     datetime="<?= $arItem['DISPLAY_ACTIVE_FROM']; ?>"
                                                     class="date font_xs"><?= $arItem['DISPLAY_ACTIVE_FROM']; ?></div>
                                            <? endif; */ ?>
                                        </div>
                                        <? if ($review): ?>
                                            <div class="text" itemprop="reviewBody"><?= $review ?></div>
                                        <? endif; ?>
                                        <? if (mb_strlen($arParams['PREVIEW_TRUNCATE_LEN']) && mb_strlen($arItem['DISPLAY_PROPERTIES']['MESSAGE']['DISPLAY_VALUE']) > $arParams['PREVIEW_TRUNCATE_LEN']): ?>
                                            <div class="link-block-more">
                                                <span class="btn btn-default btn-transparent btn-xs animate-load"
                                                    data-event="jqm" data-param-id="<?= $arItem['ID']; ?>"
                                                    data-param-type="review"
                                                    data-name="review"><?= Loc::getMessage('MORE'); ?></span>
                                            </div>
                                        <? endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                <? endforeach; ?>
            </ul>
        </div>
    </div>
    </div>
<? endif; ?>