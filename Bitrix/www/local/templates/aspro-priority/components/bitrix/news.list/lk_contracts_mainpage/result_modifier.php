<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$rsUser = CUser::GetByID($USER->GetID());
$arUser = $rsUser->Fetch();

//Общий баланс
//$arResult["OVERAL_BALANCE"] = 0;
$arResult["OVERAL_BALANCE"] = $arUser["UF_USER_BALANCE"];

foreach($arResult["ITEMS"] as $key=>$arItem) {
	//$arResult["OVERAL_BALANCE"] = $arResult["OVERAL_BALANCE"] + (round($arItem["PROPERTIES"]["BALANCE"]["VALUE"], 2) * -1);

	//получаем данные о боксе
	if(strlen($arItem["PROPERTIES"]["BOX"]["VALUE"]) > 0) {	
		$res = CIBlockElement::GetList(Array("ID"=>"ASC"), Array("IBLOCK_ID"=>STORAGES_CATALOG_IBLOCK, "ID"=>$arItem["PROPERTIES"]["BOX"]["VALUE"]), false, Array("nTopCount"=>1), Array("ID", "IBLOCK_ID", "NAME", "IBLOCK_SECTION_ID", "PROPERTY_BOX_NUMBER"));
		if($ob = $res->GetNextElement()) {
			$arBoxFields = $ob->GetFields();
			if(strlen($arBoxFields["IBLOCK_SECTION_ID"]) > 0) {
				$resSect = CIBlockSection::GetByID($arBoxFields["IBLOCK_SECTION_ID"]);
				if($arSklad = $resSect->GetNext()) {					
					$arBoxFields["SKLAD"] = $arSklad;
				}
			}			
			$arResult["ITEMS"][$key]["BOX"] = $arBoxFields;			
		}		
	}
	
	//находим все связанные с договором неоплаченные счета
	if(strlen($arItem["PROPERTIES"]["CONTRACT_GUID"]["VALUE"]) > 0) {
		$res = CIBlockElement::GetList(Array("PROPERTY_DATE_CREATE"=>"DESC"), Array("IBLOCK_ID"=>53, "PROPERTY_USER"=>$arItem["PROPERTIES"]["USER"]["VALUE"], "PROPERTY_CONTRACT_GUID"=>$arItem["PROPERTIES"]["CONTRACT_GUID"]["VALUE"], "PROPERTY_STATUS"=>Array(354, 356)), false, Array(), Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_DATE_FROM"));
		while($ob = $res->GetNextElement()) {
			$arInvoiceFields = $ob->GetFields();
			if(strlen($arInvoiceFields["PROPERTY_DATE_FROM_VALUE"]) > 0) {
				$arInvoiceFields["MONTH"] = FormatDate("f Y", MakeTimeStamp($arInvoiceFields["PROPERTY_DATE_FROM_VALUE"]));
				$arResult["ITEMS"][$key]["INVOICES"][] = $arInvoiceFields;
			}
		}	
	}
}
?>

<?$this->__component->SetResultCacheKeys(array("CACHED_TPL"));?>