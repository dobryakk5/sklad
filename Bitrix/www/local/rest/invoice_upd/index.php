<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

require_once $_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/email.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/payment.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/client.php';

require_once $_SERVER["DOCUMENT_ROOT"] . '/exchange/index.php';

$hash = '678klkl567p34wqx';
$parse_url = parse_url($_SERVER['REQUEST_URI']);
$url_chunk = explode('/', $parse_url['path']);

if($url_chunk[2] != $hash) return;

$data = json_decode(file_get_contents('php://input'), true);
$sch = $data['result']['Deals'][0]['INVOICES'][0]['ACCOUNT_NUMBER'];
$TotalInvoiceAmount = $data['result']['Deals'][0]['INVOICES'][0]['TOTALINVOICEAMOUNT'];
$price = $data['result']['Deals'][0]['INVOICES'][0]['PRICE'];
$InvoiceStatus = Exchange::invoiceStatus2Id($data['result']['Deals'][0]['INVOICES'][0]['STATUS_ID']);
$sch = urldecode($sch);

if ($data) {
    //file_put_contents(__DIR__ .'/log.txt', date(DATE_RFC822)."\nСчет: ".print_r($sch, true), FILE_APPEND);
    //file_put_contents(__DIR__ .'/log.txt', "\nСумма: ".print_r($TotalInvoiceAmount, true)."\n", FILE_APPEND);
   file_put_contents(__DIR__ .'/log_all.txt', date(DATE_RFC822)."\n".print_r($data, true), FILE_APPEND);
   
   if (!is_dir(__DIR__ .'/logs')) {
       mkdir(__DIR__ .'/logs');
   }

   file_put_contents(__DIR__ .'/logs/log-' . date('Y-m-d') . '.txt', date(DATE_RFC822)."\n".print_r($data, true), FILE_APPEND);

    $status = 'Invoice not found';

    if (CModule::IncludeModule("iblock")) {


        $arFilter = Array("IBLOCK_ID"=>53,  '=PROPERTY_NUMBER' => $sch);
        $arSelect = Array("ID", "IBLOCK_ID", "NAME",'PROPERTY_NUMBER');
        $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>1), $arSelect);
        if($ob = $res->GetNextElement()){
            $arFields = $ob->GetFields();
        }

        if(! empty($arFields)) {
            $status = 'Invoice updated!';
            CIBlockElement::SetPropertyValuesEx($arFields['ID'], false, array('TotalInvoiceAmount' => $TotalInvoiceAmount));
            
            CIBlockElement::SetPropertyValuesEx($arFields['ID'], false, ['STATUS' => $InvoiceStatus]);
            
            $res = CPrice::GetList(
                array(),
                array(
                    "PRODUCT_ID" => $arFields['ID'],
                    "CATALOG_GROUP_ID" => 1
                )
            );
            $field["PRICE"] = $price;
            if ($arr = $res->Fetch())
            {
                CPrice::Update($arr["ID"], $field);
            }
        }

    }
    $json = ['data' => $status];

    header("Content-Type: application/json");
    echo json_encode($json);
    exit();
}
die();
