<?php

use Enum\Helper;
use Api\Models\User;
use Api\DomainServices\Orders\OrdersService;
use Bitrix\Main\Context;
use Bitrix\Sale\Registry;
use Bitrix\Sale\PaySystem;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");


class Exchange
{
	public static function GetKey()
	{
		return md5(date('d.m.Y') . 'alfasklad');
	}

	public $file_recive_deal = '/logs/resiveDealFromB24_new.txt';
	public $file_recive_contact = '/logs/resiveContactFromB24_new.txt';
	public $file_recive_contract = '/logs/resiveContractFromB24_new.txt';
	//public $file_recive_invoice = '/logs/resiveInvoiceFromB24.txt';
	public $file_recive_invoice = '/logs/resiveInvoiceFromB24_new.txt';
	public $file_DeleteOrder = '/logs/deleteOrder.txt';
	public $file_DeleteInvoice = '/logs/deleteInvoice.txt';
	public $file_DeleteContract = '/logs/deleteContract.txt';
	public $file_DeleteContact = '/logs/deleteContact.txt';
	public $file_autopayment = '/logs/autopayments.txt';

	public $key;

	public function start()
	{
		$this->key = $this->GetKey();
		$keyRequest = $_REQUEST['key'];

		if ($keyRequest == $this->key) {

			$action = htmlspecialchars($_REQUEST['action']);

			file_put_contents(__DIR__ . '/rest_log.txt', date("Y-m-d H:i:s") . "\n", FILE_APPEND);
			file_put_contents(__DIR__ . '/rest_log.txt', print_r($_REQUEST, true), FILE_APPEND);



			switch ($action) {
				case 'addOrder':
					$data = $_REQUEST;
					$this->AddOrder($data);
					break;
				case 'addcontact':
					$arrCompany = $_REQUEST['dataCompany'];
					$this->AddContact($arrCompany);
					break;
				case 'updateContact':
					//AddMessage2Log(print_r($_REQUEST['dataCompany'], true), "updateContact");
					//AddMessage2Log(print_r($_REQUEST, true), "updateContact");
					$arrCompany = $_REQUEST['dataCompany'];
					$this->UpdateContact($arrCompany);
					break;
				case 'addContract':
					$data = $_REQUEST;
					$this->AddContract($data);
					break;
				case 'addInvoice':
					$data = $_REQUEST;
					$this->AddInvoice($data);
					break;
				case 'DeleteOrder':
					$data = $_REQUEST;
					$this->DeleteOrder($data);
					break;
				case 'DeleteInvoice':
					$data = $_REQUEST;
					$this->DeleteInvoice($data);
					break;
				case 'DeleteContract':
					$data = $_REQUEST;
					$this->DeleteContract($data);
					break;
				case 'DeleteContact':
					$data = $_REQUEST;
					$this->DeleteContact($data);
					break;
			}
		}
	}


	function UpdateContact($arrCompany)
	{
		$USER_ID = (int)$arrCompany['ID_SITE_COMPANY'];
		if ($USER_ID > 0 && isset($arrCompany['COMPANY_BALANCE'])) {
			$user = new \CUser;
			$userBalance = -1 * $arrCompany['COMPANY_BALANCE'];

			$updateFields = [
				'UF_USER_BALANCE' => $userBalance,
			];
			if (isset($arrCompany['COMPANY_PENALTIES'])) {
				$updateFields['UF_PENALTIES'] = $arrCompany['COMPANY_PENALTIES'];
			}
			$user->Update($USER_ID, $updateFields);
			//AddMessage2Log(print_r($user, true), "updateContact");
		}
	}


