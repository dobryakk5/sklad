<?php

namespace Enum;
use CFile;

class HelperRest extends Rest
{
    public static $fieldsCompany = [
        'Внешний код' => 'UF_CRM_1571961672318',
        'Отчество' => 'UF_CRM_5D8DB2A6BD8F5',
        'Имя' => 'UF_CRM_5D8DB2A6ADAD0',
        'Фамилия' => 'UF_CRM_5D8DB2A69DE5D',
        'ID_SITE' => 'UF_CRM_1571962390546',
        'ID_CRM' => 'UF_CRM_1571962893469',
        'Тип клиента (юр/физ)' => 'UF_CRM_1566814003549',
        'Зачем нужен Альфасклад?' => 'UF_CRM_1566814180',
        'Как о нас узнали' => 'UF_CRM_1566814349',
        'Ответственное лицо' => 'ASSIGNED_BY_ID',
        'Первичный контакт с клиентом' => 'UF_CRM_5D4D2C7C5AB88',
        'Возраст' => 'UF_CRM_1565331589394',
        'Пол' => 'UF_CRM_1565331609793',
        'Район проживания' => '',
        'Источник' => '',
        'GUID_COMPANY'=>'UF_CRM_1575757712470',
        'GUID_INVOICES'=>'UF_CRM_1575757827147',
        'GUID_CONTRACTS'=>'UF_CRM_1575757846633',
        'Передано в 1С'=>'UF_SENT_TO_1C',
        'ID_SITE_COMPANY' => 'UF_CRM_1571962390546',
        'Дата рождения' => 'UF_CRM_1577453560160',

        'Получать СМС рассылку' => 'UF_CRM_1580469487808',
        'Получать почтовую рассылку' => 'UF_CRM_1580469577888',
        'Комментарий из 1С' => 'UF_CRM_1580469600026',
        'Комментарий к Как о нас узнали?' => 'UF_CRM_1580469630824',
        'Комментарий к Зачем нужен Альфасклад?' => 'UF_CRM_1580469655680',

        'Цель бокса' => 'UF_CRM_1565339208126',
        'Требуется ли перевозка?'=> 'UF_CRM_1565339315825',
        'Баланс по контрагенту' => 'UF_CRM_1585244549622',
        'Сумма штрафных санкций' => 'UF_CRM_1659364647311',
        'STOCK_ID' => 'UF_CRM_5D4120841E702',
        //'LOGIN'=>'UF_CRM_1584092083901' // ДОБАВИТЬ НА БОЙ!!!!!!!!!!!!!!!


    ];
    public static $fieldsDeal = [
        'Доставка' => 'UF_CRM_1572624145',
        'Оплата' => 'UF_CRM_1572624176',
        'Склад' => 'UF_CRM_5D412084264DE',
        'Оплачено с сайта' => 'UF_CRM_1572520516665',
        'Номер договора' => 'UF_CRM_1564480466954',
        'ID пользователя' => 'UF_CRM_1570714799409',
        'Код размера' => 'UF_CRM_1570715186012',
        'Скидка' => 'UF_CRM_1570715402035',
        'Название скидки' => 'UF_CRM_1573560667781',
        'Процент скидки' => 'UF_CRM_1573560721790',
        'Дата создания' => 'DATE_CREATE',
        'Вид аренды' => 'UF_CRM_1570716108184',
        'Комментарий' => 'UF_CRM_1570716582786',
        'ID_сайта' => 'UF_CRM_1570716777035',
        'ID_CRM' => 'UF_CRM_1570716892413',
        'Внешний код' => 'UF_CRM_1570717010623',
        'ID Заказа' => 'UF_CRM_1570717395621',
        'Дата начала аренды' => 'UF_CRM_1565340795697',
        'Дата окончания аренды' => 'UF_CRM_1567412053',
        //'Бокс XML_ID' => 'UF_CRM_1571254893',
        //'Филиал' => 'UF_CRM_1571249157',
        //'срок действия скидки' => 'UF_CRM_1571237002420',
        'Статус Бокса XML_ID' => 'UF_CRM_1571254361',
        'Ответственное лицо' => 'ASSIGNED_BY_ID',
        'ID_пользователя сайта' => 'UF_CRM_1570716777035',

        'GUID_DEAL'=> 'UF_CRM_1580470450332',
        'GUID_CONTRACT'=> 'UF_CRM_1580470461649',
        'Кто подписал предварительный договор' => 'UF_CRM_15653390440',
        'Кто подписал договор' => 'UF_CRM_1565339112',
        'STOCK_ID' => 'UF_CRM_5D412084264DE',
        'SEND_TO_1C' => 'UF_CRM_1582017868467',
        'Предполагаемая дата освобождения бокса'=> 'UF_CRM_1582040574666',
        'Бронирование' => 'UF_CRM_1592301446011'

    ];

    public static $fieldsInvoice = [
        'GUID_INVOICE' => 'UF_CRM_1582018083',
        'ID_SITE_INVOICE' => 'UF_CRM_1582018091',
        'ACCOUNT_NUMBER' => 'UF_CRM_1564549768',  //номер счета пользовательское поле
        'ORDER_ID' => 'UF_CRM_5DA735AC2F125',           //ID Заказа
        'DATE_BEGIN_OF_RENT' => 'UF_CRM_5D6A0CF9E50D9',         //Дата начала аренды
        'DATE_END_OF_RENT' => 'UF_CRM_5D412122C27C6',            //Дата окончания аренды  //ЗАМЕНИТЬ НА ГУИД НА БОЕВОМ
        'USER_ID' => 'UF_CRM_5DA735ABA51A3',              //ID пользователя
        'EXTERNAL_CODE' => 'UF_CRM_5DA735AC202D4',        //Внешний код
        'DATE_CREATE' => 'DATE_INSERT',                 //Дата
        'CONTRACT' => 'UF_CRM_1571304910',                  //Договор
        'ACCRUED_FROM' => 'UF_CRM_1571305364',                  //Начислено с
        'CHARGED_ON' => 'UF_CRM_1571305370',                   //Начислено по
        'PAYED' => 'UF_CRM_1571305770',                  //Признак оплаты
        'DELIVERY' => 'UF_CRM_1564550450',
        'FIRST_INVOICE' => 'UF_CRM_1584082451',
        'STORNO' => 'UF_CRM_1584082489'
    ];

    //перелинковка полей сделки для отправки в 1С
    public static function mapDeal($arFields)
    {
        $result = [];
        foreach ($arFields as $key => $field) {
            $item['TITLE'] = $field['TITLE'];
            $item['ID_CRM_DEAL'] = $field['ID'];
            $item['DATE_CREATE'] = $field[self::$fieldsDeal['Дата создания']];
            $item['USER_ID'] = $field[self::$fieldsDeal['ID пользователя']];
            $item['CODE_OF_SIZE'] = $field[self::$fieldsDeal['Код размера']];
            $item['DISCOUNT_PERCENT'] = $field[self::$fieldsDeal['Процент скидки']];//процент скидки
            $item['DISCOUNT_NAME'] = $field[self::$fieldsDeal['Название скидки']];
            $item['STATUS'] = $field['UF_CRM_1570715728147'];               //Статус При новом договоре через сайт - пустой; строка
            $item['STAGE_ID'] = $field['STAGE_ID'];                         //Статус При новом договоре через сайт - пустой; строка
            $item['COMMENT'] = $field[self::$fieldsDeal['Комментарий']];
            $item['USER_ID'] = $field[self::$fieldsDeal['ID_пользователя сайта']];
            //$item['ID_CRM'] = $field[self::$fieldsDeal['ID_CRM']];
            $item['ORDER_NUMBER'] = $field[self::$fieldsDeal['Номер заказа']];
            $item['EXTERNAL_CODE'] = $field[self::$fieldsDeal['Внешний код']];
            $item['ORDER_ID'] = $field[self::$fieldsDeal['ID Заказа']];
            $item['DATE_BEGIN_OF_RENT'] = $field[self::$fieldsDeal['Дата начала аренды']];
            $item['DATE_END_OF_RENT'] = $field[self::$fieldsDeal['Дата окончания аренды']];

            $item['ASSIGNED_BY_ID'] = $field[self::$fieldsDeal['Ответственное лицо']];
            $item['ASSIGNED_BY_ID_VALUE'] = Helper::getUserById($field[self::$fieldsDeal['Ответственное лицо']]);

            $item['STOCK_ID'] = $field[self::$fieldsDeal['STOCK_ID']];

            $item['SIG_DOC_0'] = Helper::getUserById($field[self::$fieldsDeal['Кто подписал предварительный договор']])['EMAIL'];
            $item['SIG_DOC_1'] = Helper::getUserById($field[self::$fieldsDeal['Кто подписал договор']])['EMAIL'];

            //$item['BOX_XML_ID'] = self::$fieldsDeal['Бокс XML_ID'];
            //$item['BRANCH'] = self::$fieldsDeal['Филиал'];
            //$item['DISCOUNT_VALIDITY'] = self::$fieldsDeal['срок действия скидки'];
            $item['STATUS_XML_ID'] = $field[self::$fieldsDeal['Статус Бокса XML_ID']];
            $item['DATE_EXEMP']= $field[self::$fieldsDeal['Предполагаемая дата освобождения бокса']];

            $item['GUID_DEAL']= $field[self::$fieldsDeal['GUID_DEAL']];
            $item['GUID_CONTRACT']= $field[self::$fieldsDeal['GUID_CONTRACT']];

            $item['PRODUCTS'] = $field['PRODUCTS'];//Продукты
            //id сделки
            // $item['VIEW_RENT'] = $field['VIEW_RENT'];
            $item['COMPANY'] = $field['COMPANY'];



            $result[$item['ID_CRM_DEAL']] = $item;
        }

        return $result;
    }

    //перелинковка полей счета для отправки в 1С
    public static function mapInvoice($arFields)
    {
        $result = [];
        foreach ($arFields as $key => $field) {
            /*if($key==0){
                $item['FIRST_INVOICE'] = 'true';
            }else{
                $item['FIRST_INVOICE'] = 'false';
            }*/

            $item['FIRST_INVOICE'] = $field[self::$fieldsInvoice['FIRST_INVOICE']];
            $item['STORNO'] = $field[self::$fieldsInvoice['STORNO']];
            $item['ID_SITE_INVOICE'] = $field[self::$fieldsInvoice['ID_SITE_INVOICE']];
            $item['ID_CRM_INVOICE'] = $field['ID'];
            $item['GUID_INVOICE'] = $field[self::$fieldsInvoice['GUID_INVOICE']];
            //$item['ACCOUNT_NUMBER'] = $field['UF_CRM_5DA735AC1184A'];               //номер счета (ACCOUNT_NUMBER - это номер Б24)
            $item['ACCOUNT_NUMBER'] = $field[self::$fieldsInvoice['ACCOUNT_NUMBER']];               //номер счета пользовательское поле
            // $item['BOX_XML_ID'] = $field['UF_CRM_5DA774F89DA75'];        //Бокс XML_ID
            //$item['BRANCH'] = $field['UF_CRM_5DA774F87D979'];            //Филиал
            $item['PRICE'] = $field['PRICE'];            //Сумма
            $item['COMMENTS'] = $field['COMMENTS'];            //Комментарий
            $item['ORDER_ID'] = $field[self::$fieldsInvoice['ORDER_ID']];           //ID Заказа
            $item['DATE_BEGIN_OF_RENT'] = $field[self::$fieldsInvoice['DATE_BEGIN_OF_RENT']];         //Дата начала аренды
            $item['DATE_END_OF_RENT'] = $field[self::$fieldsInvoice['DATE_END_OF_RENT']];            //Дата окончания аренды
            $item['USER_ID'] = $field[self::$fieldsInvoice['USER_ID']];              //ID пользователя
            $item['EXTERNAL_CODE'] = $field[self::$fieldsInvoice['EXTERNAL_CODE']];        //Внешний код
            $item['DATE_CREATE'] = $field[self::$fieldsInvoice['DATE_CREATE']];                 //Дата

            $item['CONTRACT'] = $field[self::$fieldsInvoice['CONTRACT']];                   //Договор
            $item['ACCRUED_FROM'] = $field[self::$fieldsInvoice['ACCRUED_FROM']];                   //Начислено с
            $item['CHARGED_ON'] = $field[self::$fieldsInvoice['CHARGED_ON']];                   //Начислено по
            $item['PAYED'] = $field[self::$fieldsInvoice['PAYED']];                   //Признак оплаты

            $item['STATUS_ID'] = $field['STATUS_ID'];
            $item['PAY_VOUCHER_DATE'] = $field['PAY_VOUCHER_DATE'];
            $item['PAY_VOUCHER_NUM'] = $field['PAY_VOUCHER_NUM'];
            $item['DELIVERY'] = $field[self::$fieldsInvoice['DELIVERY']];

            $item['PRODUCTS'] = $field['PRODUCTS'];                         //Продукты

            $result[] = $item;
        }

        return $result;
    }

