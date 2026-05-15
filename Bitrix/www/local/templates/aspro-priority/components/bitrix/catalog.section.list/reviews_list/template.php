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
ob_start();
?>


<?if(count($arResult["SECTIONS"]) > 0) {?>
	<div class="row">
		<div class="col-md-9 col-xs-12">
			<div class="tabs">
				<ul class="nav nav-tabs">
					<?$cnt = 0;?>
					<?foreach($arResult["SECTIONS"] as $si=>$arSection) {?>
						<li class="font_upper_md shadow border <?=($cnt==0)?'active':''?>"><a href="#<?=$arSection["CODE"]?>" data-toggle="tab"><?=$arSection["NAME"]?></a></li>
						<?$cnt++;?>
					<?}?>
				</ul>
				<div class="tab-content">
					<?$cnt = 0;?>
					<?foreach($arResult["SECTIONS"] as $si=>$arSection) {?>
						<div class="tab-pane <?=($cnt==0)?'active':''?>" id="<?=$arSection["CODE"]?>">
							<?/*#RATING_SKLAD#*/?>
							<div class="send_review_button"><span class="btn btn-default btn-xs btn-transparent" data-event="jqm" data-param-id="30" data-name="add_review">Оставить отзыв</span></div>
							<p class="mt-30-xs style-h3">Рекомендованные отзывы</p>
							#SORT#
							#REVIEWS_<?=$arSection["ID"]?>#							
						</div>						
						<?$cnt++;?>
					<?}?>
				</div>
			</div>
		</div>
		<div class="col-md-3 col-xs-12">
            <? /*
			#SKLAD_LIST#
                 */   ?>
		</div>
	</div>
<?} else {?>
	Отзывы не найдены
<?}?>


<?
$this->__component->arResult["CACHED_TPL"] = @ob_get_contents();
ob_get_clean();



?>