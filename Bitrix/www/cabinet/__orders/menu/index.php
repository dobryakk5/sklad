<?
define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Мои заказы");
?>


<?$APPLICATION->IncludeComponent(
    "bitrix:main.include",
    "",
    Array(
        "AREA_FILE_SHOW" => "file",
        "AREA_FILE_SUFFIX" => "inc",
        "EDIT_TEMPLATE" => "",
        "PATH" => "/include/cabinet/orders_my_menu.php"
    ),
    false,
    Array("HIDE_ICONS"=>"Y")
);?>



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?> 