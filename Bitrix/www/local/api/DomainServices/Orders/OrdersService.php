<?php

namespace Api\DomainServices\Orders;

use Api\Models\Document;
use Bitrix\Main;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Request;
use Bitrix\Main\Web\Json;
use Bitrix\Sale;
use Bitrix\Sale\Delivery;
use Bitrix\Sale\DiscountCouponsManager;
use Bitrix\Sale\Location\GeoIp;
use Bitrix\Sale\Location\LocationTable;
use Bitrix\Sale\Order;
use Bitrix\Sale\Payment;
use Bitrix\Sale\PaySystem;
use Bitrix\Sale\PaySystem\BaseServiceHandler;
use Bitrix\Sale\PaySystem\Logger;
use Bitrix\Sale\PaySystem\Manager;
use Bitrix\Sale\PaySystem\Service;
use Bitrix\Sale\PaySystem\ServiceResult;
use Bitrix\Sale\PersonType;
use Bitrix\Sale\Result;
use Bitrix\Sale\Services\Company;
use Bitrix\Sale\Shipment;

Loc::loadMessages(__FILE__);

class OrdersService
{
    /**
     * Создает заказ для пользователя из корзины
     * @param $user
     * @param $basket
     * @return int|null
     * @throws Main\ArgumentException
     * @throws Main\ArgumentNullException
     * @throws Main\ArgumentOutOfRangeException
     * @throws Main\LoaderException
     * @throws Main\NotImplementedException
     * @throws Main\NotSupportedException
     * @throws Main\ObjectException
     * @throws Main\ObjectNotFoundException
     * @throws Main\SystemException
     */
    public function createOrder($user, $basket, $dealCategoryId)
    {
        $typeIndividual = $user->defineUserType();
        file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/local/Api/log.txt', 'createOrder 1' . "\n", FILE_APPEND);

        if (!Loader::includeModule("sale")) {
            throw new \Exception('Module sale not found');
        }
        file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/local/Api/log.txt', 'createOrder 2' . "\n", FILE_APPEND);

        $basket->save();

        $order = Order::create(SITE_ID, $user->getId());
        $typeUser = $typeIndividual ? 1 : 2;
        $order->setPersonTypeId($typeUser);
        $order->setBasket($basket);

        $shipmentCollection = $order->getShipmentCollection();
        $shipment = $shipmentCollection->createItem(
            \Bitrix\Sale\Delivery\Services\Manager::getObjectById(1)
        );

        $shipmentItemCollection = $shipment->getShipmentItemCollection();

        foreach ($basket as $basketItem) {
            $item = $shipmentItemCollection->createItem($basketItem);
            $item->setQuantity($basketItem->getQuantity());
        }

        $paymentCollection = $order->getPaymentCollection();

        // это временно на тест
        //       $payID=3;
//        global $USER;
//        if($USER->IsAdmin()){
//            $payID=7;
//        }

        $payID = 7;
        $payment = $paymentCollection->createItem(
            \Bitrix\Sale\PaySystem\Manager::getObjectById($payID)
        );

        $payment->setField("SUM", $order->getPrice());
        $payment->setField("CURRENCY", $order->getCurrency());

        $result = $order->save();
        $orderId = null;
        if (!$result->isSuccess()) {
            throw new \Exception('Ошибка во время создания заказа');
        } else {
            $orderId = $result->getId();

            if ($typeIndividual) {
                $additionalProps = [
                    'LAST_NAME' => $user->getLastName(),
                    'NAME' => $user->getName(),
                    'SECOND_NAME' => $user->getSecondName(),
                    'BIRTHDAY' => $user->getBirthDay(false),
                    'ADDRESS' => $user->getAddress(),
                    'ACTUAL_ADDRESS' => $user->getActualAddress(),
                    'PHONE' => $user->getPhone(),
                    'EMAIL' => $user->getEmail(),
                    'PASSPORT_SERIES' => $user->getPassportSeries(),
                    'PASSPORT_NUMBER' => $user->getPassportNumber(),
                    'IS_PUBLIC_ORDER' => 'N',
                    'IS_ORDER_FROM_CRM' => 'N',
                    'DEAL_CATEGORY_ID' => $dealCategoryId,
                ];
            } else {
                $additionalProps = [
                    'INN' => $user->getInn(),
                    'KPP' => $user->getKpp(),
                    'NAME' => $user->getName(),
                    'EMAIL' => $user->getEmail(),
                    'PHONE' => $user->getPhone(),
                    'FIO_IN_COMPANY' => $user->getContactFio(),
                    'IS_PUBLIC_ORDER' => 'N',
                    'DEAL_CATEGORY_ID' => $dealCategoryId,
                    'IS_ORDER_FROM_CRM' => 'N',
                ];
            }

            foreach ($additionalProps as $propCode => $propValue) {
                $dbRes = \CSaleOrderPropsValue::GetList(
                    array(),
                    array(
                        'ORDER_ID' => $orderId,
                        'CODE' => $propCode
                    )
                );
                $orderProp = $dbRes->Fetch();

                $db_props = \CSaleOrderProps::GetList(
                    array("SORT" => "ASC"),
                    array(
                        'CODE' => $propCode,
                        'PERSON_TYPE_ID' => $typeUser
                    ),
                    false,
                    false,
                    array()
                );

                $prop = $db_props->Fetch();

                \CSaleOrderPropsValue::Update($orderProp['ID'], [
                    'NAME' => $prop['NAME'],
                    'CODE' => $prop['CODE'],
                    'ORDER_PROPS_ID' => $prop['ID'],
                    'ORDER_ID' => $orderId,
                    'VALUE' => $propValue,
                ]);
            }
        }
        return $orderId;
    }

