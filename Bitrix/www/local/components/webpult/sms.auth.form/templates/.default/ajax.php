<?php

use Bitrix\Main\Context;
use Bitrix\Main\Sms\Event;
use Bitrix\Main\UserPhoneAuthTable;

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

// Подключаем модуль веб-форм
CModule::IncludeModule("form");


function ValidatePhone($phone)
{
    $saveOnlyNumberInPhone = preg_replace('/[^0-9]/', '', $phone);
    if ((strlen($saveOnlyNumberInPhone) == 11) && (substr($saveOnlyNumberInPhone, 0, 1) == "7")) {
        $phone = $saveOnlyNumberInPhone;
    }

    if ((strlen($saveOnlyNumberInPhone) == 11) && (substr($saveOnlyNumberInPhone, 0, 1) == "8")) {
        $phone = "7" . substr($saveOnlyNumberInPhone, 1);
    }

    if (strlen($saveOnlyNumberInPhone) == 10) {
        $phone = "7" . $saveOnlyNumberInPhone;
    }
    return $phone;
}


// Проверка валидности отправки формы
if (check_bitrix_sessid()) {

    $request   = Context::getCurrent()->getRequest();
    $form_type = $request->getPost("form_type");

    if ($form_type == 'request_sms') {
        $userPhone = $request->getPost("USER_PHONE");

        $userPhone = ValidatePhone($userPhone);
        if (strlen($userPhone) == 11) {
            $filter  = ["=PERSONAL_PHONE" => $userPhone, "ACTIVE" => "Y"];
            $rsUsers = CUser::GetList(
                ($by = "ID"),
                ($order = "ASC"),
                $filter,
                ["FIELDS" => ["ID"]]
            );
            $userIds = [];
            while ($arUser = $rsUsers->Fetch()) {
                $userIds[] = (int)$arUser['ID'];
            }


            if (count($userIds) === 1) {
                $user_id = $userIds[0];
                $_SESSION['rand_sms'] = rand(100000, 999999);
                // если найден записываем его id в сессию по коду из смс
                $_SESSION['USER_ID_AUTH'][$_SESSION['rand_sms']] = $user_id;

                // отправляем смс
                $sms = new Event(
                    "SMS_USER_CONFIRM_NUMBER",
                    [
                        "USER_PHONE" => $userPhone,
                        "CODE"       => $_SESSION['rand_sms'],
                    ]
                );
                $sms->send(true);

                echo json_encode(['success' => true, 'errors' => [], 'user_Phone' => $user_id]);
            } elseif (count($userIds) > 1) {
                echo json_encode(['success' => false, 'errors' => ['sessid' => 'К этому номеру привязано несколько аккаунтов. Войдите по email или обратитесь в поддержку']]);
            } else {
                echo json_encode(['success' => false, 'errors' => ['sessid' => 'Номер телефона не найден']]);
            }
        }else{
            echo json_encode(['success' => false, 'errors' => ['sessid' => 'Номер телефона указан неверно']]);
        }
    } elseif ($form_type == 'sms_code') {
        $smsCode = $request->getPost("USER_PHONE_SMS_CODE");

        if (isset($_SESSION['USER_ID_AUTH'][$smsCode])) {
            global $USER;
            $USER->Authorize($_SESSION['USER_ID_AUTH'][$smsCode]);
            echo json_encode(['success' => true, 'errors' => []]);
        } else {
            echo json_encode(['success' => false, 'errors' => ['sessid' => 'Код указан неверно'], '_request' => array_map("htmlspecialcharsbx", $_REQUEST)]);
        }
    }
} else {
    // Предотвратили CSRF атаку
    echo json_encode(['success' => false, 'errors' => ['sessid' => 'Не верная сессия. Попробуйте обновить страницу'], '_request' => array_map("htmlspecialcharsbx", $_REQUEST)]);
}

// Файл ниже подключать обязательно, там закрытие соединения с базой данных
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php';