	//создание контакта который прилетает из Б24 из сделки
	function AddContact($arrCompany)
	{
		Helper::log($this->file_recive_contact, $arrCompany);

		\Bitrix\Main\Loader::includeModule("sale");

		$email = $arrCompany['EMAIL'];
		$phone = $arrCompany['PHONE'];
		$USER_ID = $arrCompany['ID_SITE_COMPANY'];

		/*
        if ($arrCompany['PROFILE_TYPE'] == 1) {
			if((strlen($arrCompany['PASSPORT_SERIES']) > 0) and (strlen($arrCompany['PASSPORT_NUMBER']) > 0)) {
				$PROFILE = $this->getUserByPassport($arrCompany['PASSPORT_SERIES'], $arrCompany['PASSPORT_NUMBER']);
				$PROFILE_ID = $PROFILE['PROFILE_ID'];
				$USER_ID = $PROFILE['USER_ID'];
				Helper::log($this->file_recive_contact, 'Пользователь с паспортом '.$arrCompany['PASSPORT_SERIES'].' '.$arrCompany['PASSPORT_NUMBER'].' уже существует, берем его ID = ' . $USER_ID);
			}
        }
        if ($arrCompany['PROFILE_TYPE'] == 2) {
			if((strlen($arrCompany['INN']) > 0) and (strlen($arrCompany['KPP']) > 0)) {
				$PROFILE = $this->getUserByInnAndKpp($arrCompany['INN'], $arrCompany['KPP']);
				$PROFILE_ID = $PROFILE['PROFILE_ID'];
				$USER_ID = $PROFILE['USER_ID'];
			}
        }
		*/

		if (!$USER_ID) {
			//ищем по email
			$PROFILE = $this->getUserByEmail($email);
			$PROFILE_ID = $PROFILE['PROFILE_ID'];
			$USER_ID = $PROFILE['USER_ID'];
			Helper::log($this->file_recive_contact, 'Пользователь с email ' . $email . ' уже существует, берем его ID = ' . $USER_ID);
		}

		/*
		if(!$USER_ID) {
			//пробуем найти пользователя по телефону в профиле покупателя
			$PROFILE = $this->getUserByPhone($phone);
			$PROFILE_ID = $PROFILE['PROFILE_ID'];
			$USER_ID = $PROFILE['USER_ID'];	
			Helper::log($this->file_recive_contact, 'Пользователь с телефоном ' . $phone . ' уже существует, берем его ID = ' . $USER_ID);
		}
		*/

		if (!$USER_ID) {
			//если не известен ID пользователя то создаем нового + профиль

			//сначала проверяем, нет ли других пользователей с таким же email
			$similarEmailUsers = 0;
			$b = "";
			$o = "";
			$res = \CUser::GetList($b, $o, array(
				"=EMAIL" => $email
			), array(
				"FIELDS" => array("ID")
			));
			while ($ar = $res->Fetch()) {
				$similarEmailUsers++;
			}
			if ($similarEmailUsers > 0) {
				$login = $email . "_" . ($similarEmailUsers + 1);
			} else {
				$login = $email;
			}

			//преобразование баланса пользователя
			/*$userBalance = 0;
			if(strlen($arrCompany["COMPANY_BALANCE"]) > 0) {
				$arrCompany["COMPANY_BALANCE"] = intval($arrCompany["COMPANY_BALANCE"]);
				$userBalance = -1 * $arrCompany["COMPANY_BALANCE"];
			}*/

			/*
			if (strpos($arrCompany["COMPANY_BALANCE"], "+") !== false) {
				//в балансе есть "+"
				$userBalance = str_replace("+", "-", $arrCompany["COMPANY_BALANCE"]);
			}
			if (strpos($arrCompany["COMPANY_BALANCE"], "-") !== false) {
				//в балансе есть "-"
				$userBalance = str_replace("-", "", $arrCompany["COMPANY_BALANCE"]);
			}
			*/


			$user = new \CUser;
			$arFields = array(
				"NAME" => $arrCompany['NAME'],
				"LAST_NAME" => $arrCompany['LAST_NAME'],
				"SECOND_NAME" => $arrCompany['SECOND_NAME'],
				"PERSONAL_BIRTHDAY" => $arrCompany['DATE_BIRTH'],
				"EMAIL" => $email,
				"LOGIN" => $login,
				"LID" => "ru",
				"ACTIVE" => "Y",
				"GROUP_ID" => array(3, 4),
				"PASSWORD" => "123456",
				"CONFIRM_PASSWORD" => "123456",
				//"UF_USER_BALANCE" => $userBalance
			);

			$USER_ID = $user->Add($arFields);

			if ($USER_ID > 0) {
				Helper::log($this->file_recive_contact, 'Создали пользователя ' . $USER_ID);
			} else {
				Helper::log($this->file_recive_contact, 'Контакт не создан!!! ' . $user->LAST_ERROR);
			}


			//создаём профиль
			//PERSON_TYPE_ID - идентификатор типа плательщика, для которого создаётся профиль
			$arProfileFields = array(
				"NAME" => "Профиль покупателя.",
				"USER_ID" => $USER_ID,
				"PERSON_TYPE_ID" => $arrCompany['PROFILE_TYPE']
			);
			$PROFILE_ID = \CSaleOrderUserProps::Add($arProfileFields);

			if ($PROFILE_ID > 0) {
				Helper::log($this->file_recive_contact, 'Создали профиль ' . $PROFILE_ID);
			} else {
				Helper::log($this->file_recive_contact, 'Профиль не создан!!!');
			}
			//если профиль создан
			if ($PROFILE_ID) {
				//физ лицо
				if ($arrCompany['PROFILE_TYPE'] == 1) {
					//формируем массив свойств
					$PROPS = array(
						array(
							//LAST_NAME
							"USER_PROPS_ID" => $PROFILE_ID,
							"ORDER_PROPS_ID" => 1,
							"NAME" => "Фамилия по паспорту (для заключения договора)",
							"VALUE" => $arrCompany['LAST_NAME']
						),
						array(
							//NAME
							"USER_PROPS_ID" => $PROFILE_ID,
							"ORDER_PROPS_ID" => 2,
							"NAME" => "Имя по паспорту (для заключения договора)",
							"VALUE" => $arrCompany['NAME']
						),
						array(
							//SECOND_NAME
							"USER_PROPS_ID" => $PROFILE_ID,
							"ORDER_PROPS_ID" => 3,
							"NAME" => "Отчество по паспорту (для заключения договора)",
							"VALUE" => $arrCompany['SECOND_NAME']
						),
						array(
							//BIRTHDAY
							"USER_PROPS_ID" => $PROFILE_ID,
							"ORDER_PROPS_ID" => 4,
							"NAME" => "Дата рождения",
							"VALUE" => $arrCompany['DATE_BIRTH']
						),
						array(
							//ADDR
							"USER_PROPS_ID" => $PROFILE_ID,
							"ORDER_PROPS_ID" => 5,
							"NAME" => "Адрес регистрации",
							"VALUE" => $arrCompany['REQUSITES']['ADDR'][4]['ADDRESS_1']
						),
						array(
							//ADDR
							"USER_PROPS_ID" => $PROFILE_ID,
							"ORDER_PROPS_ID" => 6,
							"NAME" => "Фактический адрес",
							"VALUE" => $arrCompany['REQUSITES']['ADDR'][1]['ADDRESS_1']
						),
						array(
							//PHONE
							"USER_PROPS_ID" => $PROFILE_ID,
							"ORDER_PROPS_ID" => 7,
							"NAME" => "Телефон",
							"VALUE" => $phone
						),
						array(
							//EMAIL
							"USER_PROPS_ID" => $PROFILE_ID,
							"ORDER_PROPS_ID" => 8,
							"NAME" => "E-mail",
							"VALUE" => $email
						),
						array(
							//PASSPORT_SERIES
							"USER_PROPS_ID" => $PROFILE_ID,
							"ORDER_PROPS_ID" => 9,
							"NAME" => "Серия паспорта (для заключения договора)",
							"VALUE" => $arrCompany['PASSPORT_SERIES']
						),
						array(
							//PASSPORT_NUMBER
							"USER_PROPS_ID" => $PROFILE_ID,
							"ORDER_PROPS_ID" => 10,
							"NAME" => "Номер паспорта (для заключения договора)",
							"VALUE" => $arrCompany['PASSPORT_NUMBER']
						)
					);
				}
				//юр лицо
				if ($arrCompany['PROFILE_TYPE'] == 2) {
					//формируем массив свойств
					$PROPS = array(
						array(
							//	INN
							"USER_PROPS_ID" => $PROFILE_ID,
							"ORDER_PROPS_ID" => 11,
							"NAME" => "ИНН",
							"VALUE" => $arrCompany['INN']
						),
						array(
							//	KPP
							"USER_PROPS_ID" => $PROFILE_ID,
							"ORDER_PROPS_ID" => 12,
							"NAME" => "КПП",
							"VALUE" => $arrCompany['KPP']
						),
						array(
							//	NAME
							"USER_PROPS_ID" => $PROFILE_ID,
							"ORDER_PROPS_ID" => 13,
							"NAME" => "Наименование компании",
							"VALUE" => $arrCompany['TITLE']
						),
						array(
							//	EMAIL
							"USER_PROPS_ID" => $PROFILE_ID,
							"ORDER_PROPS_ID" => 14,
							"NAME" => "E-mail",
							"VALUE" => $arrCompany['EMAIL']
						),
						array(
							//	PHONE
							"USER_PROPS_ID" => $PROFILE_ID,
							"ORDER_PROPS_ID" => 15,
							"NAME" => "Телефон",
							"VALUE" => $arrCompany['PHONE']
						)
						/*array(
                            //	FIO_IN_COMPANY
                            "USER_PROPS_ID" => $PROFILE_ID,
                            "ORDER_PROPS_ID" => 15,
                            "NAME" => "ФИО контактного лица",
                            "VALUE" => $arrCompany['TITLE']
                        ),*/
					);
				}
				//добавляем значения свойств к созданному ранее профилю
				foreach ($PROPS as $prop)
					\CSaleOrderUserPropsValue::Add($prop);
			}
		} else {
			//если известен ID пользователя сайта

			//ищем $PROFILE_ID
			$db_sales = CSaleOrderUserProps::GetList(
				array("USER_ID" => "ASC"),
				array("USER_ID" => $USER_ID),
				false,
				false,
				array("*")
			);
			if ($arSaleProfile = $db_sales->Fetch()) {
				$PROFILE_ID = $arSaleProfile["ID"];
			}
			if (strlen($PROFILE_ID) == 0) {
				$arProfileFields = array(
					"NAME" => "Профиль покупателя.",
					"USER_ID" => $USER_ID,
					"PERSON_TYPE_ID" => $arrCompany['PROFILE_TYPE']
				);
				$PROFILE_ID = \CSaleOrderUserProps::Add($arProfileFields);
			}

			if ($arrCompany['PROFILE_TYPE'] == 1) {
				//формируем массив свойств
				$PROPS = array(
					array(
						//LAST_NAME
						"USER_PROPS_ID" => $PROFILE_ID,
						"ORDER_PROPS_ID" => 1,
						"NAME" => "Фамилия по паспорту (для заключения договора)",
						"VALUE" => $arrCompany['LAST_NAME']
					),
					array(
						//NAME
						"USER_PROPS_ID" => $PROFILE_ID,
						"ORDER_PROPS_ID" => 2,
						"NAME" => "Имя по паспорту (для заключения договора)",
						"VALUE" => $arrCompany['NAME']
					),
					array(
						//SECOND_NAME
						"USER_PROPS_ID" => $PROFILE_ID,
						"ORDER_PROPS_ID" => 3,
						"NAME" => "Отчество по паспорту (для заключения договора)",
						"VALUE" => $arrCompany['SECOND_NAME']
					),
					array(
						//ADDR
						"USER_PROPS_ID" => $PROFILE_ID,
						"ORDER_PROPS_ID" => 5,
						"NAME" => "Адрес регистрации",
						"VALUE" => $arrCompany['REQUSITES']['ADDR'][4]['ADDRESS_1']
					),
					array(
						//ADDR
						"USER_PROPS_ID" => $PROFILE_ID,
						"ORDER_PROPS_ID" => 6,
						"NAME" => "Фактический адрес",
						"VALUE" => $arrCompany['REQUSITES']['ADDR'][1]['ADDRESS_1']
					),
					array(
						//BIRTHDAY
						"USER_PROPS_ID" => $PROFILE_ID,
						"ORDER_PROPS_ID" => 4,
						"NAME" => "Дата рождения",
						"VALUE" => $arrCompany['DATE_BIRTH']
					),
					array(
						//PHONE
						"USER_PROPS_ID" => $PROFILE_ID,
						"ORDER_PROPS_ID" => 7,
						"NAME" => "Телефон",
						"VALUE" => $phone
					),
					array(
						//EMAIL
						"USER_PROPS_ID" => $PROFILE_ID,
						"ORDER_PROPS_ID" => 8,
						"NAME" => "E-mail",
						"VALUE" => $email
					),
					array(
						//PASSPORT_SERIES
						"USER_PROPS_ID" => $PROFILE_ID,
						"ORDER_PROPS_ID" => 9,
						"NAME" => "Серия паспорта (для заключения договора)",
						"VALUE" => $arrCompany['PASSPORT_SERIES']
					),
					array(
						//PASSPORT_NUMBER
						"USER_PROPS_ID" => $PROFILE_ID,
						"ORDER_PROPS_ID" => 10,
						"NAME" => "Номер паспорта (для заключения договора)",
						"VALUE" => $arrCompany['PASSPORT_NUMBER']
					)
				);
			}
			//юр лицо
			if ($arrCompany['PROFILE_TYPE'] == 2) {
				//формируем массив свойств
				$PROPS = array(
					array(
						//	INN
						"USER_PROPS_ID" => $PROFILE_ID,
						"ORDER_PROPS_ID" => 11,
						"NAME" => "ИНН",
						"VALUE" => $arrCompany['INN']
					),
					array(
						//	KPP
						"USER_PROPS_ID" => $PROFILE_ID,
						"ORDER_PROPS_ID" => 12,
						"NAME" => "КПП",
						"VALUE" => $arrCompany['KPP']
					),
					array(
						//	NAME
						"USER_PROPS_ID" => $PROFILE_ID,
						"ORDER_PROPS_ID" => 13,
						"NAME" => "Наименование компании",
						"VALUE" => $arrCompany['TITLE']
					),
					array(
						//	EMAIL
						"USER_PROPS_ID" => $PROFILE_ID,
						"ORDER_PROPS_ID" => 14,
						"NAME" => "E-mail",
						"VALUE" => $arrCompany['EMAIL']
					),
					array(
						//	PHONE
						"USER_PROPS_ID" => $PROFILE_ID,
						"ORDER_PROPS_ID" => 15,
						"NAME" => "Телефон",
						"VALUE" => $arrCompany['PHONE']
					)
					/*array(
                        //	FIO_IN_COMPANY
                        "USER_PROPS_ID" => $PROFILE_ID,
                        "ORDER_PROPS_ID" => 15,
                        "NAME" => "ФИО контактного лица",
                        "VALUE" => $arrCompany['TITLE']
                    ),*/
				);
			}

			foreach ($PROPS as $prop) {
				$errors = [];
				$properties[$prop['ORDER_PROPS_ID']] = $prop['VALUE'];
			}

			\CSaleOrderUserProps::DoSaveUserProfile(
				$USER_ID,
				$PROFILE_ID,
				'Профиль',
				$arrCompany['PROFILE_TYPE'],
				$properties,
				$errors
			);

			Helper::log($this->file_recive_contact, 'Обновление профиля покупателя с ID=' . $PROFILE_ID);


			//обновляем ФИО пользователя и баланс
			$user = new \CUser;
			$arFields = array(
				"NAME" => $arrCompany['NAME'],
				"LAST_NAME" => $arrCompany['LAST_NAME'],
				"SECOND_NAME" => $arrCompany['SECOND_NAME'],
				"PERSONAL_BIRTHDAY" => $arrCompany['DATE_BIRTH']
			);

			//преобразование баланса пользователя			
			/*if(strlen($arrCompany["COMPANY_BALANCE"]) > 0) {
				
				$arrCompany["COMPANY_BALANCE"] = intval($arrCompany["COMPANY_BALANCE"]);
				$userBalance = -1 * $arrCompany["COMPANY_BALANCE"];							
				
				$arFields["UF_USER_BALANCE"] = $userBalance;
			}*/

			$user->Update($USER_ID, $arFields);
			Helper::log($this->file_recive_contact, 'Обновление профиля пользователя с ID=' . $USER_ID);
			Helper::log($this->file_recive_contact, $properties);
		}

		Helper::log($this->file_recive_contact, '###############################################');

		return $USER_ID;
	}

