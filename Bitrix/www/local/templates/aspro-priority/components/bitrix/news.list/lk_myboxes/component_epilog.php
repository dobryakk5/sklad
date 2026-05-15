<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
global $APPLICATION;
global $DinamicData;
$DinamicData = Array();
?>

<?$arResult["CACHED_TPL"] = preg_replace_callback(
    "/#SERVICES_BUTTONS#/is".BX_UTF_PCRE_MODIFIER,
   function($matches) {ob_start();
    $GLOBALS["APPLICATION"]->IncludeComponent(
		"bitrix:news.list", 
		"lk_services_buttons", 
		array(
			"COMPONENT_TEMPLATE" => "lk_services_buttons",
			"IBLOCK_TYPE" => "aspro_priority_content",
			"IBLOCK_ID" => "54",
			"NEWS_COUNT" => "20",
			"SORT_BY1" => "SORT",
			"SORT_ORDER1" => "ASC",
			"SORT_BY2" => "ID",
			"SORT_ORDER2" => "ASC",
			"FILTER_NAME" => "",
			"FIELD_CODE" => array(
				0 => "NAME",
				1 => "",
			),
			"PROPERTY_CODE" => array(
				0 => "LINK",
				1 => "",
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
			"ACTIVE_DATE_FORMAT" => "d.m.Y",
			"SET_TITLE" => "N",
			"SET_BROWSER_TITLE" => "N",
			"SET_META_KEYWORDS" => "N",
			"SET_META_DESCRIPTION" => "N",
			"SET_LAST_MODIFIED" => "N",
			"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
			"ADD_SECTIONS_CHAIN" => "N",
			"HIDE_LINK_WHEN_NO_DETAIL" => "N",
			"PARENT_SECTION" => "",
			"PARENT_SECTION_CODE" => "",
			"INCLUDE_SUBSECTIONS" => "N",
			"STRICT_SECTION_CHECK" => "N",
			"DISPLAY_DATE" => "N",
			"DISPLAY_NAME" => "N",
			"DISPLAY_PICTURE" => "N",
			"DISPLAY_PREVIEW_TEXT" => "N",
			"PAGER_TEMPLATE" => ".default",
			"DISPLAY_TOP_PAGER" => "N",
			"DISPLAY_BOTTOM_PAGER" => "N",
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


<?
foreach($arResult["ITEMS"] as $arItem) {
	$DinamicData["MODAL_FLOOR_MAP_".$arItem["BOX"]["ID"]] = '';
	ob_start(); 
	?>
	
	<div class="modal fade modal_box_on_floor_map" id="modalFloorMap_<?=$arItem["BOX"]["ID"]?>" tabindex="-1" role="dialog" aria-labelledby="modalSkladListLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="modalSkladListLabel">Карта <?=$arItem["BOX"]["SKLAD"]["FLOOR_NAME"]?> этажа: <?=$arItem["BOX"]["SKLAD"]["NAME"]?></h4>
					</div>
				<div class="modal-body">                
					<?$APPLICATION->IncludeComponent(
						"bitrix:catalog.section.list",
						"lk_floor_map",
						Array(
							"ADD_SECTIONS_CHAIN" => "N",
							"CACHE_FILTER" => "N",
							"CACHE_GROUPS" => "N",
							"CACHE_TIME" => "36000",
							"CACHE_TYPE" => "A",
							"COMPONENT_TEMPLATE" => "lk_floor_map",
							"COUNT_ELEMENTS" => "N",
							"FILTER_NAME" => "",
							"FLOOR_CODE" => "floor-".$arItem["BOX"]["PROPERTY_FLOOR_VALUE"],
							"IBLOCK_ID" => STORAGES_CATALOG_IBLOCK,
							"IBLOCK_TYPE" => "aspro_priority_catalog",
							"SECTION_CODE" => $arItem["BOX"]["SKLAD"]["CODE"],
							"SECTION_FIELDS" => array(0=>"NAME",1=>"PICTURE",2=>"",),
							"SECTION_ID" => "",
							"SECTION_URL" => "",
							"SECTION_USER_FIELDS" => array(0=>"UF_ADDRESS",1=>"UF_RECEPTION",2=>"UF_DOSTUP_TIME",3=>"UF_PHONE",4=>"UF_FLOORS",5=>"UF_MAP_FLOOR_1",6=>"UF_MAP_FLOOR_2",7=>"UF_MAP_FLOOR_3",8=>"UF_MAP_FLOOR_4",9=>"UF_MAP_FLOOR_5",),
							"SHOW_PARENT_NAME" => "Y",
							"TOP_DEPTH" => "2",
							"VIEW_MODE" => "LINE",
							"SHOW_MAP" => "Y",
							"BOX_ID" => $arItem["BOX"]["ID"]
						),
						false,
						Array("HIDE_ICONS"=>"Y")
					);?>
				</div>
			</div>
		</div>
	</div>
	
	<?
	$DinamicData["MODAL_FLOOR_MAP_".$arItem["BOX"]["ID"]] .= @ob_get_contents();
	ob_get_clean(); 	
}
?>
<? 
$arResult["CACHED_TPL"] = preg_replace_callback(
	"/#MODAL_FLOOR_MAP_([\d]+)#/is".BX_UTF_PCRE_MODIFIER,
	function($matches) {ob_start();
		echo $GLOBALS["DinamicData"]["MODAL_FLOOR_MAP_".$matches[1]];
	$retrunStr = @ob_get_contents();
	ob_get_clean();
	return $retrunStr;},
	$arResult["CACHED_TPL"]
);		
?>



<?// вывод
echo $arResult["CACHED_TPL"];
?>