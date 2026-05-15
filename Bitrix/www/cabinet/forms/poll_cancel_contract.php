<?
define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Опрос");
?>

<h5>
Для улучшения качества обслуживания просим вас указать причину расторжения договора
</h5>

<?$APPLICATION->IncludeComponent(
	"bitrix:voting.form", 
	"pool_cancel_contract", 
	array(
		"COMPONENT_TEMPLATE" => "pool_cancel_contract",
		"VOTE_ID" => "2",
		"VOTE_RESULT_TEMPLATE" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>