<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?
$this->setFrameMode(true);
?>


<?if(count($arResult["ITEMS"]) > 0) {?>
	<?if($arParams["AJAX_LOAD"] != "Y") {?>
		<div class="reviews-block">
			<div class="reviews-list">
	<?}?>
	
				<?foreach($arResult["ITEMS"] as $key=>$arItem) {?>
					<div class="item" itemscope itemtype="http://schema.org/Review">
						<div class="head">
                            <a href="/about/reviews/" style="display:none;" itemprop="url"></a>

                            <?if(strlen($arItem["PREVIEW_PICTURE"]["RESIZE"]["src"]) > 0 && $arItem["PROPERTIES"]["HIDE_NAME"]["VALUE"] != "Y") {?>
								<div class="image">
									<img src="<?=$arItem["PREVIEW_PICTURE"]["RESIZE"]["src"]?>" />
								</div>
							<?}?>

							<div itemprop="author" itemscope itemtype="https://schema.org/Person">
								<div class="name" itemprop="name">
									<?if($arItem["PROPERTIES"]["HIDE_NAME"]["VALUE"] == "Y") {?>
										Пользователь скрыл свои данные
									<?} else {?>
										<?=$arItem["NAME"]?>
									<?}?>
								</div>
							</div>
						</div>
						<div class="rating-block">
							<?
							$ratingValue = ($arItem["PROPERTIES"]["RATING"]["VALUE"] ? $arItem["PROPERTIES"]["RATING"]["VALUE"] : 0);
							?>
							<div class="rating_wrap clearfix"  itemprop="reviewRating" itemscope
                                 itemtype="http://schema.org/Rating">
								<div class="rating current_<?=$ratingValue?>">
                                    <meta itemprop="worstRating" content="0">
                                    <span style="display:none;" itemprop="ratingValue"><?= $ratingValue ?></span>
                                    <span style="display:none;" itemprop="bestRating">5</span>

									<span class="stars_current "></span>
								</div>
								<?if(!empty($arItem["SKLAD"])) {?>
									<div class="sklad"><a href="/rental_catalog/<?=$arItem["SKLAD"]["CODE"]?>/"><?=$arItem["SKLAD"]["NAME"]?></a></div>
								<?}?>
							</div>
						</div>
						<div class="message" itemprop="reviewBody">
							<?=$arItem["PROPERTIES"]["MESSAGE"]["~VALUE"]["TEXT"]?>
						</div>
						<div class="date" itemprop="datePublished" datetime="<?=$arItem["DISPLAY_ACTIVE_FROM"]?>">
							<?=$arItem["DISPLAY_ACTIVE_FROM"]?>
						</div>

                        <div style="display:none;" itemprop="itemReviewed" itemscope="" itemtype="https://schema.org/Organization">
                            <div itemprop="name">АльфаСклад</div>
                            <link itemprop="url" href="https://alfasklad.ru">
                            <span itemprop="telephone" href="tel:+7(495)292-45-23"></span>
                            <div itemprop="address" itemscope="" itemtype="https://schema.org/PostalAddress">
                                <span itemprop="addressCountry">Россия</span>,
                                <span itemprop="addressLocality">Москва</span>,
                                <span itemprop="streetAddress">ул. Верхнелихоборская, д. 8А</span>
                            </div>
                        </div>

					</div>
				<?}?>
				
				<?
				$NavPageNomer = intval($arResult["NAV_RESULT"]->NavPageNomer);
				$NavPageCount = intval($arResult["NAV_RESULT"]->NavPageCount);
				?>
				<?if($NavPageNomer < $NavPageCount) {?>
					<div class="pagination-block">
						<a class="btn btn-default wc ajax_load_reviews" href="javascript:void(0);" data-page="<?=$NavPageNomer+1?>" data-section-id="<?=$arResult["SECTION"]["PATH"][0]["ID"]?>" data-sklad-id="<?=$GLOBALS["arrFilterSkladList"]["PROPERTY_SKLAD"]?>" data-count="<?=$arParams["NEWS_COUNT"]?>"><i class="fa fa-plus"></i><span>Показать больше отзывов</span></a>
					</div>
				<?}?>
	<?if($arParams["AJAX_LOAD"] != "Y") {?>				
			</div>	
		</div>
	<?}?>	
<?} else {?>
	<?if($arParams["AJAX_LOAD"] != "Y") {?>
		<p>Отзывы не найдены</p>
	<?}?>
<?}?>


