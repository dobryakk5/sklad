<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
foreach($arResult["ITEMS"] as $key=>$arItem) {
        //получаем данные о боксе
        if(strlen($arItem["PROPERTIES"]["BOX"]["VALUE"]) > 0) {
                $res = CIBlockElement::GetList(
                        Array("ID"=>"ASC"), 
                        Array("IBLOCK_ID"=>STORAGES_CATALOG_IBLOCK, "ID"=>$arItem["PROPERTIES"]["BOX"]["VALUE"]), 
                        false, 
                        Array("nTopCount"=>1), 
                        Array("ID", "IBLOCK_ID", "IBLOCK_SECTION_ID", "NAME", "PREVIEW_PICTURE", "PROPERTY_NAME_FOR_SITE", "PROPERTY_BOX_NUMBER", "PROPERTY_SQUARE", "PROPERTY_VOLUME", "PROPERTY_FLOOR", "PROPERTY_VIDEO_LINK", "PROPERTY_VIDEO_LINK_ACTIVE")
                );
                if($ob = $res->GetNextElement()) {
                        $arBoxFields = $ob->GetFields();
                        if(strlen($arBoxFields["PREVIEW_PICTURE"]) > 0) {
                                $arBoxFields["PREVIEW_PICTURE_SRC"] = CFile::GetPath($arBoxFields["PREVIEW_PICTURE"]);
                        }
                        if(strlen($arBoxFields["PROPERTY_NAME_FOR_SITE_VALUE"]) > 0) {
                                $arBoxFields["NAME"] = $arBoxFields["PROPERTY_NAME_FOR_SITE_VALUE"];
                        }
                        if(strlen($arBoxFields["PROPERTY_FLOOR_VALUE"]) > 0) {
                                $arBoxFields["PROPERTY_FLOOR_VALUE"] = preg_replace("/[^0-9]/", '', $arBoxFields["PROPERTY_FLOOR_VALUE"]);
                        }
                        if(strlen($arBoxFields["IBLOCK_SECTION_ID"]) > 0) {
                                $resSect = CIBlockSection::GetByID($arBoxFields["IBLOCK_SECTION_ID"]);
                                if($arSklad = $resSect->GetNext()) {
                                        //название этажа
                                        $arFloorNames = Array(1=>"первого", "второго", "третьего", "четвертого", "пятого");
                                        $arSklad["FLOOR_NAME"] = $arFloorNames[$arBoxFields["PROPERTY_FLOOR_VALUE"]];

                                        $arBoxFields["SKLAD"] = $arSklad;
                                }
                        }
                        $arResult["ITEMS"][$key]["BOX"] = $arBoxFields;
                }
        }

        //находим все связанные с договором неоплаченные счета
        if(strlen($arItem["PROPERTIES"]["CONTRACT_GUID"]["VALUE"]) > 0) {
                $res = CIBlockElement::GetList(
                        Array("PROPERTY_DATE_CREATE"=>"DESC"),
                        Array(
                                "IBLOCK_ID"=>53,
                                "PROPERTY_USER"=>$arItem["PROPERTIES"]["USER"]["VALUE"],
                                "PROPERTY_CONTRACT_GUID"=>$arItem["PROPERTIES"]["CONTRACT_GUID"]["VALUE"],
                                "PROPERTY_STATUS"=>Array(354, 356)
                        ),
                        false,
                        Array(),
                        Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_DATE_FROM", "PROPERTY_USER", "PROPERTY_CONTRACT_GUID")
                );
                while($ob = $res->GetNextElement()) {
                        $arInvoiceFields = $ob->GetFields();
                        if(strlen($arInvoiceFields["PROPERTY_DATE_FROM_VALUE"]) > 0) {
                                $arInvoiceFields["MONTH"] = FormatDate("f Y", MakeTimeStamp($arInvoiceFields["PROPERTY_DATE_FROM_VALUE"]));
                                $arResult["ITEMS"][$key]["INVOICES"][] = $arInvoiceFields;
                        }
                }
        }

}
?>

<?$this->__component->SetResultCacheKeys(array("CACHED_TPL", "ITEMS"));?>