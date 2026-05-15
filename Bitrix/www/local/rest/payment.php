<?php

$lockFile = '/tmp/alfasklad_payment_export.lock';
$lockFp = fopen($lockFile, 'c');

if (!$lockFp) {
    http_response_code(500);
    echo date(DATE_ATOM) . " Cannot open lock file\n";
    exit;
}

if (!flock($lockFp, LOCK_EX | LOCK_NB)) {
    file_put_contents(__DIR__ . '/paed_lock_skip.txt', date(DATE_ATOM) . " payment export already running\n", FILE_APPEND);
    echo date(DATE_ATOM) . " payment export already running\n";
    exit;
}

register_shutdown_function(static function () use ($lockFp) {
    flock($lockFp, LOCK_UN);
    fclose($lockFp);
});

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

require_once '/home/bitrix/www/local/php_interface/email.php';
require_once '/home/bitrix/www/local/php_interface/payment.php';
require_once '/home/bitrix/www/local/php_interface/client.php';

$payment = new Payment();
$data = $payment->getItem();

if (! empty($data)) {
    $new_json = [];

    foreach ($data as $id => $item) {
        $new_json[$id]['ID'] = $item['ID'];
        $new_json[$id]['DATA'] = json_decode($item['UF_JSON_DATA'], true);
    }

    $rest = Client::getRequest('/PaymentUploadPost', json_encode($new_json));

    file_put_contents(__DIR__ . '/paed_res.txt', date(DATE_ATOM) . "\n" . print_r($rest, true) . "\n", FILE_APPEND);

    if (! empty($rest)) {
        foreach ($rest as $k => $dt) {
            if (trim($dt['DATA']['Answer']) == 'ERROR') {
                $payment->updateItemError($dt['ID'], $dt['DATA']['Description']);
            } elseif (trim($dt['DATA']['Answer']) == 'OK') {
                $payment->updateItem($dt['ID']);
            }
        }
    }
}
