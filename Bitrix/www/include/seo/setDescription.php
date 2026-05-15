<?php 
global $APPLICATION;
use \Bitrix\Main\Data\Cache;

$arUrl = explode('?', $_SERVER['REQUEST_URI']);
$sNoGetUrl = $arUrl[0];

if (CModule::IncludeModule('iblock') && !empty($sNoGetUrl)) 
{
	
	/* ======== CACHE SEO START ======== */
	$cache = Cache::createInstance(); 
	$cashe_key = md5(json_encode($sNoGetUrl));
	if ($cache->initCache(36000, $cashe_key, $cashe_key)) 
	{ 	
	    $arSeoHelper = $cache->getVars(); 
	}
	elseif ($cache->startDataCache()) 
	{
		$res = CIBlockElement::GetList([], ["IBLOCK_ID" => 61, "ACTIVE" => "Y", "NAME" => $sNoGetUrl], false, ["nTopCount" => 1], ["ID", "NAME", "PROPERTY_STITLE", "PROPERTY_SDESCR", "PROPERTY_SH1"]);
		while($obFields = $res->Fetch())
		{
		 	$arSeoHelper['STITLE'] = $obFields['PROPERTY_STITLE_VALUE'];
		 	$arSeoHelper['SDESCR'] = $obFields['PROPERTY_SDESCR_VALUE'];
		 	$arSeoHelper['SH1'] = $obFields['PROPERTY_SH1_VALUE'];
		}
	    $cache->endDataCache($arSeoHelper);
	}
	/* ======== CACHE SEO END ======== */
	
	if (!empty($arSeoHelper['STITLE'])) 
	{
		$APPLICATION->SetPageProperty("title", $arSeoHelper['STITLE']);
	}

	if (!empty($arSeoHelper['SH1'])) 
	{
		$APPLICATION->SetTitle($arSeoHelper['SH1']);
	}


	if (!empty($arSeoHelper['SDESCR'])) 
	{
		$APPLICATION->SetPageProperty("description", $arSeoHelper['SDESCR']);
	}

	
}