	//создание заказа который прилетает из Б24
	function AddOrder($data)
	{
		\Bitrix\Main\Loader::includeModule("iblock");
		\Bitrix\Main\Loader::includeModule("catalog");
		\Bitrix\Main\Loader::includeModule("sale");

		Helper::log($this->file_recive_deal, '###############################################');
		Helper::log($this->file_recive_deal, $data);
		$id_contact = $this->AddContact($data['dataCompany']);

		if (!$id_contact) {
			Helper::log($this->file_recive_deal, 'Не найден пользователь! Заказ не создан!');
			Helper::log($this->file_recive_deal, $data);
			die();
		}

		$order = $data['order'];
		$person_type_id = $data['dataCompany']['PROFILE_TYPE'];

		$isNewOrder = true;
		$orderID = intval($order["ID Заказа"]);
		if ($orderID > 0) {
			$myOrder = \Bitrix\Sale\Order::load($orderID);
			if (!empty($myOrder)) {
				$isNewOrder = false;
			}
		}

		$products = array();
		foreach ($order['PRODUCTS'] as $product) {
			$product_id = $this->getProductByID($product['XML_ID']);
			if ($product_id) {
				if (strlen($product['PRICE_MAIN']) > 0) {
					$price = floatval($product['PRICE_MAIN']);
				} else {
					$price = floatval($product['PRICE']) * floatval($product['QUANTITY']);
				}
				$products[$product_id] = [
					"NAME" => $product['PRODUCT_NAME'],
					"QUANTITY" => "1",
					"CURRENCY" => Bitrix\Currency\CurrencyManager::getBaseCurrency(),
					"LID" => Bitrix\Main\Context::getCurrent()->getSite(),
					"PRODUCT_PROVIDER_CLASS" => "CCatalogProductProvider",
					"PRICE" => $price,
					"CUSTOM_PRICE" => "Y"
				];
			} else {
				Helper::log($this->file_recive_deal, 'Товар ' . $product['PRODUCT_NAME'] . ' не найден, либо неактивен! Создание заказа невозможно.');
				die();
			}
		}

		if (count($products) > 0) {
			if ($isNewOrder) {
				//добавляем новый заказ
				Helper::log($this->file_recive_deal, 'Создание нового заказа');

				$order = \Bitrix\Sale\Order::create(SITE_ID, $id_contact);
				$order->setPersonTypeId($person_type_id);

				$basket = \Bitrix\Sale\Basket::create(SITE_ID);

				foreach ($products as $prodID => $prod) {
					$item = $basket->createItem("catalog", $prodID);
					$item->setFields($prod);
				}

				$order->setBasket($basket);
				$propertyCollection = $order->getPropertyCollection();
				foreach ($propertyCollection as $propertyItem) {
					if ($propertyItem->getField("CODE") == "IS_ORDER_FROM_CRM") {
						$propertyItem->setValue("Y");
					}
				}

				$result = $order->save();
				$orderID = $order->getId();
				$orderUserId = $order->getUserId();
				if (!$result->isSuccess()) {
					$errors = $result->getErrors();
					Helper::log($this->file_recive_deal, 'Заказ не создан!');
					Helper::log($this->file_recive_deal, $errors);
					die();
				}
			} else {
				//обновляем имеющийся заказ
				Helper::log($this->file_recive_deal, "Обновление заказа с ID=" . $orderID);

				$order = \Bitrix\Sale\Order::load($orderID);
				$basket = $order->getBasket();
				$basketItems = $basket->getBasketItems();
				//очищаем корзину заказа
				foreach ($basketItems as $basketItem) {
					$basket->getItemById($basketItem->getId())->delete();
				}

				//добавляем товары из сделки
				foreach ($products as $prodID => $prod) {
					$item = $basket->createItem("catalog", $prodID);
					$item->setFields($prod);
				}
				$basket->refreshData();
				$result = $order->save();
				$orderUserId = $order->getUserId();
				if (!$result->isSuccess()) {
					$errors = $result->getErrors();
					Helper::log($this->file_recive_deal, 'Заказ не создан!');
					Helper::log($this->file_recive_deal, $errors);
					die();
				}
			}
		}

		$returnData = array("ORDER_ID" => $orderID, "USER_ID" => $id_contact);
		Helper::log($this->file_recive_deal, 'Возвращаем данные в Б24');
		Helper::log($this->file_recive_deal, $returnData);

		echo json_encode($returnData);
	}

