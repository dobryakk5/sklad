<?php

namespace Api\DomainServices\User;

use Api\DomainServices\Common\Traits\Form;
use Api\DomainServices\Orders\OrdersService;
use Api\DomainServices\User\Data\AutoDebitData;
use Api\DomainServices\User\Data\ContractPayData;
use Api\DomainServices\User\Data\UpdData;
use Api\Exceptions\ContractNotFoundException;
use Api\Exceptions\DocumentNotFoundException;
use Api\Helpers\Date;
use Api\Models\Box;
use Api\Models\Contract;
use Api\Models\Document;
use Api\Models\User;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Sale\Basket;
use Bitrix\Sale\Delivery\Services\Manager;
use Bitrix\Sale\Order;

class DocumentsService
{
    use Form;

    /**
     * Делает запрос УПД для счета конкретного договора
     * @param UpdData $updData
     * @return bool
     * @throws ContractNotFoundException
     * @throws DocumentNotFoundException
     * @throws \Exception
     */
    public function sendUpd(UpdData $updData)
    {
        $user = $updData->getUser();
        $contractId = $updData->getContractId();
        $documentId = $updData->getDocumentId();

        $contract = $this->getContractForUser($user->getId(), $contractId);
        $document = $this->getDocumentForUser($contract->getGUID(), $documentId);

        $needFields = [
            "INVOICE_NUMBER" => $document->getNumber(),
            "INVOICE_GUID" => $document->getGUID(),
            "CONTRACT_NUMBER" => $contract->getNumber(),
            "CONTRACT_GUID" => $contract->getGUID(),
            "USER_EMAIL" => $user->getEmail(),
        ];

        $formSID = 'upd_request';

        $formId = $this->getFormIdBySID($formSID);

        $arValues = $this->collectCorrectFormData($formId, $needFields);

        return $this->saveResult($formId, $arValues, $user->getId());
    }

    /**
     * Делает запрос счета на Email для счета конкретного договора
     * @param $user
     * @param $contractId
     * @param $docsIds
     * @return bool
     * @throws \Exception
     */
    public function sendDocsEmail(ContractPayData $data)
    {
        $user = $data->getUser();
        $contractId = $data->getContractId();
        $docsIds = $data->getDocs();

        $contract = $this->getContractForUser($user->getId(), $contractId);
        $documents = [];
        foreach ($docsIds as $docsId) {
            $documents[] = $this->getDocumentForUser($contract->getGUID(), $docsId);
        }

        $formSID = 'invoice_request';
        $formId = $this->getFormIdBySID($formSID);
        $result = true;

        foreach ($documents as $document) {
            $needFields = [
                "INVOICE_NUMBER" => $document->getNumber(),
                "INVOICE_GUID" => $document->getGUID(),
                "CONTRACT_NUMBER" => $contract->getNumber(),
                "CONTRACT_GUID" => $contract->getGUID(),
                "USER_EMAIL" => $user->getEmail(),
            ];
            $arValues = $this->collectCorrectFormData($formId, $needFields);
            $this->saveResult($formId, $arValues, $user->getId());
        }

        return $result;
    }

    /**
     * Создаёт заказ оплаты счетов договора и выдаёт ссылку на оплату
     * @param User $user
     * @param $contractId
     * @param ContractPayData $data
     * @return mixed|null
     * @throws \Exception
     */
    public function getPayLinkForContractDocuments(ContractPayData $data)
    {
        /**
         * @var User $user
         */
        $user = $data->getUser();
        $contractId = $data->getContractId();
        $docs = $data->getDocs();
        /**
         * @var Contract $contract
         */
        $contract = $this->getContractForUser($user->getId(), $contractId);
        $documents = [];
        foreach ($docs as $docId) {
            $documents[] = $this->getDocumentForUser($contract->getGUID(), $docId);
        }

        //добавляем в корзину товар
        if (!Loader::includeModule("sale")) {
            ShowError(Loc::getMessage("SOA_MODULE_NOT_INSTALL"));
            return;
        }
        \CSaleBasket::DeleteAll(\CSaleBasket::GetBasketUserID());
        $basket = \Bitrix\Sale\Basket::loadItemsForFUser($user->getId(), \Bitrix\Main\Context::getCurrent()->getSite());

        foreach ($documents as $document) {
            /**
             * @var Document $document
             */
            $productId = intval($document->getId());
            $quantity = 1;
            $item = $basket->createItem("catalog", $productId);

            $arSetFields = array(
                "QUANTITY" => $quantity,
                "CURRENCY" => \Bitrix\Currency\CurrencyManager::getBaseCurrency(),
                "LID" => \Bitrix\Main\Context::getCurrent()->getSite(),
                "PRODUCT_PROVIDER_CLASS" => 'CCatalogProductProvider',
                "VAT_INCLUDED" => "Y",
            );

            if (!empty($document->getGUID())) {
                //записываем свойства
                $basketPropertyCollection = $item->getPropertyCollection();

                $arForPropCollection = array(
                    array(
                        "NAME" => "Номер договора",
                        "CODE" => "CONTRACT_NUMBER",
                        "VALUE" => $contract->getNumber(),
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
                        "VALUE" => $contract->getGUID(),
                        "SORT" => 120,
                    )
                );

                //ищем склад по боксу в договоре
                if (!empty($contract->getBoxId())) {
                    $db_sections = \CIBlockElement::GetElementGroups($contract->getBoxId(), true);
                    if ($arSect = $db_sections->Fetch()) {
                        $arForPropCollection[] = array(
                            "NAME" => "Внешний код склада",
                            "CODE" => "SKLAD_XML_ID",
                            "VALUE" => $arSect["XML_ID"],
                            "SORT" => 130,
                        );
                    }
                }

                $basketPropertyCollection->setProperty($arForPropCollection);
            }

            $item->setFields($arSetFields);
        }

        $orderService = new OrdersService();
        $orderId = $orderService->createOrder($user, $basket, 3);
        return $orderService->getOrderLink($orderId);
    }

