<?php

namespace Api\Models;

use Api\Helpers\Date;

/**
 * Модель пользователя
 * Class User
 * @package Api\Models
 */
class User extends Model
{
    const TYPE_INDIVIDUAL = 1, // Физическое лицо
        TYPE_LEGAL_ENTITY = 2; // Юридическое лицо

    protected $additionalFields = null;

    public static function getUserById($userId)
    {
        $dbRes = \CUser::GetByID($userId);
        $user = $dbRes->Fetch();
        if (!$user) {
            return null;
        }
        return new User($user);
    }

    public static function getByFilter($arFilter)
    {
        $by = 'ID';
        $order = 'ASC';

        $arParams = [
            'SELECT' => [
                'ID',
                'UF_*'
            ]
        ];

        $rsUsers = \CUser::GetList($by, $order, $arFilter, $arParams);

        $users = [];
        while($user = $rsUsers->Fetch()) {
            $users[] = new User($user);
        }
        return $users;
    }

    /**
     * Методы для основных полей
     */

    /**
     * @param array $newFields
     */
    public function updateUserFields(array $newFields)
    {
        $bitrixUserModel = new \CUser();
        $id = $this->getId();
        $bitrixUserModel->Update($id, $newFields);
    }

    public function getSecondName()
    {
        return $this->fields['SECOND_NAME'] ?? null;
    }

    public function getLastName()
    {
        return $this->fields['LAST_NAME'] ?? null;
    }

    public function getEmail()
    {
        return $this->fields['EMAIL'] ?? null;
    }

    public function getPhone()
    {
        return $this->fields['PERSONAL_PHONE'] ?? null;
    }

    public function getBirthDay($timestamp = true)
    {
        $date = null;
        $birthday = $this->fields['PERSONAL_BIRTHDAY'] ?? null;

        if (!empty($birthday) && $timestamp) {
            $date = Date::getTimestampWithMilliseconds($birthday);
        }

        return $date ?? null;
    }

    public function getEmailNotify()
    {
        return (bool) $this->fields['UF_EMAIL_NOTIFY'];
    }

    public function getSmsNotify()
    {
        return (bool) $this->fields['UF_SMS_NOTIFY'];
    }


    /**
     * Методы для дополнительных полей
     */

    /**
     * @return array|bool
     * @throws \Bitrix\Main\LoaderException
     */
    protected function fetchAdditionalFields()
    {
        // Если доп. поля уже заданы, то не выполняем запрос
        if (isset($this->additionalFields)) {
            return false;
        }

        \Bitrix\Main\Loader::includeModule("sale");
        $db_sales = \CSaleOrderUserProps::GetList(
            array("USER_ID" => "ASC"),
            array("USER_ID" => $this->getId()),
            false,
            false,
            array("*")
        );

        $arSkipFilelds = [];
        $arResult = array();

        if($arSaleProfile = $db_sales->Fetch()) {
            $db_propVals = \CSaleOrderUserPropsValue::GetList(array("ID" => "ASC"), Array("USER_PROPS_ID"=>$arSaleProfile["ID"]));
            while ($arPropVals = $db_propVals->Fetch()) {
                if(!in_array($arPropVals["PROP_CODE"], $arSkipFilelds)) {
                    $arResult[$arPropVals['PROP_CODE']] = $arPropVals['VALUE'];
                }
            }
        }

        $this->additionalFields = $arResult;

        return true;
    }

    public function defineUserType()
    {
        $type = null;

        if ($this->getKpp() && $this->getInn()) {
            $type = self::TYPE_LEGAL_ENTITY;
        } elseif ($this->getPassportSeries() && $this->getPassportNumber()) {
            $type = self::TYPE_INDIVIDUAL;
        }

        return (int) $type;
    }

    public function getPassportSeries()
    {
        $this->fetchAdditionalFields();
        return $this->additionalFields['PASSPORT_SERIES'] ?? null;
    }

    public function getPassportNumber()
    {
        $this->fetchAdditionalFields();
        return $this->additionalFields['PASSPORT_NUMBER'] ?? null;
    }

    public function getAddress()
    {
        $this->fetchAdditionalFields();
        return $this->additionalFields['ADDRESS'] ?? null;
    }

    public function getActualAddress()
    {
        $this->fetchAdditionalFields();
        return $this->additionalFields['ACTUAL_ADDRESS'] ?? null;
    }

    public function getInn()
    {
        $this->fetchAdditionalFields();
        return $this->additionalFields['INN'] ?? null;
    }

    public function getKpp()
    {
        $this->fetchAdditionalFields();
        return $this->additionalFields['KPP'] ?? null;
    }

    public function getContactFio()
    {
        $this->fetchAdditionalFields();
        return $this->additionalFields['FIO_IN_COMPANY'] ?? null;
    }

    public function getPhoneFromAdditional()
    {
        $this->fetchAdditionalFields();
        return $this->additionalFields['PHONE'] ?? null;
    }

    public function getNameFromAdditional()
    {
        $this->fetchAdditionalFields();
        return $this->additionalFields['NAME'] ?? null;
    }

    public function getTokens()
    {
        return $this->fields['UF_APP_TOKEN'] ?? [];
    }

    public function getBalance()
    {
        return $this->fields['UF_USER_BALANCE'] ?? 0;
    }
}