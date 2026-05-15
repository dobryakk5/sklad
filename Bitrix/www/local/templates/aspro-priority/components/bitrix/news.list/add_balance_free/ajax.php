<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php";

use Bitrix\Main\Context,
	Bitrix\Currency\CurrencyManager,
	Bitrix\Sale\Order,
	Bitrix\Sale\Registry,
	Bitrix\Sale\Basket,
	Bitrix\Sale\Delivery,
	Bitrix\Sale\PaySystem;

use Api\Models\User;
use Api\DomainServices\Orders\OrdersService;

Bitrix\Main\Loader::includeModule("sale");
Bitrix\Main\Loader::includeModule("catalog");
Bitrix\Main\Loader::includeModule("iblock");

global $USER;

if ($_REQUEST["ACTION"] == "FIND_CONTRACT") {
	if ($_REQUEST["CONTRACT_NUMBER"]) {

		if (!preg_match('/^\d+\/\d+/', $_REQUEST["CONTRACT_NUMBER"])) {
			exit(json_encode([
				'success' => 'N',
				'error' => 'Номер договора должен быть в формате 8/3538',
				'code' => 6,
			]));
		}

		$contract = \CIBlockElement::GetList([], [
			"IBLOCK_ID" => CONTRACTS_IBLOCK,
			"%PROPERTY_NUMBER" => $_REQUEST["CONTRACT_NUMBER"],
		], false, false, ["ID", 'IBLOCK_ID', 'PROPERTY_CONTRACT_GUID', 'PROPERTY_USER'])->Fetch();

		if ($contract) {

			$user = CUser::GetByID($contract['PROPERTY_USER_VALUE'])->Fetch();

			$invoice = \CIBlockElement::GetList(['created_date' => 'desc'], [
				"IBLOCK_ID" => INVOICES_IBLOCK,
				"PROPERTY_CONTRACT_GUID" => $contract['PROPERTY_CONTRACT_GUID_VALUE'],
				'!PROPERTY_STATUS' => INVOICE_STATUS_PAID_ID,
			], false, false, ['ID', 'PROPERTY_TotalInvoiceAmount'])->Fetch();

			$response = [
				'success' => 'Y',
				'name' => $user['NAME'],
				'last_name' => $user['LAST_NAME'],
				'phone' => strlen($user['PERSONAL_PHONE']) > 7 ? (substr($user['PERSONAL_PHONE'], 0, 3) . ' ... ' . substr($user['PERSONAL_PHONE'], -3)) : ('...' . substr($user['PERSONAL_PHONE'], -3)),
				'last_invoice_id' => $invoice['ID'] ?? '',
				'last_invoice_sum' => str_replace(' ', '', $invoice['PROPERTY_TOTALINVOICEAMOUNT_VALUE']) ?? 0,
				'contract_id' => $contract['ID'],
				'contract_guid' => $contract['PROPERTY_CONTRACT_GUID_VALUE'],
			];
		} else {
			$response = [
				'success' => 'N',
				'error' => 'Договор с таким номером не найден',
				'code' => 5,
			];
		}
	} else {
		$response = [
			'success' => 'N',
			'error' => 'Номер договора не указан',
			'code' => 1,
		];
	}

	exit(json_encode($response));
}

