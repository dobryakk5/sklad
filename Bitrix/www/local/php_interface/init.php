<?php

use Bitrix\Main;

define('PATH_ROOT', $_SERVER['DOCUMENT_ROOT']);
define('PATH_LOCAL', PATH_ROOT . '/local/');


require_once PATH_ROOT . '/bitrix/php_interface/init.php';
require_once(PATH_LOCAL . 'Api/Helpers/functions.php');
require_once(PATH_LOCAL . 'Api/define.php');
require_once(PATH_LOCAL . 'Api/DomainServices/UserNotPaidDocumentService.php');

require_once PATH_ROOT . '/local/php_interface/lib/Mobile_Detect.php';

require_once PATH_ROOT . '/local/php_interface/email.php';

require_once PATH_ROOT . '/local/php_interface/payment.php';
require_once PATH_ROOT . '/local/php_interface/client.php';


//ограничение для службы оплаты
CModule::IncludeModule('sale');
require_once PATH_ROOT . '/local/php_interface/lib/salerestrictions.php';

Bitrix\Main\EventManager::getInstance()->addEventHandler(
    'sale',
    'onSalePaySystemRestrictionsClassNamesBuildList',
    'myPayFunction'
);

function myPayFunction()
{
    return new \Bitrix\Main\EventResult(
        \Bitrix\Main\EventResult::SUCCESS,
        array(
            '\MyPaySystemRestriction' => '/local/php_interface/include/mypayrestriction.php',
        )
    );
}

//ограничение для службы оплаты


spl_autoload_register(function ($class) {
    $file = PATH_LOCAL . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});


require_once PATH_ROOT . '/local/vendor/autoload.php';

use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

Loader::registerAutoLoadClasses(null, [
    'CPriority_ext' => '/local/php_interface/lib/CPriority_ext.php',
]);

// Событие после создания элемента инфоблока
AddEventHandler("iblock", "OnAfterIBlockElementAdd", array("Events", "OnAfterIBlockElementAdd"));


class Events
{
    // создаем обработчик события "OnAfterIBlockElementAdd"
    public static function OnAfterIBlockElementAdd(&$arFields)
    {
        \Api\DomainServices\EventsHandler::OnAfterIBlockElementAdd($arFields);
        return true;
    }
}


if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/local/php_interface/lib/customtypehtml.php"));
include $_SERVER['DOCUMENT_ROOT'] . "/local/php_interface/lib/customtypehtml.php";


//AddEventHandler("main", "OnEndBufferContent", "ChangeMyContentTagB"); //Удаление тега b из контента не статичных страниц
//
//function ChangeMyContent(&$content)
//{
//    $content = sanitize_output($content);
//}
//
//function sanitize_output($buffer)
//{
//    $buffer = preg_replace('<b>', '<span class="bold">', $buffer);
//    $buffer = preg_replace('</b>', '</span>', $buffer);
//    return $buffer;
//}

AddEventHandler("main", "OnEndBufferContent", "ResetLinks");

function ResetLinks(&$content)
{
    $content = str_replace('/services/vremennoe-khranenie/', '/services/vremennoe-khranenie/', $content);
    $content = str_replace('/services/dostavka/', '/services/dostavka/', $content);
    $content = str_replace('/rental_catalog/', '/rental_catalog/', $content);
    $content = str_replace('/rental_catalog/', '/rental_catalog/', $content);

    $content = str_replace('khranenie-mebeli/', 'khranenie-mebeli/', $content);
    $content = str_replace('khranenie-mebeli/', 'khranenie-mebeli/', $content);
    $content = str_replace('khranenie-mebeli/', 'khranenie-mebeli/', $content);
    $content = str_replace('khranenie-mebeli/', 'khranenie-mebeli/', $content);
    //    $content = str_replace('khranenie-veshchey-s-kvartiry-kotoruyu-planiruyut-sdat-v-arendu/', 'storage/', $content);
    $content = str_replace('price/', 'price/', $content);
    $content = str_replace('<a class="link" href="sklad-vremennogo-khraneniya/"></a>', '<a class="link" href="/services/vremennoe-khranenie/"></a>', $content);
}