    //список счетов по сделкам
    public static function GetInvoicesByDeal(&$Deals)
    {

        $filter = [
            'UF_DEAL_ID' => array_keys($Deals),//Текущие клиенты
            //'ID' => 881
        ];
        $select = [
            '*',
            'UF_*'
        ];


        $resInvoice = \CCrmInvoice::GetList([], $filter, false, false, $select);
        while ($invoice = $resInvoice->Fetch()) {
            $DEAL_ID = $invoice['UF_DEAL_ID'];
            $products = \CCrmInvoice::GetProductRows($invoice['ID']);
            $invoice['PRODUCTS'] = $products;
            $Deals[$DEAL_ID]['INVOICES'][] = $invoice;
        }


        foreach ($Deals as $id_deal => $deal) {
            $invoices = self::mapInvoice($deal['INVOICES']);
            $Deals[$id_deal]['INVOICES'] = $invoices;
        }


    }
    //Формируем массив с информацией по документу для 1С
    public static function getContractMap($arFields, $arProps){
        $inventory = new CFile();
        $contract = [];
        $contract['NAME'] = $arFields['NAME'];
        $contract['ID_CRM_CONTRACT'] = $arFields['ID'];
        $contract['ID_CRM_DEALS'] = $arProps['SVYAZANNYE_SDELKI']['VALUE'];
        $contract['ID_CRM_COMPANY'] = $arProps['SVYAZANNAYA_KOMPANIYA']['VALUE'];
        $contract['ID_SITE_COMPANY'] = self::searchCompanyByID($arProps['SVYAZANNAYA_KOMPANIYA']['VALUE'])[self::$fieldsCompany['ID_SITE_COMPANY']];
        $contract['GUID_CONTRACT'] = $arProps['GUID_DOGOVORA']['VALUE'];
        $contract['GUID_COMPANY'] = $arProps['GUID_KONTRAGENTA']['VALUE'];
        $contract['GUID_INVOICES'] = $arProps['GUIDY_SCHETOV']['VALUE'];
        if($arProps['BALANS_PO_DOGOVORU']['VALUE']) {
            $contract['BALANS_PO_DOGOVORU'] = $arProps['BALANS_PO_DOGOVORU']['VALUE'].'|RUB';
        }else{
            $contract['BALANS_PO_DOGOVORU'] = '0';
        }
        $contract['VNESHNIY_KOD_BOKSA'] = $arProps['VNESHNIY_KOD_BOKSA']['VALUE'];
        $contract['DATA_DOGOVORA'] = $arProps['DATA_DOGOVORA']['VALUE'];
        $contract['DATA_OKONCHANIYA_OPLATY_DOGOVORA'] = $arProps['DATA_OKONCHANIYA_OPLATY_DOGOVORA']['VALUE'];
        $contract['ID_POLZOVATELYA_SAYTA'] = $arProps['ID_POLZOVATELYA_SAYTA']['VALUE'];
        $contract['STATUS_ID'] = $arProps['STATUS']['VALUE_ENUM_ID'];
        $contract['STATUS_VALUE'] = $arProps['STATUS']['VALUE'];
        $contract['PERIODIC'] = $arProps['PERIODIC']['VALUE'];
        $contract['PAYMENT_PER_PERIOD'] = $arProps['PAYMENT_PER_PERIOD']['VALUE'];
        $contract['DEBTS'] = $arProps['DEBTS']['VALUE'];
        foreach ($arProps['INVENTORY']['VALUE'] as $fileId) {
                $contract['INVENTORY'][] = $inventory->GetPath($fileId);
        }
        AddMessage2Log(print_r($contract,true), "test_rdn inventory");

        return $contract;
    }


    public static function updateContractFrom1C($id, $query, $iblock){
        if($id==0){
            return 0;
        }

        if(!$query['ID_CRM_COMPANY']){
            $company = self::searchCompanyByGuid($query['GUID_COMPANY']);
        }else{
            $company['ID'] = $query['ID_CRM_COMPANY'];
        }

        //AddMessage2Log(print_r($query,true), "test_rdn");

        $el = new \CIBlockElement;
        
        if($query['BALANS_PO_DOGOVORU']){
            $query['BALANS_PO_DOGOVORU'] = str_replace(',','.', $query['BALANS_PO_DOGOVORU']);
            $query['BALANS_PO_DOGOVORU'] = str_replace('|RUB','', $query['BALANS_PO_DOGOVORU']);
        }else{
            $query['BALANS_PO_DOGOVORU'] ='';
        }

        $fields =[
            131 => $query['ID_CRM_DEALS'],
            132 => $company['ID'],
            133 => $query['GUID_CONTRACT'],
            146 => $query['BALANS_PO_DOGOVORU'],
            135 => $query['VNESHNIY_KOD_BOKSA'],
            136 => $query['DATA_DOGOVORA'],
            140 => $query['GUID_COMPANY'],
            141 => $query['GUID_INVOICES'],
            142 => $query['STATUS_ID'],
            144 => $query['DATA_OKONCHANIYA_OPLATY_DOGOVORA'],
            139 => $query['ID_POLZOVATELYA_SAYTA'],
            148 => $query['DEBTS'],
            149 => $query['PERIODIC'],
            150 => $query['PAYMENT_PER_PERIOD'],

        ];

        $inventoryFiles = [];
        $inventoryTmpFiles = [];
        if (is_countable($query['INVENTORY']) && count($query['INVENTORY']) > 0 ) {
                $fields[147] = [];
                foreach ($query['INVENTORY'] as $inventory) {
                        $dataPos = strpos($inventory['PICTURE_DATA'], ',') + 1;
                        $image = base64_decode(trim(substr($inventory['PICTURE_DATA'], $dataPos)));
                        $FPName = $inventory['PICTURE_ID'].'.jpg';
                        $FPPath = $_SERVER['DOCUMENT_ROOT'].'/upload/tmp/'.$FPName;
                        //$FPPath = '/home/bitrix/www/upload/tmp/'.$FPName;
                        $inventoryTmpFiles[] = $FPPath;
                        try {
                                file_put_contents($FPPath, $image, LOCK_EX);
                                $arFile = ["name" => $FPName, "size" => filesize($FPPath), "tmp_name" => $FPPath, "type" => "image/jpeg"];
                                $fields[147][] = ['VALUE' => $arFile, 'DESCRIPTION' => $inventory['PICTURE_ID']];
                                //$inventoryFiles[147][] = array('VALUE' => CFile::MakeFileArray($FPPath), 'DESCRIPTION' => $inventory['PICTURE_ID']);
                        } catch (Exception $e) {
                                AddMessage2Log($e->getMessage(), "test_rdn");
                        }

                }
                //AddMessage2Log(print_r($inventoryFiles,true), "test_rdn");

        }
        //AddMessage2Log(print_r($inventoryTmpFiles,true), "test_rdn");


        $deal = [];
        if($query['GUID_CONTRACT']){
            $filter = [
                self::$fieldsDeal['GUID_DEAL'] => $query['GUID_CONTRACT'],
            ];
            $select = [
                'ID',
            ];
            $deal = \CCrmDeal::GetListEx([], $filter, false, false, $select)->Fetch();
            if($deal){
                $fields[131][] =$deal['ID'];
            }
        }

        if($query['STATUS_ID']==113 && $deal['ID']>0){
            Helper::setValueUserField('CRM_DEAL', $deal['ID'], self::$fieldsDeal['STAGE_ID'], 'C1:1');
        }

        $arLoadProductArray = Array(
            "IBLOCK_SECTION" => false,
            "PROPERTY_VALUES"=> $fields,
            "NAME"           => urldecode($query['NAME']),
            "ACTIVE"         => "Y",
        );
        $result = 0;
        if($id>0 && !is_array($id)){
            if($el->Update($id, $arLoadProductArray)){
                // upload inventory files
                if (is_countable($fields[147]) && count($fields[147]) > 0)  {
                        foreach ($inventoryTmpFiles as $tmpPath) {unlink($tmpPath);}
                }
                //
                $result = $query['GUID_CONTRACT'];
            }
        }
        return $result;
    }

    //Добавляем договор по запросу из 1С
    public static function addContractFrom1C($query, $iblock){

        if(!$query['ID_CRM_COMPANY']){
            $company = self::searchCompanyByGuid($query['GUID_COMPANY']);
        }else{
            $company['ID'] = $query['ID_CRM_COMPANY'];
        }

        if($query['BALANS_PO_DOGOVORU']){
            $query['BALANS_PO_DOGOVORU'] = str_replace(',','.', $query['BALANS_PO_DOGOVORU']);
            $query['BALANS_PO_DOGOVORU'] = str_replace('|RUB','', $query['BALANS_PO_DOGOVORU']);
        }else{
            $query['BALANS_PO_DOGOVORU'] ='';
        }
        $el = new \CIBlockElement;
        $fields =[
            131 => $query['ID_CRM_DEALS'],
            132 => $company['ID'],
            133 => $query['GUID_CONTRACT'],
            146 => $query['BALANS_PO_DOGOVORU'],
            135 => $query['VNESHNIY_KOD_BOKSA'],
            136 => $query['DATA_DOGOVORA'],
            140 => $query['GUID_COMPANY'],
            141 => $query['GUID_INVOICES'],
            142 => $query['STATUS_ID'],
            144 => $query['DATA_OKONCHANIYA_OPLATY_DOGOVORA'],
            139 => $query['ID_POLZOVATELYA_SAYTA'],
            148 => $query['DEBTS'],
            149 => $query['PERIODIC'],
            150 => $query['PAYMENT_PER_PERIOD'],
        ];

        $inventoryFiles = [];
        $inventoryTmpFiles = [];
        if (is_countable($query['INVENTORY']) && count($query['INVENTORY']) > 0 ) {
                $fields[147] = [];
                foreach ($query['INVENTORY'] as $inventory) {
                        $dataPos = strpos($inventory['PICTURE_DATA'], ',') + 1;
                        $image = base64_decode(trim(substr($inventory['PICTURE_DATA'], $dataPos)));
                        $FPName = $inventory['PICTURE_ID'].'.jpg';
                        $FPPath = $_SERVER['DOCUMENT_ROOT'].'/upload/tmp/'.$FPName;
                        //$FPPath = '/home/bitrix/www/upload/tmp/'.$FPName;
                        $inventoryTmpFiles[] = $FPPath;
                        try {
                                file_put_contents($FPPath, $image, LOCK_EX);
                                $arFile = ["name" => $FPName, "size" => filesize($FPPath), "tmp_name" => $FPPath, "type" => "image/jpeg"];
                                $fields[147][] = ['VALUE' => $arFile, 'DESCRIPTION' => $inventory['PICTURE_ID']];
                                //$inventoryFiles[147][] = array('VALUE' => CFile::MakeFileArray($FPPath), 'DESCRIPTION' => $inventory['PICTURE_ID']);
                        } catch (Exception $e) {
                                AddMessage2Log($e->getMessage(), "test_rdn");
                        }

                }
        }

        $deal = [];
        if($query['GUID_CONTRACT']){
            $filter = [
                self::$fieldsDeal['GUID_DEAL'] => $query['GUID_CONTRACT'],
            ];
            $select = [
                'ID',
            ];
            $deal = \CCrmDeal::GetListEx([], $filter, false, false, $select)->Fetch();
            if($deal){
                $fields[131][] =$deal['ID'];
            }
        }

        if($query['STATUS_ID']==113 && $deal['ID']>0){
            Helper::setValueUserField('CRM_DEAL', $deal['ID'], self::$fieldsDeal['STAGE_ID'], 'C1:1');
        }

        $arLoadProductArray = Array(
            "IBLOCK_SECTION" => false,
            "IBLOCK_ID"      => $iblock,
            "PROPERTY_VALUES"=> $fields,
            "NAME"           => urldecode($query['NAME']),
            "ACTIVE"         => "Y",
        );

        if($id = $el->Add($arLoadProductArray)) {
                if (is_countable($fields[147]) && count($fields[147]) > 0)  {
                        foreach ($inventoryTmpFiles as $tmpPath) {unlink($tmpPath);}
                }
            return $query['GUID_CONTRACT'];
        }
        else
            return "Error: ".$el->LAST_ERROR;
    }



    //Поиск документов при обращении из 1С
    public static function searchContractFrom1C($query, $iblock){

        $id = 0;
        if($query['ID_CRM_CONTRACT']){
            $arFilter = Array("IBLOCK_ID"=>$iblock, 'ID' => $query['ID_CRM_CONTRACT']);
            $arSelect = ['ID'];
            $res = \CIBlockElement::GetList(Array(), $arFilter, $arSelect);
            if ($ob = $res->GetNextElement()){
                $arFields = $ob->GetFields();
                $id = $arFields['ID'];
            }
        }
        if($query['GUID_CONTRACT'] && $id==0){
            $arFilter = Array("IBLOCK_ID"=>$iblock, '=PROPERTY_GUID_DOGOVORA' => $query['GUID_CONTRACT']);
            $arSelect = ['ID'];
            $res = \CIBlockElement::GetList(Array(), $arFilter, $arSelect);
            if ($ob = $res->GetNextElement()){
                $arFields = $ob->GetFields();
                $id = $arFields['ID'];
            }
        }
        return $id;

        /*$PROP = array();
        $PROP[12] = "Белый";
        $PROP[3] = 38;

        $arLoadContractArray = Array(
          "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
          "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
          "IBLOCK_ID"      => $iblock,
          "PROPERTY_VALUES"=> $PROP,
          "NAME"           => "Элемент",
          "ACTIVE"         => "Y",            // активен
        );

        if($CONTRACT_ID = $el->Add($arLoadContractArray))
          return $CONTRACT_ID;
        else
          return 0;*/
    }


