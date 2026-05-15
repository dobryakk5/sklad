<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?
CModule::IncludeModule("iblock");
CModule::IncludeModule("currency");
\Bitrix\Main\Loader::includeModule("catalog");

//Общий баланс
$arResult["OVERAL_BALANCE"] = 0;
$arResult["OVERAL_INVOICES_SUM"] = 0;

//ищем активные договоры пользователя
$res = CIBlockElement::GetList(array("property_DATE_CREATE" => "DESC"), array("IBLOCK_ID" => CONTRACTS_IBLOCK, "PROPERTY_STATUS" => CONTRACT_STATUS_ACTIVE_ID, "PROPERTY_USER" => $arParams["USER_ID"]), false, array(), array("ID", "NAME", "PROPERTY_NUMBER", "PROPERTY_BALANCE", "PROPERTY_BOX", "PROPERTY_CONTRACT_GUID"));
while ($ob = $res->GetNextElement()) {
	$arFields = $ob->GetFields();
	$arResult["OVERAL_BALANCE"] = $arResult["OVERAL_BALANCE"] + (round($arFields["PROPERTY_BALANCE_VALUE"], 2) * -1);
	$arResult["CONTRACTS"][] = $arFields;
}

//ищем неоплаченные счета пользователя
$res = CIBlockElement::GetList(array("property_DATE_CREATE" => "DESC"), [
	"IBLOCK_ID" => INVOICES_IBLOCK,
	'ACTIVE' => 'Y',
	"PROPERTY_STATUS" => [INVOICE_STATUS_NOTPAID_ID, INVOICE_STATUS_HALFPAID_ID],
	"PROPERTY_USER" => $arParams["USER_ID"]
], false, array(), array("ID", "NAME"));

while ($ob = $res->GetNextElement()) {
	$arFields = $ob->GetFields();

	$rsPrice = \Bitrix\Catalog\PriceTable::getList(
		array(
			"select" => array("*"),
			"filter" => array("=PRODUCT_ID" => $arFields["ID"], "CATALOG_GROUP_ID" => 1)
		)
	);

	if ($arInvoicePrice = $rsPrice->fetch()) {
		$arResult["OVERAL_INVOICES_SUM"] += $arInvoicePrice["PRICE"];
	}
}

//находим выбранный договор
if (!empty($arResult["CONTRACTS"])) {
	if (strlen($arParams["CONTRACT_ID"]) > 0) {
		foreach ($arResult["CONTRACTS"] as $arContract) {
			if ($arParams["CONTRACT_ID"] == $arContract["ID"]) {
				$arResult["SELECTED_CONTRACT"] = $arContract;
				break;
			}
		}
	} else {
		$arResult["SELECTED_CONTRACT"] = $arResult["CONTRACTS"][0];
	}
}
?>

<? $this->__component->SetResultCacheKeys(array("CACHED_TPL")); ?>