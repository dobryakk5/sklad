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
	<div class="invoices_list lk_table">
		<table class="table">
			<thead>
				<tr>
					<th>№&nbsp;счета</th>
					<th>Дата счета</th>
					<th>Сумма</th>
					<? /*><th>№&nbsp;договора</th> */ ?>
					<th>Примечание</th>
					<? /*<th>Дата начала</th>
					<th>Дата окончания</th> */ ?>
					<th>Статус счета</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?foreach($arResult["ITEMS"] as $arItem) {?>
                    <?
                    //d($arItem["PROPERTIES"]['TotalInvoiceAmount']);
                    ?>
					<tr>
						<td class="number">
							<?if(!empty($arItem["PROPERTIES"]["PRODUCTS"]["VALUE"])) {?>
								<a href="/cabinet/docs/invoices/<?=$arItem["ID"]?>/">
							<?}?>
								<?=$arItem["PROPERTIES"]["NUMBER"]["VALUE"]?>
							<?if(!empty($arItem["PROPERTIES"]["PRODUCTS"]["VALUE"])) {?>
								</a>
							<?}?>							
						</td>
						<td><?=$arItem["PROPERTIES"]["DATE_CREATE"]["VALUE"]?></td>
						<td>
                            <? if(! empty($arItem["PROPERTIES"]['TotalInvoiceAmount']['VALUE'])) {
                                echo 'Из общей суммы счета '.$arItem["PROPERTIES"]['TotalInvoiceAmount']['VALUE'].' руб. осталось оплатить сумму<br>';
                            }?>
                            <?=$arItem["PRICE"]?>
                        </td>
						<td><?=$arItem["PROPERTIES"]["CONTRACT_NUMBER"]["VALUE"]?></td>
						<? /*<td><?=$arItem["PROPERTIES"]["DATE_FROM"]["VALUE"]?></td>
						<td><?=$arItem["PROPERTIES"]["DATE_TO"]["VALUE"]?></td> */ ?>
						<td><?=$arItem["PROPERTIES"]["STATUS"]["VALUE"]?></td>						
						<td class="buttons">


							<?if($arItem["PROPERTIES"]["STATUS"]["VALUE_ENUM_ID"] == INVOICE_STATUS_NOTPAID_ID || $arItem["PROPERTIES"]["STATUS"]["VALUE_ENUM_ID"] == INVOICE_STATUS_HALFPAID_ID) {?>
								<span class="btn btn-default btn-xs add_invoice_to_cart" style="min-width:135px;" data-invoice-id="<?=$arItem["ID"]?>">Оплатить</span>
							<?}?>
                            <?if($arItem["PROPERTIES"]["STATUS"]["VALUE_ENUM_ID"] == INVOICE_STATUS_PAID_ID) {?>
							<span  class="upd_request btn btn-transparent btn-xs" style="margin-top:6px; min-width:135px;" 
								data-event="jqm" data-param-webform-id="16" data-param-type="webform" data-name="webform"
								data-param-invoice-guid="<?=$arItem["PROPERTIES"]["INVOICE_GUID"]["VALUE"]?>"
								data-param-contract-guid="<?=$arItem["PROPERTIES"]["CONTRACT_GUID"]["VALUE"]?>">Запросить УПД</span>
                            <?}?>
							<?if($arItem["PROPERTIES"]["PROFILE_TYPE"]["VALUE_ENUM_ID"] == "399") {?>															
								<span  class="upd_request btn btn-transparent btn-xs" style="margin-top:6px; min-width:135px;" 
									data-event="jqm" data-param-webform-id="22" data-param-type="webform" data-name="webform"
									data-param-invoice-guid="<?=$arItem["PROPERTIES"]["INVOICE_GUID"]["VALUE"]?>"
									data-param-contract-guid="<?=$arItem["PROPERTIES"]["CONTRACT_GUID"]["VALUE"]?>">Запросить<br>счет на E-mail</span>
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
