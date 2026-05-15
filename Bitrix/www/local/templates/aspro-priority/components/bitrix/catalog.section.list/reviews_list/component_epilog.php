<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
global $APPLICATION;
$GLOBALS["PARAMS"] = $arParams;
?>


<?$arResult["CACHED_TPL"] = preg_replace_callback(
    "/#RATING_SKLAD#/is".BX_UTF_PCRE_MODIFIER,
    function($matches) { ob_start();
    $GLOBALS["APPLICATION"]->IncludeComponent(
		"bitrix:catalog.section.list", 
		"rating_sklad", 
		array(
			"ADD_SECTIONS_CHAIN" => "N",
			"CACHE_FILTER" => "N",
			"CACHE_GROUPS" => "N",
			"CACHE_TIME" => "36000000",
			"CACHE_TYPE" => "A",
			"COUNT_ELEMENTS" => "N",
			"FILTER_NAME" => "",
			"IBLOCK_ID" => "40",
			"IBLOCK_TYPE" => "aspro_priority_catalog",
			"SECTION_CODE" => $_REQUEST["SKLAD_CODE"],
			"SECTION_FIELDS" => array(
				0 => "NAME",
				1 => "",
				2 => "",
			),
			"SECTION_ID" => "",
			"SECTION_URL" => "",
			"SECTION_USER_FIELDS" => array(
				0 => "UF_YMAP_REVIEWS",
				1 => "UF_YMAP_VOTES",
				2 => "UF_YMAP_RATING",
				3 => "",
				4 => "",
				5 => "",
			),
			"SHOW_PARENT_NAME" => "Y",
			"TOP_DEPTH" => "1",
			"VIEW_MODE" => "LINE",
			"COMPONENT_TEMPLATE" => "rating_sklad"
		),
		false,
		Array("HIDE_ICONS"=>"Y")
    );
    $retrunStr = @ob_get_contents();
    ob_get_clean();
    return $retrunStr;},
    $arResult["CACHED_TPL"]);
?>


<?
global $DinamicData;
$DinamicData = Array();

//sorting
$DinamicData["SORT"] = '';
ob_start();  
?>
<?
$sort_default = "active_from";
$order_default = "desc";

if(array_key_exists("sort", $_REQUEST) && !empty($_REQUEST["sort"])){
	setcookie("reviewsSort", $_REQUEST["sort"], 0, SITE_DIR);
	$_COOKIE["reviewsSort"] = $_REQUEST["sort"];
}
if(array_key_exists("order", $_REQUEST) && !empty($_REQUEST["order"])){
	setcookie("reviewsOrder", $_REQUEST["order"], 0, SITE_DIR);
	$_COOKIE["reviewsOrder"] = $_REQUEST["order"];
}

$sort = !empty($_COOKIE["reviewsSort"]) ? $_COOKIE["reviewsSort"] : $sort_default;
$order = !empty($_COOKIE["reviewsOrder"]) ? $_COOKIE["reviewsOrder"] : $order_default;

$GLOBALS["PARAMS"]["REVIEWS_SORT"] = $sort;
$GLOBALS["PARAMS"]["REVIEWS_ORDER"] = $order;

if($order == "asc") {
	$newOrder = "desc";
} else {
	$newOrder = "asc";
}
?>
<div class="sort-reviews">
	Сортировать: <a class="sort-item <?if($sort == "active_from") {?><?=$order?> active<?}?>" href="<?=$APPLICATION->GetCurPageParam('sort=active_from&order='.$newOrder, array('sort', 'order'))?>"><span>по дате</span></a>
				 <a class="sort-item <?if($sort == "property_RATING") {?><?=$order?> active<?}?>" href="<?=$APPLICATION->GetCurPageParam('sort=property_RATING&order='.$newOrder, array('sort', 'order'))?>"><span>по оценке</span></a>
</div>

<?
$DinamicData["SORT"] .= @ob_get_contents();
ob_get_clean(); 
?>
<?$arResult["CACHED_TPL"] = preg_replace_callback(
    "/#SORT#/is".BX_UTF_PCRE_MODIFIER,
    function($matches)  {ob_start();
    echo $GLOBALS["DinamicData"]["SORT"];   
    $retrunStr = @ob_get_contents();
    ob_get_clean();
    return $retrunStr;},
    $arResult["CACHED_TPL"]);
	
// end sorting
?>