    public static function searchCompanyByID($ID){
        if($ID){
            $company = 0;
            $filter['ID'] = $ID;
            $select = [
                '*',
                'UF_*'
            ];
            $res = \CCrmCompany::GetListEx([], $filter, false, false, $select);

            if ($item = $res->Fetch()) {
                $company = $item;
            }
        }

        if($company){
            return $company;
        }else{
            return 0;
        }
    }

    public static function searchCompanyByGuid($GUID_COMPANY){
        if($GUID_COMPANY){
            $company = 0;
            $filter['=UF_CRM_1575757712470'] = $GUID_COMPANY;
            $select = ['*'];
            $res = \CCrmCompany::GetListEx([], $filter, false, false, $select);

            if ($item = $res->Fetch()) {
                $company = $item;
            }
        }

        if($company){
            return $company;
        }else{
            return 0;
        }
    }

    //Поиск компании при передаче их из 1С
    public static function searchCompanyFrom1C($arPostFielsd)
    {
        //Проверяем по:
        //id_crm_company и guid_company
        //если этого нет, то проверяем по полю ИНН для юр лиц/ номер и серия паспорта для физ лиц
        //если этого нет, проверяем по номеру телефона Компании
        $companies = [];
        $filter = [];

        /*if($arPostFielsd['MANY'] && $arPostFielsd['MANY']=='Y'){
            if($arPostFielsd['ID_CRM_COMPANY']){
                $filter['ID'] = $arPostFielsd['ID_CRM_COMPANY'];
            }elseif($arPostFielsd['GUID_COMPANY']){
                $filter['=UF_CRM_1575757712470'] = $arPostFielsd['GUID_COMPANY'];
            }

            if($filter){
                $select = [
                    'ID'
                ];
                $res = \CCrmCompany::GetListEx([], $filter, false, false, $select);

                while ($item = $res->Fetch()) {
                    $companies[] = $item;
                }
            }
        }else {


            if ($arPostFielsd['PHONE']) {
                $arPostFielsd['PHONE'] = \CVoxImplantPhone::Normalize($arPostFielsd['PHONE']);
            }

            if ($arPostFielsd['ID_CRM_COMPANY']) {
                $filter['ID'] = $arPostFielsd['ID_CRM_COMPANY'];
            } else {
                if ($arPostFielsd['PASSPORT_SERIES'] && $arPostFielsd['PASSPORT_NUMBER']) {
                    $requisite = new \Bitrix\Crm\EntityRequisite();
                    $rs = $requisite->getList([
                        "filter" => [
                            "=RQ_IDENT_DOC_SER" => urldecode($arPostFielsd['PASSPORT_SERIES']),
                            "=RQ_IDENT_DOC_NUM" => urldecode($arPostFielsd['PASSPORT_NUMBER']),
                            "ENTITY_TYPE_ID" => \CCrmOwnerType::Company,
                        ],
                        "select" => ['ENTITY_ID'],
                    ]);
                    $companies = $rs->fetchAll();
                }
                if ($arPostFielsd['INN'] && count($companies) == 0) {
                    $requisite = new \Bitrix\Crm\EntityRequisite();
                    $rs = $requisite->getList([
                        "filter" => [
                            "=RQ_INN" => $arPostFielsd['INN'],
                            "ENTITY_TYPE_ID" => \CCrmOwnerType::Company,
                        ],
                        "select" => ['ENTITY_ID'],
                    ]);
                    $companies = $rs->fetchAll();
                }
            }

            //Если ничего не нашли, начинаем поиск по емеилу и телефону
            if (count($companies) == 0 && $arPostFielsd['EMAIL'] && !$arPostFielsd['ID_CRM_COMPANY']) {
                $filterD = [
                    'VALUE' => urldecode($arPostFielsd['EMAIL']),
                    'TYPE_ID' => \CCrmFieldMulti::EMAIL,
                    'ENTITY_ID' => 'COMPANY',
                ];
                $select = ['*'];
                $db = \CCrmFieldMulti::GetList([], $filterD, false, false, $select);
                if ($field = $db->Fetch()) {
                    $companies[]['ENTITY_ID'] = $field['ELEMENT_ID'];
                }
            }

            if (count($companies) == 0 && $arPostFielsd['PHONE'] && !$arPostFielsd['ID_CRM_COMPANY']) {
                $links = array("+", " ", "-", "(", ")");
                $phone = str_replace($links, "", $arPostFielsd['PHONE']);
                $filterD = [
                    'VALUE' => $phone,
                    'TYPE_ID' => \CCrmFieldMulti::PHONE,
                    'ENTITY_ID' => 'COMPANY',
                ];
                $select = ['*'];
                $db = \CCrmFieldMulti::GetList([], $filterD, false, false, $select);

                if ($field = $db->Fetch()) {
                    $companies[]['ENTITY_ID'] = $field['ELEMENT_ID'];
                }
            }


            if (!$arPostFielsd['ID_CRM_COMPANY'] && count($companies) == 0 && $arPostFielsd['GUID_COMPANY']) {
                $filter['=UF_CRM_1575757712470'] = $arPostFielsd['GUID_COMPANY'];
            }
            if (count($companies) == 0 && !empty($filter)) {
                $select = [
                    'ID'
                ];
                $res = \CCrmCompany::GetListEx([], $filter, false, false, $select);

                while ($item = $res->Fetch()) {
                    $companies[] = $item;
                }
            } elseif (count($companies) > 0) {
                $buffer = [];
                foreach ($companies as $key => $value) {
                    $buffer[]['ID'] = $value['ENTITY_ID'];
                }
                $companies = $buffer;
            }
        }*/

        if($arPostFielsd['ID_CRM_COMPANY']){
            $filter['ID'] = $arPostFielsd['ID_CRM_COMPANY'];
        }elseif($arPostFielsd['GUID_COMPANY']){
            $filter['=UF_CRM_1575757712470'] = $arPostFielsd['GUID_COMPANY'];
        }

        if (!empty($filter)) {
            $select = [
                'ID'
            ];
            $res = \CCrmCompany::GetListEx([], $filter, false, false, $select);

            while ($item = $res->Fetch()) {
                $companies[] = $item;
            }
        }

        if(is_countable($companies) && count($companies)>0){
            return $companies[0]['ID'];
        }else{
            return 0;
        }

    }


    public static function updateCompanyFrom1C($companyId, $companyData)
    {
        $resultData['TITLE'] = urldecode($companyData['TITLE']);
        $resultData['NAME'] = urldecode($companyData['NAME']);
        $resultData['LAST_NAME'] = urldecode($companyData['LAST_NAME']);
        $resultData['SECOND_NAME'] = urldecode($companyData['SECOND_NAME']);
        //$resultData['LOGIN'] = $companyData['LOGIN'];
        $resultData['COMPANY_BALANCE'] = $companyData['COMPANY_BALANCE'];
        $resultData['COMPANY_PENALTIES'] = $companyData['COMPANY_PENALTIES'];
        $resultData['STOCK_ID'] = $companyData['STOCK_ID'];

        $resultData['EMAIL'] = urldecode($companyData['EMAIL']);
        $resultData['PHONE'] = $companyData['PHONE'];
        $resultData['EMAILS'] = $companyData['EMAILS'];
        $resultData['PHONES'] = $companyData['PHONES'];
        $resultData['ASSIGNED_BY_ID'] = $companyData['ASSIGNED_BY_ID'];

        $resultData['PERSON_TYPE'] = $companyData['PROFILE_TYPE'];
        $resultData['GUID_COMPANY'] = $companyData['GUID_COMPANY'];
        $resultData['GUID_INVOICES'] = $companyData['GUID_INVOICES'];
        $resultData['GUID_CONTRACTS'] = $companyData['GUID_CONTRACTS'];


        $resultData['COMMENTS'] = urldecode($companyData['COMMENTS']);
        $resultData['COMMENTS_1C'] = urldecode($companyData['COMMENTS_1C']);
        $resultData['DATE_BIRTH'] = $companyData['DATE_BIRTH'];

        $resultData['PROFILE']['HOW_FIND_ID'] = $companyData['PROFILE']['HOW_FIND_ID'];
        $resultData['PROFILE']['HOW_FIND_COMMENTS'] = urldecode($companyData['PROFILE']['HOW_FIND_COMMENTS']);
        $resultData['PROFILE']['WHY_NEED_ID'] = $companyData['PROFILE']['WHY_NEED_ID'];
        $resultData['PROFILE']['WHY_NEED_COMMENTS'] = urldecode($companyData['PROFILE']['WHY_NEED_COMMENTS']);
        $resultData['PROFILE']['GENDER'] = $companyData['PROFILE']['GENDER'];
        $resultData['PROFILE']['FIRST_CONTACT_EMAIL'] = $companyData['PROFILE']['FIRST_CONTACT_EMAIL'];

        $resultData['PROFILE']['GET_SMS'] = $companyData['PROFILE']['GET_SMS'];
        $resultData['PROFILE']['GET_EMAILS'] = $companyData['PROFILE']['GET_EMAILS'];

        $resultData['PROFILE']['PURPOSE'] = $companyData['PROFILE']['PURPOSE'];
        $resultData['PROFILE']['TRANSPORTATION'] = $companyData['PROFILE']['TRANSPORTATION'];


        $resultData['ID_SITE_COMPANY'] = $companyData['ID_SITE_COMPANY'];
        if(isset($companyData['REQUSITES']) && !empty($companyData['REQUSITES'])){
            $resultData['REQUSITES'] = $companyData['REQUSITES'];
            $resultData['REQUSITES']['RQ_NAME'] = urldecode($resultData['REQUSITES']['RQ_NAME']);
            $resultData['REQUSITES']['RQ_FIRST_NAME'] = urldecode($resultData['REQUSITES']['RQ_FIRST_NAME']);
            $resultData['REQUSITES']['RQ_LAST_NAME'] = urldecode($resultData['REQUSITES']['RQ_LAST_NAME']);
            $resultData['REQUSITES']['RQ_COMPANY_NAME'] = urldecode($resultData['REQUSITES']['RQ_COMPANY_NAME']);
            $resultData['REQUSITES']['RQ_COMPANY_FULL_NAME'] = urldecode($resultData['REQUSITES']['RQ_COMPANY_FULL_NAME']);
            $resultData['REQUSITES']['RQ_DIRECTOR'] = urldecode($resultData['REQUSITES']['RQ_DIRECTOR']);
            $resultData['REQUSITES']['RQ_CEO_WORK_POS'] = urldecode($resultData['REQUSITES']['RQ_CEO_WORK_POS']);
            $resultData['REQUSITES']['RQ_IDENT_DOC'] = urldecode($resultData['REQUSITES']['RQ_IDENT_DOC']);
            $resultData['REQUSITES']['RQ_IDENT_DOC_ISSUED_BY'] = urldecode($resultData['REQUSITES']['RQ_IDENT_DOC_ISSUED_BY']);
            $resultData['REQUSITES']['RQ_IDENT_DOC_SER'] = urldecode($resultData['REQUSITES']['RQ_IDENT_DOC_SER']);
            $resultData['REQUSITES']['RQ_IDENT_DOC_NUM'] = urldecode($resultData['REQUSITES']['RQ_IDENT_DOC_NUM']);
            if($companyData['ADDRESS_ACTUAL']){
                $resultData['REQUSITES']['RQ_ADDR'][1]['ADDRESS_1'] = urldecode($companyData['ADDRESS_ACTUAL']);
            }
            if($companyData['ADDRESS_REGISTRATION']) {
                $resultData['REQUSITES']['RQ_ADDR'][4]['ADDRESS_1'] = urldecode($companyData['ADDRESS_REGISTRATION']);
            }

        }
        return self::updateCompany1C($companyId, $resultData);
    }



