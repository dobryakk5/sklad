<?php
global $arRegionLink;

if (!$this->__template) {
    $this->InitComponentTemplate();
}

if(!empty($arRegionLink["PROPERTY_LINK_REGION"]) && !empty($arResult["PROPERTIES"]["LINK_REGION"]["VALUE"])){
    if(in_array($arRegionLink["PROPERTY_LINK_REGION"],$arResult["PROPERTIES"]["LINK_REGION"]["VALUE"]) === false){
        if (Bitrix\Main\Loader::includeModule("iblock")) {
            Bitrix\Iblock\Component\Tools::process404(
                'Страница не найдена'
                , true
                , true
                , true
                , '/404.php'
            );
        }
    }
}
