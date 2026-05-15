<?
define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Лист ожидания");?>

<a class="btn btn-transparent" href="/rental_catalog/">Перейти в каталог</a>
<br><br>

<?$APPLICATION->IncludeComponent(
	"bitrix:form.result.list.my", 
	"lk_waiting_list", 
	array(
		"COMPONENT_TEMPLATE" => "lk_waiting_list",
		"EDIT_URL" => "",
		"FORMS" => array(
			0 => "15",
			1 => "",
		),
		"LIST_URL" => "",
		"NUM_RESULTS" => "999",
		"VIEW_URL" => ""
	),
	false
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>