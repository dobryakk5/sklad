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
	<div class="contracts_list lk_table">
		<table class="table">
			<thead>
				<tr>
					<th>№&nbsp;договора</th>
					<th>№&nbsp;бокса</th>
					<th>Дата договора</th>
					<th>Оплачено до</th>
					<th>Баланс по договору</th>
					<th>Статус договора</th>
					<th>Счета</th>
				</tr>
			</thead>
			<tbody>
				<?foreach($arResult["ITEMS"] as $arItem) {?>
					<tr>
						<td><?=$arItem["PROPERTIES"]["NUMBER"]["VALUE"]?></td>
						<td><?=$arItem["BOX"]["PROPERTY_BOX_NUMBER_VALUE"]?></td>
						<td><?=$arItem["PROPERTIES"]["DATE_CREATE"]["VALUE"]?></td>
						<td><?=$arItem["PROPERTIES"]["PAID_DATE_TO"]["VALUE"]?></td>
						<td><?=FormatCurrency(intval($arItem["PROPERTIES"]["BALANCE"]["VALUE"])*-1, "RUB")?></td>
						<td><?=$arItem["PROPERTIES"]["STATUS"]["VALUE"]?></td>
						<td>
							<?if(count($arItem["INVOICES"]) > 0) {?>
								<?foreach($arItem["INVOICES"] as $arInvoice) {?>
									<div class="invoice_link">Счет за <a href="/cabinet/docs/invoices_not_paid/"><?=$arInvoice["MONTH"]?> г.</a></div>
								<?}?>
							<?} else {?>
								-
							<?}?>
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