	//получить id товара по внешнему коду
	public function getProductByID($external_id, $onlyActive = true)
	{
		$return = false;
		if (!$external_id)
			return false;

		$arFilter = [
			'IBLOCK_ID' => array(40, 48),
			'XML_ID' => $external_id,
		];

		if ($onlyActive) {
			$arFilter["ACTIVE"] = "Y";
		}

		$arSelect = [
			'ID',
			'IBLOCK_ID',
			'XML_ID',
		];

		$res = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
		if ($item = $res->Fetch()) {
			$return = $item['ID'];
		}

		return $return;
	}

	//получить пользователя по почте
	public function getUserByEmail($email)
	{
		$ID = false;
		$PROFILE_ID = false;
		$result = false;
		if (strlen($email) > 0) {
			$b = "";
			$o = "";
			$res = \CUser::GetList($b, $o, array(
				"=EMAIL" => $email,
				"EXTERNAL_AUTH_ID" => ''
			), array(
				"FIELDS" => array("ID")
			));
			while ($ar = $res->Fetch()) {
				if (intval($ar["ID"]) !== $ID) {
					$ID = $ar["ID"];
					$result = array();
					$result["USER_ID"] = $ID;
					//ищем профиль покупателя
					\Bitrix\Main\Loader::includeModule("sale");
					$db_sales = CSaleOrderUserProps::GetList(
						array("USER_ID" => "ASC"),
						array("USER_ID" => $ID),
						false,
						false,
						array("*")
					);
					if ($arSaleProfile = $db_sales->Fetch()) {
						$PROFILE_ID = $arSaleProfile["ID"];
						$result["PROFILE_ID"] = $PROFILE_ID;
					}
				}
			}
		}


		return $result;
	}

	//получить пользователя по серии и номеру паспорта
	public function getUserByPassport($ser_passport, $num_passport)
	{
		global $DB;

		$strSql =
			"SELECT * " .
			"FROM b_sale_user_props_value UP JOIN b_sale_user_props U ON UP.USER_PROPS_ID=U.ID " .
			"WHERE UP.ORDER_PROPS_ID = 9 AND VALUE = '" . $ser_passport . "' AND UP.USER_PROPS_ID=(SELECT UP2.USER_PROPS_ID FROM b_sale_user_props_value UP2 WHERE UP2.ORDER_PROPS_ID = 10 AND VALUE = '" . $num_passport . "' AND UP.USER_PROPS_ID=UP2.USER_PROPS_ID)";


		$dbRes = $DB->Query($strSql, false, "File: " . __FILE__ . "<br>Line: " . __LINE__);
		if ($arRes = $dbRes->Fetch()) {
			return [
				'PROFILE_ID' => $arRes['USER_PROPS_ID'],
				'USER_ID' => $arRes['USER_ID']
			];
		}

		return false;
	}