/* Таргет Консалт Компани: получаем utm метки */
function getUtmByUserId($USER_ID)
{
    global $APPLICATION;
    $UTM = array();
    if (CModule::IncludeModule("statistic")) {
        $arFilter = array(
            "USER_ID" => $USER_ID,
            "CHECK_PERMISSIONS" => "N"
        );
        if ($rs = CGuest::GetList(($by = "ID"), ($order = "DESC"), $arFilter, $is_filtered)) {
            while ($ar = $rs->Fetch()) {
                $url = $ar['FIRST_URL_TO'];
                parse_str(parse_url($url, PHP_URL_QUERY), $GET);
                $UTM['UTM_MEDIUM'] = $GET['utm_medium'] ? $GET['utm_medium'] : $ar['FIRST_REFERER1'];
                $UTM['UTM_SOURCE'] = $GET['utm_source'] ? $GET['utm_source'] : $ar['FIRST_REFERER2'];
                $UTM['UTM_CAMPAIGN'] = $GET['utm_campaign'] ? $GET['utm_campaign'] : $ar['FIRST_REFERER3'];
                $UTM['UTM_CONTENT'] = $GET['utm_content'];
                $UTM['UTM_TERM'] = $GET['utm_term'];
            }
        }
        if (empty($UTM)) {
            if ($APPLICATION->get_cookie('utm_source'))
                $UTM['UTM_SOURCE'] = $APPLICATION->get_cookie('utm_source');
            if ($APPLICATION->get_cookie('utm_medium'))
                $UTM['UTM_MEDIUM'] = $APPLICATION->get_cookie('utm_medium');
            if ($APPLICATION->get_cookie('utm_term'))
                $UTM['UTM_TERM'] = $APPLICATION->get_cookie('utm_term');
            if ($APPLICATION->get_cookie('utm_campaign'))
                $UTM['UTM_CAMPAIGN'] = $APPLICATION->get_cookie('utm_campaign');
            if ($APPLICATION->get_cookie('utm_content'))
                $UTM['UTM_CONTENT'] = $APPLICATION->get_cookie('utm_content');
        }
    }
    return $UTM;
}

AddEventHandler('main', 'OnProlog', 'UtmOnProlog');
function UtmOnProlog()
{
    global $APPLICATION;
    $utmArray = array('utm_source', 'utm_medium', 'utm_term', 'utm_campaign', 'utm_content');
    foreach ($utmArray as $utmItem) {
        if (isset($_GET[$utmItem]) && $_GET[$utmItem]) {
            $APPLICATION->set_cookie($utmItem, htmlspecialcharsEx($_GET[$utmItem]));
            $prefix = COption::GetOptionString("main", "cookie_name", "BITRIX_SM");
            $_COOKIE[$prefix . '_' . $utmItem] = htmlspecialcharsEx($_GET[$utmItem]);
        }
    }
    if ($_SERVER['HTTP_REFERER']) {
        $url = parse_url($_SERVER['HTTP_REFERER']);
        if ($url['host'] != $_SERVER['SERVER_NAME']) { // Внешний переход
            if (!isset($_GET['utm_source'])) {
                $utm_source = $url['host'];
                $utm_medium = 'referral';
                if (stripos($utm_source, 'yandex') !== false) {
                    $utm_source = 'yandex';
                    $utm_medium = 'organic';
                }
                if (stripos($utm_source, 'google') !== false) {
                    $utm_source = 'google';
                    $utm_medium = 'organic';
                }
                $APPLICATION->set_cookie('utm_source', $utm_source);
                $APPLICATION->set_cookie('utm_medium', $utm_medium);
                $prefix = COption::GetOptionString("main", "cookie_name", "BITRIX_SM");
                $_COOKIE[$prefix . '_utm_source'] = $utm_source;
                $_COOKIE[$prefix . '_utm_medium'] = $utm_medium;
            }
        }
    }
}


AddEventHandler('iblock', 'OnAfterIBlockElementAdd', 'OnAfterIBlockElementHandler');
AddEventHandler('iblock', 'OnAfterIBlockElementUpdate', 'OnAfterIBlockElementHandler');

function getFirstKey($array)
{
    if (is_array($array)) {
        reset($array);
        $first_key = key($array);
        return $first_key;
    }
    return null;
}

