<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once '/home/bitrix/www/local/php_interface/email.php';
require_once '/home/bitrix/www/local/php_interface/payment.php';
require_once '/home/bitrix/www/local/php_interface/client.php';

$hash = '008qllbkg76ujadh';
$parse_url = parse_url($_SERVER['REQUEST_URI']);
$url_chunk = explode('/', $parse_url['path']);
$method = $url_chunk[3];

if($url_chunk[2] != $hash) return;

switch ($method) {
        /**
         * Кнопка получить логин ПК в 1с
         */
    case "GetUserLoginAndPhone":
        if(! empty($_GET['id'])) {
            $arr_user = [];
            $rsUser = CUser::GetByID((int)$_GET['id']);
            $arUser = $rsUser->Fetch();
            $arr_user['ID'] = $arUser['ID'];
            $arr_user['login'] = $arUser['LOGIN'] ?? "";
            $arr_user['phone'] = $arUser['PERSONAL_PHONE'] ?? "";
            header("Content-Type: application/json");
            echo json_encode($arr_user);
            exit();
        }
        break;

        /**
         * Кнопка изменить логин ПК в 1с
         */
    case "SetNewUserLoginAndPhone":
        $data = json_decode(file_get_contents('php://input'), true);
        if ($data['ID'] && $data['login'] && $data['phone']) {
            $user = new CUser;
            $fields = Array(
                "EMAIL"             => $data['login'],
                "LOGIN"             => $data['login'],
                // чтобы заполнялось и поле "Номер телефона для регистрации" для входа по телефону вместо логина
                // 'PHONE_NUMBER' => $data['phone'],
                'PERSONAL_PHONE' => $data['phone'],
            );
            $user->Update($data['ID'], $fields);
            $json = ['data' => 'User data updated!'];
        } else {
            $json = ['data' =>'Error!'];
        }
        header("Content-Type: application/json");
        echo json_encode($json);
        exit();

    case "GetVerifiedEmailById":
        if(! empty($_GET['id'])) {
            $email = new EmailConfirm();
            if ($email->hashExist($_GET['id'])) {
                $json = ['data' => 'Email exist'];
            } else {
                $json = ['data' =>'Email not found'];
            }
            header("Content-Type: application/json");
            echo json_encode($json);
            exit();
        }
        break;
}
?>