	//получить пользователя по ИНН и КПП
	public function getUserByInnAndKpp($inn, $kpp)
	{
		global $DB;

		$strSql =
			"SELECT * " .
			"FROM b_sale_user_props_value UP JOIN b_sale_user_props U ON UP.USER_PROPS_ID=U.ID " .
			"WHERE UP.ORDER_PROPS_ID = 11 AND VALUE = '" . $inn . "' AND UP.USER_PROPS_ID=(SELECT UP2.USER_PROPS_ID FROM b_sale_user_props_value UP2 WHERE UP2.ORDER_PROPS_ID = 12 AND VALUE = '" . $kpp . "' AND UP.USER_PROPS_ID=UP2.USER_PROPS_ID)";

		$dbRes = $DB->Query($strSql);
		if ($arRes = $dbRes->Fetch()) {
			return [
				'PROFILE_ID' => $arRes['USER_PROPS_ID'],
				'USER_ID' => $arRes['USER_ID']
			];
		}
		return false;
	}

	//получить пользователя по номеру телефона
	public function getUserByPhone($phone)
	{
		global $DB;
		$result = false;

		if (strlen($phone) > 5) {
			//физ. л.
			$strSql =
				"SELECT * " .
				"FROM b_sale_user_props_value UP JOIN b_sale_user_props U ON UP.USER_PROPS_ID=U.ID " .
				"WHERE UP.ORDER_PROPS_ID = 7 AND VALUE = '" . $phone . "'";

			$dbRes = $DB->Query($strSql);
			if ($arRes = $dbRes->Fetch()) {
				$result = array(
					'PROFILE_ID' => $arRes['USER_PROPS_ID'],
					'USER_ID' => $arRes['USER_ID']
				);
			}

			//юр. л.
			if (!$result) {
				$strSql =
					"SELECT * " .
					"FROM b_sale_user_props_value UP JOIN b_sale_user_props U ON UP.USER_PROPS_ID=U.ID " .
					"WHERE UP.ORDER_PROPS_ID = 15 AND VALUE = '" . $phone . "'";

				$dbRes = $DB->Query($strSql);
				if ($arRes = $dbRes->Fetch()) {
					$result = array(
						'PROFILE_ID' => $arRes['USER_PROPS_ID'],
						'USER_ID' => $arRes['USER_ID']
					);
				}
			}
		}

		return $result;
	}


	//создание/обновление договора, прилетевшего из Б24
	function AddContract($data)
	{
		Helper::log($this->file_recive_contract, $data);

		\Bitrix\Main\Loader::includeModule("iblock");

		$dataDoc = $data["dataDoc"];

		//AddMessage2Log(print_r($dataDoc, true), "testInventory");

		$USER_ID = $this->AddContact($dataDoc["COMPANY"]);

		$error = false;
		if (strlen($USER_ID) == 0) {
			$error = true;
			Helper::log($this->file_recive_contract, "Пользователь неизвестен, договор не добавлен!");
		}
		if (strlen($dataDoc["VNESHNIY_KOD_BOKSA"]) == 0) {
			$error = true;
			Helper::log($this->file_recive_contract, "Внешний код бокса неизвестен, договор не добавлен!");
		}
		if (strlen($dataDoc["GUID_CONTRACT"]) == 0) {
			$error = true;
			Helper::log($this->file_recive_contract, "GUID договора неизвестен, договор не добавлен!");
		}

		if (!$error) {
			$box_id = $this->getProductByID($dataDoc["VNESHNIY_KOD_BOKSA"], false);
			if ($box_id) {
				//формируем массив с данными для записи
				$contractStatusId = "";
				if ($dataDoc["STATUS_ID"] == "112") {
					$contractStatusId = "352";
				} elseif ($dataDoc["STATUS_ID"] == "113") {
					$contractStatusId = "353";
				}
				$arSaveData = array(
					"IBLOCK_ID"         => 52,
					"IBLOCK_SECTION_ID" => false,
					"PROPERTY_VALUES"   => array(
						"BOX"                => $box_id,
						"USER"               => $USER_ID,
						"STATUS"             => $contractStatusId,
						"NUMBER"             => $dataDoc["NAME"],
						"DATE_CREATE"        => $dataDoc["DATA_DOGOVORA"],
						"PAID_DATE_TO"       => $dataDoc["DATA_OKONCHANIYA_OPLATY_DOGOVORA"],
						"BALANCE"            => explode("|", $dataDoc["BALANS_PO_DOGOVORU"])[0],
						"COUNTERAGENT_GUID"  => $dataDoc["GUID_COMPANY"],
						"CONTRACT_GUID"      => $dataDoc["GUID_CONTRACT"],
						"PERIODIC"           => $dataDoc["PERIODIC"],
						"PAYMENT_PER_PERIOD" => $dataDoc["PAYMENT_PER_PERIOD"],
						"DEBTS"              => $dataDoc["DEBTS"],
					),
					"NAME"              => $dataDoc["NAME"],
					"ACTIVE"            => "Y"
				);



				//ищем, есть ли в БД договор с таким GUID_CONTRACT
				$contract_id = $this->getContractByGuid($dataDoc["GUID_CONTRACT"]);

				if ($contract_id) {
					//обновляем договор
					$el = new CIBlockElement;
					if ($res = $el->Update($contract_id, $arSaveData)) {
						Helper::log($this->file_recive_contract, "Обновлен договор с ID=" . $contract_id);
					} else {
						Helper::log($this->file_recive_contract, "Не удалось обновить договор! " . $el->LAST_ERROR);
					}
				} else {
					//добавляем договор
					$el = new CIBlockElement;
					if ($contract_id = $el->Add($arSaveData)) {
						Helper::log($this->file_recive_contract, "Добавлен новый договор с ID=" . $contract_id);
					} else {
						Helper::log($this->file_recive_contract, "Не удалось добавить договор! " . $el->LAST_ERROR);
					}
				}

				// INVENTORY ###################################################
				if (is_countable($dataDoc["INVENTORY"]) && count($dataDoc["INVENTORY"]) > 0) {

					$arSaveDataInvProp = array(
						"USER" => $USER_ID,
						"CRM_FILES" => $dataDoc["INVENTORY"],
						"CONTRACT" => $contract_id
					);
					$arSaveDataInv = array(
						"IBLOCK_ID" => 58,
						"IBLOCK_SECTION_ID" => false,
						"PROPERTY_VALUES" => $arSaveDataInvProp,
						"NAME" => 'Опись к: ' . $dataDoc["NAME"],
						"ACTIVE" => "Y"
					);
					$inventory_id = $this->getInventoryByContract($contract_id);
					$inv = new CIBlockElement;
					if ($inventory_id) {
						$resInventory = $inv->SetPropertyValuesEx($inventory_id, 58, $arSaveDataInvProp);
					} else {
						$inventory_id = $inv->Add($arSaveDataInv);
					}
				}
				//

				//возвращаем данные
				$returnData = array("CONTRACT_ID" => $contract_id, "USER_ID" => $USER_ID);
				Helper::log($this->file_recive_contract, 'Возвращаем данные в Б24');
				Helper::log($this->file_recive_contract, $returnData);
				echo json_encode($returnData);
			} else {
				Helper::log($this->file_recive_contract, "Бокс с таким внешним кодом не найден, либо не активен! Договор не добавлен.");
			}
		}

		Helper::log($this->file_recive_contract, "######################################");
	}

