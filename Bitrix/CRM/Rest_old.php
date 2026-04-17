
/* /home/bitrix/www/bitrix/php_interface/classes/enum/Rest.php ?> */
<?

namespace Enum;
use Bitrix\Main\UserTable;

class Rest
{
    public static $url = 'https://crm.alfasklad.ru/rest/101/78wukog3j7cb26wn';

    static function NewOrderSendToB24(\Bitrix\Main\Event $event)
    {

                \Bitrix\Main\Loader::includeModule("sale");
                \Bitrix\Main\Loader::includeModule("catalog");

        $order = $event->getParameter("ENTITY");
        $isNew = $event->getParameter("IS_NEW");

        $ID_SITE = $order->getSiteId();
        $ORDER_ID = $order->getId();
        $DATE_CREATE = $order->getDateInsert()->format('d.m.Y H:i:s');
        $PERSON_TYPE = $order->getPersonTypeId();
        $USER_ID = $order->getUserId();
        $basket = $order->getBasket();
        $comment = $order->getField("USER_DESCRIPTION");
        $ACCOUNT_NUMBER = $order->getField("ACCOUNT_NUMBER");

        $priceOrder = $basket->getPrice(); // Цена с учетом скидок
        $fullPriceOrder = $basket->getBasePrice(); // Цена без учета скидок
        $discount = $fullPriceOrder - $priceOrder; //скидка

        $paymentIds = $order->getPaymentSystemId(); // массив id способов оплат
        $deliveryIds = $order->getDeliverySystemId(); // массив id способов доставки
        $discountData = $order->getDiscount()->getApplyResult();//скидки

        /* deb($paymentIds);
         deb($deliveryIds);
         deb($discountData);
         die();
 */
        //достанем внешний код
        $rsUser = \CUser::GetByID($USER_ID);
        $arUser = $rsUser->Fetch();
        $XML_ID = '';//
        $ID_CRM = '';//todo по условию ТЗ пока пустой

        // Получим номер телефона пользователя

        $PHONE = '';
        $user = UserTable::getList([
            'select' => ['PERSONAL_PHONE'],
            'filter' => ['ID' => $USER_ID]
        ])->fetch();

        $PHONE = $user['PERSONAL_PHONE'];



        /*$order = \Bitrix\Sale\Order::load($ORDER_ID);


        deb($order);
        die();*/

        //получим поля профиля покупателя
        /* $db_sales = \CSaleOrderUserProps::GetList(
             array("DATE_UPDATE" => "DESC"),
             array("USER_ID" => $USER_ID)
         );

         //берем первый профиль, так как по умолчанию у пользователя может быть только один профиль
         if ($ar_sales = $db_sales->Fetch()) {
             $db_propVals = \CSaleOrderUserPropsValue::GetList(array("ID" => "ASC"), Array("USER_PROPS_ID" => $ar_sales['ID']));
             while ($arPropVals = $db_propVals->Fetch()) {
                 echo $arPropVals["USER_VALUE_NAME"] . "=" . $arPropVals["VALUE"] . "<br>";
             }
             die();
         }*/

        //$order = Sale\Order::loadByAccountNumber($orderNumber);


        if ($isNew) {

            Helper::log('/logs/rest.txt', '############################новый заказ с сайта################, №' . $ORDER_ID);

            $propertyCollection = $order->getPropertyCollection();    // Получаем свойства заказа

            $PRODUCTS = [];
            $EXTERNAL_ID_SECTION = '';
            foreach ($basket as $basketItem) {
                $price = $basketItem->getPrice();//за еденицу
                $price_total = $basketItem->getFinalPrice();//Сумма
                $price_base = $basketItem->getBasePrice();//Сумма
                $count = $basketItem->getQuantity();
                $name = $basketItem->getField('NAME');
                $xml_id_item = $basketItem->getField('PRODUCT_XML_ID');
                $ID_PRODUCT = $basketItem->getField('PRODUCT_ID');

                                if(strlen($xml_id_item) == 0) {
                                        \Bitrix\Main\Loader::includeModule("iblock");
                                        $res = \CIBlockElement::GetList([], Array('IBLOCK_ID'=>Array(40, 48), 'ID'=>$ID_PRODUCT), false, false, Array('ID', 'IBLOCK_ID', 'XML_ID'));
                                        if($item = $res->Fetch()) {
                                                $xml_id_item = $item['XML_ID'];
                                        }
                                }

                // Helper::getValueUserField('iblock',$ID_PRODUCT,'');

                $basketPropertyCollection = $basketItem->getPropertyCollection();

                //свойства товара в корзине (размер бокса, планируемы срок и тд)
                $propertyCollectionProduct = $basketPropertyCollection->getPropertyValues();

                $product['IS_ARENDA_BOX'] = 'N';

                //если это аренда бокса
                $discount = 0;
                                $discountInfo = Array();
                $elem = \CIBlockElement::GetByID($ID_PRODUCT)->Fetch();
                if ($elem['IBLOCK_ID'] == 40) {
                    $section = \CIBlockSection::GetByID($elem['IBLOCK_SECTION_ID'])->Fetch();
                    $EXTERNAL_ID_SECTION = $section['XML_ID'];
                    $product['IS_ARENDA_BOX'] = 'Y';

                    //$discount = ($price_base * $propertyCollectionProduct['ORDER_MONTH_COUNT']['VALUE']) - $price_total;

                                        //Если скидка применена
                                        if(strlen($propertyCollectionProduct['DISCOUNT_0_ID']['VALUE']) > 0) {
                                                //ищем сумму за бокс без скидок
                                                $sumPriceWithoutDiscounts = 0;
                                                for ($i = 1; $i<=$propertyCollectionProduct['ORDER_MONTH_COUNT']['VALUE']; $i++) {
                                                        if ($i == 1) {
                                                                $dateStartOrderPeriod = $propertyCollectionProduct['DATE_START']['VALUE'];
                                                                $daysInMonth = date("t", MakeTimeStamp($dateStartOrderPeriod, "DD.MM.YYYY"));
                                                                $countDays = ($daysInMonth - date("d", MakeTimeStamp($dateStartOrderPeriod, "DD.MM.YYYY"))) + 1;
                                                                $priceOrderPeriod = intval(($price_base/$daysInMonth) * $countDays);
                                                        } else {
                                                                $priceOrderPeriod = $price_base;
                                                        }
                                                        $sumPriceWithoutDiscounts = $sumPriceWithoutDiscounts + $priceOrderPeriod;
                                                }
                                                if($sumPriceWithoutDiscounts > 0) {
                                                        //получаем размер реальной скидки [сумма за бокс без скидок]-[сумма за бокс со скидками]
                                                        $discount = $sumPriceWithoutDiscounts - $price_total;

                                                        //Ищем инфу о скидке
                                                        $arDiscounts = \Bitrix\Sale\Internals\DiscountTable::getList([
                                                                'filter' => [
                                                                        'ID' => $propertyCollectionProduct['DISCOUNT_0_ID']['VALUE'],
                                                                ],
                                                                'select' => [
                                                                        "*"
                                                                ]
                                                        ]) -> fetch();
                                                        $discountInfo = Array("VALUE"=>$arDiscounts["SHORT_DESCRIPTION_STRUCTURE"]["VALUE"], "VALUE_TYPE"=>$arDiscounts["SHORT_DESCRIPTION_STRUCTURE"]["VALUE_TYPE"]);
                                                }
                                        }
                }

                $product['PROP'] = $propertyCollectionProduct;
                $product['PRICE_TOTAL'] = $price_total;
                $product['PRICE_BASE'] = $price_base;
                $product['DISCOUNT'] = $discount;
                                $product['DISCOUNT_INFO'] = $discountInfo;
                $product['COUNT'] = $count;
                $product['NAME'] = $name;
                $product['EXTERNAL_CODE'] = $xml_id_item;

                $product['ID_PRODUCT'] = $ID_PRODUCT;

                $PRODUCTS[] = $product;
            }

            //$emailPropValue = $propertyCollection->getUserEmail();

            $userArr = [
                'PERSON_TYPE' => $PERSON_TYPE,
                'ID_SITE' => $USER_ID,
                'ID_CRM' => $ID_CRM,
                'ID' => $USER_ID,
            ];

                        //флаг о том, что данный заказ создан на основе сделки из Б24
                        $isOrderFromCrm = false;


            // Перебираем свойства заказа (ИНН почта фио и тд)
            foreach ($propertyCollection as $propertyItem) {
                                if(($propertyItem->getField("CODE") == "IS_ORDER_FROM_CRM") and ($propertyItem->getValue() == "Y")) {
                                        $isOrderFromCrm = true;
                                }

                $userArr[$propertyItem->getField("CODE")] = $propertyItem->getValue();
            }

            //если у пользователя нет xml_id то сгенерим его и сохраним
            if ($XML_ID == '') {
                //$CUser = new \CUser();

                //серия и номер паспорта - физ лицо
                if (isset($userArr['PASSPORT_SERIES']) && isset($userArr['PASSPORT_NUMBER'])) {
                    $arFieldsNew['XML_ID'] = md5($userArr['PASSPORT_SERIES'] . $userArr['PASSPORT_NUMBER']);
                    // $CUser->Update($USER_ID, $arFieldsNew);
                    $userArr['EXTERNAL_CODE'] = $arFieldsNew['XML_ID'];
                }

                //ИНН и КПП - юр лицо
                if (isset($userArr['INN']) && isset($userArr['KPP'])) {
                    $arFieldsNew['XML_ID'] = md5($userArr['INN'] . $userArr['KPP']);
                    //$CUser->Update($USER_ID, $arFieldsNew);
                    $userArr['EXTERNAL_CODE'] = $arFieldsNew['XML_ID'];
                }
            }
            /////////

            $orderArr = [
                'PAYMENT' => array_shift($paymentIds),
                'DELIVERY' => array_shift($deliveryIds),
                'ID_SITE' => $USER_ID,
                'ID_CRM' => $ID_CRM,
                'ORDER_ID' => $ORDER_ID,
                'DATE_CREATE' => $DATE_CREATE,
                'DISCOUNT' => $discount,
                'FULL_PRICE' => $fullPriceOrder,
                'PRICE' => $priceOrder,
                'COMMENT' => $comment,
                'ACCOUNT_NUMBER' => $ACCOUNT_NUMBER,
                'EXTERNAL_ID_SECTION' => $EXTERNAL_ID_SECTION,
                'VIEW_RENT' => '',//TODO вид аренды
                'PRODUCTS' => $PRODUCTS,
            ];

            $userArr['PHONE'] = $PHONE;
            $data['requestId'] = $ct_result['requestId'];
            $data['UTM'] = getUtmByUserId($USER_ID);
            $data['USER'] = $userArr;
            $data['ORDER'] = $orderArr;

                        if(!$isOrderFromCrm) {
                                //отправляем в Б24 только те заказы, которые были созданы на стороне сайта
                                $answer = self::SendB24($data, '/enum.addDealFromSite/');

                                /* Отправка в колтач */ 
                                // global $APPLICATION;
                                // $call_value = $_COOKIE['_ct_session_id']; /* ID сессии Calltouch, полученный из cookie */
                                // $ct_site_id = '26167';
                                // $ch = curl_init();
                                // $arLead = array(
                                //      'fio' => $userArr['LAST_NAME'].' '.$userArr['NAME'].' '.$userArr['SECOND_NAME'],     
                                //      'requestNumber' => $orderArr['ORDER_ID'].'_order_id',
                                //      'phoneNumber' => $userArr['PHONE'],
                                //      'email' => $userArr['EMAIL'],
                                //      'comment' => $userArr['ID_SITE'],
                                //      'subject' => 'Сделка с сайта',
                                //      'sessionId' => $call_value,
                                //      'requestUrl' => $APPLICATION->GetCurPage()
                                // );

                                // curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/x-www-form-urlencoded;charset=utf-8"));
                                // curl_setopt($ch, CURLOPT_URL,'https://api.calltouch.ru/calls-service/RestAPI/requests/'.$ct_site_id.'/register/');
                                // curl_setopt($ch, CURLOPT_POST, 1);
                                // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($arLead));
                                // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                // $calltouch = curl_exec ($ch);
                                // $ct_result = json_decode($calltouch, true);
                                // curl_close ($ch);

                        }  else {

                                Helper::log('/logs/rest.txt', "Заказ не отправлен в Б24, т.к. он был получен оттуда (был создан на основе сделки Б24)");

                                /* Отправка в колтач */ 
                                // global $APPLICATION;
                                // global $USER;
                                // $rsUser = CUser::GetByID($USER->GetID());
                                // $arUser = $rsUser->Fetch();
                                // $call_value = $_COOKIE['_ct_session_id']; /* ID сессии Calltouch, полученный из cookie */
                                // $ct_site_id = '26167';
                                // $ch = curl_init();
                                // $arLead = array(
                                //      'fio' => $USER->GetFullName(),     
                                //      'requestNumber' => $orderArr['ORDER_ID'].'_order_id',
                                //      'phoneNumber' => $arUser['PERSONAL_PHONE'], 
                                //      'email' => $USER->GetEmail(),
                                //      'requestDate' => str_replace(' ', '%20', $orderArr['DATE_CREATE']),
                                //      'comment' => '',
                                //      'subject' => 'Сделка на сайте на основе Б24',
                                //      'sessionId' => $call_value,
                                //      'requestUrl' => $APPLICATION->GetCurPage()
                                // );

                                // curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/x-www-form-urlencoded;charset=utf-8"));
                                // curl_setopt($ch, CURLOPT_URL,'https://api.calltouch.ru/calls-service/RestAPI/requests/'.$ct_site_id.'/register/');
                                // curl_setopt($ch, CURLOPT_POST, 1);
                                // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($arLead));
                                // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                // $calltouch = curl_exec ($ch);
                                // $ct_result = json_decode($calltouch, true);
                                // curl_close ($ch);

                        }
                        Helper::log('/logs/calltouch.txt', $arLead);

            //deb($answer);die();
            Helper::log('/logs/rest.txt', $data);
            Helper::log('/logs/rest.txt', $answer);
        }
    }