<?
if(strlen($_REQUEST["SKLAD_CODE"]) > 0) {
	//кэшируем
	$obCache = new CPHPCache();
	$cacheLifetime = 7200; 
	$cacheID = "sklad_data_".$_REQUEST["SKLAD_CODE"]; 
	$cachePath = "/sklad_data/";
	if($obCache->InitCache($cacheLifetime, $cacheID, $cachePath)) {
		$vars = $obCache->GetVars();
		$arSklad = $vars["SKLAD_DATA"];
		$GLOBALS["arrFilterSkladList"]["PROPERTY_SKLAD"] = $arSklad["ID"];
        if($arParams['SET_TITLE'] == 'Y') {
		    $APPLICATION->SetTitle("Отзывы о складе «".$arSklad["NAME"]."»");
        }
	} elseif($obCache->StartDataCache()) {
		$arFilter = array("IBLOCK_ID"=>40, "DEPTH_LEVEL"=>1, "CODE"=>$_REQUEST["SKLAD_CODE"]);
		$rsSect = CIBlockSection::GetList(array("sort"=>"asc"), $arFilter);
		if($arSklad = $rsSect->GetNext()) {
			$GLOBALS["arrFilterSkladList"]["PROPERTY_SKLAD"] = $arSklad["ID"];
            if($arParams['SET_TITLE'] == 'Y') {
			    $APPLICATION->SetTitle("Отзывы о складе «".$arSklad["NAME"]."»");
            }
		}	
		$obCache->EndDataCache(array("SKLAD_DATA"=>$arSklad));
	}

	if(strlen($arSklad["ID"]) == 0) {
		if (Bitrix\Main\Loader::includeModule("iblock")) {
			Bitrix\Iblock\Component\Tools::process404(
				'Страница не найдена'
				,true
				,true
				,true
				,'/404.php'
			);
		}	
	}
}
?>
<?$arResult["CACHED_TPL"] = preg_replace_callback(
    "/#REVIEWS_([\\d]+)#/is".BX_UTF_PCRE_MODIFIER,
    function($matches){
        ob_start();
    $GLOBALS["APPLICATION"]->IncludeComponent(
		"bitrix:news.list", 
		"reviews_custom", 
		array(
			"COMPONENT_TEMPLATE" => "reviews_custom",
			"IBLOCK_TYPE" => "aspro_priority_form",
			"IBLOCK_ID" => "30",
			"NEWS_COUNT" => $GLOBALS["PARAMS"]["NEWS_COUNT"],
			"SORT_BY1" => $GLOBALS["PARAMS"]["REVIEWS_SORT"],
			"SORT_ORDER1" => $GLOBALS["PARAMS"]["REVIEWS_ORDER"],
			"SORT_BY2" => "SORT",
			"SORT_ORDER2" => "ASC",
			"FILTER_NAME" => "arrFilterSkladList",
			"FIELD_CODE" => array(
				0 => "NAME",
				1 => "",
			),
			"PROPERTY_CODE" => array(
				0 => "NAME",
				1 => "POST",
				2 => "VIDEO",
				3 => "MESSAGE",
				4 => "RATING",
				5 => "",
			),
			"CHECK_DATES" => "Y",
			"DETAIL_URL" => "",
			"AJAX_MODE" => "N",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "Y",
			"AJAX_OPTION_HISTORY" => "N",
			"AJAX_OPTION_ADDITIONAL" => "",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "36000000",
			"CACHE_FILTER" => "N",
			"CACHE_GROUPS" => "N",
			"PREVIEW_TRUNCATE_LEN" => "",
			"ACTIVE_DATE_FORMAT" => "j F Y",
			"SET_TITLE" => "N",
			"SET_BROWSER_TITLE" => "N",
			"SET_META_KEYWORDS" => "N",
			"SET_META_DESCRIPTION" => "N",
			"SET_LAST_MODIFIED" => "N",
			"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
			"ADD_SECTIONS_CHAIN" => "N",
			"HIDE_LINK_WHEN_NO_DETAIL" => "N",
			"PARENT_SECTION" => $matches[1],
			"PARENT_SECTION_CODE" => "",
			"INCLUDE_SUBSECTIONS" => "N",
			"STRICT_SECTION_CHECK" => "N",
			"PAGER_TEMPLATE" => ".default",
			"DISPLAY_TOP_PAGER" => "N",
			"DISPLAY_BOTTOM_PAGER" => "Y",
			"PAGER_TITLE" => "Новости",
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_DESC_NUMBERING" => "N",
			"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
			"PAGER_SHOW_ALL" => "N",
			"PAGER_BASE_LINK_ENABLE" => "N",
			"SET_STATUS_404" => "N",
			"SHOW_404" => "N",
			"MESSAGE_404" => ""
		),
		false,
		Array("HIDE_ICONS"=>"Y")
    );
    $retrunStr = @ob_get_contents();
    ob_get_clean();
    return $retrunStr;},
    $arResult["CACHED_TPL"]);
?>



<?$arResult["CACHED_TPL"] = preg_replace_callback(
    "/#SKLAD_LIST#/is".BX_UTF_PCRE_MODIFIER,
   function($matches)  { ob_start();
    $GLOBALS["APPLICATION"]->IncludeComponent(
		"bitrix:catalog.section.list", 
		"sklad_list_right_col", 
		array(
			"ADD_SECTIONS_CHAIN" => "N",
			"CACHE_FILTER" => "N",
			"CACHE_GROUPS" => "N",
			"CACHE_TIME" => "36000000",
			"CACHE_TYPE" => "A",
			"COUNT_ELEMENTS" => "N",
			"FILTER_NAME" => "",
			"IBLOCK_ID" => "40",
			"IBLOCK_TYPE" => "aspro_priority_catalog",
			"SECTION_CODE" => $_REQUEST["SECTION_CODE"],
			"SECTION_FIELDS" => array(
				0 => "NAME",
				1 => "PICTURE",
				2 => "",
			),
			"SECTION_ID" => "",
			"SECTION_URL" => "#CODE#/",
			"SECTION_USER_FIELDS" => array(
				0 => "UF_ADDRESS",
				1 => "UF_RECEPTION",
				2 => "UF_DOSTUP_TIME",
				3 => "UF_PHONE",
				4 => "UF_MAP",
				5 => "UF_YMAP_REVIEWS",
				6 => "UF_YMAP_VOTES",
				7 => "UF_YMAP_RATING",
			),
			"SHOW_PARENT_NAME" => "Y",
			"TOP_DEPTH" => "1",
			"VIEW_MODE" => "LINE",
			"COMPONENT_TEMPLATE" => "sklad_list_right_col"
		),
		false,
		Array("HIDE_ICONS"=>"Y")
    );
    $retrunStr = @ob_get_contents();
    ob_get_clean();
    return $retrunStr;},
    $arResult["CACHED_TPL"]);
?>


<?// вывод
echo $arResult["CACHED_TPL"];

include $_SERVER['DOCUMENT_ROOT'] . '/include/seo/setDescription.php';
?>