	//получить ID договора по его GUID
	public function getContractByGuid($guid)
	{
		$return = false;
		if (!$guid)
			return false;

		$arFilter = [
			'IBLOCK_ID' => 52,
			'PROPERTY_CONTRACT_GUID' => $guid,
		];

		$arSelect = [
			'ID',
			'IBLOCK_ID'
		];

		$res = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
		if ($item = $res->Fetch()) {
			$return = $item['ID'];
		}

		return $return;
	}




	//получить ID описи по договору
	public function getInventoryByContract($contract_id)
	{
		$return = false;
		if (!$contract_id)
			return false;

		$arFilter = [
			'IBLOCK_ID' => 58,
			'PROPERTY_CONTRACT' => $contract_id,
		];

		$arSelect = [
			'ID',
			'IBLOCK_ID'
		];

		$res = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
		if ($item = $res->Fetch()) {
			$return = $item['ID'];
		}

		return $return;
	}


	//создание/обновление счета, прилетевшего из Б24
	function AddInvoice($data)
	{
		\Bitrix\Main\Loader::includeModule("iblock");
		\Bitrix\Main\Loader::includeModule("catalog");
		\Bitrix\Main\Loader::includeModule("sale");

		Helper::log($this->file_recive_invoice, $data);

		$dataDoc = $data["dataDoc"];

		$USER_ID = $this->AddContact($dataDoc["COMPANY"]);

		$error = false;
		if (strlen($USER_ID) == 0) {
			$error = true;
			Helper::log($this->file_recive_invoice, "Пользователь неизвестен, счет не добавлен!");
		}
		if (strlen($dataDoc["GUID_INVOICE"]) == 0) {
			$error = true;
			Helper::log($this->file_recive_invoice, "GUID счета неизвестен, счет не добавлен!");
		}
		if (strlen($dataDoc["GUID_CONTRACT"]) == 0) {
			$error = true;
			Helper::log($this->file_recive_invoice, "GUID договора неизвестен, счет не добавлен!");
		}

		if (!$error) {
			//формируем массив с данными для записи
			$invoiceStatusId = self::invoiceStatus2Id($dataDoc["STATUS_ID"]);

			$arSaveData = array(
				"IBLOCK_ID" => 53,
				"IBLOCK_SECTION_ID" => false,
				"PROPERTY_VALUES" => array(
					"USER" => $USER_ID,
					"PROFILE_TYPE" => ($dataDoc["COMPANY"]["PROFILE_TYPE"] == "1") ? "398" : "399",
					"INVOICE_GUID" => $dataDoc["GUID_INVOICE"],
					"CONTRACT_GUID" => $dataDoc["GUID_CONTRACT"],
					"STATUS" => $invoiceStatusId,
					"NUMBER" => $dataDoc["ACCOUNT_NUMBER"],
					"DATE_CREATE" => $dataDoc["DATE_CREATE"],
					"CONTRACT_NUMBER" => $dataDoc["NAME_CONTRACT"],
					"DATE_FROM" => $dataDoc["DATE_BEGIN_OF_RENT"],
					"DATE_TO" => $dataDoc["DATE_END_OF_RENT"],
					"TYPE" => ""
				),
				"NAME" => "Счет №" . $dataDoc["ACCOUNT_NUMBER"],
				"PREVIEW_PICTURE" => CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"] . "/upload/images/invoice_pic.png"),
				"ACTIVE" => "Y"
			);

			//ищем, есть ли в БД счет с таким GUID_INVOICE
			$isOK = false;

			$invoice_id = $this->getContractInvoiceByGuid($dataDoc["GUID_CONTRACT"], $dataDoc["GUID_INVOICE"]);
			// $invoice_id = $this->getInvoiceByGuid($dataDoc["GUID_INVOICE"]);	

			if ($invoice_id) {
				// обновляем счет
				$el = new CIBlockElement;
				if ($res = $el->Update($invoice_id, $arSaveData)) {
					$isOK = true;
					Helper::log($this->file_recive_invoice, "Обновлен счет с ID=" . $invoice_id);
				} else {
					Helper::log($this->file_recive_invoice, "Не удалось обновить счет! " . $el->LAST_ERROR);
				}

				$isNewInvoice = false;
			} else {
				$isNewInvoice = true;

				// добавляем счет
				$el = new CIBlockElement;
				if ($invoice_id = $el->Add($arSaveData)) {
					$isOK = true;
					Helper::log($this->file_recive_invoice, "Добавлен новый счет с ID=" . $invoice_id);

					//Отправляем пуш уведомление о новом счёте пользователю
					// Оборачиваем, чтобы не сломать основной скрипт
					try {
						// Если счёт не оплачен, то отправляем по нему пуш
						//AddMessage2Log("Пуш перед создание счёта: " . print_r($dataDoc, true));
						if ($invoiceStatusId == "354") {
							// Получает договор по его GUID
							$contract = \Api\Models\Contract::getByGUID($dataDoc["GUID_CONTRACT"]);
							// Если договор счёта есть в системе, то отправляем пуш
							if (!empty($contract)) {
								$title = 'Новый неоплаченный счет';
								$text = 'У вас новый неоплаченный счет. ' . $contract->getName();
								$data = [
									'type' => \Api\DomainServices\PushesService::TYPE_PUSH_NOT_PAID_DOCUMENT,
									'id' => $contract->getId()
								];
								AddMessage2Log("Пуш после создания счёта: " . print_r($dataDoc, true));
								AddMessage2Log("Пуш для пользователя " . $USER_ID  . " после создания счёта: " . $title . " " . $text);
								\Api\DomainServices\PushesService::sendPushToUser($USER_ID, $title, $text, $data);
								sleep(1);
							}
						}
					} catch (\Exception $e) {
						AddMessage2Log("Ошибка после создания счёта: " . $e->getMessage());
					}
				} else {
					Helper::log($this->file_recive_invoice, "Не удалось добавить счет! " . $el->LAST_ERROR);
				}
			}

			//дополнительные действия по созданному/обновленному счету
			if ($isOK) {
				//Обновляем сумму счета
				if (CCatalogProduct::Add(array("ID" => $invoice_id))) {
					$dbPrice = \Bitrix\Catalog\Model\Price::getList([
						"filter" => array(
							"PRODUCT_ID" => $invoice_id,
							"CATALOG_GROUP_ID" => "1"
						)
					]);
					if ($arPrice = $dbPrice->fetch()) {
						$res = \Bitrix\Catalog\Model\Price::update($arPrice["ID"], array("PRODUCT_ID" => $invoice_id, "CATALOG_GROUP_ID" => "1", "PRICE" => $dataDoc["PRICE"], "CURRENCY" => "RUB"));
						if ($res->isSuccess()) {
							Helper::log($this->file_recive_invoice, "Установлена сумма счета с ID=" . $invoice_id);
						}
					} else {
						$res = \Bitrix\Catalog\Model\Price::add(array("PRODUCT_ID" => $invoice_id, "CATALOG_GROUP_ID" => "1", "PRICE" => $dataDoc["PRICE"], "CURRENCY" => "RUB"));
						if ($res->isSuccess()) {
							Helper::log($this->file_recive_invoice, "Установлена сумма счета с ID=" . $invoice_id);
						}
					}
				}

				//Формируем состав счета (продукты для детализации счета)
				if (count($dataDoc["PRODUCTS"]) > 0) {
					//ищем продукты детализации счета (ИБ 59), которые ранее могли быть привязаны к этому счету, и удаляем их.
					$res = CIBlockElement::GetList(array("ID" => "ASC"), array("IBLOCK_ID" => 59, "PROPERTY_INVOICE" => $invoice_id), false, array(), array("ID", "IBLOCK_ID", "NAME", "PROPERTY_INVOICE"));
					while ($ob = $res->GetNextElement()) {
						$invoiceProductFields = $ob->GetFields();
						CIBlockElement::Delete($invoiceProductFields["ID"]);
					}
					//добавляем новые продукты
					$arNewInvoiceProducts = array();
					foreach ($dataDoc["PRODUCTS"] as $invoiceProduct) {
						$arSaveData = array(
							"IBLOCK_ID" => 59,
							"IBLOCK_SECTION_ID" => false,
							"PROPERTY_VALUES" => array(
								"PRODUCT" => $this->getProductByID($invoiceProduct["PRODUCT_XML_ID"], false),
								"INVOICE" => $invoice_id,
								"PRICE" => $invoiceProduct["PRICE"]
							),
							"NAME" => urldecode($invoiceProduct["PRODUCT_NAME"]),
							"ACTIVE" => "Y"
						);
						if ($invoiceProduct_id = $el->Add($arSaveData)) {
							$arNewInvoiceProducts[] = $invoiceProduct_id;
						}
					}
					//Связываем новые продукты со счетом
					if (!empty($arNewInvoiceProducts)) {
						CIBlockElement::SetPropertyValuesEx($invoice_id, 53, array("PRODUCTS" => $arNewInvoiceProducts));
					}
				}

				// если счёт не оплачен и у пользователя подключён автоплатёж, то проводим списание со счёта
				// перенесли в крон для единовременного списания
				/*
				try 
				{
					if ($isNewInvoice && $invoiceStatusId == 354 && $pmid = userAutopaymentEnabled($USER_ID)) {

						Helper::log($this->file_autopayment, "Начало автоплатежа: " . $USER_ID . ", счёт: {$invoice_id}");

						// Получает договор по его GUID
						if (!isset($contract)) {
							$contract = \Api\Models\Contract::getByGUID($dataDoc["GUID_CONTRACT"]);
						}

						CSaleBasket::DeleteAll(CSaleBasket::GetBasketUserID());

						// создаём корзину добавляем товар
						$basket = \Bitrix\Sale\Basket::loadItemsForFUser($USER_ID, Bitrix\Main\Context::getCurrent()->getSite());
						$item = $basket->createItem("catalog", $invoice_id);

						$item->setFields([
							"QUANTITY" => 1,
							"CURRENCY" => Bitrix\Currency\CurrencyManager::getBaseCurrency(),
							"LID" => Bitrix\Main\Context::getCurrent()->getSite(),
							"PRODUCT_PROVIDER_CLASS" => 'CCatalogProductProvider',
							'CUSTOM_PRICE' => 'Y',
							"PRICE" => floatval($dataDoc["PRICE"]),
						]);

						$basketPropertyCollection = $item->getPropertyCollection();

						$arForPropCollection = [];
						if (strlen($dataDoc["GUID_CONTRACT"]) > 0) {
							//записываем свойства
							$arForPropCollection = array(
								array(
									"NAME" => "Номер договора",
									"CODE" => "CONTRACT_NUMBER",
									"VALUE" => $dataDoc['NAME_CONTRACT'],
									"SORT" => 100,
								),
								array(
									"NAME" => "ID договора",
									"CODE" => "CONTRACT_ID",
									"VALUE" => $contract->getId(),
									"SORT" => 110,
								),
								array(
									"NAME" => "GUID договора",
									"CODE" => "CONTRACT_GUID",
									"VALUE" => $dataDoc["GUID_CONTRACT"],
									"SORT" => 120,
								)
							);
						}

						$basketPropertyCollection->setProperty($arForPropCollection);

						$user = new User(['ID' => $USER_ID]);

						$orderService = new OrdersService();
						$orderId = $orderService->createOrder($user, $basket, 3);

						$registry = Registry::getInstance(Registry::REGISTRY_TYPE_ORDER);
						$orderClassName = $registry->getOrderClassName();

						if ($order = $orderClassName::loadByAccountNumber($orderId)) {
							$paymentCollection = $order->getPaymentCollection();

							$payment = $paymentCollection[0];
							$payment->setField('PS_RECURRING_TOKEN', $pmid);

							if (!$payment->isPaid()) {
								$paySystemService = PaySystem\Manager::getObjectById($payment->getPaymentSystemId());
								if (!empty($paySystemService)) {
									$initResult = $paySystemService->repeatRecurrent($payment);
									Helper::log($this->file_autopayment, "Результат автоплатежа: " . json_encode($initResult));
								}
							}
						}
					}
				} catch (\Exception $e) {
					Helper::log($this->file_autopayment, "Ошибка автоплатежа по счёту ID {$invoice_id}: " . $e->getMessage());
				}
				*/

				//возвращаем данные
				$returnData = array("INVOICE_ID" => $invoice_id, "USER_ID" => $USER_ID);
				Helper::log($this->file_recive_invoice, 'Возвращаем данные в Б24');
				Helper::log($this->file_recive_invoice, $returnData);
				echo json_encode($returnData);
			}
		}

		Helper::log($this->file_recive_invoice, "######################################");
	}

