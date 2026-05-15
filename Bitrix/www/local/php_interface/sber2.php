<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Web;

die();
$getAmount = '2000';
$getOrderNumber = '25243';
$getDescription =   "Иванов Иван";
$returnUrl = urlencode('https://alfasklad.ru/payment/success_payment/');


$params = array(
    //'userName' => urlencode('alfasklad-api'), // А тут его логин
    //'password' => urlencode('?s2a8Q5?T'), // Здесь пароль от вашего API юзера в Сбербанке
    'orderNumber' => $getOrderNumber, // Внутренний ID заказа
    'amount' => $getAmount, // Сумма пополнения
    'returnUrl' => 'https://alfasklad.ru/payment/success_payment/' ,    // URL куда вернуть пользователя после перечисления средств
    'description' => $getDescription, // Описание
);

$url = 'https://securepayments.sberbank.ru/payment/rest/register.do?userName=alfasklad-api&password='.urlencode('?s2a8Q5?T').'&orderNumber='.$getOrderNumber.'&amount='.$getAmount.'&returnUrl='.$returnUrl;

$curl = curl_init($url);

curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$headers = array(
    "Content-type: application/x-www-form-urlencoded",

);

curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_VERBOSE,  true);
curl_setopt($curl, CURLINFO_HEADER_OUT, true);


$data = $params;

$body = json_encode($data);

//file_put_contents(__DIR__ . '/sber_log2.txt', print_r($body, true), LOCK_EX);

curl_setopt($curl, CURLOPT_POSTFIELDS, $body);

$resp = curl_exec($curl);

$res = json_decode($resp, true);

print_r($res);

curl_close($curl);
