<?php

use Bitrix\Sale\PaySystem,
	Bitrix\Main\Web\HttpClient;

require_once $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/local/php_interface/yookassa.php";

\Bitrix\Main\Loader::includeModule('sale');

global $USER, $USER_FIELD_MANAGER;

$ps_settings = \CSalePaySystemAction::getParamsByConsumer('PAYSYSTEM_' . 7, 's1');

$yookassa = new Yookassa(
	$ps_settings['YANDEX_CHECKOUT_SHOP_ID']['VALUE'],
	$ps_settings['YANDEX_CHECKOUT_SECRET_KEY']['VALUE'],
	new HttpClient([])
);

/**
 * Запрос создания нового способа оплаты
 */
if ($_REQUEST["ACTION"] == 'plug_autopay' || $_REQUEST["ACTION"] == 'link_card') {
	try {
		if (!$USER->IsAuthorized()) {
			throw new Exception('Пользователь не авторизован', 4);
		}

		$rc = $yookassa->requestConfirmation();

		// сохранить способ оплаты в юзера
		$pid = (new CIBlockElement)->Add(
			[
				"IBLOCK_SECTION_ID" => false,
				"IBLOCK_ID"      	=> 69,
				"NAME"				=> $rc['id'],
				"ACTIVE"         	=> "N",
				'PROPERTY_VALUES' 	=> [
					'PM_STATUS' => $rc['status'],
					'PM_SAVED' => $rc['saved'],
					'AUTOPAY' => $_REQUEST["ACTION"] == 'plug_autopay' ? 'Y' : 'N',
				],
			]
		);

		if ($pid) {

			$USER_FIELD_MANAGER->Update("USER", $USER->GetID(), ["UF_AUTOPAYMEN_METHOD" => $pid]);

			exit(json_encode([
				'success' => 'Y',
				'url' => $rc['url'],
			]));
		} else {
			throw new Exception('Не удалось привязатать способ оплаты к пользователю', 3);
		}
	} catch (\Exception $e) {
		exit(json_encode([
			'success' => 'N',
			'error' => /*$e->getMessage()*/ 'Произошла ошибка при попытке подключения автоплатежа. Обратитесь к администрации (' . $e->getCode() . ')',
			'code' => $e->getCode(),
		]));
	}
}
/**
 * Сохраняем признак передавать merchant_id в yookassa или нет
 */
if ($_REQUEST["ACTION"] == 'save_merchant') {
	try {
		if (!$USER->IsAuthorized()) {
			throw new Exception('Пользователь не авторизован', 4);
		}

		$rsUser = CUser::GetByID($USER->GetID());
		$arUser = $rsUser->Fetch();

		$current = $arUser['UF_SAVE_PAYMENT_MERCHANT'] == 'Y' || $arUser['UF_SAVE_PAYMENT_MERCHANT'] == 1 ? 0 : 1;

		$USER_FIELD_MANAGER->Update("USER", $USER->GetID(), ["UF_SAVE_PAYMENT_MERCHANT" => $current]);

		exit(json_encode([
			'success' => 'Y',
			'current' => $current,
		]));

	} catch (\Exception $e) {
		exit(json_encode([
			'success' => 'N',
			'error' => /*$e->getMessage()*/ 'Произошла ошибка при выполнении операции. Обратитесь к администрации (' . $e->getCode() . ')',
			'code' => $e->getCode(),
		]));
	}
}

/**
 * Отключение автоплатежа
 */
if ($_REQUEST["ACTION"] == 'unlink_card') {
	try {
		if (!$USER->IsAuthorized()) {
			throw new Exception('Пользователь не авторизован', 4);
		}

		$rsUser = CUser::GetByID($USER->GetID());
		$arUser = $rsUser->Fetch();

		if ($arUser['UF_AUTOPAYMEN_METHOD']) {

			$res = CIBlockElement::GetByID($arUser['UF_AUTOPAYMEN_METHOD']);
			if ($pm = $res->Fetch()) {

				if ($pm['ACTIVE'] == 'Y') {
					(new CIBlockElement)->Update(
						$pm['ID'],
						['ACTIVE' => 'N'],
					);
				}

				exit(json_encode([
					'success' => 'Y',
				]));
			} else {
				throw new Exception('Вы не подключали автоплатёж ранее', 33);
			}
		} else {
			throw new Exception('Вы не подключали автоплатёж ранее', 3);
		}
	} catch (\Exception $e) {
		exit(json_encode([
			'success' => 'N',
			'error' => /*$e->getMessage()*/ 'Произошла ошибка при попытке отключения автоплатежа. Обратитесь к администрации (' . $e->getCode() . ')',
			'code' => $e->getCode(),
		]));
	}
}


/**
 * Отключение автоплатежа
 */
if ($_REQUEST["ACTION"] == 'unplug') {
	try {
		if (!$USER->IsAuthorized()) {
			throw new Exception('Пользователь не авторизован', 4);
		}

		$rsUser = CUser::GetByID($USER->GetID());
		$arUser = $rsUser->Fetch();

		if ($arUser['UF_AUTOPAYMEN_METHOD']) {

			$res = CIBlockElement::GetByID($arUser['UF_AUTOPAYMEN_METHOD']);
			if ($pm = $res->Fetch()) {
				CIBlockElement::SetPropertyValues(
					$pm['ID'],
					$pm['IBLOCK_ID'],
					'N',
					'AUTOPAY'
				);
				exit(json_encode([
					'success' => 'Y',
				]));
			} else {
				throw new Exception('Вы не подключали автоплатёж ранее', 33);
			}
		} else {
			throw new Exception('Вы не подключали автоплатёж ранее', 3);
		}
	} catch (\Exception $e) {
		exit(json_encode([
			'success' => 'N',
			'error' => /*$e->getMessage()*/ 'Произошла ошибка при попытке отключения автоплатежа. Обратитесь к администрации (' . $e->getCode() . ')',
			'code' => $e->getCode(),
		]));
	}
}



require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_after.php");