	//получить ID счета по его GUID
	public function getInvoiceByGuid($guid)
	{
		$return = false;
		if (!$guid)
			return false;

		$arFilter = [
			'IBLOCK_ID' => 53,
			'PROPERTY_INVOICE_GUID' => $guid,
		];

		$arSelect = [
			'ID',
			'IBLOCK_ID'
		];

		$res = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
		if ($item = $res->Fetch()) {
			$return = $item['ID'];
		}

		return $return;
	}

	/**
	 * получить ID счета по его GUID и по GUID договора
	 * GUID счёта мжет быть не уникальным в рамках разных договоров
	 * @param string $contract_guid
	 * @param string $guid
	 */
	public function getContractInvoiceByGuid($contract_guid, $guid)
	{
		$return = false;

		if (!$contract_guid)
			return false;

		if (!$guid)
			return false;

		$arFilter = [
			'IBLOCK_ID' => 53,
			'PROPERTY_CONTRACT_GUID' => $contract_guid,
			'PROPERTY_INVOICE_GUID' => $guid,
		];

		$arSelect = [
			'ID',
			'IBLOCK_ID'
		];

		$res = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
		if ($item = $res->Fetch()) {
			$return = $item['ID'];
		}

		return $return;
	}


	//удаление заказа
	function DeleteOrder($data)
	{
		\Bitrix\Main\Loader::includeModule("iblock");
		\Bitrix\Main\Loader::includeModule("catalog");
		\Bitrix\Main\Loader::includeModule("sale");

		Helper::log($this->file_DeleteOrder, "######################################");
		Helper::log($this->file_DeleteOrder, $data);

		$orderID = intval($data["ID_Заказа"]); // задаем переменную, в которой находится ID удаляемого заказа
		$returnData = array();
		$isError = false;

		//проверяем, что мы получили ID заказа
		if ($orderID <= 0) {
			$isError = true;
			$returnData = array("status" => "error", "message" => "Empty order ID");
		}

		//проверяем, что есть заказ с таким ID
		if (!$isError) {
			if (!($order = \Bitrix\Sale\Order::load($orderID))) {
				$isError = true;
				$returnData = array("status" => "error", "message" => "Order not found");
			}
		}

		//удаляем заказ с таким ID
		if (!$isError) {
			$res = \Bitrix\Sale\Order::delete($orderID);
			if (!$res->isSuccess()) {
				$returnData = array("status" => "error", "message" => $res->getErrors());
			} else {
				$returnData = array("status" => "ok", "message" => "Delete order success");
			}
		}

		Helper::log($this->file_DeleteOrder, $returnData);
		echo json_encode($returnData);
	}