    public static function updateCompany1C($companyId, $user)
    {
        if (!is_array($user))
            return false;

        $result = 0;
        $TITLE = $user['TITLE'];

        $NAME = $user['NAME'];
        $LAST_NAME = $user['LAST_NAME'];
        $SECOND_NAME = $user['SECOND_NAME'];

        $EMAIL = $user['EMAIL'];
        $PHONE = $user['PHONE'];
        $EMAILS = $user['EMAILS'];
        $PHONES = $user['PHONES'];

        $PERSON_TYPE = $user['PERSON_TYPE'];
        $ID_SITE = $user['ID_SITE_COMPANY'];

        $GUID_COMPANY = $user['GUID_COMPANY'];
        $GUID_INVOICES = $user['GUID_INVOICES'];
        $GUID_CONTRACTS = $user['GUID_CONTRACTS'];

        $COMMENTS = $user['COMMENTS'];
        $COMMENTS_1C = $user['COMMENTS_1C'];
        $DATE_BIRTH = $user['DATE_BIRTH'];

        $HOW_FIND = $user['PROFILE']['HOW_FIND_ID'];
        $HOW_FIND_COMMENTS = $user['PROFILE']['HOW_FIND_COMMENTS'];
        $WHY_NEED= $user['PROFILE']['WHY_NEED_ID'];
        $WHY_NEED_COMMENTS= $user['PROFILE']['WHY_NEED_COMMENTS'];
        $GENDER = $user['PROFILE']['GENDER'];
        $FIRST_CONTACT_EMAIL = $user['PROFILE']['FIRST_CONTACT_EMAIL'];


        if (HelperRest::isFis($user)) {
            $PERSON_TYPE = 94;
        }
        if (HelperRest::isJur($user)) {
            $PERSON_TYPE = 95;
        }
        if (HelperRest::isIp($user)) {
            $PERSON_TYPE = 96;
        }

        $arFields = [
            'TITLE' => $TITLE,
            self::$fieldsCompany['Тип клиента (юр/физ)'] => $PERSON_TYPE,
            self::$fieldsCompany['Фамилия'] => $LAST_NAME,
            self::$fieldsCompany['Имя'] => $NAME,
            self::$fieldsCompany['Отчество'] => $SECOND_NAME,

            self::$fieldsCompany['Ответственное лицо'] => $user['ASSIGNED_BY_ID'],
            'MODIFY_BY'  => $user['ASSIGNED_BY_ID'],
            'MODIFY_BY_ID'  => $user['ASSIGNED_BY_ID'],

            self::$fieldsCompany['STOCK_ID'] => $user['STOCK_ID'],
            self::$fieldsCompany['GUID_COMPANY'] => $GUID_COMPANY,
            self::$fieldsCompany['GUID_INVOICES'] => $GUID_INVOICES,
            self::$fieldsCompany['GUID_CONTRACTS'] => $GUID_CONTRACTS,
            self::$fieldsCompany['Передано в 1С'] => 1,

            self::$fieldsCompany['Дата рождения'] => $DATE_BIRTH,
            self::$fieldsCompany['Первичный контакт с клиентом'] => Helper::getUserByLogin($FIRST_CONTACT_EMAIL),
            //self::$fieldsCompany['LOGIN'] => $user['LOGIN'],
            self::$fieldsCompany['Пол'] => $GENDER,

            self::$fieldsCompany['Получать СМС рассылку'] => $user['PROFILE']['GET_SMS'],
            self::$fieldsCompany['Получать почтовую рассылку'] => $user['PROFILE']['GET_EMAILS'],

            self::$fieldsCompany['Цель бокса'] => $user['PROFILE']['PURPOSE'],
            self::$fieldsCompany['Требуется ли перевозка?'] => $user['PROFILE']['TRANSPORTATION'],

            'COMMENTS'=>$COMMENTS,
            self::$fieldsCompany['Комментарий к Как о нас узнали?']=>$HOW_FIND_COMMENTS,
            self::$fieldsCompany['Комментарий к Зачем нужен Альфасклад?']=>$WHY_NEED_COMMENTS,
            self::$fieldsCompany['Баланс по контрагенту']=>$user['COMPANY_BALANCE'],
            self::$fieldsCompany['Сумма штрафных санкций']=>$user['COMPANY_PENALTIES'],

            self::$fieldsCompany['Зачем нужен Альфасклад?'] => $WHY_NEED,
            self::$fieldsCompany['Как о нас узнали'] => $HOW_FIND,
        ];
        if($ID_SITE){$arFields[self::$fieldsCompany['ID_SITE_COMPANY']]=$ID_SITE;}
        //if($WHY_NEED){$arFields[self::$fieldsCompany['Зачем нужен Альфасклад?']]=$WHY_NEED;}else{$arFields[self::$fieldsCompany['Зачем нужен Альфасклад?']]=107;}
        //if($HOW_FIND){$arFields[self::$fieldsCompany['Как о нас узнали']]=$HOW_FIND;}else{$arFields[self::$fieldsCompany['Как о нас узнали']]=160;}
        //if($GENDER){$arFields[self::$fieldsCompany['Пол']]=$GENDER;}
        if($COMMENTS_1C){$arFields[self::$fieldsCompany['Комментарий из 1С']]=$COMMENTS_1C;}


        if ($companyId>0){

            //////
            $arFilter = array(
                'ENTITY_ID'  => 'COMPANY',
                'ELEMENT_ID' => $companyId,
                'TYPE_ID'    => array('PHONE', 'EMAIL'),
            );
            $resCont = \CCrmFieldMulti::GetListEx(array(),$arFilter);
            while ($arCont = $resCont->fetch())
            {
                $contact = new \CCrmFieldMulti(false);
                $contact->Delete($arCont['ID']);
            }
            ///////

            $CCrmCompany = new \CCrmCompany(false);
            if($CCrmCompany->Update($companyId, $arFields, false, false, ['DISABLE_USER_FIELD_CHECK' => true])){
                $result = $GUID_COMPANY;
            }

            Helper::addEmail($companyId, \CCrmOwnerType::CompanyName, $EMAIL);
            Helper::addPhone($companyId, \CCrmOwnerType::CompanyName, $PHONE);

            if($EMAILS){
                foreach ($EMAILS as $key => $value) {
                    Helper::addEmail($companyId, \CCrmOwnerType::CompanyName, urldecode($value));
                }
            }
            if($PHONES){
                foreach ($PHONES as $key => $value) {
                    Helper::addPhone($companyId, \CCrmOwnerType::CompanyName, urldecode($value));
                }
            }


            if(isset($user['REQUSITES']) && !empty($user['REQUSITES'])){
                $arFieldsRequisites = $user['REQUSITES'];
                $arFieldsRequisites['ID'] = 0;
                $arFieldsRequisites['PSEUDO_ID'] = 0;
                if (HelperRest::isFis($user)) {
                    $arFieldsRequisites['PRESET_ID'] = 3;
                    $arFieldsRequisites['NAME'] = 'Физ. Лицо';
                }
                if (HelperRest::isJur($user)) {
                    $arFieldsRequisites['PRESET_ID'] = 1;
                    $arFieldsRequisites['NAME'] = 'организация';
                }
                if (HelperRest::isIp($user)) {
                    $arFieldsRequisites['PRESET_ID'] = 2;
                    $arFieldsRequisites['NAME'] = 'ИП';
                }
                $arFieldsRequisitesMain['REQUISITE'][] = $arFieldsRequisites;

                $res_add = \Enum\Helper::AddRequisitesCompany($companyId, $arFieldsRequisitesMain, true, false);
            }
        }
        return $result;
    }



































    public static function addCompanyFrom1C($companyData)
    {
        $resultData['TITLE'] = urldecode($companyData['TITLE']);
        $resultData['NAME'] = urldecode($companyData['NAME']);
        $resultData['LAST_NAME'] = urldecode($companyData['LAST_NAME']);
        $resultData['SECOND_NAME'] = urldecode($companyData['SECOND_NAME']);
        //$resultData['LOGIN'] = $companyData['LOGIN'];
        $resultData['COMPANY_BALANCE'] = $companyData['COMPANY_BALANCE'];
        $resultData['COMPANY_PENALTIES'] = $companyData['COMPANY_PENALTIES'];
        $resultData['STOCK_ID'] = $companyData['STOCK_ID'];

        $resultData['EMAIL'] = urldecode($companyData['EMAIL']);
        $resultData['PHONE'] = $companyData['PHONE'];
        $resultData['EMAILS'] = $companyData['EMAILS'];
        $resultData['PHONES'] = $companyData['PHONES'];
        $resultData['ASSIGNED_BY_ID'] = $companyData['ASSIGNED_BY_ID'];

        $resultData['PERSON_TYPE'] = $companyData['PROFILE_TYPE'];
        $resultData['GUID_COMPANY'] = $companyData['GUID_COMPANY'];
        $resultData['GUID_INVOICES'] = $companyData['GUID_INVOICES'];
        $resultData['GUID_CONTRACTS'] = $companyData['GUID_CONTRACTS'];

        $resultData['COMMENTS'] = urldecode($companyData['COMMENTS']);
        $resultData['COMMENTS_1C'] = urldecode($companyData['COMMENTS_1C']);
        $resultData['DATE_BIRTH'] = $companyData['DATE_BIRTH'];

        $resultData['PROFILE']['HOW_FIND_ID'] = $companyData['PROFILE']['HOW_FIND_ID'];
        $resultData['PROFILE']['HOW_FIND_COMMENTS'] = urldecode($companyData['PROFILE']['HOW_FIND_COMMENTS']);
        $resultData['PROFILE']['WHY_NEED_ID'] = $companyData['PROFILE']['WHY_NEED_ID'];
        $resultData['PROFILE']['WHY_NEED_COMMENTS'] = urldecode($companyData['PROFILE']['WHY_NEED_COMMENTS']);
        $resultData['PROFILE']['GENDER'] = $companyData['PROFILE']['GENDER'];
        $resultData['PROFILE']['FIRST_CONTACT_EMAIL'] = $companyData['PROFILE']['FIRST_CONTACT_EMAIL'];

        $resultData['PROFILE']['GET_SMS'] = $companyData['PROFILE']['GET_SMS'];
        $resultData['PROFILE']['GET_EMAILS'] = $companyData['PROFILE']['GET_EMAILS'];

        $resultData['PROFILE']['PURPOSE'] = $companyData['PROFILE']['PURPOSE'];
        $resultData['PROFILE']['TRANSPORTATION'] = $companyData['PROFILE']['TRANSPORTATION'];

        $resultData['ID_SITE_COMPANY'] = $companyData['ID_SITE_COMPANY'];
        if(isset($companyData['REQUSITES']) && !empty($companyData['REQUSITES'])){
            $resultData['REQUSITES'] = $companyData['REQUSITES'];
            $resultData['REQUSITES']['RQ_NAME'] = urldecode($resultData['REQUSITES']['RQ_NAME']);
            $resultData['REQUSITES']['RQ_FIRST_NAME'] = urldecode($resultData['REQUSITES']['RQ_FIRST_NAME']);
            $resultData['REQUSITES']['RQ_LAST_NAME'] = urldecode($resultData['REQUSITES']['RQ_LAST_NAME']);
            $resultData['REQUSITES']['RQ_COMPANY_NAME'] = urldecode($resultData['REQUSITES']['RQ_COMPANY_NAME']);
            $resultData['REQUSITES']['RQ_COMPANY_FULL_NAME'] = urldecode($resultData['REQUSITES']['RQ_COMPANY_FULL_NAME']);
            $resultData['REQUSITES']['RQ_DIRECTOR'] = urldecode($resultData['REQUSITES']['RQ_DIRECTOR']);
            $resultData['REQUSITES']['RQ_CEO_WORK_POS'] = urldecode($resultData['REQUSITES']['RQ_CEO_WORK_POS']);
            $resultData['REQUSITES']['RQ_IDENT_DOC'] = urldecode($resultData['REQUSITES']['RQ_IDENT_DOC']);
            $resultData['REQUSITES']['RQ_IDENT_DOC_ISSUED_BY'] = urldecode($resultData['REQUSITES']['RQ_IDENT_DOC_ISSUED_BY']);
            $resultData['REQUSITES']['RQ_IDENT_DOC_SER'] = urldecode($resultData['REQUSITES']['RQ_IDENT_DOC_SER']);
            $resultData['REQUSITES']['RQ_IDENT_DOC_NUM'] = urldecode($resultData['REQUSITES']['RQ_IDENT_DOC_NUM']);
            if($companyData['ADDRESS_ACTUAL']){
                $resultData['REQUSITES']['RQ_ADDR'][1]['ADDRESS_1'] = urldecode($companyData['ADDRESS_ACTUAL']);
            }
            if($companyData['ADDRESS_REGISTRATION']) {
                $resultData['REQUSITES']['RQ_ADDR'][4]['ADDRESS_1'] = urldecode($companyData['ADDRESS_REGISTRATION']);
            }
        }
        return self::addCompany1C($resultData);
    }



