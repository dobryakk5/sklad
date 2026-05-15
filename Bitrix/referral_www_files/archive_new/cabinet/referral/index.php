<?php
define("NEED_AUTH", true);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle("Реферальная программа");

global $USER;

$APPLICATION->IncludeComponent(
    "alfasklad:referral.program",
    "",
    [
        "USER_ID" => $USER->GetID(),
    ],
    false
);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
