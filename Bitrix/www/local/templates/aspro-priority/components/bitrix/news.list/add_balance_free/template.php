<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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

<div class="cabinet_balance">
	<div class="inner_border">
		<?php if (count($arResult["ITEMS"]) > 0): ?>
			<h1 class="sale-personal-section-account-sub-header">
				Оплата по договору
			</h1>
			<div class="bx-sap" id="bx-sap0zYtTOjonO">
				<div class="container-fluid">
					<div class="row">
						<div class="col-xs-12">
							<div class="contract_container">
								<h3 class="sale-acountpay-title">Договор</h3>

								<?php if ($arResult["CONTRACTS"]): ?>

									<select>
										<?php foreach ($arResult["CONTRACTS"] as $arContract): ?>
											<option value="<?= $arContract["PROPERTY_NUMBER_VALUE"] ?>"
												data-box-id="<?= $arContract["PROPERTY_BOX_VALUE"] ?>"
												data-contract-id="<?= $arContract["ID"] ?>"
												data-contract-guid="<?= $arContract["PROPERTY_CONTRACT_GUID_VALUE"] ?>"
												<? if ($arResult["SELECTED_CONTRACT"]["ID"] == $arContract["ID"]) { ?>selected="selected" <? } ?>><?= $arContract["PROPERTY_NUMBER_VALUE"] ?>
											</option>
										<?php endforeach; ?>
									</select>

								<?php else: ?>

									<div class="row">
										<div class="col-md-4">
											<div class="input-group input-group--contract">
												<input type="text"
													class="form-control"
													name="CONTRACT_NUMBER"
													required
													value="<?= $_GET['contract'] ?? '' ?>">
												<span class="input-group-btn">
													<button class="btn btn-default btn-transparent animate-load" type="button" id="find_conract">Найти договор</button>
												</span>
											</div>
										</div>
									</div>

									<div class="input_help">Введите номер договора в формате 8/3538 и нажмите кнопку "Найти договор".<br>
										После того, как договор будет найден, Вы сможете ввести сумму оплаты и пополнить баланс. Уточнить номер можно у менеджера, в счёте или в договоре.</div>

								<?php endif; ?>
							</div>
						</div>
					</div>

					<div class="row hidden contract-info">
						<div class="col-md-12"></div>
					</div>

					<div class="row">
						<div class="col-md-6 col-xs-12 hidden sale-acounterror-block"></div>
					</div>

					<div class="row">
						<div class="col-md-4 col-xs-12 sale-acountpay-block">
							<div class="custom_sum_container">
								<h3 class="sale-acountpay-title">Введите сумму</h3>
								<input type="text"
									class="form-control custom_sum"
									value="<?= str_replace(' ', '', $_GET['sum'] ?? '') ?>"
									data-product-id="9819"
									onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 44"
									<?php if (!$arResult["CONTRACTS"]): ?>disabled<?php endif; ?>>
								<small>сумма к оплате за следующий месяц</small>
							</div>
						</div>
					</div>

					<?php /*					
					<div class="row">
						<div class="col-xs-12 sale-acountpay-block">						
							<h3 class="sale-acountpay-title">Фиксированный платёж</h3>
							<div class="sale-acountpay-fixedpay-container">
								<div class="sale-acountpay-fixedpay-list">
									<?foreach($arResult["ITEMS"] as $arItem) {

										if($arItem["ID"] == 9819) continue;
										?>
										<div class="btn btn-default btn-xs btn-transparent sale-acountpay-fixedpay-item fixed_sum_btn" data-product-id="<?=$arItem["ID"]?>">
											<?=$arItem["PREVIEW_TEXT"]?>											
										</div>
									<?}?>
								</div>
							</div>
						</div>
					</div>
					*/ ?>

					<div class="row">
						<div class="col-xs-12">
							<?php if (!$arResult["CONTRACTS"]): ?>
								<a href="javascript:void(0);" class="btn btn-default btn-lg button disabled" id="find_conract_2">Пополнить</a>
							<?php endif; ?>

							<a href="javascript:void(0);" class="btn btn-default btn-lg sale-account-pay-button button disabled add_balance <?php if (!$arResult["CONTRACTS"]): ?>hidden<?php endif; ?>">Пополнить</a>

						</div>

						<div class="col-xs-12">
							<p>
								<br>
								Обработка платежа проходит в банковский рабочий день. В связи с этим при оплате вечером, в выходные и праздничные дни возможны задержки поступления средств.
							</p>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php
$this->__component->arResult["CACHED_TPL"] = @ob_get_contents();
ob_get_clean();
?>