// Автоматически заполняем поле STORE_ID в договоре
function OnAfterIBlockElementHandler(&$arFields)
{
    if ($arFields['IBLOCK_ID'] == 52) {
        if (CModule::IncludeModule("iblock")) {
            $_p_box = $arFields["PROPERTY_VALUES"][509];
            if (is_array($_p_box[getFirstKey($_p_box)])) {
                $_p_box_value = $_p_box[getFirstKey($_p_box)]["VALUE"];
            } else {
                $_p_box_value = $_p_box[getFirstKey($_p_box)];
            }

            $arSelect = ["ID", 'IBLOCK_ID', "IBLOCK_SECTION_ID"];
            $arFilter = ["IBLOCK_ID" => 40, 'ID' => $_p_box_value];
            $res = CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
            if (is_object($res)) {
                $ob = $res->GetNextElement();
                $box_data = $ob->GetFields();
                CIBlockElement::SetPropertyValuesEx($arFields["ID"], 52, ['STORE_ID' => $box_data['IBLOCK_SECTION_ID']]);
            }
        }
    }
}

/*
Очищаем корзину после сохранения заказа
*/
\Bitrix\Main\EventManager::getInstance()->addEventHandler(
    'sale',
    'OnSaleOrderSaved',
    'SetOrderDescription'
);
function SetOrderDescription(\Bitrix\Main\Event $event)
{
    Bitrix\Main\Loader::includeModule("sale");
    Bitrix\Main\Loader::includeModule("catalog");
    Bitrix\Main\Loader::includeModule("iblock");
    Bitrix\Main\Loader::includeModule("currency");

    $order = $event->getParameter("ENTITY");
    $isNew = $event->getParameter("IS_NEW");

    if ($isNew) {
        $basket = $order->getBasket();
        $basketItems = $basket->getBasketItems();
        $jsonParams = [];
        $orderDescription = '';

        $rwUser = \Bitrix\Main\UserTable::getList([
            'filter' => ["ID" => $order->getUserId()],
            'select' => ['NAME', 'LAST_NAME', 'SECOND_NAME', "EMAIL"]
        ])->fetch();
        $user = ($rwUser['LAST_NAME'] ?? '') . ' ' . ($rwUser['NAME'] ?? '') . ' ' . ($rwUser['SECOND_NAME'] ?? '');

        // поправляем товары без имени
        foreach ($basketItems as $basketItem) {
            if ($basketItem->getField('NAME') == '') {
                $basketItem->setField('NAME', "Оплата по счету"); //Аренда склада
                $basketItem->save();
            }
        }
        $basket->save();

        //ищем, есть ли в заказе счета
        $is_invoice = false;
        foreach ($basketItems as $basketItem) {
            $rsInvoice = CIBlockElement::GetList(
                ["ID" => "DESC"],
                ["IBLOCK_ID" => INVOICES_IBLOCK, "ID" => $basketItem->getProductId()],
                false,
                false,
                ['ID', 'NAME', 'PROPERTY_NUMBER', 'PROPERTY_CONTRACT_NUMBER']
            );
            if ($rwInvoice = $rsInvoice->fetch()) {
                $is_invoice = true;
                $jsonParams[] = [
                    'name' => $user,
                    'numberContract' => $rwInvoice['PROPERTY_CONTRACT_NUMBER_VALUE'],
                    'numberInvoice' => $rwInvoice['NAME'],
                ];
            }
        }

        //ищем, есть ли в заказе пополнение баланса
        $is_balance = false;
        foreach ($basketItems as $basketItem) {
            $rsBalance = CIBlockElement::GetList(
                ["ID" => "DESC"],
                ["IBLOCK_ID" => BALANCE_ITEMS_IBLOCK, "ID" => $basketItem->getProductId()],
                false,
                false,
                ['ID', 'NAME']
            );
            if ($rwBalance = $rsBalance->fetch()) {
                $is_balance = true;
                $numberContract = '';

                $basketPropertyCollection = $basketItem->getPropertyCollection();
                foreach ($basketPropertyCollection as $propertyItem) {
                    if ($propertyItem->getField('CODE') == 'CONTRACT_NUMBER') {
                        $numberContract = $propertyItem->getField('VALUE');
                        break;
                    }
                }

                $jsonParams[] = [
                    'name' => $user,
                    'numberContract' => $numberContract,
                    'target' => $rwBalance['NAME']
                ];
            }
        }
        $order->setField('USER_DESCRIPTION', json_encode($jsonParams, JSON_UNESCAPED_UNICODE));

        $order->save();
    }
}


AddEventHandler("main", "OnEpilog", "SetNoIndex");

