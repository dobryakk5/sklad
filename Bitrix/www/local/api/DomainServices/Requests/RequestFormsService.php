<?php

namespace Api\DomainServices\Requests;

use Api\DomainServices\Requests\Data\BoxData;
use Api\DomainServices\Requests\Data\CalculateRentData;
use Api\DomainServices\Requests\Data\DeliveryData;
use Api\DomainServices\Requests\Data\FastRequestData;
use Api\DomainServices\Requests\Data\SpaceVolumeStorageRentData;
use Api\Exceptions\ValidationException;
use Api\DomainServices\Common\Traits\Form;


class RequestFormsService
{
    use Form;
    /**
     * Форма “Быстрая заявка”
     * @param $name
     * @param $phone
     * @param $email
     * @return bool
     * @throws ValidationException
     * @throws \Exception
     */
    public function requestFastForm(FastRequestData $fastRequestData)
    {
        $needFields = [
            "NAME" => $fastRequestData->getName(),
            "PHONE" => $fastRequestData->getPhone(),
            "EMAIL" => $fastRequestData->getEmail(),
        ];

        $formSID = 'formManagerOrder_4';

        $formId = $this->getFormIdBySID($formSID);

        $arValues = $this->collectCorrectFormData($formId, $needFields);

        return $this->saveResult($formId, $arValues);
    }

    /**
     * Форма оформления доставки
     * @param DeliveryData $deliveryData
     * @return bool
     * @throws \Exception
     */
    public function requestDelivery(DeliveryData $deliveryData)
    {
        (new \CUser())->Logout();
        $needFields = $deliveryData->getDataForForm();

        $formSID = 'delivery_request';

        $formId = $this->getFormIdBySID($formSID);

        $arValues = $this->collectCorrectFormData($formId, $needFields);

        return $this->saveResult($formId, $arValues, 0);
    }

    public function requestBox(BoxData $boxData)
    {
        $formSID = 'formManagerOrder_3';
        $formId = $this->getFormIdBySID($formSID);
        $fields = $boxData->getDataForForm();
        $arValues = $this->collectCorrectFormData($formId, $fields);

        return $this->saveResult($formId, $arValues, 0);
    }

    /**
     * Форма расчета стоимости аренды
     * @param CalculateRentData $calculateRentData
     * @return bool
     */
    public function requestCalculateRent(CalculateRentData $calculateRentData)
    {
        $formSID = 'formCalculationRentalCost';
        $formId = $this->getFormIdBySID($formSID);
        $fields = $calculateRentData->getDataForForm();
        $arValues = $this->collectCorrectFormData($formId, $fields);

        return $this->saveResult($formId, $arValues, 0);
    }

    /**
     * Форма заявки на аренду по площади, объему и складу
     * @param SpaceVolumeStorageRentData $spaceVolumeStorageData
     * @return bool
     */
    public function requestSpaceVolumeStorage(SpaceVolumeStorageRentData $spaceVolumeStorageData)
    {
        $formSID = 'formSpaceVolumeStorageRent';
        $formId = $this->getFormIdBySID($formSID);
        $fields = $spaceVolumeStorageData->getDataForForm();
        $arValues = $this->collectCorrectFormData($formId, $fields);

        return $this->saveResult($formId, $arValues, 0);
    }
}