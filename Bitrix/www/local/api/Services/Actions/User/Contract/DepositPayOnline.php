<?php


namespace Api\Services\Actions\User\Contract;


use Api\DomainServices\User\DocumentsService;
use Api\Models\User;
use Api\Services\ActionResult;
use Api\Services\Actions\User\ActionWithUserAbstract;

class DepositPayOnline extends ActionWithUserAbstract
{
    protected $needParams = [
        'sum',
    ];

    public function execute()
    {
        $data = $this->data;

        $contractId = $this->urlParams['contractId'];
        $user = $this->user;
        $sum = (float) $data['sum'];

//      file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/local/Api/log.txt', date(DATE_RFC822)."\n", FILE_APPEND);
//        file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/local/Api/log.txt', '$user'.print_r($user, true)."\n", FILE_APPEND);
//        file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/local/Api/log.txt', 'contractId'. print_r($contractId, true)."\n", FILE_APPEND);

        $documentsService = new DocumentsService();
        try {
            $paymentLink = $documentsService->getPayLinkForContractDeposit($user, $contractId, $sum);
        } catch (\Exception $e) {
            throw $e;
        }

        //file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/local/Api/log.txt', print_r($paymentLink, true)."\n", FILE_APPEND);


        $actionResult = new ActionResult();
        $actionResult->setParams([
            'url' => $paymentLink
        ]);
        $actionResult->setApiCode(200);
        return $actionResult;


        // Пользователь
        $user = $this->user;
        $userFields = array(
            'LAST_NAME' => $user->getLastName(),
            'NAME' => $user->defineUserType() == User::TYPE_LEGAL_ENTITY ? $user->getNameFromAdditional() : $user->getName(),
            'SECOND_NAME' => $user->getSecondName(),
            'BIRTHDAY' => $user->getBirthDay(),
            'ADDRESS' => $user->getAddress(),
            'ACTUAL_ADDRESS' => $user->getActualAddress(),
            'PHONE' => !empty($user->getPhone()) ? $user->getPhone() : $user->getPhoneFromAdditional(),
            'EMAIL' => $user->getEmail(),
            'PASSPORT_SERIES' => $user->getPassportSeries(),
            'PASSPORT_NUMBER' => $user->getPassportNumber(),
            'INN' => $user->getInn(),
            'KPP' => $user->getKpp(),
            'FIO_IN_COMPANY' => $user->getContactFio(),
            'IS_PUBLIC_ORDER' => false,

            //TODO FIELD 'DEAL_CATEGORY_ID'
            'DEAL_CATEGORY_ID' => null,
            'IS_ORDER_FROM_CRM' => false,
        );

        // Договор
        $documentService = new DocumentsService();
        $contractId = $this->urlParams['contractId'];
        $contract = $documentService->getContractDetail($user->getId(), $contractId);

        // Сумма пополнения

        // Создание заказа
        $product = array();

        $basket = \Bitrix\Sale\Basket::create(SITE_ID);

        $basketItem = $basket->createItem("catalog", $customPriceId);
        $basketItem->setFields($product);

        $order = \Bitrix\Sale\Order::create(SITE_ID, $user->getId());
        $order->setPersonTypeId($user->getId());
        $order->setBasket($basket);

        $shipmentCollection = $order->getShipmentCollection();
        $shipment = $shipmentCollection->createItem(
            \Bitrix\Sale\Delivery\Services\Manager::getObjectById(1)
        );

        $shipmentItemCollection = $shipment->getShipmentItemCollection();

        foreach ($basket as $basketItem)
        {
            $item = $shipmentItemCollection->createItem($basketItem);
            $item->setQuantity($basketItem->getQuantity());
        }





        return true;
    }

}