    public static function addCompany1C($user)
    {
        if (!is_array($user))
            return false;

        $id_company = false;
        $result = 0;
        $TITLE = $user['TITLE'];

        $NAME = $user['NAME'];
        $LAST_NAME = $user['LAST_NAME'];
        $SECOND_NAME = $user['SECOND_NAME'];

        $EMAIL = $user['EMAIL'];
        $PHONE = $user['PHONE'];
        $EMAILS = $user['EMAIL'];
        $PHONES = $user['PHONE'];

        $PERSON_TYPE = $user['PERSON_TYPE'];
        $ID_SITE = $user['ID_SITE_COMPANY'];

        $GUID_COMPANY = $user['GUID_COMPANY'];
        $GUID_INVOICES = $user['GUID_INVOICES'];
        $GUID_CONTRACTS = $user['GUID_CONTRACTS'];

        $COMMENTS = $user['COMMENTS'];
        $COMMENTS_1C = $user['COMMENTS_1C'];
        $DATE_BIRTH = $user['DATE_BIRTH'];

        $HOW_FIND = $user['PROFILE']['HOW_FIND_ID'];
        $HOW_FIND_COMMENTS = $user['PROFILE']['HOW_FIND_COMMENTS'];
        $WHY_NEED= $user['PROFILE']['WHY_NEED_ID'];
        $WHY_NEED_COMMENTS= $user['PROFILE']['WHY_NEED_COMMENTS'];
        $GENDER = $user['PROFILE']['GENDER'];
        $FIRST_CONTACT_EMAIL = $user['PROFILE']['FIRST_CONTACT_EMAIL'];


        if (HelperRest::isFis($user)) {
            $PERSON_TYPE = 94;
        }
        if (HelperRest::isJur($user)) {
            $PERSON_TYPE = 95;
        }
        if (HelperRest::isIp($user)) {
            $PERSON_TYPE = 96;
        }

        $arFields = [
            'TITLE' => $TITLE,
            self::$fieldsCompany['Тип клиента (юр/физ)'] => $PERSON_TYPE,

            self::$fieldsCompany['Фамилия'] => $LAST_NAME,
            self::$fieldsCompany['Имя'] => $NAME,
            self::$fieldsCompany['Отчество'] => $SECOND_NAME,

            self::$fieldsCompany['Ответственное лицо'] => $user['ASSIGNED_BY_ID'],
            'CREATED_BY' => $user['ASSIGNED_BY_ID'],
            'CREATED_BY_ID' => $user['ASSIGNED_BY_ID'],
            'MODIFY_BY'  => $user['ASSIGNED_BY_ID'],
            'MODIFY_BY_ID'  => $user['ASSIGNED_BY_ID'],

            self::$fieldsCompany['GUID_COMPANY'] => $GUID_COMPANY,
            self::$fieldsCompany['GUID_INVOICES'] => $GUID_INVOICES,
            self::$fieldsCompany['GUID_CONTRACTS'] => $GUID_CONTRACTS,
            self::$fieldsCompany['Передано в 1С'] => 0,
            self::$fieldsCompany['STOCK_ID'] => $user['STOCK_ID'],

            self::$fieldsCompany['Дата рождения'] => $DATE_BIRTH,
            self::$fieldsCompany['Первичный контакт с клиентом'] => Helper::getUserByLogin($FIRST_CONTACT_EMAIL),
            //self::$fieldsCompany['LOGIN'] => $user['LOGIN'],
            self::$fieldsCompany['Пол'] => $GENDER,

            self::$fieldsCompany['Получать СМС рассылку'] => $user['PROFILE']['GET_SMS'],
            self::$fieldsCompany['Получать почтовую рассылку'] => $user['PROFILE']['GET_EMAILS'],

            self::$fieldsCompany['Цель бокса'] => $user['PROFILE']['PURPOSE'],
            self::$fieldsCompany['Требуется ли перевозка?'] => $user['PROFILE']['TRANSPORTATION'],

            'COMMENTS'=>$COMMENTS,
            self::$fieldsCompany['Комментарий из 1С']=>$COMMENTS_1C,
            self::$fieldsCompany['Комментарий к Как о нас узнали?']=>$HOW_FIND_COMMENTS,
            self::$fieldsCompany['Комментарий к Зачем нужен Альфасклад?']=>$WHY_NEED_COMMENTS,
            self::$fieldsCompany['Баланс по контрагенту']=>$user['COMPANY_BALANCE'],
            self::$fieldsCompany['Сумма штрафных санкций']=>$user['COMPANY_PENALTIES'],

            self::$fieldsCompany['Зачем нужен Альфасклад?'] => $WHY_NEED,
            self::$fieldsCompany['Как о нас узнали'] => $HOW_FIND,
        ];
        if($ID_SITE){$arFields[self::$fieldsCompany['ID_SITE_COMPANY']]=$ID_SITE;}
        //if($WHY_NEED){$arFields[self::$fieldsCompany['Зачем нужен Альфасклад?']]=$WHY_NEED;}else{$arFields[self::$fieldsCompany['Зачем нужен Альфасклад?']]=107;}
        //if($HOW_FIND){$arFields[self::$fieldsCompany['Как о нас узнали']]=$HOW_FIND;}else{$arFields[self::$fieldsCompany['Как о нас узнали']]=160;}
        //if($GENDER){$arFields[self::$fieldsCompany['Пол']]=$GENDER;}

        $CCrmCompany = new \CCrmCompany(false);

        $id_company = $CCrmCompany->Add($arFields, false, ['DISABLE_USER_FIELD_CHECK' => true]);
        if ($id_company){
            $result = $GUID_COMPANY;

            Helper::addEmail($id_company, \CCrmOwnerType::CompanyName, $EMAIL);
            Helper::addPhone($id_company, \CCrmOwnerType::CompanyName, $PHONE);

            if($EMAILS){
                foreach ($EMAILS as $key => $value) {
                    Helper::addEmail($id_company, \CCrmOwnerType::CompanyName, urldecode($value));
                }
            }
            if($PHONES){
                foreach ($PHONES as $key => $value) {
                    Helper::addPhone($id_company, \CCrmOwnerType::CompanyName, urldecode($value));
                }
            }

            if(isset($user['REQUSITES']) && !empty($user['REQUSITES'])){
                $arFieldsRequisites = $user['REQUSITES'];
                $arFieldsRequisites['ID'] = 0;
                $arFieldsRequisites['PSEUDO_ID'] = 0;
                if (HelperRest::isFis($user)) {
                    $arFieldsRequisites['PRESET_ID'] = 3;
                    $arFieldsRequisites['NAME'] = 'Физ. Лицо';
                }
                if (HelperRest::isJur($user)) {
                    $arFieldsRequisites['PRESET_ID'] = 1;
                    $arFieldsRequisites['NAME'] = 'организация';
                }
                if (HelperRest::isIp($user)) {
                    $arFieldsRequisites['PRESET_ID'] = 2;
                    $arFieldsRequisites['NAME'] = 'ИП';
                }
                $arFieldsRequisitesMain['REQUISITE'][] = $arFieldsRequisites;

                $res_add = \Enum\Helper::AddRequisitesCompany($id_company, $arFieldsRequisitesMain, false, false);
            }
        }


        return $result;

    }









    public static function searchDealFrom1C($arPostFielsd)
    {
        $dealId = 0;
        if($arPostFielsd['ID_CRM_DEAL']){
            $filter = ['ID' => $arPostFielsd['ID_CRM_DEAL']];
            $select = ['*','UF_*'];
            $deals = \CCrmDeal::GetListEx([], $filter, false, false, $select);
            if ($deal = $deals->Fetch()) {
                $dealId = $deal['ID'];
            }
        }elseif($arPostFielsd['GUID_DEAL'] && $dealId==0){
            $filter = [self::$fieldsDeal['GUID_DEAL'] => $arPostFielsd['GUID_DEAL']];
            $select = ['*','UF_*'];
            $deals = \CCrmDeal::GetListEx([], $filter, false, false, $select);
            if ($deal = $deals->Fetch()) {
                $dealId = $deal['ID'];
            }
        }elseif($arPostFielsd['GUID_CONTRACT'] && $dealId==0){
            $filter = [self::$fieldsDeal['GUID_CONTRACT'] => $arPostFielsd['GUID_CONTRACT']];
            $select = ['*','UF_*'];
            $deals = \CCrmDeal::GetListEx([], $filter, false, false, $select);
            if ($deal = $deals->Fetch()) {
                $dealId = $deal['ID'];
            }
        }

        return $dealId;
    }


    public static function addDealFrom1C($query){

        if($query['STAGE_ID']){
            $STAGE_ID = $query['STAGE_ID'];
        }elseif($query['DATE_EXEMP']){
            $STAGE_ID = 'C1:1';
        }elseif(is_countable($query['INVOICES']) && count($query['INVOICES'])>0){
            $status = 1;
            foreach ($query['INVOICES'] as $key => $value) {
                if($value['STATUS_ID']!='P' && $value['STATUS_ID']!='D'){
                    $status = 0;
                }
            }
            if($status == 0){
                $STAGE_ID = 'C1:NEW';
            }else{
                $STAGE_ID = 'C1:WON';
            }
        }

        $result = 0;
        $field['TITLE'] = urldecode($query['TITLE']);
        $field['CATEGORY_ID'] = 1;
        $field[self::$fieldsDeal['Дата создания']] = $query['DATE_CREATE'];
        $field[self::$fieldsDeal['ID пользователя']] = $query['USER_ID'];
        $field[self::$fieldsDeal['Код размера']] = $query['CODE_OF_SIZE'];
        $field[self::$fieldsDeal['Процент скидки']] = $query['DISCOUNT_PERCENT'];//процент скидки
        $field[self::$fieldsDeal['Название скидки']] = urldecode($query['DISCOUNT_NAME']);
        $field['STAGE_ID'] = $STAGE_ID; //Статус При новом договоре через сайт - пустой; строка
        $field[self::$fieldsDeal['Комментарий']] = urldecode($query['COMMENT']);
        $field[self::$fieldsDeal['ID_пользователя сайта']] = $query['USER_ID'];
        $field[self::$fieldsDeal['Номер заказа']] = $query['ORDER_NUMBER'];
        $field[self::$fieldsDeal['Внешний код']] = $query['EXTERNAL_CODE'];
        $field[self::$fieldsDeal['ID Заказа']] = $query['ORDER_ID'];
        $field[self::$fieldsDeal['Дата начала аренды']] = $query['DATE_BEGIN_OF_RENT'];
        $field[self::$fieldsDeal['Дата окончания аренды']] = $query['DATE_END_OF_RENT'];
        $field[self::$fieldsDeal['Ответственное лицо']] = $query['ASSIGNED_BY_ID'];
        $field['CREATED_BY'] = $query['ASSIGNED_BY_ID'];

        $field[self::$fieldsDeal['Статус Бокса XML_ID']] = $query['STATUS_XML_ID'];
        $field[self::$fieldsDeal['Предполагаемая дата освобождения бокса']] = $query['DATE_EXEMP'];

        $field[self::$fieldsDeal['GUID_DEAL']] = $query['GUID_DEAL'];
        $field[self::$fieldsDeal['GUID_CONTRACT']] = $query['GUID_CONTRACT'];
        $field[self::$fieldsDeal['STOCK_ID']] = $query['STOCK_ID'];

        $field[self::$fieldsDeal['Кто подписал предварительный договор']] = Helper::getUserByLogin($query['SIG_DOC_0']);
        $field[self::$fieldsDeal['Кто подписал договор']] = Helper::getUserByLogin($query['SIG_DOC_1']);

        $field[self::$fieldsDeal['SEND_TO_1C']] = 0;

        $companyId = 0;
        if(!$query['COMPANY']['ID_CRM_COMPANY']){
            if($query['COMPANY']['GUID_COMPANY']){
                $companyId = self::searchCompanyByGuid($query['COMPANY']['GUID_COMPANY']);
                if($companyId){
                    $companyId = $companyId['ID'];
                }
            }
        }elseif($query['COMPANY']['ID_CRM_COMPANY']){
            $companyId = $query['COMPANY']['ID_CRM_COMPANY'];
        }

        if($companyId>0){
            $field['COMPANY_ID'] = $companyId;
        }

        $CCrmDeal = new \CCrmDeal(false);
        $dealID = $CCrmDeal->Add($field, false, ['DISABLE_USER_FIELD_CHECK' => true]);
        if($dealID){
            $result = $query['GUID_DEAL'];
            if($query['GUID_DEAL']) {
                $arFilter = Array("IBLOCK_ID" => 37, "=PROPERTY_GUID_DOGOVORA" => $query['GUID_DEAL']);
                $res = \CIBlockElement::GetList(Array(), $arFilter, false, [], ['ID']);
                if ($ob = $res->GetNextElement()) {
                    $arFields = $ob->GetFields();
                    \CIBlockElement::SetPropertyValuesEx($arFields['ID'], 37, array('SVYAZANNYE_SDELKI' => [$dealID]));
                }
            }

            if(is_countable($query['PRODUCTS']) && count($query['PRODUCTS'])>0){
                foreach ($query['PRODUCTS'] as $key => $value) {
                    if($value['XML_ID']){
                        $product_id = self::getProductByID($value['XML_ID']);
                        $query['PRODUCTS'][$key]['PRODUCT_ID'] = $product_id;
                    }
                    $query['PRODUCTS'][$key]['PRODUCT_NAME'] = urldecode($value['PRODUCT_NAME']);
                    $query['PRODUCTS'][$key]['ORIGINAL_PRODUCT_NAME'] = urldecode($value['ORIGINAL_PRODUCT_NAME']);
                    $query['PRODUCTS'][$key]['MEASURE_NAME'] = urldecode($value['MEASURE_NAME']);
                    $query['PRODUCTS'][$key]['VIEW_RENT'] = urldecode($value['VIEW_RENT']);
                    $query['PRODUCTS'][$key]['PRODUCT_DESCRIPTION'] = urldecode($value['PRODUCT_DESCRIPTION']);
                    $query['PRODUCTS'][$key]['PRICE_BRUTTO'] = $value['PRICE_NETTO'];
                    $query['PRODUCTS'][$key]['SORT'] = ($key+10);
                    $query['PRODUCTS'][$key]['CUSTOMIZED'] = 'Y';
                }
                $bSuccess = \CCrmDeal::SaveProductRows($dealID, $query['PRODUCTS'] , true, true, true);
            }

            if(is_countable($query['INVOICES']) && count($query['INVOICES'])>0){
                foreach ($query['INVOICES'] as $key => $value) {
                    $invoiceId = self::searchInvoiceFrom1C($value);
                    if($invoiceId>0){
                        $invoiceId = self::updateInvoiceFrom1C($invoiceId,$dealID, $companyId, $value);
                    }else{
                        $invoiceId = self::addInvoiceFrom1C($dealID, $companyId, $value);
                    }
                }
            }
        }

        return $result;
    }


