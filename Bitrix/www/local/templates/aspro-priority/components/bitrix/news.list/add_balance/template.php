	<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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

	<? if ($arResult["CONTRACTS"]) { ?>
		<div class="cabinet_balance">
			<div class="inner_border">
				<div class="sale-personal-account-wallet-container">
					<div class="row">
						<div class="col-md-12 col-xs-12">
							<div class="sale-personal-account-wallet-title__bak contract_container">
								<h5 class="sale-acountpay-title">Состояние лицевого счета на <?= date("d.m.Y") ?>: <?= CCurrencyLang::CurrencyFormat($arResult["OVERAL_BALANCE"], "RUB", false) ?> руб.</h5>
							</div>

							<? /*
 на основании встречи 08,08
                        <div class="sale-personal-account-wallet-list-container">
							<div class="sale-personal-account-wallet-list">
								<div class="sale-personal-account-wallet-list-item">
									<div class="sale-personal-account-wallet-sum">
										<?=CCurrencyLang::CurrencyFormat($arResult["OVERAL_BALANCE"], "RUB", false)?>
									</div>
									<div class="sale-personal-account-wallet-currency">
										<div class="sale-personal-account-wallet-currency-item">RUB</div>
										<div class="sale-personal-account-wallet-currency-item">Рубль</div>
									</div>
								</div>
							</div>
						</div>
                        */ ?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 col-xs-12">
							<div class="contract_container">
								<h5 class="sale-acountpay-title">Договор:</h5>
								<select>
									<? foreach ($arResult["CONTRACTS"] as $arContract) { ?>
										<option value="<?= $arContract["PROPERTY_NUMBER_VALUE"] ?>" data-box-id="<?= $arContract["PROPERTY_BOX_VALUE"] ?>" data-contract-id="<?= $arContract["ID"] ?>" data-contract-guid="<?= $arContract["PROPERTY_CONTRACT_GUID_VALUE"] ?>" <? if ($arResult["SELECTED_CONTRACT"]["ID"] == $arContract["ID"]) { ?>selected="selected" <? } ?>><?= $arContract["PROPERTY_NUMBER_VALUE"] ?></option>
									<? } ?>
								</select>
							</div>
						</div>
						<div class="col-md-6 col-xs-12">
							<? /*   <div class="contract_balance">
							Баланс по договору: <span><?=CCurrencyLang::CurrencyFormat(round($arResult["SELECTED_CONTRACT"]["PROPERTY_BALANCE_VALUE"], 2)*-1, "RUB")?></span>
						</div> */ ?>
						</div>
					</div>
				</div>

				<?php if ($USER->IsAuthorized()): ?>
					<div class="contract_container autopay-anonse-desktop">
						Уважаемый Клиент!<br>
						Рады сообщить, что с 4 февраля 2026 г. доступна функция автоплатежа. После подключения управлять функцией (менять данные карты, отключать, снова подключать) Вы сможете в данном разделе ЛК в блоке «Мои платежные средства».
					</div>
					<!-- <div class="contract_container autopay-anonse-mobile">
						Уважаемый Клиент!<br>
						Рады сообщить, что с 10 октября 2025 г. доступна функция автоплатежа. После подключения управлять функцией (менять данные карты, отключать, снова подключать) Вы сможете в данном разделе ЛК в блоке «Мои платежные средства».
					</div> -->
				<?php endif; ?>

				<? if (count($arResult["ITEMS"]) > 0) { ?>
					<h3 class="sale-personal-section-account-sub-header">
						Пополнение лицевого счета
					</h3>
					<div class="bx-sap" id="bx-sap0zYtTOjonO">
						<div class="container-fluid">
							<div class="row">
								<div class="col-xs-12 sale-acountpay-block">
									<div class="custom_sum_container">
										<h3 class="sale-acountpay-title">Введите сумму</h3>
										<input type="text" class="custom_sum" value="<?= $arResult["OVERAL_BALANCE"] <= 0 && $arResult['OVERAL_INVOICES_SUM'] > 0 ? $arResult['OVERAL_INVOICES_SUM'] : 0 ?>" data-product-id="9819" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 44" />
									</div>

									<?php if (false): ?>
										<h3 class="sale-acountpay-title">Фиксированный платёж</h3>
										<div class="sale-acountpay-fixedpay-container">
											<div class="sale-acountpay-fixedpay-list">
												<? foreach ($arResult["ITEMS"] as $arItem) {

													if ($arItem["ID"] == 9819) continue;
												?>
													<div class="btn btn-default btn-xs btn-transparent sale-acountpay-fixedpay-item fixed_sum_btn" data-product-id="<?= $arItem["ID"] ?>">
														<?= $arItem["PREVIEW_TEXT"] ?>
													</div>
												<? } ?>
											</div>
										</div>
									<?php endif; ?>
								</div>
							</div>

							<div class="row">
								<div class="col-xs-12">
									<a href="javascript:void(0);"
										class="btn btn-default btn-lg sale-account-pay-button button <?php if (empty($arResult['OVERAL_INVOICES_SUM'])): ?>disabled<?php endif; ?> add_balance"
										<?php if ($arResult["OVERAL_BALANCE"] <= 0 && $arResult['OVERAL_INVOICES_SUM'] > 0): ?>
										data-product-id="9819"
										data-custom-sum="<?= $arResult['OVERAL_INVOICES_SUM'] ?>"
										<?php endif; ?>>Пополнить</a>
								</div>

								<div class=" col-xs-12">
									<p>
										<br>
										Обработка платежа проходит в банковский рабочий день. В связи с этим при оплате вечером, в выходные и праздничные дни возможны задержки поступления средств.
										Во избежание начисления штрафов необходимо производить оплату счета заблаговременно.
									</p>
								</div>
							</div>
						</div>
					</div>
				<? } ?>
			</div>
		</div>
	<? } else { ?>
		<p>Активные договоры не найдены.</p>
	<? } ?>


	<?
	$this->__component->arResult["CACHED_TPL"] = @ob_get_contents();
	ob_get_clean();
	?>