function SetNoIndex()
{
    CModule::IncludeModule("iblock");
    $arSelect = array("ID", "NAME", "PROPERTY_NOINDEX");
    $arFilter = array("IBLOCK_ID" => IntVal(61), "NAME" => $GLOBALS["APPLICATION"]->GetCurDir(), "ACTIVE" => "Y");
    $res = CIBlockElement::GetList(array(), $arFilter, false, array("nTopCount" => 1), $arSelect);
    while ($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();
    }

    if ($arFields["PROPERTY_NOINDEX_VALUE"] == "Да") {
        $GLOBALS["APPLICATION"]->AddHeadString('<meta name="robots" content="noindex" /> ');
    }
}

AddEventHandler('main', 'OnEpilog', 'OnEpilogHandler');

function OnEpilogHandler()
{
    global $APPLICATION;
    $r_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $APPLICATION->AddHeadString('<link rel="canonical" href="https://' . $_SERVER['SERVER_NAME'] . parse_url($url, PHP_URL_SCHEME) . ($r_path && $r_path != '/' ? $r_path : '') . '" />');
}


AddEventHandler('form', 'onAfterResultAdd', 'onAfterResultAddUpdateIB');
function onAfterResultAddUpdateIB($WEB_FORM_ID, $RESULT_ID)
{
    global $USER;
    Bitrix\Main\Loader::includeModule("iblock");

    $r = CFormResult::GetByID($RESULT_ID);
    $ans = [];
    $result_data = CFormResult::GetDataByID($RESULT_ID, [], $r, $ans);

    $rsForm = CForm::GetByID($WEB_FORM_ID);
    $arForm = $rsForm->Fetch();

    $el = new CIBlockElement;
    $PROP = array(
        'FORM_CODE' => $arForm['SID'],
        'FORM_RESULT_ID' => $RESULT_ID,
    );
    $i = 0;

    $text = "";

    foreach ($result_data as $value) {

        $answer = $value[0]['USER_TEXT'] ?? (trim($value[0]['ANSWER_TEXT']));
        if (!strlen($answer)) {
            $answer = $value[0]['ANSWER_VALUE'];
        }
        //        $text .= $value[0]['TITLE'].":\n". ($value[0]['USER_TEXT'] ?? (trim($value[0]['ANSWER_TEXT'])) ?? $value[0]['ANSWER_VALUE']) . "\n\n";
        if ($answer) {
            $text .= $value[0]['TITLE'] . ":\n" . $answer . "\n\n";

            $PROP['FIELDS']['n' . $i++] = [
                "VALUE" => $value[0]['TITLE'],
                //                "DESCRIPTION" => $value[0]['USER_TEXT'] ?? $value[0]['ANSWER_TEXT']
                "DESCRIPTION" => $answer,
            ];
        }
    }


    $PROP['REQUEST'][0] = array("VALUE" => array("TEXT" => $text, "TYPE" => "text"));

    $PROP['USER'] = $USER->GetID();
    $arLoadProductArray = array(
        "MODIFIED_BY" => $USER->GetID(),
        "IBLOCK_SECTION_ID" => false,
        "IBLOCK_ID" => 67,
        "NAME" => $arForm['NAME'],
        "PROPERTY_VALUES" => $PROP,
        "ACTIVE" => "Y",
    );

    if ($EL_ID = $el->Add($arLoadProductArray)) {
        //        loger($arLoadProductArray, 'arLoadProductArray');
    } else {
        loger($el->LAST_ERROR, 'LAST_ERROR');
    }
}


function pre($var, $stop = false, $inconsole = false, $UID = false)
{
    if ($GLOBALS['USER']->IsAdmin()) {
        $bt = debug_backtrace();
        $bt = $bt[0];
        $dRoot = $_SERVER["DOCUMENT_ROOT"];
        $dRoot = str_replace("/", "\\", $dRoot);
        $bt["file"] = str_replace($dRoot, "", $bt["file"]);
        $dRoot = str_replace("\\", "/", $dRoot);
        $bt["file"] = str_replace($dRoot, "", $bt["file"]);


        if ($inconsole) {
            echo "<script>console.log('File: " . $bt['file'] . " [" . $bt['line'] . "]');console.debug(" . json_encode($var) . ");</script>";
        } else {
            echo '<div style="padding:3px 5px; background:#99CCFF; font-weight:bold;">File: ' . $bt["file"] . ' [' . $bt["line"] . ']</div>';
            echo '<pre>';
            ((is_array($var) || is_object($var)) ? print_r($var) : var_dump($var));
            echo '</pre>';
        }
        if ($stop)
            die();
    }
}