    /**
     * Получает активные договора для пользователя
     * @param null $limit
     * @param int $lastId
     * @param $userId
     * @return array
     */
    public function getUserContracts($userId, $limit = null, int $lastId = null)
    {
        return Contract::getAllForUser($userId, $limit, $lastId);
    }

    /**
     * Получает детализацию договора
     * @param $userId
     * @param $contractId
     * @return array
     */
    public function getContractDetail($userId, $contractId)
    {
        $contract = $this->getContractForUser($userId, $contractId);
        $contractDocuments = $this->getDocumentsForContract($contract->getGUID());
        $documents = [];
        foreach ($contractDocuments as $contractDocument) {
            /**
             * @var Document $contractDocument
             */

            $documentsDetails = $this->getDocumentsDetails($contractDocument->getId());
            $docDetails = [];
            $documentSum = 0;
            $boxTitle = null;
            $boxPrice = null;

            foreach ($documentsDetails as $documentsDetail) {
                $docDetails[] = [
                    'title' => $documentsDetail['title'],
                    'sum' => (float)$documentsDetail['sum'],
                ];
                $documentSum = $documentSum + (float)$documentsDetail['sum'];
                if ($documentsDetail['isBox']) {
                    $boxTitle = $documentsDetail['title'];
                    $boxPrice = (float)$documentsDetail['sum'];
                }
            }

            $documents[] = [
                'id' => $contractDocument->getId(),
                'sum' => $documentSum,
                'number' => $contractDocument->getNumber(),
                'dateStart' => $contractDocument->getDateFrom(true),
                'dateEnd' => $contractDocument->getDateTo(true),
                'status' => $contractDocument->getStatusId(),
                'boxTitle' => $boxTitle,
                'boxPrice' => $boxPrice,
                'details' => $docDetails,
            ];
        }

        $documentBalance = $contract->getBalance();
        if ($documentBalance != 0) {
            $documentBalance = $documentBalance * (-1);
        }

        $detail = [
            'balanceValue' => $documentBalance,
            'balanceDate' => Date::getNowMilliseconds(),
            'number' => $contract->getNumber(),
            'payedTill' => $contract->getPaidDateTo(true),
            'documents' => $documents,
            'isAutoEnabled' => false,
        ];
        return $detail;
    }

    /**
     * Получает счета для документа
     * @param $guidContract
     * @return array
     */
    public function getDocumentsForContract($guidContract)
    {
        return Document::getAllForContract($guidContract);
    }

    /**
     * Получает строки детализации счета (продукты счета)
     * @param $documentId
     * @return array
     */
    public function getDocumentsDetails($documentId)
    {
        $arFilter = [
            'IBLOCK_ID' => 59,
            'PROPERTY_INVOICE' => $documentId,
        ];
        $dbRes = \CIBlockElement::GetList(array('ID' => 'ASC'), $arFilter);
        $docDetails = [];
        while ($docDetail = $dbRes->GetNextElement()) {
            $fields = $docDetail->GetFields();
            $properties = $docDetail->GetProperties();
            // Продук это бокс?
            $isBox = false;
            if (!empty($properties['PRODUCT']['VALUE'])) {
                $productId = $properties['PRODUCT']['VALUE'];
                $box = Box::getById($productId);
                if ($box) {
                    $isBox = true;
                }
            }
            $docDetails[] = [
                'title' => $fields['NAME'],
                'sum' => $properties['PRICE']['VALUE'],
                'isBox' => $isBox
            ];
        }
        return $docDetails;
    }

    /**
     * Получает договор для пользователя
     * @param $userId
     * @param $contractId
     * @return Contract
     * @throws ContractNotFoundException
     */
    public function getContractForUser($userId, $contractId)
    {
        $contract = Contract::getForUserById($userId, $contractId);
        if (!$contract) {
            throw new ContractNotFoundException("Договор №$contractId не найден");
        }
        return $contract;
    }

    /**
     * Получает счет для
     * @param $guidContract
     * @param $documentId
     * @return Document
     * @throws DocumentNotFoundException
     */
    private function getDocumentForUser($guidContract, $documentId)
    {
        $document = Document::getForContract($guidContract, $documentId);
        if (!$document) {
            throw new DocumentNotFoundException("Счет №$documentId не найден");
        }
        return $document;
    }

