<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
CModule::IncludeModule("iblock");
CModule::IncludeModule("currency");

//Общий баланс
$arResult["OVERAL_BALANCE"] = 0;

//ищем активные договоры пользователя
$res = CIBlockElement::GetList(Array("property_DATE_CREATE"=>"DESC"), Array("IBLOCK_ID"=>CONTRACTS_IBLOCK, "PROPERTY_STATUS"=>CONTRACT_STATUS_ACTIVE_ID, "PROPERTY_USER"=>$arParams["USER_ID"]), false, Array(), Array("ID", "NAME", "PROPERTY_NUMBER", "PROPERTY_BALANCE", "PROPERTY_BOX", "PROPERTY_CONTRACT_GUID"));
while($ob = $res->GetNextElement())
{
	$arFields = $ob->GetFields();
	$arResult["OVERAL_BALANCE"] = $arResult["OVERAL_BALANCE"] + (round($arFields["PROPERTY_BALANCE_VALUE"], 2) * -1);
	$arResult["CONTRACTS"][] = $arFields;
}

//находим выбранный договор
if(!empty($arResult["CONTRACTS"])) {
	if(strlen($arParams["CONTRACT_ID"]) > 0) {
		foreach($arResult["CONTRACTS"] as $arContract) {
			if($arParams["CONTRACT_ID"] == $arContract["ID"] || (isset($_GET['contract']) && $_GET['contract'] == $arContract["PROPERTY_NUMBER_VALUE"])) {
				$arResult["SELECTED_CONTRACT"] = $arContract;
				break;
			}
		}
	} else {
		$arResult["SELECTED_CONTRACT"] = $arResult["CONTRACTS"][0];
	}
}
?>

<?$this->__component->SetResultCacheKeys(array("CACHED_TPL"));?>