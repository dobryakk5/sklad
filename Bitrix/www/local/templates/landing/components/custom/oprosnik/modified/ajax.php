<?require_once $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php";?>
<?
CModule::IncludeModule("iblock");

if($_REQUEST["ACTION"] == "NEXT_STEP") {
	$arResult = Array();
	session_start();
	if($_REQUEST["IS_FIRST_QUESTION"] == "Y") {
		$_SESSION["OPROSNIK_DATA"] = Array();
	}
	$_SESSION["OPROSNIK_DATA"][$_REQUEST["CURRENT_QUESTION_ID"]] = Array("CURRENT_QUESTION_ID"=>$_REQUEST["CURRENT_QUESTION_ID"], "CURRENT_ANSWER_ID"=>explode(",", $_REQUEST["CURRENT_ANSWER_ID"]), "CURRENT_ANSWER_VALUE"=>explode(",", $_REQUEST["CURRENT_ANSWER_VALUE"]));

	if(strlen($_REQUEST["NEXT_QUESTION_ID"]) > 0) {
		?>
		<?$APPLICATION->IncludeComponent(
			"custom:oprosnik", 
			".default", 
			array(
				"AJAX_LOAD" => "Y",
				"COMPONENT_TEMPLATE" => ".default",
				"IBLOCK_ID" => "45",
				"QUESTION_ID" => $_REQUEST["NEXT_QUESTION_ID"],
				"OPROSNIK_DATA" => $_SESSION["OPROSNIK_DATA"],
				"CACHE_TYPE" => "A",
				"CACHE_TIME" => "36000000",
			),
			false
		);?>		
		<?
	} else {
		CModule::IncludeModule("iblock");

		//возвращаем текстовый результат
		$oprosnik_result = "";
		
		if(!empty($_SESSION["OPROSNIK_DATA"])) {
			foreach($_SESSION["OPROSNIK_DATA"] as $arQuestion) {
				$res = CIBlockSection::GetByID($arQuestion["CURRENT_QUESTION_ID"]);
				if($arRes = $res->GetNext()) {
					$oprosnik_result .= "---------------------\r\n".$arRes["NAME"]."\r\n";
					foreach($arQuestion["CURRENT_ANSWER_ID"] as $k=>$arAnswer) {
						$resAnswer = CIBlockElement::GetByID($arAnswer);
						if($arResAnswer = $resAnswer->GetNext()) {
							$oprosnik_result .= $arResAnswer["NAME"]." -> ".$arQuestion["CURRENT_ANSWER_VALUE"][$k]."\r\n";
							
							//обновляем статистику ответов
							$db_props = CIBlockElement::GetProperty(45, $arResAnswer["ID"], array("sort"=>"asc"), Array("CODE"=>"COUNTER"));
							if($arCounter = $db_props->Fetch()) {
								CIBlockElement::SetPropertyValuesEx($arResAnswer["ID"], 45, array("COUNTER"=>$arCounter["VALUE"]+1));
							}
						}
					}
				}
			}
		}
		
		echo "SHOW_FORM_".$oprosnik_result;
	}
}

if($_REQUEST["ACTION"] == "PREV_STEP") {
	$arResult = Array();
	session_start();

	if(strlen($_REQUEST["PREV_QUESTION_ID"]) > 0) {
		?>
		<?$APPLICATION->IncludeComponent(
			"custom:oprosnik", 
			"modified",
			array(
				"AJAX_LOAD" => "Y",
				"COMPONENT_TEMPLATE" => ".default",
				"IBLOCK_ID" => "45",
				"QUESTION_ID" => $_REQUEST["PREV_QUESTION_ID"],
				"OPROSNIK_DATA" => $_SESSION["OPROSNIK_DATA"],
				"CACHE_TYPE" => "A",
				"CACHE_TIME" => "36000000",
			),
			false
		);?>		
		<?
	}

	array_pop($_SESSION["OPROSNIK_DATA"]);

}

?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>