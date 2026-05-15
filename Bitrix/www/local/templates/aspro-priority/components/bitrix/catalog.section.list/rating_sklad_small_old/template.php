<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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

<?if(!empty($arResult["DATA"])) {?>
	<div class="rating_small_block">
		<div class="row">
			<div class="col-md-12">
				<div class="rt_item">
					<div class="r-val"><?=$arResult["DATA"]["RATING"]?></div>
					<?
					$ratingValue = round($arResult["DATA"]["RATING"]);
					?>
					<div class="rating_wrap">
						<div class="rating current_<?=$ratingValue?>">
							<span class="stars_current"></span>
						</div>
					</div>					
				</div>
			</div>
			<div class="col-md-12">
				<div class="rt_item">
					<div class="str-1"><?=num_decline($arResult["DATA"]["VOTES"], Array("оценка", "оценки", "оценок"))?></div>
					<div class="str-2">за все время</div>				
				</div>			
			</div>	
			<div class="col-md-12">
				<div class="rt_item">
					<div class="str-1"><?=num_decline($arResult["DATA"]["REVIEWS"], Array("отзыв", "отзыва", "отзывов"))?></div>
					<div class="str-2">за все время</div>
				</div>			
			</div>
			<div class="col-md-12">
				<div class="buttons">
					<?
					$link = "/rental_catalog/";
					if(strlen($arResult["DATA"]["CODE"]) > 0) {
						$link = "/rental_catalog/".$arResult["DATA"]["CODE"]."/";
					}
					?>
					<a class="btn btn-default btn-xs btn-transparent" href="<?=$link?>">Все отзывы</a>
					<span class="btn btn-default btn-xs btn-transparent" data-event="jqm" data-param-id="30" data-name="add_review">Оставить отзыв</span>
				</div>			
			</div>			
		</div>
	</div>
<?}?>