<?php

namespace Api\DomainServices\Storages;

use Api\DomainServices\Storages\Data\StoragesCostData;
use Api\Models\Storage;
use Bitrix\Main\Loader;

class StorageService
{
    const SIZE_TYPE_M2 = 1,
        SIZE_TYPE_M3 = 2;

    public function getStorage(int $id)
    {
        return Storage::getStorage($id);
    }

    /**
     * Получение списка складов
     * @param null $idList
     * @param int|null $limit
     * @param int|null $lastId
     * @return mixed
     */
    public function getStorages($idList = null, $limit = null, $lastId = null)
    {
        return Storage::getStoragesList($idList, $limit, $lastId);
    }

    /**
     * Получение максимальной доступной площади бокса для склада
     * @param string $storageCode
     * @return int
     */
    public function getSquareToForStorage(string $storageCode)
    {
        // Default value
        $squareTo = 15;

        $arSelect = Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_SQUARE");
        $arFilter = Array("IBLOCK_ID"=>STORAGES_CATALOG_IBLOCK, "ACTIVE"=>"Y", "SECTION_CODE"=>$storageCode, "PROPERTY_STATUS"=>BOX_STATUS_OPENED_ID);
        $res = \CIBlockElement::GetList(Array("property_SQUARE"=>"desc"), $arFilter, false, Array("nTopCount"=>1), $arSelect);
        $ob = $res->GetNextElement();

        if ($ob) {
            $arItem = $ob->GetFields();
            $squareTo = ceil($arItem["PROPERTY_SQUARE_VALUE"]);

            if($squareTo <= 1) {
                $squareTo = 2;
            }
        }

        return (float) $squareTo;
    }

    /**
     * Получение максимального доступного объема бокса для склада
     * @param string $storageCode
     * @return int
     */
    public function getVolumeToForStorage(string $storageCode)
    {
        // Default value
        $volumeTo = 15;

        $arSelect = Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_VOLUME");
        $arFilter = Array("IBLOCK_ID"=>STORAGES_CATALOG_IBLOCK, "ACTIVE"=>"Y", "SECTION_CODE"=>$storageCode, "PROPERTY_STATUS"=>BOX_STATUS_OPENED_ID);
        $res = \CIBlockElement::GetList(Array("property_VOLUME"=>"desc"), $arFilter, false, Array("nTopCount"=>1), $arSelect);
        $ob = $res->GetNextElement();

        if ($ob) {
            $arItem = $ob->GetFields();
            $volumeTo = ceil($arItem["PROPERTY_VOLUME_VALUE"]);

            if($volumeTo <= 1) {
                $volumeTo = 2;
            }
        }

        return (float) $volumeTo;
    }

    public function calculatePerMonthCost(StoragesCostData $storagesCostData)
    {
        if (!Loader::includeModule('catalog')) {
            throw new \Exception('Не подключен модуль каталога');
        }

        $sizeType = $storagesCostData->getSizeType();
        $size = $storagesCostData->getSize();
        $storageId = $storagesCostData->getStorageId();
        $storage = Storage::getStorage($storageId);

        $costPerMonth = $this->getStorageMinPricePerMonth($sizeType, $size, $storage->getCode());
        return $costPerMonth;
    }

    public function getStorageMinPricePerMonth($sizeType, $size, $skladCode)
    {
        global $arrFilterRentalCatalog3;
        global $APPLICATION;
        // Этаж
        if (strlen($_REQUEST["FLOOR_CODE"]) > 0) {
            $arrFilterRentalCatalog3["PROPERTY_FLOOR_VALUE"] = preg_replace("/[^0-9]/", "", $_REQUEST["FLOOR_CODE"]) . " этаж";
        }
        if ($sizeType == 1) {
            $arrFilterRentalCatalog3[">=PROPERTY_SQUARE"] = $size;
            $arrFilterRentalCatalog3["<=PROPERTY_SQUARE"] = $size;
        } else {
            $arrFilterRentalCatalog3[">=PROPERTY_VOLUME"] = $size;
            $arrFilterRentalCatalog3["<=PROPERTY_VOLUME"] = $size;
        }

        $SKLAD_CODE = $skladCode;
        $SHOW_ALL_WO_SECTION = "N";

        $arrFilterRentalCatalog3["PROPERTY_STATUS"] = BOX_STATUS_OPENED_ID;
        $arrFilterRentalCatalog3["SECTION_GLOBAL_ACTIVE"] = "Y";
        $PROP_SIZE = $sizeType == 1 ? 'SQUARE' : 'VOLUME';

        ob_start();
        $APPLICATION->IncludeComponent(
            "bitrix:catalog.section",
            "rental_catalog_bestprice_item_price",
            array(
                "ELEMENT_SORT_FIELD" => "catalog_PRICE_1",
                "ELEMENT_SORT_FIELD2" => "property_" . $PROP_SIZE,
                "ELEMENT_SORT_ORDER" => "asc",
                "ELEMENT_SORT_ORDER2" => "asc",
                "ENLARGE_PRODUCT" => "STRICT",
                "FILTER_NAME" => "arrFilterRentalCatalog3",
                "HIDE_NOT_AVAILABLE" => "N",
                "HIDE_NOT_AVAILABLE_OFFERS" => "N",
                "IBLOCK_ID" => "40",
                "IBLOCK_TYPE" => "aspro_priority_catalog",
                "INCLUDE_SUBSECTIONS" => "Y",
                "PAGE_ELEMENT_COUNT" => "1",
                "PARTIAL_PRODUCT_PROPERTIES" => "N",
                "PRICE_CODE" => array(
                    0 => "BASE",
                ),
                "PRICE_VAT_INCLUDE" => "Y",
                "PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons",
                "PRODUCT_ID_VARIABLE" => "id",
                "PRODUCT_PROPS_VARIABLE" => "prop",
                "PRODUCT_QUANTITY_VARIABLE" => "quantity",
                "PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false}]",
                "PRODUCT_SUBSCRIPTION" => "Y",
                "SECTION_CODE" => $SKLAD_CODE,
                "SECTION_ID" => "",
                "SECTION_ID_VARIABLE" => "SECTION_ID",
                "SHOW_ALL_WO_SECTION" => $SHOW_ALL_WO_SECTION,
                "SHOW_PRICE_COUNT" => "1",
                "COMPONENT_TEMPLATE" => "rental_catalog_bestprice_item_price",
            ),
            false
        );
        $cost = @ob_get_contents();
        ob_get_clean();

        return (float) $cost;
    }

}