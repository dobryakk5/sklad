<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?
if($arParams["WEB_FORM_ID"] == "15") {
	//SKLAD
	if(strlen($arParams["SKLAD_ID"]) > 0) {
		CModule::IncludeModule("iblock");
		$res = CIBlockSection::GetByID($arParams["SKLAD_ID"]);
		if($arSklad = $res->GetNext()) {
			$arResult["QUESTIONS"]["SKLAD"]["HTML_CODE"] = str_replace('value=""', 'value="'.$arSklad["NAME"].'"', $arResult["QUESTIONS"]["SKLAD"]["HTML_CODE"]);
			$arResult["QUESTIONS"]["SKLAD"]["VALUE"] = $arSklad["NAME"];		
		}	
	}
	//SQUARE
	if((strlen($arParams["SQUARE_FROM"]) > 0) and (strlen($arParams["SQUARE_TO"]) > 0)) {
		$edIzm = "кв.м.";
		if($arParams["PROP_SIZE"] == "VOLUME") {
			$edIzm = "куб.м.";
			$arResult["QUESTIONS"]["SQUARE"]["CAPTION"] = "Объем бокса";
		}
		
		if($arParams["SQUARE_FROM"] == $arParams["SQUARE_TO"]) {
			$squareText = $arParams["SQUARE_FROM"]." ".$edIzm;
		} else {
			$squareText = "от ".$arParams["SQUARE_FROM"]." до ".$arParams["SQUARE_TO"]." ".$edIzm;
		}
		
		$arResult["QUESTIONS"]["SQUARE"]["HTML_CODE"] = str_replace('value=""', 'value="'.$squareText.'"', $arResult["QUESTIONS"]["SQUARE"]["HTML_CODE"]);
		$arResult["QUESTIONS"]["SQUARE"]["VALUE"] = $squareText;
	}
	//USER_ID
	if(strlen($arParams["USER_ID"]) > 0) {
		$arResult["QUESTIONS"]["USER_ID"]["HTML_CODE"] = str_replace('value=""', 'value="'.$arParams["USER_ID"].'"', $arResult["QUESTIONS"]["USER_ID"]["HTML_CODE"]);
		$arResult["QUESTIONS"]["USER_ID"]["VALUE"] = $arParams["USER_ID"];		
	}
}

