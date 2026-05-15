<?php
$arResult = Array();
die();



//    $amount = str_replace(' ', '', $_POST['SUMM']);
//    $floatAmount = floatval(str_replace(',', '.', $amount));
//    $formatedAmount = number_format($floatAmount, 2, ',', '');
    $getAmount = 10000;
    $getOrderNumber = '3423412312';
    $getDescription =   "Иванов Иван";

    if ($getAmount && $getDescription && $getOrderNumber) {

        //file_put_contents(__DIR__ . '/log2.txt', print_r($getAmount .' '.$getDescription .' '.$getOrderNumber, true), FILE_APPEND);

        $params = array(
            'amount' => $getAmount, // Сумма пополнения
            'orderNumber' => $getOrderNumber, // Внутренний ID заказа
            'description' => $getDescription, // Описание
            'currency' => '643',
            'password' => '?s2a8Q5?T', // Здесь пароль от вашего API юзера в Сбербанке
            'userName' => 'alfasklad-api', // А тут его логин
            'returnUrl' => 'https://alfasklad.ru/payment/success_payment/' ,    // URL куда вернуть пользователя после перечисления средств
            'failUrl' => 'https://alfasklad.ru/payment/success_payment/'
        );

        $opts = array(// А здесь параметры для POST запроса
            'http' => array(
                'method' => 'POST',
                'header' => "Content-type: application/x-www-form-urlencoded",
                'content' => http_build_query($params),
                'timeout' => 3
            )
        );
      file_put_contents(__DIR__ . '/sber_log.txt', print_r(http_build_query($params), true), LOCK_EX);

        // И отправляем эти данные на сервер сбербанка
        $context = stream_context_create($opts);
        //file_put_contents(__DIR__ . '/log2.txt', print_r($context, true), FILE_APPEND);
        // Здесь должен быть URL тестового и боевого сервера Сбербанка
        $url = 'https://securepayments.sberbank.ru/payment/rest/register.do';

        $result = file_get_contents($url, false, $context);
        var_dump($result);

        // Расшифруем полученные данные в массив $arSbrfResult
        $arSbrfResult = (array)json_decode($result);
    }