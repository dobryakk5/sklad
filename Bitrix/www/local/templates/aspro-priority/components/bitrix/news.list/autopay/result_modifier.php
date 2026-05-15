<?

use Bitrix\Main\UserFieldTable;

 if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?php

CModule::IncludeModule("iblock");

global $USER, $USER_FIELD_MANAGER;

if ($USER->IsAuthorized()) {

	$rsUser = CUser::GetByID($USER->GetID());
	$arUser = $rsUser->Fetch();

	if ($arUser['UF_AUTOPAYMEN_METHOD']) {
		$res = CIBlockElement::GetList(["id" => "DESC"], ['ID' => $arUser['UF_AUTOPAYMEN_METHOD']], false, false, [
			'ID',
			'IBLOCK_ID',
			'NAME',
			'ACTIVE',
			'PROPERTY_PM_STATUS',
			'PROPERTY_PM_SAVED',
			'PROPERTY_AUTOPAY',
		]);

		if ($arPayment = $res->GetNext()) {

			$arResult["PM"] = [
				'ID' 	 => $arPayment['NAME'],
				'ACTIVE' => $arPayment['ACTIVE'],
				'STATUS' => $arPayment['PROPERTY_PM_STATUS_VALUE'],
				'SAVED'  => $arPayment['PROPERTY_PM_SAVED_VALUE'],
				'AUTOPAY'  => $arPayment['PROPERTY_AUTOPAY_VALUE'],
				];
		}
	}

	$userField = UserFieldTable::getList([
		'filter' => ['ENTITY_ID' => 'USER', 'FIELD_NAME' => 'UF_SAVE_PAYMENT_MERCHANT']
	])->Fetch();

	if ($userField) {
		$arResult['REMEMBER_CARD'] = true;
		$arResult['PAYMENT_MERCHANT_SAVED'] = $arUser['UF_SAVE_PAYMENT_MERCHANT'];
	} else {
		$arResult['REMEMBER_CARD'] = false;
	}

	// последний не оплаченный счёт
	$res = CIBlockElement::GetList(array("property_DATE_CREATE" => "DESC"), array(
		"IBLOCK_ID" => INVOICES_IBLOCK,
		"PROPERTY_STATUS" => [INVOICE_STATUS_NOTPAID_ID, INVOICE_STATUS_HALFPAID_ID],
		"PROPERTY_USER" => $arParams["USER_ID"]
	), false, array(), array("ID", "NAME",));

	if ($ob = $res->GetNextElement()) {
		$arResult["LAST_INVOICE"] = $ob->GetFields();
	}
}

?>

<? /*$this->__component->SetResultCacheKeys(array("CACHED_TPL"));*/ ?>