<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?
global $USER;

if($_REQUEST["ACTION"] == "DELETE") {
	if($USER->IsAuthorized()) {
		CFormResult::Delete($_REQUEST["ID"]);
	}
}
?> 