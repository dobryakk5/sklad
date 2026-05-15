<?require_once $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php";?>
<?
CModule::IncludeModule("iblock");

function roundToHalf($x) {
    return ceil($x/0.5)*0.5;
}

if($_REQUEST["ACTION"] == "UPDATE") {
	
	$result = Array();
	$result["TOTAL_SQUARE"] = 0;
	$selectedItems = Array();

	foreach($_REQUEST["CALCULATOR_DATA"] as $calcItem) {
		//общая площадь
		$result["TOTAL_SQUARE"] = $result["TOTAL_SQUARE"] + roundToHalf(round(($calcItem["CALC_ITEM_SQUARE"] * $calcItem["CALC_ITEM_COUNT"]), 1));
		
		//общий объем
		$result["TOTAL_VOLUME"] = roundToHalf($result["TOTAL_SQUARE"] * 3);
		
		//выбранные предметы
		if($calcItem["CALC_ITEM_COUNT"] > 0) {
			$res = CIBlockElement::GetByID($calcItem["CALC_ITEM_ID"]);
			if($arItem = $res->GetNext()) {				
				if(strlen($arItem["PREVIEW_TEXT"]) > 0) {
					$nameItem = $arItem["PREVIEW_TEXT"];
				} else {
					$nameItem = $arItem["NAME"];
				}	
				$selectedItems[$calcItem["CALC_ITEM_ID"]] = Array("NAME"=>$nameItem, "SQUARE"=>roundToHalf(round(($calcItem["CALC_ITEM_SQUARE"] * $calcItem["CALC_ITEM_COUNT"]), 1)));
			}
		}
	}
	
	//slider
	ob_start();
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
				"SECTION_CODE" => "",
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
				"SIZE_FROM" => ceil($result["TOTAL_SQUARE"]),
				"SMALL_THMB" => "Y"
			),
			false,
			Array("HIDE_ICONS"=>"Y")
		);?>
	<?
	$result["SLIDER"] = @ob_get_contents();
	ob_get_clean();	


	//mobile_slider
	ob_start();
	?>
		<?$APPLICATION->IncludeComponent(
			"bitrix:catalog.section.list", 
			"fotogalereya_calculator_mobile", 
			array(
				"ADD_SECTIONS_CHAIN" => "N",
				"CACHE_FILTER" => "N",
				"CACHE_GROUPS" => "N",
				"CACHE_TIME" => "36000",
				"CACHE_TYPE" => "A",
				"COMPONENT_TEMPLATE" => "fotogalereya_calculator_mobile",
				"COUNT_ELEMENTS" => "N",
				"FILTER_NAME" => "",
				"IBLOCK_ID" => "40",
				"IBLOCK_TYPE" => "aspro_priority_catalog",
				"SECTION_CODE" => "",
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
				"SIZE_FROM" => ceil($result["TOTAL_SQUARE"]),
				"SMALL_THMB" => "Y"
			),
			false,
			Array("HIDE_ICONS"=>"Y")
		);?>
	<?
	$result["MOBILE_SLIDER"] = @ob_get_contents();
	ob_get_clean();	
	
	
	//selectedItems
	ob_start();
	foreach($selectedItems as $key=>$arSelectedItem) {		
		?>
		<div class="item">
			<div class="icon yes"><img src="<?=SITE_TEMPLATE_PATH?>/images/custom/icon-calc-01.png" /></div>
			<div class="name"><?=$arSelectedItem["NAME"]?></div>
			<div class="square"><?=$arSelectedItem["SQUARE"]?> м<sup>2</sup></div>
			<div class="icon delete" data-calc-item-id="<?=$key?>"><img src="<?=SITE_TEMPLATE_PATH?>/images/custom/icon-calc-02.png" /></div>
		</div>		
		<?
	}
	$result["SELECTED_ITEMS"] = @ob_get_contents();
	ob_get_clean();	
	
	
	//sklad_list
	ob_start();
	?>
		<?$APPLICATION->IncludeComponent(
			"bitrix:catalog.section.list", 
			"calculator_storage_list", 
			array(
				"SQUARE_FROM" => ($result["TOTAL_SQUARE"] > 15)?"15":$result["TOTAL_SQUARE"],
				"ADD_SECTIONS_CHAIN" => "N",
				"CACHE_FILTER" => "N",
				"CACHE_GROUPS" => "N",
				"CACHE_TIME" => "36000",
				"CACHE_TYPE" => "A",
				"COUNT_ELEMENTS" => "N",
				"FILTER_NAME" => "",
				"IBLOCK_ID" => "40",
				"IBLOCK_TYPE" => "aspro_priority_catalog",
				"SECTION_CODE" => "",
				"SECTION_FIELDS" => array(
					0 => "NAME",
					1 => "DESCRIPTION",
					2 => "",
				),
				"SECTION_ID" => "",
				"SECTION_URL" => "#CODE#/",
				"SECTION_USER_FIELDS" => array(
					0 => "UF_ADDRESS",
				),
				"SHOW_PARENT_NAME" => "Y",
				"TOP_DEPTH" => "1",
				"VIEW_MODE" => "LINE",
				"COMPONENT_TEMPLATE" => "calculator_storage_list"
			),
			false,
			Array("HIDE_ICONS"=>"Y")
		);?>	
	<?
	$result["SKLAD_LIST"] = @ob_get_contents();
	ob_get_clean();		
	
	
	
	
	
	echo json_encode($result);

}

?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>