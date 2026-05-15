<?require_once $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php";?>

<?
Bitrix\Main\Loader::includeModule("sale");
Bitrix\Main\Loader::includeModule("catalog");
Bitrix\Main\Loader::includeModule("iblock");


if($_REQUEST["ACTION"] == "PAY_INVOICE") {
	if(strlen($_REQUEST["INVOICE_ID"]) > 0) {
		//очищаем корзину
		CSaleBasket::DeleteAll(CSaleBasket::GetBasketUserID());
		
		//добавляем в корзину счет	
		$basket = \Bitrix\Sale\Basket::loadItemsForFUser(\Bitrix\Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());		
		
		$quantity = 1;		
		$item = $basket->createItem("catalog", $_REQUEST["INVOICE_ID"]);
		$item->setFields(array(
			"QUANTITY" => $quantity,
			"CURRENCY" => Bitrix\Currency\CurrencyManager::getBaseCurrency(),
			"LID" => Bitrix\Main\Context::getCurrent()->getSite(),
			"PRODUCT_PROVIDER_CLASS" => 'CCatalogProductProvider',
		));

		$basketPropertyCollection = $item->getPropertyCollection();
		
		//ищем полную инфу о счете
		$res = CIBlockElement::GetList(Array("ID"=>"ASC"), Array("IBLOCK_ID"=>INVOICES_IBLOCK, "ID"=>$_REQUEST["INVOICE_ID"]), false, Array(), Array("ID", "IBLOCK_ID", "PROPERTY_CONTRACT_GUID"));
		if($ob = $res->GetNextElement()) {
			$arInvoiceFields = $ob->GetFields();
			//ищем полную инфу о договоре
			if(strlen($arInvoiceFields["PROPERTY_CONTRACT_GUID_VALUE"]) > 0) {
				$res = CIBlockElement::GetList(Array("ID"=>"ASC"), Array("IBLOCK_ID"=>CONTRACTS_IBLOCK, "PROPERTY_CONTRACT_GUID"=>$arInvoiceFields["PROPERTY_CONTRACT_GUID_VALUE"]), false, Array(), Array("ID", "IBLOCK_ID", "PROPERTY_BOX", "PROPERTY_NUMBER", "PROPERTY_CONTRACT_GUID"));
				if($ob = $res->GetNextElement()) {
					$arContractFields = $ob->GetFields();
					if(strlen($arContractFields["PROPERTY_NUMBER_VALUE"]) > 0) {
						$arForPropCollection[] = Array(
							"NAME" => "Номер договора",
							"CODE" => "CONTRACT_NUMBER",
							"VALUE" => $arContractFields["PROPERTY_NUMBER_VALUE"],
							"SORT" => 100,
						);
					}
					if(strlen($arContractFields["ID"]) > 0) {
						$arForPropCollection[] = Array(
							"NAME" => "ID договора",
							"CODE" => "CONTRACT_ID",
							"VALUE" => $arContractFields["ID"],
							"SORT" => 110,
						);
					}
					if(strlen($arContractFields["ID"]) > 0) {
						$arForPropCollection[] = Array(
							"NAME" => "GUID договора",
							"CODE" => "CONTRACT_GUID",
							"VALUE" => $arContractFields["PROPERTY_CONTRACT_GUID_VALUE"],
							"SORT" => 120,
						);
					}						
		
					//ищем склад по боксу в договоре
					if(strlen($arContractFields["PROPERTY_BOX_VALUE"]) > 0) {
						$db_sections = CIBlockElement::GetElementGroups($arContractFields["PROPERTY_BOX_VALUE"], true);
						if($arSect = $db_sections->Fetch()) {
							$arForPropCollection[] = Array(
								"NAME" => "Внешний код склада",
								"CODE" => "SKLAD_XML_ID",
								"VALUE" => $arSect["XML_ID"],
								"SORT" => 130,
							);
						}							
					}
				}					
			}
		}

		$basketPropertyCollection->setProperty($arForPropCollection);
			
		
		$basket->save();		
		echo "OK";		
	}
}
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>