    public function getPayLinkForContractDeposit($user, $contractId, $sum)
    {

        file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/local/Api/log.txt', 'getPayLinkForContractDeposit' . "\n", FILE_APPEND);

        /**
         * @var User $user
         */
        $cUser = new \CUser();
        $cUser->Authorize($user->getId(), false, false);

        /**
         * @var Contract $contract
         */
        $contract = $this->getContractForUser($user->getId(), $contractId);

        \Bitrix\Main\Loader::includeModule("sale");
        \Bitrix\Main\Loader::includeModule("catalog");
        \Bitrix\Main\Loader::includeModule("iblock");
        //очищаем корзину
        \CSaleBasket::DeleteAll(\CSaleBasket::GetBasketUserID());

        //добавляем в корзину товар
        $basket = \Bitrix\Sale\Basket::loadItemsForFUser(\Bitrix\Sale\Fuser::getId(), \Bitrix\Main\Context::getCurrent()->getSite());
//        $basket = \Bitrix\Sale\Basket::loadItemsForFUser(\Bitrix\Sale\Fuser::getId(), "s1");
        $productId = 9819; //Пополнение баланса на произвольную сумму

        $quantity = 1;
        $item = $basket->createItem("catalog", $productId);

        $arSetFields = array(
            "QUANTITY" => $quantity,
            "CURRENCY" => \Bitrix\Currency\CurrencyManager::getBaseCurrency(),
            //"LID" => \Bitrix\Main\Context::getCurrent()->getSite(),
            "LID" => "s1",
            //"PRODUCT_PROVIDER_CLASS" => 'CCatalogProductProvider',
                "VAT_INCLUDED" => "Y",
        );
        $arSetFields["CUSTOM_PRICE"] = "Y";
        $arSetFields["PRICE"] = $sum;

        $item->setFields($arSetFields);
        $basket->save();


        //записываем свойства
        $basketPropertyCollection = $item->getPropertyCollection();
        $arForPropCollection = array(
            array(
                "NAME" => "Номер договора",
                "CODE" => "CONTRACT_NUMBER",
                "VALUE" => $contract->getNumber(),
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
                "VALUE" => $contract->getGUID(),
                "SORT" => 120,
            )
        );

        //ищем склад по боксу в договоре
        if (!empty($contract->getBoxId())) {
            $db_sections = \CIBlockElement::GetElementGroups($contract->getBoxId(), true);
            if ($arSect = $db_sections->Fetch()) {
                $arForPropCollection[] = array(
                    "NAME" => "Внешний код склада",
                    "CODE" => "SKLAD_XML_ID",
                    "VALUE" => $arSect["XML_ID"],
                    "SORT" => 130,
                );
            }
        }

        $basketPropertyCollection->setProperty($arForPropCollection);
        //file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/local/Api/log.txt', date(DATE_RFC822)."\n", FILE_APPEND);
        // file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/local/Api/log.txt', 'сумма:'. print_r($sum, true)."\n", FILE_APPEND);
        //file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/local/Api/log.txt', 'сумма в корзине:'. print_r( $basket->getPrice(), true)."\n", FILE_APPEND);
//        file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/local/Api/log.txt', 'getPayLinkForContractDeposit2' . "\n", FILE_APPEND);
        $orderService = new OrdersService();
//        file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/local/Api/log.txt', print_r($orderService) . "\n", FILE_APPEND);
//        file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/local/Api/log.txt', 'getPayLinkForContractDeposit3' . "\n", FILE_APPEND);
        $orderId = $orderService->createOrder($user, $basket, 4);
//        file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/local/Api/log.txt', 'getPayLinkForContractDeposit4' . "\n", FILE_APPEND);
//        file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/local/Api/log.txt', 'Номер заказа:' . print_r($orderId, true) . "\n", FILE_APPEND);
        $cUser->Logout();
        return $orderService->getOrderLink($orderId, 'https://alfasklad.ru/order/?ORDER_ID=' . $orderId);
    }

    public function setAutoDebit(AutoDebitData $updateData)
    {
        $result = false;
        $user = $updateData->getUser();
        $contract = $this->getContractForUser($user->getId(), $updateData->getContractId());

        //TODO: заменить на название настоящего поля, когда будет готов функционал со стороны клиента
        $newFields = array(
            'AUTO_DEBIT_ENABLED' => $updateData->getIsAutoEnabled()
        );

        try {
            $contract->updateFields($newFields);
            $result = true;
        } catch (\Exception $e) {
            throw $e;
        }

        return $result;
    }

    /**
     * Получает данные о балансе договора пользователя
     * @param $userId
     * @param $contractId
     * @return array
     */
    public function getContractDebtDetail($userId, $contractId)
    {
        $contract = $this->getContractForUser($userId, $contractId);
        $contractBalance = $contract->getBalance();

        if ($contractBalance != 0) {
            $contractBalance = $contractBalance * (-1);
        }

        $debtDetail = [
            "number" => $contract->getNumber(),
            "balanceValue" => $contractBalance,
            "balanceDate" => Date::getNowMilliseconds(),
        ];
        return $debtDetail;
    }
}