<?php
class Client
{
    static $login = 'obmen';
    static $password = 'obmen';

    public  static function getRequest($r, $json)
    {


        //$url = "http://188.65.135.222:2111/sklad_adex/hs/Payment/v1" . $r;

        //$url = "http://188.65.135.222:2111/Sklad-managers/hs/Payment/v1" . $r;
        $url = "http://188.92.245.179:2111/Sklad-managers/hs/Payment/v1" . $r;

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "Accept: application/json",
            "Authorization: Basic " . base64_encode(self::$login . ":" . self::$password),
        );

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($curl, CURLOPT_POST, 1);

        // $data = array_merge(['token' => self::$token], $params);

        curl_setopt($curl, CURLOPT_POSTFIELDS,  $json);


        $resp = curl_exec($curl);

        $info = curl_getinfo($curl);

        $resp = json_decode($resp, true);


        file_put_contents(__DIR__ . '/rest_log.txt', date(DATE_ATOM)."\n".print_r( $resp, true), FILE_APPEND);

       //d($resp);

        curl_close($curl);

        return $resp;
//        if ($resp['status'] == 200) {
//            return $resp['result'];
//        } else {
//            //var_dump($resp);
//            return false;
//        }


    }



}
?>