    public static function updateDealFrom1C($dealID, $query){

        if($query['STAGE_ID']){
            $STAGE_ID = $query['STAGE_ID'];
        }elseif($query['DATE_EXEMP']){
            $STAGE_ID = 'C1:1';
        }elseif(is_countable($query['INVOICES']) && count($query['INVOICES'])>0){
            $status = 1;
            foreach ($query['INVOICES'] as $key => $value) {
                if($value['STATUS_ID']!='P' && $value['STATUS_ID']!='D'){
                    $status = 0;

                }
            }
            if($status == 0){
                $STAGE_ID = 'C1:NEW';
            }else{
                $STAGE_ID = 'C1:WON';
            }
        }

        $result=0;
        $field['TITLE'] = urldecode($query['TITLE']);
        $field['CATEGORY_ID'] = 1;
        //$field[self::$fieldsDeal['Дата создания']] = $query['DATE_CREATE'];
        $field[self::$fieldsDeal['ID пользователя']] = $query['USER_ID'];
        $field[self::$fieldsDeal['Код размера']] = $query['CODE_OF_SIZE'];
        $field[self::$fieldsDeal['Процент скидки']] = $query['DISCOUNT_PERCENT'];//процент скидки
        $field[self::$fieldsDeal['Название скидки']] = urldecode($query['DISCOUNT_NAME']);
        $field['STAGE_ID'] = $STAGE_ID; //Статус При новом договоре через сайт - пустой; строка
        $field[self::$fieldsDeal['Комментарий']] = urldecode($query['COMMENT']);
        $field[self::$fieldsDeal['ID_пользователя сайта']] = $query['USER_ID'];
        $field[self::$fieldsDeal['Номер заказа']] = $query['ORDER_NUMBER'];
        $field[self::$fieldsDeal['Внешний код']] = $query['EXTERNAL_CODE'];
        $field[self::$fieldsDeal['ID Заказа']] = $query['ORDER_ID'];
        $field[self::$fieldsDeal['Дата начала аренды']] = $query['DATE_BEGIN_OF_RENT'];
        $field[self::$fieldsDeal['Дата окончания аренды']] = $query['DATE_END_OF_RENT'];
        $field[self::$fieldsDeal['Ответственное лицо']] = $query['ASSIGNED_BY_ID'];
        $field['CREATED_BY'] = $query['ASSIGNED_BY_ID'];

        $field[self::$fieldsDeal['Статус Бокса XML_ID']] = $query['STATUS_XML_ID'];
        $field[self::$fieldsDeal['Предполагаемая дата освобождения бокса']] = $query['DATE_EXEMP'];
        $field[self::$fieldsDeal['GUID_DEAL']] = $query['GUID_DEAL'];
        $field[self::$fieldsDeal['GUID_CONTRACT']] = $query['GUID_CONTRACT'];

        $field[self::$fieldsDeal['STOCK_ID']] = $query['STOCK_ID'];
        $field[self::$fieldsDeal['Кто подписал предварительный договор']] = Helper::getUserByLogin($query['SIG_DOC_0']);
        $field[self::$fieldsDeal['Кто подписал договор']] = Helper::getUserByLogin($query['SIG_DOC_1']);
        $field[self::$fieldsDeal['SEND_TO_1C']] = 0;

        $companyId = 0;
        if(!$query['COMPANY']['ID_CRM_COMPANY']){
            if($query['COMPANY']['GUID_COMPANY']){
                $companyId = self::searchCompanyByGuid($query['COMPANY']['GUID_COMPANY']);
                if($companyId){
                    $companyId = $companyId['ID'];
                }
            }
        }elseif($query['COMPANY']['ID_CRM_COMPANY']){
            $companyId = $query['COMPANY']['ID_CRM_COMPANY'];
        }

        if($companyId>0){
            $field['COMPANY_ID'] = $companyId;
        }

        $CCrmDeal = new \CCrmDeal(false);
        if($CCrmDeal->Update($dealID, $field)){
            $result = $query['GUID_DEAL'];

            if($query['GUID_DEAL']) {
                $arFilter = Array("IBLOCK_ID" => 37, "=PROPERTY_GUID_DOGOVORA" => $query['GUID_DEAL']);
                $res = \CIBlockElement::GetList(Array(), $arFilter, false, [], ['ID']);
                if ($ob = $res->GetNextElement()) {
                    $arFields = $ob->GetFields();
                    \CIBlockElement::SetPropertyValuesEx($arFields['ID'], 37, array('SVYAZANNYE_SDELKI' => [$dealID]));
                }
            }

        }
        if(is_countable($query['PRODUCTS']) && count($query['PRODUCTS'])>0){
            foreach ($query['PRODUCTS'] as $key => $value) {
                if($value['XML_ID']){
                    $product_id = self::getProductByID($value['XML_ID']);
                    $query['PRODUCTS'][$key]['PRODUCT_ID'] = $product_id;
                }
                $query['PRODUCTS'][$key]['PRODUCT_NAME'] = urldecode($value['PRODUCT_NAME']);
                $query['PRODUCTS'][$key]['ORIGINAL_PRODUCT_NAME'] = urldecode($value['ORIGINAL_PRODUCT_NAME']);
                $query['PRODUCTS'][$key]['MEASURE_NAME'] = urldecode($value['MEASURE_NAME']);
                $query['PRODUCTS'][$key]['VIEW_RENT'] = urldecode($value['VIEW_RENT']);
                $query['PRODUCTS'][$key]['PRODUCT_DESCRIPTION'] = urldecode($value['PRODUCT_DESCRIPTION']);
                $query['PRODUCTS'][$key]['PRICE_BRUTTO'] = $value['PRICE_NETTO'];
                $query['PRODUCTS'][$key]['SORT'] = ($key+10);
                $query['PRODUCTS'][$key]['CUSTOMIZED'] = 'Y';
            }
            $bSuccess = \CCrmDeal::SaveProductRows($dealID, $query['PRODUCTS'] , true, true, true);
        }else{
            $bSuccess = \CCrmDeal::SaveProductRows($dealID, [] , true, true, true);
        }

        if(is_countable($query['INVOICES']) && count($query['INVOICES'])>0){
            foreach ($query['INVOICES'] as $key => $value) {
                $invoiceId = self::searchInvoiceFrom1C($value);
                if($invoiceId>0){
                    $invoiceId = self::updateInvoiceFrom1C($invoiceId,$dealID, $companyId, $value);
                }else{
                    $invoiceId = self::addInvoiceFrom1C($dealID, $companyId, $value);
                }
            }
        }

        return $result;
    }


    public static function searchInvoiceFrom1C($query){
        $invoiceId = 0;
        if($query['ID_CRM_INVOICE']){
            $filter = ['ID' => $query['ID_CRM_INVOICE']];
            $select = ['*','UF_*'];
            $resInvoice = \CCrmInvoice::GetList([], $filter, false, false, $select);
            if ($invoice = $resInvoice->Fetch()) {
                $invoiceId = $invoice['ID'];
            }
        }elseif($query['GUID_INVOICE'] && $invoiceId==0){
            $filter = [self::$fieldsInvoice['GUID_INVOICE'] => $query['GUID_INVOICE']];
            $select = ['*','UF_*'];
            $resInvoice = \CCrmInvoice::GetList([], $filter, false, false, $select);
            if ($invoice = $resInvoice->Fetch()) {
                $invoiceId = $invoice['ID'];
            }
        }

        return $invoiceId;
    }
    public static function addInvoiceFrom1C($dealID, $companyId, $item){

        file_put_contents(__DIR__ ."/addInvoiceFrom1C.txt",print_r($item,true), FILE_APPEND);

        $field['ORDER_TOPIC'] = "Счет клиенту";
        if($item['RESPONSIBLE_EMAIL']){
            $field['RESPONSIBLE_ID'] = Helper::getUserByLogin(urldecode($item['RESPONSIBLE_EMAIL']));
        }
        $field['UF_DEAL_ID'] = $dealID;
        $field["UF_QUOTE_ID"] = 0;
        $field["UF_COMPANY_ID"] = $companyId;
        //$field["STATUS_ID"] = $item['STATUS_ID'];
        $field["UF_CONTACT_ID"] = 0;
        $field["PERSON_TYPE_ID"] = 1;
        $field["PAY_SYSTEM_ID"] = 1;

        $field[self::$fieldsInvoice['ID_SITE_INVOICE']] = $item['ID_SITE_INVOICE'];
        //$field['ID'] = $item['ID_CRM_INVOICE'];
        $field[self::$fieldsInvoice['GUID_INVOICE']] = $item['GUID_INVOICE'];
        $field[self::$fieldsInvoice['ACCOUNT_NUMBER']] = urldecode($item['ACCOUNT_NUMBER']);
        $field['PRICE'] = $item['PRICE'];
        $field['COMMENTS'] = urldecode($item['COMMENTS']);
        $field[self::$fieldsInvoice['ORDER_ID']] = $item['ORDER_ID'];
        $field[self::$fieldsInvoice['DATE_BEGIN_OF_RENT']] = $item['DATE_BEGIN_OF_RENT'];
        $field[self::$fieldsInvoice['DATE_END_OF_RENT']] = $item['DATE_END_OF_RENT'];
        $field[self::$fieldsInvoice['USER_ID']] = $item['USER_ID'];
        $field[self::$fieldsInvoice['EXTERNAL_CODE']] = $item['EXTERNAL_CODE'];
        $field[self::$fieldsInvoice['DATE_CREATE']] = $item['DATE_CREATE'];

        $field[self::$fieldsInvoice['CONTRACT']] = $item['CONTRACT'];
        $field[self::$fieldsInvoice['ACCRUED_FROM']] = $item['ACCRUED_FROM'];
        $field[self::$fieldsInvoice['CHARGED_ON']] = $item['CHARGED_ON'];
        $field[self::$fieldsInvoice['PAYED']] = $item['PAYED'];

        $field['STATUS_ID'] = $item['STATUS_ID'];
        $field['PAY_VOUCHER_DATE'] = $item['PAY_VOUCHER_DATE'];
        $field['PAY_VOUCHER_NUM'] = $item['PAY_VOUCHER_NUM'];
        $field[self::$fieldsInvoice['DELIVERY']] = $item['DELIVERY'];
        if($item['FIRST_INVOICE']=='TRUE') {
            $field[self::$fieldsInvoice['FIRST_INVOICE']] = 1;
        }else{
            $field[self::$fieldsInvoice['FIRST_INVOICE']] = 0;
        }
        if($item['STORNO']=='TRUE') {
            $field[self::$fieldsInvoice['STORNO']] = 1;
            $field['STATUS_ID'] = 2;
        }else{
            $field[self::$fieldsInvoice['STORNO']] = 0;
            $field['STATUS_ID'] = $item['STATUS_ID'];
        }

        $field['PRODUCT_ROWS'] = $item['PRODUCTS_INVOICE'];
        foreach ($field['PRODUCT_ROWS'] as $key => $value) {
            $field['PRODUCT_ROWS'][$key]['CUSTOM_PRICE'] = 'Y';
            $field['PRODUCT_ROWS'][$key]['DISCOUNT_PRICE'] = $value['DISCOUNT_SUM'];
            $field['PRODUCT_ROWS'][$key]['MODULE'] = '';
            $field['PRODUCT_ROWS'][$key]['VAT_RATE'] = $value['TAX_RATE'];
            $field['PRODUCT_ROWS'][$key]['VAT_INCLUDED'] = $value['TAX_INCLUDED'];
            $field['PRODUCT_ROWS'][$key]['CATALOG_XML_ID'] = 'boxes_filials';
            $field['PRODUCT_ROWS'][$key]['PRODUCT_NAME'] = urldecode($value['PRODUCT_NAME']);
            $field['PRODUCT_ROWS'][$key]['ORIGINAL_PRODUCT_NAME'] = urldecode($value['ORIGINAL_PRODUCT_NAME']);
            $field['PRODUCT_ROWS'][$key]['PRODUCT_DESCRIPTION'] = urldecode($value['PRODUCT_DESCRIPTION']);
            $field['PRODUCT_ROWS'][$key]['MEASURE_NAME'] = urldecode($value['MEASURE_NAME']);

            if($value['XML_ID']){
                $product_id = self::getProductByID($value['XML_ID']);
                $field['PRODUCT_ROWS'][$key]['PRODUCT_ID'] = $product_id;
            }
        }

        $field["INVOICE_PROPERTIES"] = Array("10" => "", "11" => "","8" => "","9" => "","12" => "","13" => "","14" => "","15" => "","16" => "","17" => "","18" => "","19" =>" ");

        $CCrmInvoice = new \CCrmInvoice(false);
        $invoiseID = $CCrmInvoice->Add($field);

        return $invoiseID;
    }

