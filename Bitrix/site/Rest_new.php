<?php

namespace Enum;

use Bitrix\Main\UserTable;

class Rest
{
    public static $url = 'https://crm.alfasklad.ru/rest/101/78wukog3j7cb26wn';

    private static function isOrderFromCrm($order)
    {
        $propertyCollection = $order->getPropertyCollection();

        foreach ($propertyCollection as $propertyItem) {
            if (
                $propertyItem->getField("CODE") === "IS_ORDER_FROM_CRM"
                && $propertyItem->getValue() === "Y"
            ) {
                return true;
            }
        }

        return false;
    }

    private static function buildOrderData($order)
    {
        \Bitrix\Main\Loader::includeModule("sale");
        \Bitrix\Main\Loader::includeModule("catalog");
        \Bitrix\Main\Loader::includeModule("iblock");

        $ORDER_ID = $order->getId();
        $DATE_CREATE = $order->getDateInsert()->format('d.m.Y H:i:s');
        $PERSON_TYPE = $order->getPersonTypeId();
        $USER_ID = $order->getUserId();
        $basket = $order->getBasket();
        $comment = $order->getField("USER_DESCRIPTION");
        $ACCOUNT_NUMBER = $order->getField("ACCOUNT_NUMBER");

        $priceOrder = $basket->getPrice();          // Цена с учетом скидок
        $fullPriceOrder = $basket->getBasePrice(); // Цена без учета скидок
        $orderDiscount = $fullPriceOrder - $priceOrder;

        $paymentIds = $order->getPaymentSystemId();
        $deliveryIds = $order->getDeliverySystemId();

        $rsUser = \CUser::GetByID($USER_ID);
        $arUser = $rsUser->Fetch();

        $XML_ID = '';
        if (!empty($arUser['XML_ID'])) {
            $XML_ID = (string)$arUser['XML_ID'];
        }

        $ID_CRM = ''; // todo по условию ТЗ пока пустой

        $PHONE = '';
        $user = UserTable::getList([
            'select' => ['PERSONAL_PHONE'],
            'filter' => ['ID' => $USER_ID]
        ])->fetch();

        if (!empty($user['PERSONAL_PHONE'])) {
            $PHONE = $user['PERSONAL_PHONE'];
        }

        $propertyCollection = $order->getPropertyCollection();

        $PRODUCTS = [];
        $EXTERNAL_ID_SECTION = '';

        foreach ($basket as $basketItem) {
            $price = $basketItem->getPrice(); // за единицу
            $price_total = $basketItem->getFinalPrice(); // сумма
            $price_base = $basketItem->getBasePrice(); // сумма без скидок
            $count = $basketItem->getQuantity();
            $name = $basketItem->getField('NAME');
            $xml_id_item = (string)$basketItem->getField('PRODUCT_XML_ID');
            $ID_PRODUCT = $basketItem->getField('PRODUCT_ID');

            if ($xml_id_item === '') {
                $res = \CIBlockElement::GetList(
                    [],
                    ['IBLOCK_ID' => [40, 48], 'ID' => $ID_PRODUCT],
                    false,
                    false,
                    ['ID', 'IBLOCK_ID', 'XML_ID']
                );

                if ($item = $res->Fetch()) {
                    $xml_id_item = (string)$item['XML_ID'];
                }
            }

            $basketPropertyCollection = $basketItem->getPropertyCollection();
            $propertyCollectionProduct = $basketPropertyCollection->getPropertyValues();

            $product = [];
            $product['IS_ARENDA_BOX'] = 'N';

            $productDiscount = 0;
            $discountInfo = [];

            $elem = \CIBlockElement::GetByID($ID_PRODUCT)->Fetch();
            if ($elem && (int)$elem['IBLOCK_ID'] === 40) {
                $section = \CIBlockSection::GetByID($elem['IBLOCK_SECTION_ID'])->Fetch();
                if ($section && !empty($section['XML_ID'])) {
                    $EXTERNAL_ID_SECTION = $section['XML_ID'];
                }

                $product['IS_ARENDA_BOX'] = 'Y';

                if (!empty($propertyCollectionProduct['DISCOUNT_0_ID']['VALUE'])) {
                    $sumPriceWithoutDiscounts = 0;
                    $monthCount = (int)$propertyCollectionProduct['ORDER_MONTH_COUNT']['VALUE'];

                    for ($i = 1; $i <= $monthCount; $i++) {
                        if ($i === 1) {
                            $dateStartOrderPeriod = $propertyCollectionProduct['DATE_START']['VALUE'];
                            $daysInMonth = date("t", MakeTimeStamp($dateStartOrderPeriod, "DD.MM.YYYY"));
                            $countDays = ($daysInMonth - date("d", MakeTimeStamp($dateStartOrderPeriod, "DD.MM.YYYY"))) + 1;
                            $priceOrderPeriod = intval(($price_base / $daysInMonth) * $countDays);
                        } else {
                            $priceOrderPeriod = $price_base;
                        }

                        $sumPriceWithoutDiscounts += $priceOrderPeriod;
                    }

                    if ($sumPriceWithoutDiscounts > 0) {
                        $productDiscount = $sumPriceWithoutDiscounts - $price_total;

                        $arDiscounts = \Bitrix\Sale\Internals\DiscountTable::getList([
                            'filter' => [
                                'ID' => $propertyCollectionProduct['DISCOUNT_0_ID']['VALUE'],
                            ],
                            'select' => ['*']
                        ])->fetch();

                        if (!empty($arDiscounts["SHORT_DESCRIPTION_STRUCTURE"])) {
                            $discountInfo = [
                                "VALUE" => $arDiscounts["SHORT_DESCRIPTION_STRUCTURE"]["VALUE"],
                                "VALUE_TYPE" => $arDiscounts["SHORT_DESCRIPTION_STRUCTURE"]["VALUE_TYPE"]
                            ];
                        }
                    }
                }
            }

            $product['PROP'] = $propertyCollectionProduct;
            $product['PRICE'] = $price;
            $product['PRICE_TOTAL'] = $price_total;
            $product['PRICE_BASE'] = $price_base;
            $product['DISCOUNT'] = $productDiscount;
            $product['DISCOUNT_INFO'] = $discountInfo;
            $product['COUNT'] = $count;
            $product['NAME'] = $name;
            $product['EXTERNAL_CODE'] = $xml_id_item;
            $product['ID_PRODUCT'] = $ID_PRODUCT;

            $PRODUCTS[] = $product;
        }

        $userArr = [
            'PERSON_TYPE' => $PERSON_TYPE,
            'ID_SITE' => $USER_ID,
            'ID_CRM' => $ID_CRM,
            'ID' => $USER_ID,
        ];

        foreach ($propertyCollection as $propertyItem) {
            $userArr[$propertyItem->getField("CODE")] = $propertyItem->getValue();
        }

        if ($XML_ID !== '') {
            $userArr['EXTERNAL_CODE'] = $XML_ID;
        } else {
            // серия и номер паспорта - физ лицо
            if (!empty($userArr['PASSPORT_SERIES']) && !empty($userArr['PASSPORT_NUMBER'])) {
                $userArr['EXTERNAL_CODE'] = md5($userArr['PASSPORT_SERIES'] . $userArr['PASSPORT_NUMBER']);
            }

            // ИНН и КПП - юр лицо
            if (!empty($userArr['INN']) && !empty($userArr['KPP'])) {
                $userArr['EXTERNAL_CODE'] = md5($userArr['INN'] . $userArr['KPP']);
            }
        }

        $orderArr = [
            'PAYMENT' => is_array($paymentIds) ? array_shift($paymentIds) : $paymentIds,
            'DELIVERY' => is_array($deliveryIds) ? array_shift($deliveryIds) : $deliveryIds,
            'ID_SITE' => $USER_ID,
            'ID_CRM' => $ID_CRM,
            'ORDER_ID' => $ORDER_ID,
            'DATE_CREATE' => $DATE_CREATE,
            'DISCOUNT' => $orderDiscount,
            'FULL_PRICE' => $fullPriceOrder,
            'PRICE' => $priceOrder,
            'COMMENT' => $comment,
            'ACCOUNT_NUMBER' => $ACCOUNT_NUMBER,
            'EXTERNAL_ID_SECTION' => $EXTERNAL_ID_SECTION,
            'VIEW_RENT' => '',
            'PRODUCTS' => $PRODUCTS,
        ];

        $userArr['PHONE'] = $PHONE;

        $data = [];
        $data['requestId'] = '';
        $data['UTM'] = getUtmByUserId($USER_ID);
        $data['USER'] = $userArr;
        $data['ORDER'] = $orderArr;

        return $data;
    }

