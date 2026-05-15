<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Личный кабинет");?>

<?if ($USER->IsAuthorized()):
	LocalRedirect("/cart/");
endif?>

<?$APPLICATION->IncludeComponent(
	"bitrix:system.auth.form",
	"main",
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"REGISTER_URL" => "",
		"FORGOT_PASSWORD_URL" => "/auth/forgotpasswd/",
		"PROFILE_URL" => "",
		"SHOW_ERRORS" => "N",
		"USE_BACKURL" => "Y"
	),
	false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
