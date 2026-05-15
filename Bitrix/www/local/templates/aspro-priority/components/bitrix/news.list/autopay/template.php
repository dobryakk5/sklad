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
//ob_start();
?>


<br>

<div class="mb-6 row three-buttons">
	<div class="text-center col-md-6 col-sm-12">
		<?php if (isset($arResult['PM']) && $arResult['PM']['ACTIVE'] == 'Y' && $arResult['PM']['AUTOPAY'] == 'Y' && $arResult['PM']['STATUS'] == 'active' && $arResult['PM']['SAVED']): ?>
			<a class="btn btn-default btn-transparent btn-lg unplug_autopay" href="#" onclick="javascript:void(0);">Отключить автоплатёж</a>

			<div class="autopay-accordion">
				<!-- <a href="javascript: void(0)" class="autopay-accordion-toggle">подбробнее</a> -->
				<div class="text storage-services__accordion">
					Автоплатёж подключен<br>
					Оплата за следующий месяц автоматически спишется в последний день текущего.
				</div>
			</div>
		<?php elseif (isset($arResult['PM']) && $arResult['PM']['ACTIVE'] == 'N' && $arResult['PM']['AUTOPAY'] == 'Y' && $arResult['PM']['STATUS'] == 'pending' && $arResult['PM']['SAVED']): ?>
			<p class="text" style="color:green">Заявка на автоплатеж сформирована успешно. Ожидаем подтверждение банка.</p>
		<?php else: ?>

			<!-- <p class="text">
				Подключая автоплатёж, вы даёте согласие на автоматическое списание денежных средств с указанной банковской карты ежемесячно в последний день месяца. Сумма списания определяется размером ежемесячного платежа, отражённого в счетах, будет списываться без дополнительного подтверждения с вашей стороны, пока автоплатёж активен.
			</p>
			<p class="text">
				Отключить автоплатёж можно в любой момент, нажав кнопку «Отключить автоплатёж в личном кабинете. После отключения списания прекращаются, однако платежи, инициированные до момента отмены, могут быть обработаны.
			</p> -->

			<a class="btn btn-default btn-lg plug_autopay" href="#" onclick="javascript:void(0);">Подключить автоплатёж</a>

			<div class="autopay-accordion">
				<!-- <a href="javascript: void(0)" class="autopay-accordion-toggle">подробнее</a> -->
				<!-- <div class="text storage-services__accordion hidden"> -->
				<div class="text storage-services__accordion">
					После подключения автоплатежа с Вашей банковской карты в последний день месяца будет автоматически списываться сумма по договору на следующий месяц.
				</div>
			</div>
		<?php endif; ?>
	</div>


	<?php if ($arResult['REMEMBER_CARD']): ?>
		<div class="text-center col-md-6 col-sm-12">
			<?php if ($arResult['PAYMENT_MERCHANT_SAVED'] == 'Y' || $arResult['PAYMENT_MERCHANT_SAVED'] == 1): ?>
				<a class="btn btn-default btn-transparent btn-lg forget_merchant" href="#" onclick="javascript:void(0);">Отвязать карту</a>
				<div class="autopay-accordion">
					<div class="text storage-services__accordion">
						Данные карты сохранены в платёжном сервисе<br>
						Это надёжно и безопасно. При следующих пополнениях вы сможете произвести оплату в 1 клик.
					</div>
				</div>
			<?php else: ?>
				<a class="btn btn-default btn-lg save_merchant <?php if (empty($arResult['LAST_INVOICE'])): ?>disabled<?php else: ?><?php endif; ?>"
					data-invoice-id="<?= $arResult['LAST_INVOICE']['ID'] ?>"
					href="#"
					onclick="javascript:void(0);">Оплатить и запомнить карту</a>
				<div class="autopay-accordion">
					<div class="text storage-services__accordion">
						Выбрав эту опцию, Вы сможете внести оплату и сохранить данные банковской карты, чтобы использовать ее для будущих платежей.
					</div>
				</div>

			<?php endif; ?>
		</div>
	<?php endif; ?>


	<?php if (false): ?>
		<!-- <div class="text-center col-md-4 col-sm-12">
			<?php if (isset($arResult['PM']) && $arResult['PM']['ACTIVE'] == 'Y' && $arResult['PM']['STATUS'] == 'active' && $arResult['PM']['SAVED']): ?>
				<a class="btn btn-default btn-transparent btn-lg unlink_card" href="#" onclick="javascript:void(0);">Отвязать карту</a>

				<div class="autopay-accordion">
					<a href="javascript: void(0)" class="autopay-accordion-toggle">подбробнее</a>
					<div class="text storage-services__accordion hidden">
						Автоплатёж подключен<br>
						Оплата за следующий месяц автоматически спишется в последний день текущего.
					</div>
				</div>

			<?php elseif (isset($arResult['PM']) && $arResult['PM']['ACTIVE'] == 'N' && $arResult['PM']['AUTOPAY'] == 'N' && $arResult['PM']['STATUS'] == 'pending' && $arResult['PM']['SAVED']): ?>
				<p class="text" style="color:green">Заявка на привязку карты сформирована успешно. Ожидаем подтверждение банка.</p>
			<?php else: ?>
				<a class="btn btn-default btn-lg link_card" href="#" onclick="javascript:void(0);">Привязать карту</a>

				<div class="autopay-accordion">
					<a href="javascript: void(0)" class="autopay-accordion-toggle">подробнее</a>
					<div class="text storage-services__accordion hidden">
						Выбрав эту опцию, Вы сможете ввести и сохранить данные банковской карты, чтобы использовать ее для будущих платежей.
					</div>
				</div>
			<?php endif; ?>
		</div> -->
	<?php endif; ?>



</div>

<br>

<?
//$this->__component->arResult["CACHED_TPL"] = @ob_get_contents();
//ob_get_clean();
?>