    public static function NewOrderSendToB24(\Bitrix\Main\Event $event)
    {
        \Bitrix\Main\Loader::includeModule("sale");
        \Bitrix\Main\Loader::includeModule("catalog");

        $order = $event->getParameter("ENTITY");
        $isNew = $event->getParameter("IS_NEW");

        if (!$isNew) {
            return;
        }

        $ORDER_ID = $order->getId();
        $isOrderFromCrm = self::isOrderFromCrm($order);

        if ($isOrderFromCrm) {
            Helper::log('/logs/rest.txt', [
                'ORDER_ID' => $ORDER_ID,
                'event' => 'NewOrderSendToB24',
                'status' => 'skip',
                'reason' => 'Заказ получен из Б24, до оплаты не отправляем addDealFromSite'
            ]);
        } else {
            Helper::log('/logs/rest.txt', [
                'ORDER_ID' => $ORDER_ID,
                'event' => 'NewOrderSendToB24',
                'status' => 'skip',
                'reason' => 'Новый заказ сайта не отправляем в B24 до успешной оплаты'
            ]);
        }
    }

    public static function OnAfterUserAddHandler(&$arFields)
    {
        $data = [];
        $CUser = new \CUser();

        if ($arFields['XML_ID'] == '') {
            // серия и номер паспорта - физ лицо
            if (isset($_REQUEST['ORDER_PROP_9']) && isset($_REQUEST['ORDER_PROP_10'])) {
                $arFieldsNew['XML_ID'] = md5($_REQUEST['ORDER_PROP_9'] . $_REQUEST['ORDER_PROP_10']);
                $CUser->Update($arFields['ID'], $arFieldsNew);
            }

            // ИНН и КПП - юр лицо
            if (isset($_REQUEST['ORDER_PROP_11']) && isset($_REQUEST['ORDER_PROP_12'])) {
                $arFieldsNew['XML_ID'] = md5($_REQUEST['ORDER_PROP_9'] . $_REQUEST['ORDER_PROP_10']);
                $CUser->Update($arFields['ID'], $arFieldsNew);
            }
        }

        Helper::log('/logs/newUser.txt', $arFields);

        self::SendB24($data, '/enum.addCompanyFromSite/');

        return $arFields;
    }

