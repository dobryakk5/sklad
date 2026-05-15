<?
CModule::IncludeModule("iblock");
global $filtered_boxes_list;
$filtered_boxes_list = Array();
if(strlen($_REQUEST["ACTION_ID"]) > 0) {	
	// кешируем запрос
	$obCache = new CPHPCache();
	$cacheLifetime = 3600; 
	$cacheID = "boxes_action_".$_REQUEST["ACTION_ID"]; 
	$cachePath = "/boxes_in_action/";
	if($obCache->InitCache($cacheLifetime, $cacheID, $cachePath)) {
		$vars = $obCache->GetVars();
		$filtered_boxes_list = $vars["BOXES_LIST"];
		$APPLICATION->SetTitle($vars["TITLE"]);
	} elseif($obCache->StartDataCache()) {
		$res = CIBlockElement::GetList(Array("id"=>"asc"), Array("IBLOCK_ID"=>44, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "ID"=>intval($_REQUEST["ACTION_ID"])), false, Array("nTopCount"=>1), Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_BOXES"));
		if($ob = $res->GetNextElement()) {
			$arActionFields = $ob->GetFields();
			$arActionProps = $ob->GetProperties();
			$APPLICATION->SetTitle("Все боксы, участвующие в акции «".$arActionFields["NAME"]."»");
			$filtered_boxes_list = $arActionProps["BOXES"]["VALUE"];

			$obCache->EndDataCache(array("BOXES_LIST"=>$filtered_boxes_list, "TITLE"=>"Все боксы, участвующие в акции «".$arActionFields["NAME"]."»"));	
		}	
	}	
}


//Тип "размера бокса" в фильтре
$propSize_default = "SQUARE";

if(array_key_exists("propSize", $_REQUEST) && !empty($_REQUEST["propSize"])){
    setcookie("propSize", $_REQUEST["propSize"], 0, SITE_DIR);
    $_COOKIE["propSize"] = $_REQUEST["propSize"];
}

$propSize = !empty($_COOKIE["propSize"]) ? $_COOKIE["propSize"] : $propSize_default;

global $BOX_LIST_propSize;
$BOX_LIST_propSize = $propSize;
?>

<div class="box_filter_main_container">
	<div class="row">
		<div class="col-md-8 col-xs-12">
			<?
            $APPLICATION->IncludeComponent(
					"custom:filter.rental_catalog", 
					".default", 
					array(
						"COMPONENT_TEMPLATE" => ".default",
						"SKLAD_CODE" => $_REQUEST["SKLAD_CODE"],
						"FLOOR_CODE" => $_REQUEST["FLOOR_CODE"],
						"PROP_SIZE" => $BOX_LIST_propSize,
						"BOXES_LIST" => implode(",",$filtered_boxes_list),
						"DISCOUNT_MONTHS" => array(
						),
						"DISCOUNT_PERCENT" => array(
						),
						"DISCOUNT_TEXT" => "Скидка за несколько полных месяцев",
						"DISCOUNT_ACTIVE" => "Y"
					),
					false
				);
			?>
		</div>
		<div class="col-md-4 col-xs-12 hidden-xs">
			<?
			if(strlen($_REQUEST["SKLAD_CODE"]) > 0) {
				$SKLAD_CODE = $_REQUEST["SKLAD_CODE"];
			} else {
				$SKLAD_CODE = "";
			}
			?>		
			<?$APPLICATION->IncludeComponent(
				"bitrix:catalog.section.list", 
				"fotogalereya_skladov_rental_catallog", 
				array(
					"ADD_SECTIONS_CHAIN" => "N",
					"CACHE_FILTER" => "N",
					"CACHE_GROUPS" => "N",
					"CACHE_TIME" => "36000",
					"CACHE_TYPE" => "A",
					"COMPONENT_TEMPLATE" => "fotogalereya_skladov_rental_catallog",
					"COUNT_ELEMENTS" => "N",
					"FILTER_NAME" => "",
					"IBLOCK_ID" => "40",
					"IBLOCK_TYPE" => "aspro_priority_catalog",
					"SECTION_CODE" => $SKLAD_CODE,
					"SECTION_FIELDS" => array(
						0 => "NAME",
						1 => "PICTURE",
						2 => "",
					),
					"SECTION_ID" => "",
					"SECTION_URL" => "",
					"SECTION_USER_FIELDS" => array(
						0 => "UF_PHOTOGALLERY",
						1 => "",
					),
					"SHOW_PARENT_NAME" => "Y",
					"TOP_DEPTH" => "2",
					"VIEW_MODE" => "LINE",
					"SIZE_FROM" => "1"
				),
				false
			);?>				
		</div>
	</div>
</div>