if ($_REQUEST["ACTION"] == "ADD_BALANCE") {
	if (strlen($_REQUEST["PRODUCT_ID"]) > 0) {

		if (!$USER->GetID() && strlen($_REQUEST["CONTRACT_NUMBER"]) > 0) {

			// 5/22676
			if (!preg_match('/^\d+\/\d+/', $_REQUEST["CONTRACT_NUMBER"])) {
				exit(json_encode([
					'success' => 'N',
					'error' => 'Номер договора должен быть в формате 8/3538',
					'code' => 6,
				]));
			}

			$contract = \CIBlockElement::GetList([], [
				"IBLOCK_ID" => CONTRACTS_IBLOCK,
				"%PROPERTY_NUMBER" => $_REQUEST["CONTRACT_NUMBER"]
			], false, false, ["ID", 'IBLOCK_ID', 'PROPERTY_CONTRACT_GUID', 'PROPERTY_USER'])->Fetch();

			if ($contract) {
				$_REQUEST["CONTRACT_GUID"] = $contract['PROPERTY_CONTRACT_GUID_VALUE'];
				$_REQUEST["CONTRACT_ID"] = $contract['ID'];
				$user = new User(['ID' => $contract['PROPERTY_USER_VALUE']]);
			} else {
				exit(json_encode([
					'success' => 'N',
					'error' => 'Договор с таким номером не найден',
					'code' => 5,
				]));
			}
		} else {
			$user = new User(['ID' => $USER->GetID()]);
		}

		if ($user) {
			//очищаем корзину
			CSaleBasket::DeleteAll(CSaleBasket::GetBasketUserID());

			//добавляем в корзину товар
			$basket = \Bitrix\Sale\Basket::loadItemsForFUser(\Bitrix\Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());
			$productId = intval($_REQUEST["PRODUCT_ID"]);
			$quantity = 1;
			$item = $basket->createItem("catalog", $productId);

			$arSetFields = array(
				"QUANTITY" => $quantity,
				"CURRENCY" => Bitrix\Currency\CurrencyManager::getBaseCurrency(),
				"LID" => Bitrix\Main\Context::getCurrent()->getSite(),
				"PRODUCT_PROVIDER_CLASS" => 'CCatalogProductProvider',
                        "VAT_INCLUDED" => "Y",

			);
			if (strlen($_REQUEST["CUSTOM_SUM"]) > 0) {
				$arSetFields["CUSTOM_PRICE"] = "Y";
				$arSetFields["PRICE"] = $_REQUEST["CUSTOM_SUM"];
			}
			$arForPropCollection = [];
			if (strlen($_REQUEST["CONTRACT_GUID"]) > 0) {
				//записываем свойства

				$basketPropertyCollection = $item->getPropertyCollection();
				$arForPropCollection = array(
					array(
						"NAME" => "Номер договора",
						"CODE" => "CONTRACT_NUMBER",
						"VALUE" => $_REQUEST["CONTRACT_NUMBER"],
						"SORT" => 100,
					),
					array(
						"NAME" => "ID договора",
						"CODE" => "CONTRACT_ID",
						"VALUE" => $_REQUEST["CONTRACT_ID"],
						"SORT" => 110,
					),
					array(
						"NAME" => "GUID договора",
						"CODE" => "CONTRACT_GUID",
						"VALUE" => $_REQUEST["CONTRACT_GUID"],
						"SORT" => 120,
					)
				);

				//ищем склад по боксу в договоре
				if (strlen($_REQUEST["BOX_ID"]) > 0) {
					$db_sections = CIBlockElement::GetElementGroups($_REQUEST["BOX_ID"], true);
					if ($arSect = $db_sections->Fetch()) {
						$arForPropCollection[] = array(
							"NAME" => "Внешний код склада",
							"CODE" => "SKLAD_XML_ID",
							"VALUE" => $arSect["XML_ID"],
							"SORT" => 130,
						);
					}
				}

				$basketPropertyCollection->setProperty($arForPropCollection);
			}

			$item->setFields($arSetFields);

			$orderService = new OrdersService();
			$orderId = $orderService->createOrder($user, $basket, 3);


			$registry = Registry::getInstance(Registry::REGISTRY_TYPE_ORDER);
			$orderClassName = $registry->getOrderClassName();

			if ($order = $orderClassName::loadByAccountNumber($orderId)) {
				$paymentCollection = $order->getPaymentCollection();
				$payment = $paymentCollection[0];

				if (!$payment->isPaid()) {
					$paySystemService = PaySystem\Manager::getObjectById($payment->getPaymentSystemId());
					if (!empty($paySystemService)) {
						$initResult = $paySystemService->initiatePay($payment, null, PaySystem\BaseServiceHandler::STRING);
						if ($initResult->isSuccess()) {
							$arPaySysAction['BUFFERED_OUTPUT'] = $initResult->getTemplate();
							$arPaySysAction['PAYMENT_URL'] = $initResult->getPaymentUrl();

							exit(json_encode([
								'success' => 'Y',
								'url' => $arPaySysAction['PAYMENT_URL'],
							]));
						} else {
							exit(json_encode([
								'success' => 'N',
								'error' => 'При оплате заказа произошла ошибка',
								'code' => 1,
							]));
						}
					} else {
						exit(json_encode([
							'success' => 'N',
							'error' => 'При оплате заказа произошла ошибка',
							'code' => 2,
						]));
					}
				} else {
					exit(json_encode([
						'success' => 'N',
						'error' => 'Заказ уже оплачен',
						'code' => 3,
					]));
				}
			} else {
				exit(json_encode([
					'success' => 'N',
					'error' => 'Заказ не найден',
					'code' => 4,
				]));
			}
		} else {
			exit(json_encode([
				'success' => 'N',
				'error' => 'Пользователь не найден',
				'code' => 4,
			]));
		}
	}
}
?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_after.php"); ?>