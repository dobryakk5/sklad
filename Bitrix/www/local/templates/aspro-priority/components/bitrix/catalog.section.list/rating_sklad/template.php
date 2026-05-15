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
	<div class="rating_big_block">
		<div class="row">
			<div class="col-md-4 col-xs-12">
				<div class="item table-cell">
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
			<div class="col-md-4 col-xs-12">
				<div class="item">
					<div class="str-1"><?=num_decline($arResult["DATA"]["VOTES"], Array("оценка", "оценки", "оценок"))?></div>
					<div class="str-2">за все время</div>				
				</div>			
			</div>	
			<div class="col-md-4 col-xs-12">
				<div class="item">
					<div class="str-1"><?=num_decline($arResult["DATA"]["REVIEWS"], Array("отзыв", "отзыва", "отзывов"))?></div>
					<div class="str-2">за все время</div>
				</div>			
			</div>				
		</div>
	</div>
<?}?>