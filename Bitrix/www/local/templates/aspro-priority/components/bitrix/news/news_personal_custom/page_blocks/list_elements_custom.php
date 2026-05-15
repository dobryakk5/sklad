<?
global $USER;
global $arrFilterNewsLK;
$arrFilterNewsLK["PROPERTY_SKLAD"] = false; 

//******* поиск принадледжности новостей со складами *********
$arUserSkladID = Array();

$obCache = new CPHPCache();
$cacheLifetime = 3600; 
$cacheID = "news_lk_".$USER->GetID(); 
$cachePath = "/news_lk/";
if($obCache->InitCache($cacheLifetime, $cacheID, $cachePath)) {
	$vars = $obCache->GetVars();
	$arUserSkladID = $vars["DATA"];
} elseif($obCache->StartDataCache()) {
	//ищем все боксы Пользователя по имеющимся активным договорам
	$arUserBoxes = Array();
	$res = CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>52, "ACTIVE"=>"Y", "PROPERTY_USER"=>$USER->GetID(), "PROPERTY_STATUS"=>CONTRACT_STATUS_ACTIVE_ID), false, Array(), Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_BOX"));
	while($ob = $res->GetNextElement()) {
		$arContract = $ob->GetFields();
		$arUserBoxes[] = $arContract["PROPERTY_BOX_VALUE"];
	}
	//если у пользователя есть боксы, то ищем к каким складам они относятся
	if(!empty($arUserBoxes)) {
		$arUserSkladID = Array();
		$res = CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>STORAGES_CATALOG_IBLOCK, "ID"=>$arUserBoxes), false, Array(), Array("ID", "IBLOCK_ID", "NAME", "IBLOCK_SECTION_ID"));
		while($ob = $res->GetNextElement()) {
			$arBox = $ob->GetFields();
			$arUserSkladID[] = $arBox["IBLOCK_SECTION_ID"];
		}
	}
	$obCache->EndDataCache(array("DATA" => $arUserSkladID));
}

//если известны относящиеся к пользователю склады, то добавляем новости и про них
if(!empty($arUserSkladID)) {
	$arrFilterNewsLK = Array();
	$arrFilterNewsLK[] = Array(
		"LOGIC" => "OR",
		Array("PROPERTY_SKLAD" => false),
		Array("PROPERTY_SKLAD" => $arUserSkladID)
	);
}
//******************* конец *********************

?>


<?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"news_list_1",
	Array(
		"IMAGE_POSITION" => $arParams["IMAGE_POSITION"],
		"SHOW_CHILD_SECTIONS" => $arParams["SHOW_CHILD_SECTIONS"],
		"DEPTH_LEVEL" => 1,
		"IMAGE_WIDE" => $arParams["IMAGE_WIDE"],
		"SHOW_SECTION_PREVIEW_DESCRIPTION" => $arParams["SHOW_SECTION_PREVIEW_DESCRIPTION"],
		"IBLOCK_TYPE"	=>	$arParams["IBLOCK_TYPE"],
		"IBLOCK_ID"	=>	$arParams["IBLOCK_ID"],
		"NEWS_COUNT"	=>	$arParams["NEWS_COUNT"],
		"SORT_BY1"	=>	$arParams["SORT_BY1"],
		"SORT_ORDER1"	=>	$arParams["SORT_ORDER1"],
		"SORT_BY2"	=>	$arParams["SORT_BY2"],
		"SORT_ORDER2"	=>	$arParams["SORT_ORDER2"],
		"FIELD_CODE"	=>	$arParams["LIST_FIELD_CODE"],
		"PROPERTY_CODE"	=>	$arParams["LIST_PROPERTY_CODE"],
		"DISPLAY_PANEL"	=>	$arParams["DISPLAY_PANEL"],
		"SET_TITLE"	=>	($arResult['VARIABLES'] ? $arParams['SET_TITLE'] : 'N'),
		"SET_STATUS_404" => $arParams["SET_STATUS_404"],
		"INCLUDE_IBLOCK_INTO_CHAIN"	=>	$arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
		"CACHE_TYPE"	=>	$arParams["CACHE_TYPE"],
		"CACHE_TIME"	=>	$arParams["CACHE_TIME"],
		"CACHE_FILTER"	=>	$arParams["CACHE_FILTER"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"DISPLAY_TOP_PAGER"	=>	$arParams["DISPLAY_TOP_PAGER"],
		"DISPLAY_BOTTOM_PAGER"	=>	$arParams["DISPLAY_BOTTOM_PAGER"],
		"PAGER_TITLE"	=>	$arParams["PAGER_TITLE"],
		"PAGER_TEMPLATE"	=>	$arParams["PAGER_TEMPLATE"],
		"PAGER_SHOW_ALWAYS"	=>	$arParams["PAGER_SHOW_ALWAYS"],
		"PAGER_DESC_NUMBERING"	=>	$arParams["PAGER_DESC_NUMBERING"],
		"PAGER_DESC_NUMBERING_CACHE_TIME"	=>	$arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
		"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
		"DISPLAY_DATE"	=>	$arParams["DISPLAY_DATE"],
		"DISPLAY_NAME"	=>	$arParams["DISPLAY_NAME"],
		"DISPLAY_PICTURE"	=>	$arParams["DISPLAY_PICTURE"],
		"DISPLAY_PREVIEW_TEXT"	=>	$arParams["DISPLAY_PREVIEW_TEXT"],
		"PREVIEW_TRUNCATE_LEN"	=>	$arParams["PREVIEW_TRUNCATE_LEN"],
		"ACTIVE_DATE_FORMAT"	=>	$arParams["LIST_ACTIVE_DATE_FORMAT"],
		"USE_PERMISSIONS"	=>	$arParams["USE_PERMISSIONS"],
		"GROUP_PERMISSIONS"	=>	$arParams["GROUP_PERMISSIONS"],
		"SHOW_DETAIL_LINK"	=>	$arParams["SHOW_DETAIL_LINK"],
		"FILTER_NAME"	=>	"arrFilterNewsLK",
		"HIDE_LINK_WHEN_NO_DETAIL"	=>	$arParams["HIDE_LINK_WHEN_NO_DETAIL"],
		"CHECK_DATES"	=>	$arParams["CHECK_DATES"],
		"PARENT_SECTION"	=>	$arResult["VARIABLES"]["SECTION_ID"],
		"PARENT_SECTION_CODE"	=>	$arResult["VARIABLES"]["SECTION_CODE"],
		"DETAIL_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
		"SECTION_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"IBLOCK_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
		"INCLUDE_SUBSECTIONS" => "N",
	),
	$component
);?>