if(($arParams["WEB_FORM_ID"] == "16") or ($arParams["WEB_FORM_ID"] == "22")) {
	//INVOICE_NUMBER
	/*if(strlen($arParams["INVOICE_NUMBER"]) > 0) {
		$arResult["QUESTIONS"]["INVOICE_NUMBER"]["HTML_CODE"] = str_replace('value=""', 'value="'.$arParams["INVOICE_NUMBER"].'"', $arResult["QUESTIONS"]["INVOICE_NUMBER"]["HTML_CODE"]);
		$arResult["QUESTIONS"]["INVOICE_NUMBER"]["VALUE"] = $arParams["INVOICE_NUMBER"];		
	}*/
	//INVOICE_GUID
	if(strlen($arParams["INVOICE_GUID"]) > 0) {
		$arResult["QUESTIONS"]["INVOICE_GUID"]["HTML_CODE"] = str_replace('value=""', 'value="'.$arParams["INVOICE_GUID"].'"', $arResult["QUESTIONS"]["INVOICE_GUID"]["HTML_CODE"]);
		$arResult["QUESTIONS"]["INVOICE_GUID"]["VALUE"] = $arParams["INVOICE_GUID"];
		//получение данных о счете по его GUID
        $arFilter = [
            "IBLOCK_ID" => 53,
            "PROPERTY_INVOICE_GUID" => $arParams["INVOICE_GUID"],
        ];
        $arSelect = [
            "ID",
            "IBLOCK_ID",
			"PROPERTY_NUMBER"
        ];
        $res = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        if ($item = $res->Fetch()) {
			$arResult["QUESTIONS"]["INVOICE_NUMBER"]["HTML_CODE"] = str_replace('value=""', 'value="'.$item["PROPERTY_NUMBER_VALUE"].'"', $arResult["QUESTIONS"]["INVOICE_NUMBER"]["HTML_CODE"]);
			$arResult["QUESTIONS"]["INVOICE_NUMBER"]["VALUE"] = $item["PROPERTY_NUMBER_VALUE"];	            
        }		
	}
	//CONTRACT_NUMBER
	/*if(strlen($arParams["CONTRACT_NUMBER"]) > 0) {
		$arResult["QUESTIONS"]["CONTRACT_NUMBER"]["HTML_CODE"] = str_replace('value=""', 'value="'.$arParams["CONTRACT_NUMBER"].'"', $arResult["QUESTIONS"]["CONTRACT_NUMBER"]["HTML_CODE"]);
		$arResult["QUESTIONS"]["CONTRACT_NUMBER"]["VALUE"] = $arParams["CONTRACT_NUMBER"];		
	}*/
	//CONTRACT_GUID
	if(strlen($arParams["CONTRACT_GUID"]) > 0) {
		$arResult["QUESTIONS"]["CONTRACT_GUID"]["HTML_CODE"] = str_replace('value=""', 'value="'.$arParams["CONTRACT_GUID"].'"', $arResult["QUESTIONS"]["CONTRACT_GUID"]["HTML_CODE"]);
		$arResult["QUESTIONS"]["CONTRACT_GUID"]["VALUE"] = $arParams["CONTRACT_GUID"];
		//получение данных о договоре по его GUID
        $arFilter = [
            "IBLOCK_ID" => 52,
            "PROPERTY_CONTRACT_GUID" => $arParams["CONTRACT_GUID"],
        ];
        $arSelect = [
            "ID",
            "IBLOCK_ID",
			"PROPERTY_NUMBER"
        ];
        $res = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        if ($item = $res->Fetch()) {
			$arResult["QUESTIONS"]["CONTRACT_NUMBER"]["HTML_CODE"] = str_replace('value=""', 'value="'.$item["PROPERTY_NUMBER_VALUE"].'"', $arResult["QUESTIONS"]["CONTRACT_NUMBER"]["HTML_CODE"]);
			$arResult["QUESTIONS"]["CONTRACT_NUMBER"]["VALUE"] = $item["PROPERTY_NUMBER_VALUE"];	            
        }		
	}	
	//EMAIL
	if(strlen($arParams["USER_EMAIL"]) > 0) {
		$arResult["QUESTIONS"]["USER_EMAIL"]["HTML_CODE"] = str_replace('value=""', 'value="'.$arParams["USER_EMAIL"].'"', $arResult["QUESTIONS"]["USER_EMAIL"]["HTML_CODE"]);
		$arResult["QUESTIONS"]["USER_EMAIL"]["VALUE"] = $arParams["USER_EMAIL"];		
	}	
}

