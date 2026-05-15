<? use Bitrix\Main\Loader;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?
/*
if ($GLOBALS['USER']->IsAdmin()) {
pre($arResult);
//pre($GLOBALS['USER']);
global $USER;
//
//    global $USER;
    $userID = $USER->GetID();
//pre($userID);
        $user = new CUser;
    $fields = Array(
        "PERSONAL_PHONE" => '+7 925 750 29 20',
    );
    $user->Update($userID, $fields);


    echo "Телефон: ".$USER->GetParam("PERSONAL_PHONE");

}*/

Loader::includeModule("sale");

$arResult["ADDITIONAL_FIELDS"] = array();
$arSkipFilelds                 = array("NAME", "LAST_NAME", "SECOND_NAME", "EMAIL", 'PHONE');

$db_sales = CSaleOrderUserProps::GetList(
    array("USER_ID" => "ASC"),
    array("USER_ID" => $arResult["arUser"]["ID"]),
    false,
    false,
    array("*")
);

if ($arSaleProfile = $db_sales->Fetch()) {
    $db_propVals = CSaleOrderUserPropsValue::GetList(array("ID" => "ASC"), array("USER_PROPS_ID" => $arSaleProfile["ID"]));
    while ($arPropVals = $db_propVals->Fetch()) {
        if (!in_array($arPropVals["PROP_CODE"], $arSkipFilelds)) {
            $arResult["ADDITIONAL_FIELDS"][] = $arPropVals;
        }
        /*if ($arPropVals["PROP_CODE"] == 'PHONE') {
            if (!strlen(trim($arResult["arUser"]["PERSONAL_PHONE"]))) {
                $user = new CUser;
                $fields = Array(
                    "PERSONAL_PHONE" => $arPropVals['VALUE'],
                );
                $user->Update($arResult["arUser"]["ID"], $fields);
                $arResult["arUser"]["PERSONAL_PHONE"] = $arPropVals['VALUE'];
            }
        }*/
    }
}
//pre($arSaleProfile["ID"]);
//pre($arResult);
?>