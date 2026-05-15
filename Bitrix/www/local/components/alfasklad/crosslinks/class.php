<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Web\HttpClient;

class CustomCrosslinksComponent extends CBitrixComponent
{
    public function executeComponent()
    {
        global $APPLICATION;

        if ($this->StartResultCache(false, implode('|', [SITE_ID, $APPLICATION->GetCurDir()]))) {
            $sourceElements = CIBlockElement::GetList(
                [],
                ["IBLOCK_ID" => 61, 'NAME' => $APPLICATION->GetCurDir(), "ACTIVE" => "Y", 'PROPERTY_LINK_REGION' => 1],
                false,
                ['nTopCount' => 7],
                ["ID", 'NAME', "PROPERTY_CROSSLINKS_URLS", "PROPERTY_CROSSLINKS_ANCHORS"]
            );

            if ($element = $sourceElements->GetNext()) {
                $rsProps = CIBlockElement::GetProperty(61, $element['ID'], "sort", "asc", ['CODE' => 'CROSSLINKS_%']);
                $urls = $anchors = [];
                while ($arrProps = $rsProps->Fetch()) {
                    if ($arrProps['CODE'] == 'CROSSLINKS_URLS') {
                        $urls[] = trim($arrProps['VALUE']);
                    }
                    if ($arrProps['CODE'] == 'CROSSLINKS_ANCHORS') {
                        $anchors[] = trim($arrProps['VALUE']);
                    }
                }
                foreach ($urls as $k => $u) {
                    $this->arResult['links'][$k] = [
                        'anchor' =>  $anchors[$k],
                        'url' =>  $u,
                    ];
                }
            }

            $this->SetResultCacheKeys(array(
                "links",
            ));

            $this->includeComponentTemplate();
        }
    }
}
