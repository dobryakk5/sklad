<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


<?if(!empty($arResult)) {?>
	<div class="menu-content">
		<div class="maxwidth-theme">
			<div class="menu-content-container">
				<?foreach($arResult as $arItem) {?>
					<?
					if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) 
						continue;
					?>
					<div class="menu-item">
						<div class="title">
							<a class="<?=($arItem["SELECTED"])?'active':''?>" href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
						</div>
					</div>
				<?}?>
			</div>
		</div>
		<div class="bottom-shadow"></div>
	</div>
	<div class="menu-content-buffer"></div>
<?}?>