<?php

use Enum\Helper;
use Api\Models\User;
use Api\DomainServices\Orders\OrdersService;
use Bitrix\Sale\Registry;
use Bitrix\Sale\PaySystem;

$_SERVER['DOCUMENT_ROOT'] = __DIR__ . '/..';

require $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php";

$rsUsers  = \CUser::GetList(
    'id',
    'asc',
    [
        'ACTIVE' => 'Y',
        '>UF_AUTOPAYMEN_METHOD' => 0,
    ],
    [
        'SELECT' => [
            'ID',
            'UF_AUTOPAYMEN_METHOD',
        ]
    ]
);


define('APF',  '/logs/autopayments.txt');

while ($u = $rsUsers->Fetch()) {

    if ($pmid = userAutopaymentEnabled($u['ID'])) {

        print_r($u['ID'] . ' -> ' . $pmid . PHP_EOL);

        // выбираем счета
        $res = \CIBlockElement::GetList(
            ['PROPERTY_DATE_CREATE' => 'DESC'],
            [
                'IBLOCK_ID' => 53,
                'PROPERTY_USER' => $u['ID'],
                'PROPERTY_STATUS' => INVOICE_STATUS_NOTPAID_ID,
                '>=PROPERTY_DATE_CREATE' => date('Y-m-01 00:00:00'),
                '!PROPERTY_TotalInvoiceAmount' => false,
                'ACTIVE' => 'Y',
            ],
            false,
            false,
            [
                'ID',
                'IBLOCK_ID',
                'PROPERTY_CONTRACT_GUID',
                'PROPERTY_TotalInvoiceAmount',
            ]
        );

        while ($invoice = $res->GetNext()) {

            Helper::log(APF, "Начало автоплатежа: " . $u['ID'] . ", счёт: " . $invoice['ID']);

            // Получает договор по его GUID
            $contract = \Api\Models\Contract::getByGUID($invoice['PROPERTY_CONTRACT_GUID_VALUE']);

            CSaleBasket::DeleteAll(CSaleBasket::GetBasketUserID());

            // создаём корзину добавляем товар
            $basket = \Bitrix\Sale\Basket::loadItemsForFUser($u['ID'], Bitrix\Main\Context::getCurrent()->getSite());
            $item = $basket->createItem("catalog", $invoice['ID']);

            $item->setFields([
                "QUANTITY" => 1,
                "CURRENCY" => Bitrix\Currency\CurrencyManager::getBaseCurrency(),
                "LID" => Bitrix\Main\Context::getCurrent()->getSite(),
                "PRODUCT_PROVIDER_CLASS" => 'CCatalogProductProvider',
                'CUSTOM_PRICE' => 'Y',
                "VAT_INCLUDED" => "Y",
                "PRICE" => floatval($invoice['PROPERTY_TOTALINVOICEAMOUNT_VALUE']),
            ]);

            $basketPropertyCollection = $item->getPropertyCollection();

            $arForPropCollection = [];
            if (strlen($invoice['PROPERTY_CONTRACT_GUID_VALUE']) > 0) {
                //записываем свойства
                $arForPropCollection = array(
                    array(
                        "NAME" => "Номер договора",
                        "CODE" => "CONTRACT_NUMBER",
                        "VALUE" => $contract->getName(),
                        "SORT" => 100,
                    ),
                    array(
                        "NAME" => "ID договора",
                        "CODE" => "CONTRACT_ID",
                        "VALUE" => $contract->getId(),
                        "SORT" => 110,
                    ),
                    array(
                        "NAME" => "GUID договора",
                        "CODE" => "CONTRACT_GUID",
                        "VALUE" => $invoice['PROPERTY_CONTRACT_GUID_VALUE'],
                        "SORT" => 120,
                    )
                );
            }

            $basketPropertyCollection->setProperty($arForPropCollection);

            $user = new User(['ID' => $u['ID']]);

            $orderService = new OrdersService();
            $orderId = $orderService->createOrder($user, $basket, 3);

            $registry = Registry::getInstance(Registry::REGISTRY_TYPE_ORDER);
            $orderClassName = $registry->getOrderClassName();

            if ($order = $orderClassName::loadByAccountNumber($orderId)) {
                $paymentCollection = $order->getPaymentCollection();

                $payment = $paymentCollection[0];
                $payment->setField('PS_RECURRING_TOKEN', $pmid);

                if (!$payment->isPaid()) {
                    $paySystemService = PaySystem\Manager::getObjectById($payment->getPaymentSystemId());
                    if (!empty($paySystemService)) {
                        $initResult = $paySystemService->repeatRecurrent($payment);
                        Helper::log(APF, "Результат автоплатежа: " . json_encode($initResult));
                    }
                }
            }
        }
    }
}