    public static function updateInvoiceFrom1C($invoiceId,$dealID, $companyId, $item){

        file_put_contents(__DIR__ ."/updateInvoiceFrom1C.txt",print_r($item,true), FILE_APPEND);

        $field['ID'] = $invoiceId;
        $field['ORDER_TOPIC'] = "Счет клиенту";
        if($item['RESPONSIBLE_EMAIL']){
            $field['RESPONSIBLE_ID'] = Helper::getUserByLogin(urldecode($item['RESPONSIBLE_EMAIL']));
        }
        if($dealID>0) {
            $field['UF_DEAL_ID'] = $dealID;
        }
        $field["UF_QUOTE_ID"] = 0;
        $field["UF_COMPANY_ID"] = $companyId;
        //$field["STATUS_ID"] = $item['STATUS_ID'];
        $field["UF_CONTACT_ID"] = 0;
        $field["PERSON_TYPE_ID"] = 1;
        $field["PAY_SYSTEM_ID"] = 1;

        $field[self::$fieldsInvoice['ID_SITE_INVOICE']] = $item['ID_SITE_INVOICE'];
        //$field['ID'] = $item['ID_CRM_INVOICE'];
        $field[self::$fieldsInvoice['GUID_INVOICE']] = $item['GUID_INVOICE'];
        $field[self::$fieldsInvoice['ACCOUNT_NUMBER']] = urldecode($item['ACCOUNT_NUMBER']);
        $field['PRICE'] = $item['PRICE'];
        $field['COMMENTS'] = urldecode($item['COMMENTS']);
        $field[self::$fieldsInvoice['ORDER_ID']] = $item['ORDER_ID'];
        $field[self::$fieldsInvoice['DATE_BEGIN_OF_RENT']] = $item['DATE_BEGIN_OF_RENT'];
        $field[self::$fieldsInvoice['DATE_END_OF_RENT']] = $item['DATE_END_OF_RENT'];
        $field[self::$fieldsInvoice['USER_ID']] = $item['USER_ID'];
        $field[self::$fieldsInvoice['EXTERNAL_CODE']] = $item['EXTERNAL_CODE'];
        $field[self::$fieldsInvoice['DATE_CREATE']] = $item['DATE_CREATE'];

        $field[self::$fieldsInvoice['CONTRACT']] = $item['CONTRACT'];
        $field[self::$fieldsInvoice['ACCRUED_FROM']] = $item['ACCRUED_FROM'];
        $field[self::$fieldsInvoice['CHARGED_ON']] = $item['CHARGED_ON'];
        $field[self::$fieldsInvoice['PAYED']] = $item['PAYED'];

        $field['STATUS_ID'] = $item['STATUS_ID'];
        $field['PAY_VOUCHER_DATE'] = $item['PAY_VOUCHER_DATE'];
        $field['PAY_VOUCHER_NUM'] = $item['PAY_VOUCHER_NUM'];
        $field[self::$fieldsInvoice['DELIVERY']] = $item['DELIVERY'];
        if($item['FIRST_INVOICE']=='TRUE') {
            $field[self::$fieldsInvoice['FIRST_INVOICE']] = 1;
        }else{
            $field[self::$fieldsInvoice['FIRST_INVOICE']] = 0;
        }
        if($item['STORNO']=='TRUE') {
            $field[self::$fieldsInvoice['STORNO']] = 1;
            $field['STATUS_ID'] = 2;
        }else{
            $field[self::$fieldsInvoice['STORNO']] = 0;
            $field['STATUS_ID'] = $item['STATUS_ID'];
        }

        $field['PRODUCT_ROWS'] = $item['PRODUCTS_INVOICE'];

        foreach ($field['PRODUCT_ROWS'] as $key => $value) {
            $field['PRODUCT_ROWS'][$key]['CUSTOM_PRICE'] = 'Y';
            $field['PRODUCT_ROWS'][$key]['DISCOUNT_PRICE'] = $value['DISCOUNT_SUM'];
            $field['PRODUCT_ROWS'][$key]['MODULE'] = '';
            $field['PRODUCT_ROWS'][$key]['VAT_RATE'] = $value['TAX_RATE'];
            $field['PRODUCT_ROWS'][$key]['VAT_INCLUDED'] = $value['TAX_INCLUDED'];
            $field['PRODUCT_ROWS'][$key]['CATALOG_XML_ID'] = 'boxes_filials';
            $field['PRODUCT_ROWS'][$key]['PRODUCT_NAME'] = urldecode($value['PRODUCT_NAME']);
            $field['PRODUCT_ROWS'][$key]['ORIGINAL_PRODUCT_NAME'] = urldecode($value['ORIGINAL_PRODUCT_NAME']);
            $field['PRODUCT_ROWS'][$key]['PRODUCT_DESCRIPTION'] = urldecode($value['PRODUCT_DESCRIPTION']);
            $field['PRODUCT_ROWS'][$key]['MEASURE_NAME'] = urldecode($value['MEASURE_NAME']);
            if($value['XML_ID']){
                $product_id = self::getProductByID($value['XML_ID']);
                $field['PRODUCT_ROWS'][$key]['PRODUCT_ID'] = $product_id;
            }
        }

        $field["INVOICE_PROPERTIES"] = Array("10" => "", "11" => "","8" => "","9" => "","12" => "","13" => "","14" => "","15" => "","16" => "","17" => "","18" => "","19" =>" ");

        $CCrmInvoice = new \CCrmInvoice(false);
        $invoiseID = $CCrmInvoice->update($invoiceId, $field);

        return $invoiseID;
    }






    /*
    Наименование клиента/ Фамилия Имя Отчество (строка)
    e-mail (строка)
    Телефон (строка)
    ИНН / паспорт_серия (строка) - реквизиты
    КПП / паспорт_номер (строка) - реквизиты
    ID_сайта
    Внешний код (строка)
    ID_CRM (пустой)
    Юр лицо / физ лицо (булево)
     */
    //создать компанию если такой нет по внешнему коду
    public static function addCompanyFromSite($user)
    {
        if (!is_array($user))
            return false;

        //file_put_contents($_SERVER['DOCUMENT_ROOT']."/logs/addDealFromSiteData.txt",print_r($user,true), FILE_APPEND);
        $id_company = false;

        $log_file = '/logs/addDealFromSite.txt';
        $log = [];

        $NAME = $user['NAME'];
        $LAST_NAME = $user['LAST_NAME'];
        $SECOND_NAME = $user['SECOND_NAME'];
        $BIRTHDAY = $user['BIRTHDAY'];
        $PASSPORT_SERIES = $user['PASSPORT_SERIES'];
        $PASSPORT_NUMBER = $user['PASSPORT_NUMBER'];
        $EMAIL = $user['EMAIL'];
        $PHONE = $user['PHONE'];
        $INN = $user['INN'];
        $ADDRESS = $user['ADDRESS'];//адрес регистрации
        $ACTUAL_ADDRESS = $user['ACTUAL_ADDRESS'];//фактический адрес
        $KPP = $user['KPP'];
        $PERSON_TYPE = $user['PERSON_TYPE'];
        $EXTERNAL_CODE = $user['EXTERNAL_CODE'];
        $ID_CRM = $user['ID_CRM'];//TODO
        $ID_SITE = $user['ID_SITE'];

        $GUID_COMPANY = $user['GUID_COMPANY'];
        $GUID_INVOICES = $user['GUID_INVOICES'];
        $GUID_CONTRACTS = $user['GUID_CONTRACTS'];

        if($user['ASSIGNED_BY_ID']==3){
            $user['STOCK_ID'] = 142;
        }elseif($user['ASSIGNED_BY_ID']==4){
            $user['STOCK_ID'] = 143;
        }elseif($user['ASSIGNED_BY_ID']==6){
            $user['STOCK_ID'] = 144;
        }elseif($user['ASSIGNED_BY_ID']==7){
            $user['STOCK_ID'] = 145;
        }elseif($user['ASSIGNED_BY_ID']==5){
            $user['STOCK_ID'] = 146;
        }


        if (HelperRest::isFis($user)) {
            $PERSON_TYPE = 94;
        }
        if (HelperRest::isJur($user)) {
            $PERSON_TYPE = 95;
        }

        if ($EXTERNAL_CODE != '') {
            $id_company = 0;
            if($user['ASSIGNED_BY_ID']>0){
                $id_company = self::getCompanyByExternalCodeAndStock($EXTERNAL_CODE,$user['ASSIGNED_BY_ID'],$ID_SITE);
            }else{
                $id_company = self::getCompanyByExternalCodeOrSiteID($EXTERNAL_CODE,$ID_SITE);
            }
            if($id_company==0){
            //if (!$id_company = self::getCompanyByExternalCode($EXTERNAL_CODE)) {
            //if (!$id_company = self::getCompanyByExternalCodeAndStock($EXTERNAL_CODE,$user['ASSIGNED_BY_ID'])) {
                $CCrmCompany = new \CCrmCompany(false);

                if ($NAME != '') {
                    $arFields = [
                        'TITLE' => $LAST_NAME . ' ' . $NAME . ' ' . $SECOND_NAME,
                        self::$fieldsCompany['Тип клиента (юр/физ)'] => $PERSON_TYPE,

                        self::$fieldsCompany['Зачем нужен Альфасклад?'] => 107,//TODO
                        self::$fieldsCompany['Как о нас узнали'] => 160,//TODO

                        self::$fieldsCompany['ID_CRM'] => $ID_CRM,
                        self::$fieldsCompany['ID_SITE'] => $ID_SITE,
                        self::$fieldsCompany['Внешний код'] => $EXTERNAL_CODE,

                        self::$fieldsCompany['Фамилия'] => $LAST_NAME,
                        self::$fieldsCompany['Имя'] => $NAME,
                        self::$fieldsCompany['Отчество'] => $SECOND_NAME,
                        self::$fieldsCompany['Дата рождения'] => $BIRTHDAY,

                        self::$fieldsCompany['Ответственное лицо'] => $user['ASSIGNED_BY_ID'],
                        self::$fieldsCompany['GUID_COMPANY'] => $GUID_COMPANY,
                        self::$fieldsCompany['GUID_INVOICES'] => $GUID_INVOICES,
                        self::$fieldsCompany['GUID_CONTRACTS'] => $GUID_CONTRACTS,
                        self::$fieldsCompany['Передано в 1С'] => 0,
                        self::$fieldsCompany['STOCK_ID'] => $user['STOCK_ID'],
                    ];

                    $id_company = $CCrmCompany->Add($arFields, false, ['DISABLE_USER_FIELD_CHECK' => true]);
                   // file_put_contents($_SERVER['DOCUMENT_ROOT']."/logs/1_sendFromSiteCompany.txt",print_r($arFields,true), FILE_APPEND);

                    if (!$id_company) {
                        $log[] = [
                            'msg' => 'Ошибка при создании компании',
                            'method' => 'addCompanyFromSite',
                            'arFields' => $arFields,
                        ];
                    } else {

                        Helper::addEmail($id_company, \CCrmOwnerType::CompanyName, $EMAIL);
                        Helper::addPhone($id_company, \CCrmOwnerType::CompanyName, $PHONE);

                        //создаем реквизиты
                        if (HelperRest::isFis($user)) {//физ
                            //Реквизиты компании если пришло ИНН или КПП>>>>>
                            //if ($PASSPORT_SERIES != '' || $PASSPORT_NUMBER != '') {
                            $n = 0;
                            $arFieldsRequisites['REQUISITE'][] = [
                                'ID' => $n,
                                'PRESET_ID' => 3,//физ шаблон
                                'PSEUDO_ID' => $n,
                                'NAME' => 'Физ. Лицо',
                                'RQ_IDENT_DOC_SER' => $PASSPORT_SERIES,
                                'RQ_IDENT_DOC_NUM' => $PASSPORT_NUMBER,
                                'RQ_FIRST_NAME' => $NAME,
                                'RQ_LAST_NAME' => $LAST_NAME,
                                'RQ_SECOND_NAME' => $SECOND_NAME,
                                'RQ_ADDR' => [
                                    '4' => [
                                        'ADDRESS_1' => $ADDRESS
                                    ],
                                    '1' => [
                                        'ADDRESS_1' => $ACTUAL_ADDRESS
                                    ],
                                ],

                            ];
                            $res_add = \Enum\Helper::AddRequisitesCompany($id_company, $arFieldsRequisites, false, false);

                            // }
                            //<<<<<<<<<
                        }
                        if (HelperRest::isJur($user)) {//юр
                            ////Реквизиты>>>>>
                            if ($INN != '' || $KPP != '') {
                                $n = 0;
                                $arFieldsRequisites['REQUISITE'][] = [
                                    'ID' => $n,
                                    'PRESET_ID' => 1,//юр шаблон
                                    'PSEUDO_ID' => $n,
                                    'NAME' => 'Организация',
                                    'RQ_COMPANY_FULL_NAME' => $NAME,//Название организации(Cокращенное)=Реквизиты. Полное наименование = Название компании
                                    'RQ_INN' => $INN,
                                    'RQ_KPP' => $KPP,
                                    /*'BANK_DETAILS' => [
                                        [
                                            'NAME' => 'Банк',
                                            'RQ_BANK_NAME' => $request['NAME_BANK'],//Наименование банка=Реквизиты. Банк
                                            'RQ_BIK' => $request['BIK_COMPANY'],//Реквизиты. бик
                                            'RQ_ACC_NUM' => $request['RS'],//Реквизиты. Расчетный счёт
                                            'RQ_COR_ACC_NUM' => $request['KP'],//Реквизиты. Кор. счёт
                                            'ENTITY_TYPE_ID' => \CCrmOwnerType::Requisite,
                                            'ENTITY_ID' => $this->id_company,
                                        ]
                                    ],*/
                                ];
                                $res_add = \Enum\Helper::AddRequisitesCompany($id_company, $arFieldsRequisites, false, false);

                            }
                            //<<<<<<<<<
                        }

                        $log[] = [
                            'msg' => 'Создана компании',
                            'method' => 'addCompanyFromSite',
                            'arFields' => $arFields,
                            'Requisites' => $res_add,
                            'arFieldsRequisites' => $arFieldsRequisites,
                        ];
                    }

                   // Helper::log($log_file, $log);
                }
            } else {
                $log = [
                    'msg' => 'Компания с таким внешним кодом уже существует',
                    'post' => $user,
                ];
                if ($user['EMAIL'])
                    Helper::addEmail($id_company, \CCrmOwnerType::CompanyName, $user['EMAIL']);
                //Helper::log($log_file, $log);
            }
        } else {

            $id_company = self::getCompanyBySiteID($ID_SITE);
            $log = [
                'msg' => 'При попытке создать пользователя, не найден внешний код',
                'post' => $user,
            ];
            //Helper::log($log_file, $log);
        }

        return $id_company;

    }

