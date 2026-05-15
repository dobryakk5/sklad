<?php

use Bitrix\Sale\Services\Base;
use Bitrix\Sale\Internals\Entity;
use Bitrix\Sale\Payment;

class MyPaySystemRestriction extends Base\Restriction
{
    /**
     * @param $personTypeId
     * @param array $params
     * @param int $deliveryId
     * @return bool
     */
    public static function check($usergroupId, array $params, $deliveryId = 0)
    {
        if (is_array($params) && isset($params['USER_GROUP_ID'])) {
            return sizeof(array_intersect($usergroupId, $params['USER_GROUP_ID'])) > 0;
        }
        return true;
    }

    /**
     * @param CollectableEntity $entity
     * @return int
     */
    public static function extractParams(Entity $entity)
    {
        /** @var ShipmentCollection $collection */
        $collection = $entity->getCollection();
        /** @var Order $order */
        $order = $collection->getOrder();
        if ($GLOBALS['USER']->isAuthorized())
            $usergroupId = $GLOBALS['USER']->GetUserGroupArray();
        else
            $usergroupId = array(2);
        return $usergroupId;
    }

    /**
     * @return mixed
     */
    public static function getClassTitle()
    {
        return 'По группе пользователей';
    }

    /**
     * @return mixed
     */
    public static function getClassDescription()
    {
        return 'Ограничение по группе пользователей';
    }

    /**
     * @param $deliveryId
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     */
    public static function getParamsStructure($deliveryId = 0)
    {
        $personTypeList = array();
        $dbRes = \Bitrix\Main\GroupTable::getList();
        while ($personType = $dbRes->fetch())
            $personTypeList[$personType["ID"]] = $personType["NAME"] . " (" . $personType["ID"] . ")";
        return array(
            "USER_GROUP_ID" => array(
                "TYPE" => "ENUM",
                'MULTIPLE' => 'Y',
                "LABEL" => '',
                "OPTIONS" => $personTypeList
            )
        );
    }

    /**
     * @param $mode
     * @return int
     */
    public static function getSeverity($mode)
    {
        return \Bitrix\Sale\Delivery\Restrictions\Manager::SEVERITY_STRICT;
    }
}