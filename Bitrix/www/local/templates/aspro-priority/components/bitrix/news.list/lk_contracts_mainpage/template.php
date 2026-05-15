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

<div class="balance_info_mainpage">
    <div class="row">
        <div class="col-md-8 col-xs-12">
            <div class="info">
                Общий баланс <span><?=FormatCurrency($arResult["OVERAL_BALANCE"], "RUB")?></span> на <span><?=FormatDate("j F", date())?></span>
            </div>
        </div>
        <div class="col-md-4 col-xs-12">
            <div class="button">
                <a class="btn btn-default btn-transparent" href="/cabinet/balance/">Пополнить баланс</a>
            </div>
        </div>
    </div>
</div>
<br>

<?if(count($arResult["ITEMS"]) > 0) {?>
	<div class="contracts_list_mainpage lk_table">
		<table class="table">
			<thead>
				<tr>
					<th>Номер бокса</th>
					<th>Склад</th>
					<th>Оплачено до</th>
					<th>Баланс по договору</th>
					<th>Неоплаченные счета</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?foreach($arResult["ITEMS"] as $arItem) {?>
					<tr>
						<td><?=$arItem["BOX"]["PROPERTY_BOX_NUMBER_VALUE"]?></td>
						<td><?=$arItem["BOX"]["SKLAD"]["NAME"]?></td>
						<td><?=$arItem["PROPERTIES"]["PAID_DATE_TO"]["VALUE"]?></td>
						<td><?=FormatCurrency(round($arItem["PROPERTIES"]["BALANCE"]["VALUE"], 2)*-1, "RUB")?></td>
						<td>
							<?if(count($arItem["INVOICES"]) > 0) {?>
								<?foreach($arItem["INVOICES"] as $arInvoice) {?>
									<div class="invoice_link">Счет за <a href="/cabinet/docs/invoices/<?=$arInvoice["ID"]?>/"><?=$arInvoice["MONTH"]?> г.</a></div>
								<?}?>
							<?} else {?>
								-
							<?}?>
						</td>
						<td style="text-align: right;">
							<?if(count($arItem["INVOICES"]) > 0) {?>
								<?
								$arInvID = Array();
								foreach($arItem["INVOICES"] as $arInvoice) {
									$arInvID[] = $arInvoice["ID"];
								}
								?>
								<span class="btn btn-transparent btn-xs add_invoice_to_cart" data-invoice-id="<?=implode(",", $arInvID)?>">Оплатить</span>
							<?} else {?>
								-
							<?}?>
						</td>
					</tr>
				<?}?>
			</tbody>
		</table>
	</div>
<?} else {?>
	<?
	//ищем, есть ли у этого пользователя заказы
	//и если есть, то выводим ему сообщение
	CModule::IncludeModule("sale"); 

	$dbRes = \Bitrix\Sale\Order::getList([
	  'select' => ['ID'],
	  'filter' => [
		  'USER_ID' => $arParams["USER_ID"],
		  'PAYED' => 'Y',
		  '>DATE_PAYED' => Date('d.m.Y h:i:s', strtotime('-3 days'))
	  ],
	  'order' => ['ID' => 'DESC']
	]);

	$orders = array();
	while ($order = $dbRes->fetch()){
		$orders[]=$order;
	}
	?>
	<?if(count($orders) > 0) {?>
		<div class="alert alert-info">
			<strong>Подождите, ваш заказ обрабатывается.</strong>
		</div>
	<?}?>
<?}?>

<?
$this->__component->arResult["CACHED_TPL"] = @ob_get_contents();
ob_get_clean();
?>