    public function getOrderLink($orderId, $returUrl = null)
    {
        $paymentLink = null;
        if (!Loader::includeModule("sale")) {
            ShowError(Loc::getMessage("SOA_MODULE_NOT_INSTALL"));
            return;
        }

        $registry = Sale\Registry::getInstance(Sale\Registry::REGISTRY_TYPE_ORDER);
        /** @var Order $orderClassName */
        $orderClassName = $registry->getOrderClassName();

        /** @var Order $order */
        if ($order = $orderClassName::loadByAccountNumber($orderId)) {
            $arOrder = $order->getFieldValues();
            $arResult["ORDER_ID"] = $arOrder["ID"];
            $arResult["ACCOUNT_NUMBER"] = $arOrder["ACCOUNT_NUMBER"];
            $arOrder["IS_ALLOW_PAY"] = $order->isAllowPay() ? 'Y' : 'N';
        }

        foreach (GetModuleEvents("sale", "onSalePsInitiatePaySuccess", true) as $arEvent)
            ExecuteModuleEventEx($arEvent, array($arResult["ORDER_ID"], &$arOrder, &$this->arParams));

//        file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/local/Api/log.txt', ''. $arResult["ORDER_ID"] ."\n", FILE_APPEND);

        if ($order->isAllowPay()) {
//            file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/local/Api/log.txt', 'ISALOWPAY'. $arResult["ORDER_ID"] ."\n", FILE_APPEND);

            $paymentCollection = $order->getPaymentCollection();
            /** @var Payment $payment */
            foreach ($paymentCollection as $payment) {
                if (intval($payment->getPaymentSystemId()) > 0 && !$payment->isPaid()) {
                    $paySystemService = PaySystem\Manager::getObjectById($payment->getPaymentSystemId());
                    if (!empty($paySystemService)) {
                        if ($paySystemService->getField('NEW_WINDOW') === 'N' || $paySystemService->getField('ID') == PaySystem\Manager::getInnerPaySystemId()) {
                            
                            if (!is_null($returUrl)) {
                                $paySystemService->getContext()->setUrl($returUrl);
                            }

                            /** @var PaySystem\ServiceResult $initResult */
                            $initResult = $paySystemService->initiatePay($payment, null, PaySystem\BaseServiceHandler::STRING);
                            if ($initResult->isSuccess()) {
                                $template = $initResult->getTemplate();
//                                file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/local/Api/log.txt', $template."\n", FILE_APPEND);
                                $paymentLink = $this->getPaymentLink($template);
                                file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/local/Api/log.txt', $paymentLink . "\n", FILE_APPEND);


                            }
                        }
                    }
                }
            }
        }
        return $paymentLink;
    }

    /**
     * Достаёт ссылку оплаты сбербанка
     * @param $template
     * @return mixed|null
     */
    private function getPaymentLink($template)
    {
        $link = null;
        $matches = [];
        //preg_match('/.*<a href="(.*)".*/imuU', $template, $matches);
        preg_match('/.*<a class="btn btn-lg btn-success" style="border-radius: 32px;" href="(.*)".*/imuU', $template, $matches);
        if (!empty($matches[1])) {
            $link = $matches[1];
        }
        return $link;
    }
}