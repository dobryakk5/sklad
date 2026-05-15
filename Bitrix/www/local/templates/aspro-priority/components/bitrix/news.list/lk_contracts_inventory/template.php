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
<?if(count($arResult["ITEMS"]) > 0) {?>
	<h3>Описи по договорам хранения</h3>
	<p>По описям в данном списке можно добавить и отредактировать описание без добавления или изменения фотографий</p>
	<div class="contracts_list lk_table">
		<table class="table">
			<tbody>
				<?foreach($arResult["STORE"] as $key =>$itemID) { //var_dump($arItem["PROPERTIES"]["FILES"]);?>
					<?$arItem = $arResult['ITEMS'][$key];?>
					<tr>
					<td>
 					<p>
					<a data-toggle="collapse" href="#inv<?=$arItem["ID"]?>" role="button" aria-expanded="false" aria-controls="collapseExample">		
						<?=$arItem["NAME"]?>
					</a>
					</p>
					<div class="collapse" id="inv<?=$arItem["ID"]?>">
					<div class="inventory" data-inventory-id="<?=$arItem["ID"]?>">
						<div class="pic-block">
							<?foreach ($arItem["PROPERTIES"]["CRM_FILES"]["VALUE"] as $file) {?>
								<a class="link" href="https://crm.alfasklad.ru<?=$file?>" target="_blank">
									<img src="https://crm.alfasklad.ru<?=$file?>" class="preview">
								</a>
							<?}?>
						</div>
						<div class="text-block">
							<textarea class="DETAIL_TEXT" placeholder="Введите текст..."><?=$arItem['DETAIL_TEXT']?></textarea>
						</div>
						<div class="save-block">
							<span class="btn btn-default SAVE"  data-inventory-id="<?=$arItem["ID"]?>" data-action="<?=$templateFolder?>/ajax.php">Сохранить изменения</span>
						</div>
					</div>
					</div>
					</td>
					</tr>
				<?}?>
			</tbody>
		</table>
        <?if($arParams['DISPLAY_BOTTOM_PAGER']) {?>
            <div class="pagination_nav">
                <?=$arResult['NAV_STRING']?>
            </div>
        <?}?>		
	</div>
<?}?>
<?if(count($arResult["BOX_INVENTORY"]) > 0) {?>
	<h3>Описи по договорам аренды</h3>
	<p>По описям в данном списке можно добавить и отредактировать описание, добавить или изменить фотографии</p>
	<div class="contracts_list lk_table">
		<table class="table">
			<tbody>
				<?foreach($arResult["BOX_INVENTORY"] as $arItem) {?>
					<tr>
						<td><a href="/cabinet/myboxes/current/inventory-<?=$arItem["BOX"]?>/" target="_blank"><?=$arItem["NAME"]?></td>
					</tr>
				<?}?>
			</tbody>
		</table>
        <?if($arParams['DISPLAY_BOTTOM_PAGER']) {?>
            <div class="pagination_nav">
                <?=$arResult['NAV_STRING']?>
            </div>
        <?}?>		
	</div>
<?}?>
