<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Iblock;

if (Loader::includeModule("iblock")) {
    $arIBlocks = [];
    $dbIblock = CIBlock::GetList(["SORT" => "ASC"]);
    while ($arIblock = $dbIblock->Fetch()) {
        $arIBlocks[$arIblock["ID"]] = "[" . $arIblock["ID"] . "] " . $arIblock["NAME"];
    }

    $arComponentParameters = array(
        "PARAMETERS" => array(
            "SOURCE_IBLOCK_ID" => array(
                "PARENT" => "BASE",
                "NAME" => "Инфоблок источника",
                "TYPE" => "LIST",
                "VALUES" => $arIBlocks,
                "REFRESH" => "Y",
            ),
            "REGION" => array(
                "PARENT" => "BASE",
                "NAME" => "Регион",
                "TYPE" => "STRING",
                "DEFAULT" => "1",
            ),
            "COUNT" => array(
                "PARENT" => "BASE",
                "NAME" => "Количество элементов на страницу",
                "TYPE" => "NUMBER",
                "DEFAULT" => "7",
            ),
        ),
    );
}
