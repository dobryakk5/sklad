<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$APPLICATION->RestartBuffer();
$router = new \Api\Services\ActionRouter();
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
$arResult = $router->route($request);
header('Content-Type: application/json');
echo json_encode($arResult);
die();
?>