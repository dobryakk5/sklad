<?
define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Опись вещей");
global $USER;
?>

<?$APPLICATION->IncludeComponent(
	"custom:inventory", 
	".default", 
	array(
		"IBLOCK_ID" => "58",
		"BOX_ID" => $_REQUEST["BOX_ID"],
		"USER_ID" => $USER->GetID(),
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"COMPONENT_TEMPLATE" => ".default",
		"CONTRACTS_IBLOCK_ID" => "52"
	),
	false
);?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>