    public static function OnBeforeUserAddHandler(&$arFields)
    {
    }

    public static function OnAfterUserUpdateHandler(&$arFields)
    {
        if (strlen($arFields["EMAIL"]) == 0) {
            $rsUser = \CUser::GetByID($arFields["ID"]);
            $arUser = $rsUser->Fetch();
            $arFields["EMAIL"] = $arUser["EMAIL"];
        }

        Helper::log('/logs/updateUser.txt', $arFields);

        self::SendB24($arFields, '/enum.updateCompanyFromSite/');

        return $arFields;
    }

    public static function OnSaleOrderPaidHandler(\Bitrix\Main\Event $event)
    {
        \Bitrix\Main\Loader::includeModule("sale");
        \Bitrix\Main\Loader::includeModule("catalog");

        $order = $event->getParameter("ENTITY");
        $ORDER_ID = $order->getId();
        $isOrderFromCrm = self::isOrderFromCrm($order);

        $answerAddDeal = null;
        $answerPayedDeal = null;

        if ($order->isPaid()) {
            if (!$isOrderFromCrm) {
                $data = self::buildOrderData($order);
                $answerAddDeal = self::SendB24($data, '/enum.addDealFromSite/');
            }

            $answerPayedDeal = self::SendB24([
                'ORDER_ID' => $ORDER_ID,
            ], '/enum.payedDeal/');
        }

        $log = [
            'ORDER_ID' => $ORDER_ID,
            'isPaid' => $order->isPaid() ? 'Y' : 'N',
            'isOrderFromCrm' => $isOrderFromCrm ? 'Y' : 'N',
            'answer_addDealFromSite' => $answerAddDeal,
            'answer_payedDeal' => $answerPayedDeal,
        ];

        Helper::log('/logs/payedOrder.txt', $log);
    }

    // отправка в вебхук
    public static function SendB24($data, $method)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => self::$url . $method,
            CURLOPT_POSTFIELDS => http_build_query($data),
        ]);

        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }
}