function d($data, $defaultUserId = "12833")
{
    global $USER;
    if ($USER->GetId() == $defaultUserId || $defaultUserId == '999') {
        echo "<pre >" . print_r($data, true) . "</pre>";
    }
}

function loger($array, $filename = 'log')
{
    $path = '/home/bitrix/www/logs';

    $bt = debug_backtrace();
    $bt = $bt[0];
    $dRoot = $_SERVER["DOCUMENT_ROOT"];
    $dRoot = str_replace("/", "\\", $dRoot);
    $bt["file"] = str_replace($dRoot, "", $bt["file"]);
    $dRoot = str_replace("\\", "/", $dRoot);
    $bt["file"] = str_replace($dRoot, "", $bt["file"]);


    //    $cache = var_export($array, true);
    $cache = date('d.m.Y H:i:s') . "\n";
    $cache .= 'File: ' . $bt['file'] . ' [' . $bt['line'] . ']' . "\n";
    $cache .= print_r($array, true);

    file_put_contents($path . DIRECTORY_SEPARATOR . $filename . '.txt', $cache . "\n\n\n", FILE_APPEND);
}


AddEventHandler("sale", "OnSalePayOrder", "saleOrderHandler");
function saleOrderHandler($id, $val)
{
    if ($val != 'Y') return;

    $basket = Bitrix\Sale\Order::load($id)->getBasket();
    $basketItems = $basket->getBasketItems();
    foreach ($basket as $k => $basketItem) {
        $productID = $basketItem->getProductId();
        CIBlockElement::SetPropertyValuesEx($productID, 53, array('STATUS' => 421)); //меняем статус на В обработке
    }
}


AddEventHandler("sale", "OnSalePayOrder", "saleOrderSend");
function saleOrderSend($id, $val)
{
    if ($val != 'Y') return;

    Bitrix\Main\Loader::includeModule("sale");
    Bitrix\Main\Loader::includeModule("catalog");
    Bitrix\Main\Loader::includeModule("iblock");

    $data = [];
    $order = Bitrix\Sale\Order::load($id);

    $basket = Bitrix\Sale\Order::load($id)->getBasket();
    foreach ($basket as $k => $basketItem) {
        $productID = $basketItem->getProductId();
    }
    $res = CIBlockElement::GetByID($productID);
    if ($ar_res = $res->GetNext()) {
        $iblock = $ar_res['IBLOCK_ID'];
    }

    $data['Invoice'] = $id;
    $data['PaymentPlace'] = 'Site';
    //$data['Amount'] = $order->getPrice();// Сумма заказа
    //$order->getSumPaid()
    $data['Amount'] = $order->getSumPaid();

    if ($order->getField('DATE_PAYED')) {
        $data['PaymentDate'] = $order->getField('DATE_PAYED')->format("d.m.Y H:i:s");
    } else {
        $data['PaymentDate'] = date('d.m.Y H:i:s'); //21.04.2023 11:01:24
    }

    $data['IdClient'] = $order->getUserId();


    $dbRes = \Bitrix\Sale\PaymentCollection::getList([
        'select' => ['*'],
        'filter' => [
            '=ORDER_ID' => $id
        ]
    ]);
    $PaymentNumber = 0;
    while ($item = $dbRes->fetch()) {
        $PaymentNumber = $item['ID'];
    }

    $data['PaymentNumber'] = $PaymentNumber;

    if ($iblock == 53) { //iblock счета

        //$data['PaymentNumber'] = $id;
        $arSelect = array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_INVOICE_GUID", "PROPERTY_INVOICE_GUID", "PROPERTY_CONTRACT_GUID"); //IBLOCK_ID и ID обязательно должны быть указаны, см. описание arSelectFields выше
        $arFilter = array("IBLOCK_ID" => 53, 'ID' => $productID);
        $res = CIBlockElement::GetList(array(), $arFilter, false, array("nPageSize" => 1), $arSelect);
        if ($ob = $res->fetch()) {
            // $arFields = $ob->GetFields();
            if ($ob['PROPERTY_INVOICE_GUID_VALUE']) {
                $data['GUID_Invoice'] = $ob['PROPERTY_INVOICE_GUID_VALUE'];
            } else {
                $data['GUID_Invoice'] = 0;
            }
            if ($ob['PROPERTY_CONTRACT_GUID_VALUE']) {
                $data['IdContract'] = $ob['PROPERTY_CONTRACT_GUID_VALUE'];
            } else {
                $data['IdContract'] = 0;
            }
        }
    } else {
        $data['GUID_Invoice'] = 0;
        $data['IdContract'] = 0;
    }

    $payment = new Payment();
    $payment->add(json_encode($data));
}