	//удаление счета
	function DeleteInvoice($data)
	{
		\Bitrix\Main\Loader::includeModule("iblock");
		\Bitrix\Main\Loader::includeModule("catalog");
		\Bitrix\Main\Loader::includeModule("sale");

		Helper::log($this->file_DeleteInvoice, "######################################");
		Helper::log($this->file_DeleteInvoice, $data);

		$guidInvoice = $data["GUID_INVOICE"]; // задаем переменную, в которой находится GUID удаляемого счета
		$invoiceID = $this->getInvoiceByGuid($guidInvoice);
		$returnData = array();
		$isError = false;

		//проверяем, что мы получили ID счета
		if ($invoiceID <= 0) {
			$isError = true;
			$returnData = array("status" => "error", "message" => "Invoice not found");
		}

		//удаляем счет
		if (!$isError) {
			if (!CIBlockElement::Delete($invoiceID)) {
				$returnData = array("status" => "error", "message" => "Invoice not deleted");
			} else {
				$returnData = array("status" => "ok", "message" => "Delete invoice success");
			}
		}

		Helper::log($this->file_DeleteInvoice, $returnData);
		echo json_encode($returnData);
	}

	//удаление договора
	function DeleteContract($data)
	{
		\Bitrix\Main\Loader::includeModule("iblock");
		\Bitrix\Main\Loader::includeModule("catalog");
		\Bitrix\Main\Loader::includeModule("sale");

		Helper::log($this->file_DeleteContract, "######################################");
		Helper::log($this->file_DeleteContract, $data);

		$guidContract = $data["GUID_CONTRACT"]; // задаем переменную, в которой находится GUID удаляемого договора
		$contractID = $this->getContractByGuid($guidContract);
		$returnData = array();
		$isError = false;

		//проверяем, что мы получили ID договора
		if ($contractID <= 0) {
			$isError = true;
			$returnData = array("status" => "error", "message" => "Contract not found");
		}

		//удаляем договор
		if (!$isError) {
			if (!CIBlockElement::Delete($contractID)) {
				$returnData = array("status" => "error", "message" => "Contract not deleted");
			} else {
				$returnData = array("status" => "ok", "message" => "Delete contract success");
			}
		}

		Helper::log($this->file_DeleteContract, $returnData);
		echo json_encode($returnData);
	}

	//удаление пользователя
	function DeleteContact($data)
	{
		Helper::log($this->file_DeleteContact, "######################################");
		Helper::log($this->file_DeleteContact, $data);

		$userID = intval($data["ID_SITE_COMPANY"]); // задаем переменную, в которой находится ID удаляемого пользователя	

		//проверяем, что мы получили ID пользователя
		if ($userID <= 0) {
			$isError = true;
			$returnData = array("status" => "error", "message" => "User ID not found");
		}

		//проверяем, что такой пользователь существует
		if (!$isError) {
			$rsUser = CUser::GetByID($userID);
			$arUser = $rsUser->Fetch();
			if (empty($arUser)) {
				$isError = true;
				$returnData = array("status" => "error", "message" => "User not found");
			}
		}

		//удаляем пользователя
		if (!$isError) {
			// if(\CUser::Delete($userID)) {
			if (\CUser::Update($userID, ['ACTIVE' => 'N'])) {
				$returnData = array("status" => "ok", "message" => "Delete user success");
			} else {
				$returnData = array("status" => "error", "message" => "User not deleted");
			}
		}

		Helper::log($this->file_DeleteContact, $returnData);
		echo json_encode($returnData);
	}

	public static function invoiceStatus2Id($status)
	{
		if ($status == "N") {
			return "354";
		} elseif ($status == "P") {
			return "355";
		} elseif ($status == "1") {
			return "356";
		} elseif ($status == "D") {
			return "400";
		} elseif ($status == "F") {
			return "400";
		} elseif ($status == "3") {
			return "400";
		}
		/**
		 * почему D и F одинаковые
		 * комментарий 1сника:
		 * "суть в том что статус удаление никогда не выгружался в счете, статус D уде выгружается и это не удаление, оно занято описания что это не эту секунду нету."
		 * а потом добавили ещё и "3" вместо F (потомучто для црм так нужно)
		 */
	}
}


$Exchange = new Exchange();
$Exchange->start();
