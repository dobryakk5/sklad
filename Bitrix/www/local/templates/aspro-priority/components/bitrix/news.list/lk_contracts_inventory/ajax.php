<?require_once $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php";?>
<?
CModule::IncludeModule("iblock");

if($_REQUEST["ACTION"] == "SAVE") {
	
	global $USER;
	
	if($USER->IsAuthorized()) {
		$result = Array();		
		
		$arLoadData = Array(
			"MODIFIED_BY"       => $USER->GetID(), 
			"DETAIL_TEXT"       => $_REQUEST["LOAD_DATA"]["DETAIL_TEXT"]
		);
		
		$el = new CIBlockElement;
	
		if(strlen($_REQUEST["LOAD_DATA"]["INVENTORY_ID"]) > 0) {
			//редактируем элемент
			if($el->Update($_REQUEST["LOAD_DATA"]["INVENTORY_ID"], $arLoadData)) {
				$result = Array("STATUS"=>"OK");
			}			
		} else {
				$result = Array("STATUS"=>"");
		}		
		
		echo json_encode($result);
	}	
}
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>