function getFloorValue($code)
{
    $xml_id_floors = [];
    $property_enums = CIBlockPropertyEnum::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => 40, "CODE" => 'FLOOR'));
    while ($enum_fields = $property_enums->GetNext()) {
        $xml_id_floors[$enum_fields['XML_ID']] = $enum_fields['VALUE'];
    }
    return $xml_id_floors[$code];
}


function logf($data)
{
    file_put_contents(getcwd() . '/log.txt', date(DATE_ATOM) . "\n", FILE_APPEND);
    file_put_contents(getcwd() . '/log.txt', print_r($data, true), FILE_APPEND);
}

function console($data)
{
    if (is_array($data) || is_object($data)) {
        echo ("<script>console.log('" . json_encode($data) . "');</script>");
    } else {
        echo ("<script>console.log('" . $data . "');</script>");
    }
}

AddEventHandler("main", "OnAfterUserLogin", array("MyClass", "OnAfterUserLoginHandler"));

class MyClass
{
    // создаем обработчик события "OnAfterUserLogin"
    public static function OnAfterUserLoginHandler(&$fields)
    {
        // если логин не успешен то
        if ($fields['USER_ID'] <= 0) {
            file_put_contents(__DIR__ . '/login_log.txt', date(DATE_ATOM) . "\n", FILE_APPEND);
            file_put_contents(__DIR__ . '/login_log.txt', print_r($fields, true), FILE_APPEND);
            //            if(!CModule::IncludeModule("iblock"))
            //                return;


            $mess = '';
            $rsUsers = CUser::GetList([], [], ['EMAIL' => $fields['LOGIN']]);
            $fio = '';
            $phone = '';
            if ($res = $rsUsers->fetch()) {
                $mess .= 'Неверный пароль' . "\n";
                $mess .= 'Логин: ' . $fields['LOGIN'] . "\n";

                $user_id = $res['ID'];
                $fio = $res['LAST_NAME'] . ' ' . $res['NAME'] . ' ' . $res['SECOND_NAME'];
                $phone = 'Телефон:' . $res['PERSONAL_PHONE'];
            }
            if ($user_id) {
                $arSelect = array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_STATUS", 'PROPERTY_USER', 'PROPERTY_BOX'); //IBLOCK_ID и ID обязательно должны быть указаны, см. описание arSelectFields выше
                $arFilter = array("IBLOCK_ID" => 52, "=PROPERTY_USER" => $user_id, '=PROPERTY_STATUS_VALUE' => 'Активный');
                $res = CIBlockElement::GetList(array(), $arFilter, false, array("nPageSize" => 1), $arSelect);
                $box_id = null;
                while ($ob = $res->GetNextElement()) {
                    $arFields = $ob->GetFields();
                    $box_id = $arFields['PROPERTY_BOX_VALUE'];
                }

                if ($box_id) {
                    $sect_id = null;
                    $sklad = null;
                    $res_sect = CIBlockElement::GetByID($box_id);
                    if ($ar_res = $res_sect->GetNext()) {
                        $sect_id = $ar_res['IBLOCK_SECTION_ID'];
                    }

                    if ($sect_id) {
                        $res2 = CIBlockSection::GetByID($sect_id);
                        if ($ar_res = $res2->GetNext()) {
                            $sklad = $ar_res['NAME'];
                        }
                    }
                }
                $mess .= $fio . "\n";
                $mess .= $phone . "\n";
                $mess .= $sklad . "\n";


                $arEventFields = array('MESS' => $mess);
                $res = CEvent::SendImmediate("ERROR_LOGIN", SITE_ID, $arEventFields);
            }


            //logf($res);
        }
    }
}

