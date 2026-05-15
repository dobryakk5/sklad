<?php

class Yookassa
{
    const API_URL = 'https://api.yookassa.ru';
    const API_VERSION = 'v3';

    private $shopId;
    private $secret;
    /**
     * @var object Bitrix\Main\Web\HttpClient
     */
    private $httpClient;

    public function __construct($shopId, $secret, $httpClient)
    {
        $this->shopId = $shopId;
        $this->secret = $secret;
        $this->httpClient = $httpClient;
    }

    /**
     * Запрос на получение объекта способа оплаты
     */
    public function requestConfirmation()
    {
        $result = $this->request('POST', 'payment_methods', [
            'confirmation' => [
                'type' => 'redirect',
                'return_url' => $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/cabinet/?autopayment=success',
            ],
            'type' => 'bank_card',
        ]);

        if (!isset($result->confirmation->confirmation_url)) {
            throw new Exception('Не поступил confirmation_url', 1);
        }

        return ['id' => $result->id, 'status' => $result->status, 'saved' => $result->saved, 'url' => $result->confirmation->confirmation_url];
    }

    public function request($method = 'GET', $url, $data = null, $headers = null)
    {
        $this->httpClient->setHeader('Idempotence-Key', $this->getIdempotenceKey());
        $this->httpClient->setHeader('Content-Type', 'application/json');
        $this->httpClient->setAuthorization($this->shopId, $this->secret);

        $this->httpClient->query($method, self::API_URL . '/' . self::API_VERSION . '/' . $url, json_encode($data));

        $result = json_decode($this->httpClient->getResult());

        // dd($result);

        if ($this->httpClient->getStatus() != 200) {
            throw new \Exception('Ошибка: ' . $result->description);
        } else {
        }

        return $result;
    }

    public function createPayment($sum, $pmid, $comment)
    {
        $result = $this->request('POST', 'payments', [
            'amount' => array(
                'value' => floatval($sum),
                'currency' => 'RUB',
            ),
            'capture' => true,
            'payment_method_id' => $pmid,
            'description' => $comment,
        ]);

        return $result;
    }

    private function getIdempotenceKey(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
}