if($arParams["WEB_FORM_ID"] == "18") {
	//DATE_SEND
	if(!empty($arResult["QUESTIONS"]["DATE_SEND"])) {
		$strNowDate = date("d.m.Y");
		$arResult["QUESTIONS"]["DATE_SEND"]["HTML_CODE"] = str_replace('value=""', 'value="'.$strNowDate.'" class="form-control input-date"', $arResult["QUESTIONS"]["DATE_SEND"]["HTML_CODE"]);
		$arResult["QUESTIONS"]["DATE_SEND"]["HTML_CODE"] = str_replace('(DD.MM.YYYY)', '', $arResult["QUESTIONS"]["DATE_SEND"]["HTML_CODE"]);
		$arResult["QUESTIONS"]["DATE_SEND"]["VALUE"] = $strNowDate;		
	}
	//DATE_CANCEL
	if(!empty($arResult["QUESTIONS"]["DATE_CANCEL"])) {
		$arResult["QUESTIONS"]["DATE_CANCEL"]["HTML_CODE"] = str_replace('value=""', 'value="" class="form-control input-date"', $arResult["QUESTIONS"]["DATE_CANCEL"]["HTML_CODE"]);	
		$arResult["QUESTIONS"]["DATE_CANCEL"]["HTML_CODE"] = str_replace('(DD.MM.YYYY)', 'Если вы оставите данное поле пустым, то дата будет рассчитана автоматически: 14 дней с момента подачи заявки.', $arResult["QUESTIONS"]["DATE_CANCEL"]["HTML_CODE"]);	
	}
	//Ищем договор пользователя
	if(strlen($arParams["USER_ID"]) > 0) {
		$arFilter = [
			"IBLOCK_ID" => 52,
			"PROPERTY_USER" => $arParams["USER_ID"],
			"PROPERTY_STATUS" => CONTRACT_STATUS_ACTIVE_ID
		];
		$arSelect = [
			"ID",
			"IBLOCK_ID",
			"PROPERTY_NUMBER",
			"PROPERTY_BOX"
		];
		$res = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
		if ($contract = $res->Fetch()) {
			//Номер договора
			$arResult["QUESTIONS"]["CONTRACT_NUMBER"]["HTML_CODE"] = str_replace('value=""', 'value="'.$contract["PROPERTY_NUMBER_VALUE"].'"', $arResult["QUESTIONS"]["CONTRACT_NUMBER"]["HTML_CODE"]);
			$arResult["QUESTIONS"]["CONTRACT_NUMBER"]["VALUE"] = $contract["PROPERTY_NUMBER_VALUE"];
			
			//Ищем бокс
			if(strlen($contract["PROPERTY_BOX_VALUE"]) > 0) {
				$arFilter = [
					"IBLOCK_ID" => STORAGES_CATALOG_IBLOCK,
					"ID" => $contract["PROPERTY_BOX_VALUE"],
				];
				$arSelect = [
					"ID",
					"IBLOCK_ID",
					"NAME",
					"IBLOCK_SECTION_ID"
				];
				$res = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
				if ($box = $res->Fetch()) {
					//Бокс
					$arResult["QUESTIONS"]["BOX_NAME"]["HTML_CODE"] = str_replace('value=""', 'value="'.$box["NAME"].'"', $arResult["QUESTIONS"]["BOX_NAME"]["HTML_CODE"]);
					$arResult["QUESTIONS"]["BOX_NAME"]["VALUE"] = $contract["PROPERTY_NUMBER_VALUE"];		
					//Склад				
					foreach($arResult["arAnswers"]["SKLAD"] as $arOption) {
						$arResult["QUESTIONS"]["SKLAD"]["HTML_CODE"] = str_replace('value="'.$arOption["ID"].'"', 'value="'.$arOption["ID"].'" data-sklad-id="'.$arOption["VALUE"].'"', $arResult["QUESTIONS"]["SKLAD"]["HTML_CODE"]);
					}
					$arResult["QUESTIONS"]["SKLAD"]["HTML_CODE"] = str_replace('data-sklad-id="'.$box["IBLOCK_SECTION_ID"].'"', 'data-sklad-id="'.$box["IBLOCK_SECTION_ID"].'" selected', $arResult["QUESTIONS"]["SKLAD"]["HTML_CODE"]);
				}
			}
		}
	}
}