//уведомление об ошибке оплаты
function sendErrorPayment($gateData, $gateResponse)
{
    unset($gateData['userName']);
    unset($gateData['password']);

    $desc = array_merge($gateData, $gateResponse);

    CEventLog::Add(array(
        "SEVERITY" => "SECURITY",
        "AUDIT_TYPE_ID" => "ERROR_PAYMENT",
        "MODULE_ID" => "main",
        "ITEM_ID" => null,
        "DESCRIPTION" => print_r($desc, true),
    ));

    $arEventFields = array('MESS' => print_r($desc, true));
    CEvent::SendImmediate("ERROR_PAYMENT", SITE_ID, $arEventFields);
}


AddEventHandler("main", "OnEpilog", "setHLseo");
function setHLseo()
{
    Loader::includeModule("highloadblock");
    global $APPLICATION;

    if (strpos($_SERVER["HTTP_HOST"], 'spb') !== false) {
        $site_id = 44;
    } else {
        $site_id = 43;
    }

    $hlbl = 7; // Указываем ID нашего highloadblock блока к которому будет делать запросы.
    $hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();

    $entity = HL\HighloadBlockTable::compileEntity($hlblock);
    $entity_data_class = $entity->getDataClass();

    $rsData = $entity_data_class::getList(array(
        "select" => array("*"),
        "order" => array("ID" => "ASC"),
        "limit" => 1,
        "filter" => array("UF_URL" => $APPLICATION->GetCurDir(), "UF_SITE_ID" => $site_id)  // Задаем параметры фильтра выборки
    ));

    while ($arData = $rsData->Fetch()) {
        if (!empty($arData["UF_TITLE"])) {
            $APPLICATION->SetPageProperty('title', $arData["UF_TITLE"]);
        }
        if (!empty($arData["UF_DESCRIPTION"])) {
            $APPLICATION->SetPageProperty('description', $arData["UF_DESCRIPTION"]);
        }
        if (!empty($arData["UF_KEYWORDS"])) {
            $APPLICATION->SetPageProperty('keywords', $arData["UF_KEYWORDS"]);
        }
        if (!empty($arData["UF_H1"])) {
            $APPLICATION->SetTitle($arData["UF_H1"]);
        }
    }
}


Main\EventManager::getInstance()->addEventHandler(
    'sale',
    'OnSaleOrderBeforeSaved',
    'saleOrderBeforeSaved'
);
function saleOrderBeforeSaved(Main\Event $event)
{
    $order = $event->getParameter("ENTITY");
    $propertyCollection = $order->getPropertyCollection();

    $rwUser = \Bitrix\Main\UserTable::getList([
        'filter' => ["ID" => $order->getUserId()],
        'select' => ['NAME', 'LAST_NAME', 'SECOND_NAME', "EMAIL"]
    ])->fetch();

    foreach ($propertyCollection as $propertyItem) {
        switch ($propertyItem->getField("CODE")) {
            // Установка полного адреса в формате: Адрес, Город, Индекс
            case 'EMAIL':
                $propertyItem->setField("VALUE", $rwUser['EMAIL']);
                break;
        }
    }

    $event->addResult(
        new Main\EventResult(
            Main\EventResult::SUCCESS,
            $order
        )
    );
}



if (strripos($_SERVER['REQUEST_URI'], '/?') !== false) {
    // $APPLICATION->AddHeadString('<meta name="robots" content="none">', true);
}

/**
 * при формировании сайтмапа при выборе секций инфоблока 40 проверяем их принадлежность к региону
 * убираем Питерские склады
 */
Main\EventManager::getInstance()->addEventHandler(
    'solverweb.sitemap',
    'OnBeforeSectionGetList',
    'sitemapBeforeSectionGetList'
);
function sitemapBeforeSectionGetList(&$arSectionsFilter, &$SPU, $iblock, $arMap, &$arSectionsSelect)
{
    if ($iblock == 40 && $arMap['SITE_ID'] == 's1') {
        $arSectionsFilter['!UF_LINK_REGION'] = 8897914;
    }
}


