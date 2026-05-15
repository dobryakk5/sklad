<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
Bitrix\Main\Loader::includeModule("sale");
Bitrix\Main\Loader::includeModule("catalog");
Bitrix\Main\Loader::includeModule("iblock");

require_once '/home/bitrix/www/local/php_interface/email.php';
require_once '/home/bitrix/www/local/php_interface/payment.php';
require_once '/home/bitrix/www/local/php_interface/client.php';

$data_json = json_decode(file_get_contents('php://input'), true);

if(! empty($data_json['Invoice']) && ! empty($data_json['PaymentNumber']) && ! empty($data_json['Amount'])) {


    $Invoice = $data_json['Invoice'];
    $PaymentNumber = $data_json['PaymentNumber'];
    $Amount = $data_json['Amount'];

    $data = [];
    $order = Bitrix\Sale\Order::load($Invoice);

    if($order) {
        $basket = Bitrix\Sale\Order::load($Invoice)->getBasket();
        foreach ($basket as $k => $basketItem) {
            $productID = $basketItem->getProductId();
        }
        $res = CIBlockElement::GetByID($productID);
        if ($ar_res = $res->GetNext()) {
            $iblock = $ar_res['IBLOCK_ID'];
        }

        $data['Invoice'] = $Invoice;
        $data['PaymentPlace'] = 'Site';
        $data['Amount'] = $Amount;// Сумма заказа
        //$data['PaymentDate'] = date('d.m.Y H:i:s'); //21.04.2023 11:01:24
        $data['IdClient'] = $order->getUserId();
        $data['PaymentNumber'] = $PaymentNumber;


        $dbRes = \Bitrix\Sale\PaymentCollection::getList([
            'select' => ['*'],
            'filter' => [
                '=ID' => $PaymentNumber,
            ]
        ]);

        $payment_arr = [];
        if ($item = $dbRes->fetch())
        {
            $payment_arr = $item;
        }

        $add_pay_record = true;

        if($payment_arr['PAID'] == 'N') {
            $paymentCollection = $order->getPaymentCollection();
            $onePayment = $paymentCollection[0];

            $onePayment->setField('DATE_PAID', $order->getField('DATE_INSERT'));
            $onePayment->setPaid("Y");
            $order->save();

            $payment_date = $order->getField('DATE_INSERT')->format("d.m.Y H:i:s");
            $add_pay_record = false;

        } elseif ($payment_arr['PAID'] == 'Y') {
            if(! empty($item["DATE_PAID"])) {
                $payment_date = $item["DATE_PAID"]->format("d.m.Y H:i:s");
            }
        }

        $data['PaymentDate'] = $payment_date;


        if ($iblock == 53) {//iblock счета

            //$data['PaymentNumber'] = $id;
            $arSelect = array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_INVOICE_GUID", "PROPERTY_INVOICE_GUID", "PROPERTY_CONTRACT_GUID");//IBLOCK_ID и ID обязательно должны быть указаны, см. описание arSelectFields выше
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

        if($add_pay_record) {
            $payment = new Payment();
            $payment->add(json_encode($data, JSON_UNESCAPED_UNICODE));
        }

        file_put_contents(__DIR__ . '/pay_rest_log.txt', date(DATE_ATOM) . "\n" . print_r($data, true) . "\n", FILE_APPEND);

        $json['Answer'] = 'OK';
        $json['Description'] = 'OK';

    } else {
        $json['Answer'] = 'Error';
        $json['Description'] = 'Order not found';
    }

    header("Content-Type: application/json");
    echo json_encode($json);
    exit();
}