    //достать компанию по внешнему коду
    public static function getCompanyByExternalCode($val)
    {
        if (!$val)
            return false;

        $arSelect = [
            self::$fieldsCompany['Внешний код'],
            'ID'
        ];
        $filter = [
            self::$fieldsCompany['Внешний код'] => $val
        ];
        $dbRes = \CAllCrmCompany::GetList([], $filter, $arSelect);
        if ($arResItem = $dbRes->Fetch()) {
            return $arResItem['ID'];
        }
        return false;
    }

    public static function getCompanyBySiteID($userId)
    {
        $id = 0;
        $arSelect = [
            'ID'
        ];
        $filter = [
            self::$fieldsCompany['ID_SITE'] => $userId,
        ];
        $dbRes = \CCrmCompany::GetListEx([], $filter, false, false, $arSelect);
        if ($arFields = $dbRes->Fetch()) {
            $id =  $arFields['ID'];
        }

        return $id;
    }
    public static function getCompanyByExternalCodeOrSiteID($val,$stockId)
    {
        //if (!$val)
        //    return false;

        $id = 0;
        $arSelect = [
            'ID',
            self::$fieldsCompany['Внешний код'],
        ];
        $filter = [
            self::$fieldsCompany['ID_SITE'] => $stockId,
        ];
        $dbRes = \CCrmCompany::GetListEx([], $filter, false, false, $arSelect);
        if ($arFields = $dbRes->Fetch()) {
            $id =  $arFields['ID'];
        }
        /////////////////////////////////////
        if($id==0) {
            $arSelect = [
                self::$fieldsCompany['Внешний код'],
                'ID'
            ];
            $filter = [
                self::$fieldsCompany['Внешний код'] => $val
            ];
            $dbRes = \CAllCrmCompany::GetList([], $filter, $arSelect);
            if ($arResItem = $dbRes->Fetch()) {
                $id= $arResItem['ID'];
            }
        }

        return $id;
    }

    public static function getCompanyByExternalCodeAndStock($val,$stock,$site_id)
    {
        //if (!$val)
        //    return false;

        $id = 0;
        $arSelect = [
            '*',
            self::$fieldsCompany['Внешний код'],
        ];
        $filter = [
            self::$fieldsCompany['Внешний код'] => $val,
            'ASSIGNED_BY_ID' => $stock
        ];
        $dbRes = \CCrmCompany::GetListEx([], $filter, false, false, $arSelect);
        if ($arFields = $dbRes->Fetch()) {
            $id =  $arFields['ID'];
        }
        /////////////////////////////////////
        if($id==0) {
            $filter = [
                self::$fieldsCompany['ID_SITE'] => $site_id,
                'ASSIGNED_BY_ID' => $stock
            ];
            $dbRes = \CCrmCompany::GetListEx([], $filter, false, false, $arSelect);
            if ($arFields = $dbRes->Fetch()) {
                $id = $arFields['ID'];
            }
        }

        /*$dbRes = \CAllCrmCompany::GetList([], $filter, $arSelect);
        if ($arResItem = $dbRes->Fetch()) {
            return $arResItem['ID'];
        }*/
        if($id==0) {
            return false;
        }else{
            return $id;
        }
    }

    //достать сделку по номеру заказа
    public static function getDealByOrderId($id)
    {
        if (!$id)
            return false;

        $arSelect = [
            self::$fieldsDeal['ID Заказа'],
            'ID'
        ];
        $filter = [
            self::$fieldsDeal['ID Заказа'] => $id
        ];
        $dbRes = \CAllCrmDeal::GetList([], $filter, $arSelect);
        if ($arResItem = $dbRes->Fetch()) {
            return $arResItem['ID'];
        }
        return false;
    }


    public static function getDealById($id)
    {
        if (!$id)
            return false;

        $arSelect = [
            'UF_*',
            'ID'
        ];
        $filter = [
            'ID' => $id
        ];
        $dbRes = \CAllCrmDeal::GetList([], $filter, $arSelect);
        if ($arResItem = $dbRes->Fetch()) {
            return $arResItem;
        }
        return false;
    }


    public static function getInvoicesById($id)
    {

        $filter = [
            'ID' => $id
        ];
        $select = [
            '*',
            'UF_*'
        ];


        $resInvoice = \CCrmInvoice::GetList([], $filter, false, false, $select);
        if ($invoice = $resInvoice->Fetch()) {
            return $invoice;
        }else{
            return false;
        }
    }

    /*public static function getDealByOrderIdMulty($id)
    {
        if (!$id)
            return false;

        $arSelect = [
            self::$fieldsDeal['ID Заказа'],
            'ID'
        ];
        $filter = [
            self::$fieldsDeal['ID Заказа'] => $id
        ];
        $dbRes = \CAllCrmDeal::GetList([], $filter, $arSelect);
        $id = [];
        while ($arResItem = $dbRes->Fetch()) {
            $id[] = $arResItem['ID'];
        }
        if($id){
            return $id;
        }
        return false;
    }*/

    //получить ответственного по складу внешнему коду
    public static function getAssignedBySklad($external_id)
    {
        $return = false;
        if (!$external_id)
            return false;

        $arFilter = [
            'IBLOCK_ID' => 28,
            'EXTERNAL_ID' => $external_id,
        ];

        $arSelect = [
            'ID',
            'IBLOCK_ID',
            'PROPERTY_OTVETSTVENNYY_SOTRUDNIK',
        ];

        $res = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        if ($item = $res->Fetch()) {
            $return = [
                'ID' => $item['ID'],
                'USER_ID' => $item['PROPERTY_OTVETSTVENNYY_SOTRUDNIK_VALUE'],
            ];
        }

        return $return;
    }

    //физ лицо
    public static function isFis($USER)
    {
        if ($USER['PERSON_TYPE'] == 1) return true;
        return false;
    }

    //Юр лицо
    public static function isJur($USER)
    {
        if ($USER['PERSON_TYPE'] == 2) return true;
        return false;
    }
    //Юр лицо
    public static function isIp($USER)
    {
        if ($USER['PERSON_TYPE'] == 3) return true;
        return false;
    }

    public static function SendRequest($data, $url)
    {
        $data['time'] = date('d.m.Y H:i:s');
        //file_put_contents($_SERVER['DOCUMENT_ROOT']."/logs/sendToSiteRequest.txt",print_r($data,true), FILE_APPEND);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0, //получать заголовки
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_POSTFIELDS => http_build_query($data),
        ));

        $result = curl_exec($curl);
        $resultLog = json_decode($result);
        //file_put_contents($_SERVER['DOCUMENT_ROOT']."/logs/sendToSiteRequestAfter.txt",print_r($resultLog,true), FILE_APPEND);
        curl_close($curl);

        return $result;
    }

    //получить id доставки
    public static function getDeliveryByID($id_delivery_from_site)
    {
        $return = false;
        if (!$id_delivery_from_site)
            return false;

        $arFilter = [
            'IBLOCK_ID' => 35,
            'PROPERTY_ID_DOSTAVKI_NA_SAYTE' => $id_delivery_from_site,
        ];

        $arSelect = [
            'ID',
            'IBLOCK_ID',
            'PROPERTY_ID_DOSTAVKI_NA_SAYTE',
        ];

        $res = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        if ($item = $res->Fetch()) {
            $return = $item['ID'];
        }

        return $return;
    }

    //получить id оплаты
    public static function getPaymentByID($id_payment_from_site)
    {
        $return = false;
        if (!$id_payment_from_site)
            return false;

        $arFilter = [
            'IBLOCK_ID' => 36,
            'PROPERTY_ID_OPLATY_NA_SAYTE' => $id_payment_from_site,
        ];

        $arSelect = [
            'ID',
            'IBLOCK_ID',
            'PROPERTY_ID_OPLATY_NA_SAYTE',
        ];

        $res = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        if ($item = $res->Fetch()) {
            $return = $item['ID'];
        }

        return $return;
    }

    //получить id товара по внешнему коду
    public static function getProductByID($external_id)
    {
        $return = false;
        if (!$external_id)
            return false;

        $arFilter = [
            'IBLOCK_ID' => self::$iblockCatalog,
            'XML_ID' => $external_id,
        ];

        $arSelect = [
            'ID',
            'IBLOCK_ID',
            'XML_ID',
        ];

        $res = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        if ($item = $res->Fetch()) {
            $return = $item['ID'];
        }

        return $return;
    }

    //получить внешний код товара по товара
    public static function getExternalCodeByProductID($id)
    {
        $return = false;
        if (!$id)
            return false;

        $arFilter = [
            'IBLOCK_ID' => self::$iblockCatalog,
            'ID' => $id,
        ];

        $arSelect = [
            'ID',
            'IBLOCK_ID',
            'XML_ID',
        ];

        $res = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        if ($item = $res->Fetch()) {
            $return = $item['XML_ID'];
        }

        return $return;
    }


    //Получить значение свойства-список. КОСТЫЛЬ пока не разобрался
    public static function getCrmListValueById($id)
    {
        switch ($id) {
            case '108':
                $return = 'Яндекс.Директ';
                break;
            case '152':
                $return = 'Яндекс поиск';
                break;
            case '109':
                $return = 'Google Ads';
                break;
            case '153':
                $return = 'Google поиск';
                break;
            case '110':
                $return = 'Facebook';
                break;
            case '111':
                $return = 'Instagram';
                break;
            case '112':
                $return = 'Другие сети';
                break;
            case '113':
                $return = 'Компания-перевозчик';
                break;
            case '114':
                $return = 'Рекомендация друзей';
                break;
            case '115':
                $return = 'Уже являлся клиентом';
                break;
            case '116':
                $return = 'Фасад';
                break;
            case '117':
                $return = 'Сайт';
                break;
            case '157':
                $return = 'Яндекс Карты';
                break;
            case '158':
                $return = 'Google Maps';
                break;
            case '159':
                $return = 'Skladoki.ru';
                break;
            case '97':
                $return = 'Ремонт';
                break;
            case '98':
                $return = 'Переезд';
                break;
            case '99':
                $return = 'Сезонное хранение';
                break;
            case '100':
                $return = 'Отъезд заграницу';
                break;
            case '101':
                $return = 'Неисп. вещи/ освобод. площадь';
                break;
            case '102':
                $return = 'Хранение шин';
                break;
            case '103':
                $return = 'Уменьш. площадь (офис, маг)';
                break;
            case '104':
                $return = 'Хранение архивов';
                break;
            case '105':
                $return = 'Хранение товара';
                break;
            case '106':
                $return = 'Хранение оборудования';
                break;
            case '107':
                $return = 'Другое';
                break;
            default:
                $return = 'Не определено';
                break;
        }
        return $return;
    }




}