function userSavedPayment($uid)
{
    $rsUser = \CUser::GetByID($uid);
    $arUser = $rsUser->Fetch();

    if ($arUser['UF_AUTOPAYMEN_METHOD']) {
        $res = \CIBlockElement::GetList(["id" => "DESC"], ['ID' => $arUser['UF_AUTOPAYMEN_METHOD']], false, false, [
            'ID',
            'IBLOCK_ID',
            'ACTIVE',
            'NAME',
            'PROPERTY_PM_STATUS',
            'PROPERTY_PM_SAVED',
            'PROPERTY_AUTOPAY',
        ]);
        if ($arPayment = $res->GetNext()) {
            if ($arPayment['ACTIVE'] == 'Y' && $arPayment['PROPERTY_PM_STATUS_VALUE'] == 'active' && $arPayment['PROPERTY_PM_SAVED_VALUE']) {
                return $arPayment['NAME'];
            }
        }
    }

    return false;
}

function userAutopaymentEnabled($uid)
{
    $rsUser = \CUser::GetByID($uid);
    $arUser = $rsUser->Fetch();

    if ($arUser['UF_AUTOPAYMEN_METHOD']) {
        $res = \CIBlockElement::GetList(["id" => "DESC"], ['ID' => $arUser['UF_AUTOPAYMEN_METHOD']], false, false, [
            'ID',
            'IBLOCK_ID',
            'ACTIVE',
            'NAME',
            'PROPERTY_PM_STATUS',
            'PROPERTY_PM_SAVED',
            'PROPERTY_AUTOPAY',
        ]);
        if ($arPayment = $res->GetNext()) {
            if ($arPayment['ACTIVE'] == 'Y' && $arPayment['PROPERTY_PM_STATUS_VALUE'] == 'active' && $arPayment['PROPERTY_PM_SAVED_VALUE'] && $arPayment['PROPERTY_AUTOPAY_VALUE'] == 'Y') {
                return $arPayment['NAME'];
            }
        }
    }

    return false;
}

/**
 * Пришёл корректно обаботанный платёж от платёжной системы 
 */
Main\EventManager::getInstance()->addEventHandler(
    'sale',
    'OnSaleAfterPsServiceProcessRequest',
    'saleAfterPsServiceProcessRequest'
);

function saleAfterPsServiceProcessRequest(Main\Event $event)
{
    $data = $event->getParameter('serviceResult')->getPsData();

    /**
     * Если сумма в платеже отличается от записанной суммы в платеже, то исправляем её до отправки в 1с
     */
    // print_r($event->getParameter('payment'));
    // exit('op');


    if (
        $event->getParameter('payment')->getPaySystem()->getServiceId() == 7 &&
        isset($data['PS_STATUS']) && $data['PS_STATUS'] == 'Y' &&
        isset($data['PS_RECURRING_TOKEN']) && !empty($data['PS_RECURRING_TOKEN'])
    ) {

        if ($uid = $event->getParameter('payment')->getCollection()->getOrder()->getUserId()) {

            /**
             * Если платёж оплачен и это юмани и в платеже указано что он сохранён как повторяющшийся
             * сохраняем новый способ оплаты и прикрепляем его к пользователю
             */
            $pid = (new \CIBlockElement)->Add(
                [
                    "IBLOCK_SECTION_ID" => false,
                    "IBLOCK_ID"         => 69,
                    "NAME"              => $data['PS_RECURRING_TOKEN'],
                    "ACTIVE"            => "Y",
                    'PROPERTY_VALUES'   => ['PM_STATUS' => 'active', 'PM_SAVED' => true],
                ]
            );

            if ($pid) {
                global $USER_FIELD_MANAGER;
                $USER_FIELD_MANAGER->Update("USER", $uid, ["UF_AUTOPAYMEN_METHOD" => $pid]);
            }
        }
    }
}

/**
 * Получить путь до сгенерированного изображения QR-кода по телефону
 * если его нет, создать
 * @param $phone string телефон
 * @param $asImg boolean вернуть тело картинки или путь до сохранённой
 */
function getQRphonePath($phone, $asImg = false)
{
    $phone = trim(str_replace(['(', ')', '-', '+', ' '], '', $phone));

    $path = 'upload/qr/' . md5($phone) . '.png';

    if (!file_exists($path)) {

        if (!is_dir(dirname($path))) {
            mkdir(dirname($path));
        }

        $writer = new PngWriter();

        $qrCode = new QrCode(
            data: 'tel:' . $phone,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::Low,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: new Color(0, 0, 0),
            backgroundColor: new Color(255, 255, 255)
        );

        $result = $writer->write($qrCode, null, null);

        $result->saveToFile($path);
    }

    if ($asImg) {
        return $result->getString();
    } else {
        return '/' . $path;
    }
}