if(($arParams["WEB_FORM_ID"] == "19") or ($arParams["WEB_FORM_ID"] == "20") or ($arParams["WEB_FORM_ID"] == "21") or ($arParams["WEB_FORM_ID"] == "29")) {
    //DATE
    if (!empty($arResult["QUESTIONS"]["DATE"])) {
//        $strNowDate                                      = date("d.m.Y");
        $strNowDate = date("d.m.Y", strtotime(date("Y-m-d") .'+ 1 days'));
        $arResult["QUESTIONS"]["DATE"]["HTML_CODE"] = str_replace('value=""', 'value="' . $strNowDate . '" class="form-control input-date"', $arResult["QUESTIONS"]["DATE"]["HTML_CODE"]);
        $arResult["QUESTIONS"]["DATE"]["HTML_CODE"] = str_replace('(DD.MM.YYYY)', '', $arResult["QUESTIONS"]["DATE"]["HTML_CODE"]);
        $arResult["QUESTIONS"]["DATE"]["VALUE"]     = $strNowDate;
    }

	//Ищем договор пользователя
	if(strlen($arParams["USER_ID"]) > 0) {
		$arFilter = [
			"IBLOCK_ID" => 52,
			"PROPERTY_USER" => $arParams["USER_ID"],
			"PROPERTY_STATUS" => CONTRACT_STATUS_ACTIVE_ID
		];
		$arSelect = [
			"ID",
			"IBLOCK_ID",
			"PROPERTY_NUMBER",
			"PROPERTY_BOX"
		];
		$res = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
		if ($contract = $res->Fetch()) {			
			//Ищем бокс
			if(strlen($contract["PROPERTY_BOX_VALUE"]) > 0) {
				$arFilter = [
					"IBLOCK_ID" => STORAGES_CATALOG_IBLOCK,
					"ID" => $contract["PROPERTY_BOX_VALUE"],
				];
				$arSelect = [
					"ID",
					"IBLOCK_ID",
					"NAME",
					"IBLOCK_SECTION_ID"
				];
				$res = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
				if ($box = $res->Fetch()) {
					//Бокс
					$arResult["QUESTIONS"]["BOX_NAME"]["HTML_CODE"] = str_replace('value=""', 'value="'.$box["NAME"].'"', $arResult["QUESTIONS"]["BOX_NAME"]["HTML_CODE"]);
					$arResult["QUESTIONS"]["BOX_NAME"]["VALUE"] = $contract["PROPERTY_NUMBER_VALUE"];		
					//Склад				
					foreach($arResult["arAnswers"]["SKLAD"] as $arOption) {
						$arResult["QUESTIONS"]["SKLAD"]["HTML_CODE"] = str_replace('value="'.$arOption["ID"].'"', 'value="'.$arOption["ID"].'" data-sklad-id="'.$arOption["VALUE"].'"', $arResult["QUESTIONS"]["SKLAD"]["HTML_CODE"]);
					}
					$arResult["QUESTIONS"]["SKLAD"]["HTML_CODE"] = str_replace('data-sklad-id="'.$box["IBLOCK_SECTION_ID"].'"', 'data-sklad-id="'.$box["IBLOCK_SECTION_ID"].'" selected', $arResult["QUESTIONS"]["SKLAD"]["HTML_CODE"]);
				}
			}
		}
	}
}
if($arParams["WEB_FORM_ID"] == "20") {
    //DATE
    if (!empty($arResult["QUESTIONS"]["DATE"])) {
        $strNowDate                                      = date("d.m.Y");
        $arResult["QUESTIONS"]["DATE"]["HTML_CODE"] = str_replace('value=""', 'value="' . $strNowDate . '" class="form-control input-date"', $arResult["QUESTIONS"]["DATE"]["HTML_CODE"]);
        $arResult["QUESTIONS"]["DATE"]["HTML_CODE"] = str_replace('(DD.MM.YYYY)', '', $arResult["QUESTIONS"]["DATE"]["HTML_CODE"]);
        $arResult["QUESTIONS"]["DATE"]["VALUE"]     = $strNowDate;
    }
}
if($arParams["WEB_FORM_ID"] == "23") {
	//DATE
	if(!empty($arResult["QUESTIONS"]["DATE"])) {
		$strNowDate = date("d.m.Y");
		$arResult["QUESTIONS"]["DATE"]["HTML_CODE"] = str_replace('value=""', 'value="'.$strNowDate.'"', $arResult["QUESTIONS"]["DATE"]["HTML_CODE"]);
		$arResult["QUESTIONS"]["DATE"]["HTML_CODE"] = str_replace('value=', 'class="form-control input-date" value=', $arResult["QUESTIONS"]["DATE"]["HTML_CODE"]);	
		$arResult["QUESTIONS"]["DATE"]["HTML_CODE"] = str_replace('(DD.MM.YYYY)', '', $arResult["QUESTIONS"]["DATE"]["HTML_CODE"]);	
	}
}
pre($arResult["QUESTIONS"], false, true);
?>