    static function OnAfterUserAddHandler(&$arFields)
    {
        $log = [];
        $data = [];

        $CUser = new \CUser();

        if ($arFields['XML_ID'] == '') {
            //серия и номер паспорта - физ лицо
            if (isset($_REQUEST['ORDER_PROP_9']) && isset($_REQUEST['ORDER_PROP_10'])) {
                $arFieldsNew['XML_ID'] = md5($_REQUEST['ORDER_PROP_9'] . $_REQUEST['ORDER_PROP_10']);
                $CUser->Update($arFields['ID'], $arFieldsNew);
            }

            //ИНН и КПП - юр лицо
            if (isset($_REQUEST['ORDER_PROP_11']) && isset($_REQUEST['ORDER_PROP_12'])) {
                $arFieldsNew['XML_ID'] = md5($_REQUEST['ORDER_PROP_9'] . $_REQUEST['ORDER_PROP_10']);
                $CUser->Update($arFields['ID'], $arFieldsNew);
            }
        }

        Helper::log('/logs/newUser.txt', $arFields);

        self::SendB24($data, '/enum.addCompanyFromSite/');

        return $arFields;
    }

   static function OnBeforeUserAddHandler(&$arFields)
    {

    }


    static function OnAfterUserUpdateHandler(&$arFields)
    {
                if(strlen($arFields["EMAIL"]) == 0) {
                        $rsUser = \CUser::GetByID($arFields["ID"]);
                        $arUser = $rsUser->Fetch();
                        $arFields["EMAIL"] = $arUser["EMAIL"];
                }

        Helper::log('/logs/updateUser.txt', $arFields);

        self::SendB24($arFields, '/enum.updateCompanyFromSite/');

        return $arFields;
    }


    static function OnSaleOrderPaidHandler(\Bitrix\Main\Event $event)
    {
        $order = $event->getParameter("ENTITY");
        $isPaid = $event->getParameter("IS_PAID");
        $ORDER_ID = $order->getId();

        $data = [
            'ORDER_ID' => $ORDER_ID,
        ];

        if ($order->isPaid()) {
            $answer = self::SendB24($data, '/enum.payedDeal/');
        }

        $log = [
            'ORDER_ID' => $ORDER_ID,
            'isPaid' => $order->isPaid() ? 'Y' : 'N',
            'answer' => $answer,
        ];
        Helper::log('/logs/payedOrder.txt', $log);
    }

    //отправка в вебхук
    public static function SendB24($data, $method)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0, //получать заголовки
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => self::$url . $method,
            CURLOPT_POSTFIELDS => http_build_query($data),
            /*CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_json)
            ]*/
        ));

        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }
}