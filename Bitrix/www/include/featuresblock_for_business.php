<div style="display:none;">
	<div class="featuresblock_form_start">
		<?$APPLICATION->IncludeComponent(
			"bitrix:form", 
			"formManagerOrder_2", 
			array(
				"COMPONENT_TEMPLATE" => "formManagerOrder_2",
				"START_PAGE" => "new",
				"SHOW_LIST_PAGE" => "N",
				"SHOW_EDIT_PAGE" => "N",
				"SHOW_VIEW_PAGE" => "N",
				"SUCCESS_URL" => "",
				"WEB_FORM_ID" => "12",
				"RESULT_ID" => $_REQUEST["RESULT_ID"],
				"SHOW_ANSWER_VALUE" => "N",
				"SHOW_ADDITIONAL" => "N",
				"SHOW_STATUS" => "N",
				"EDIT_ADDITIONAL" => "N",
				"EDIT_STATUS" => "N",
				"NOT_SHOW_FILTER" => array(
					0 => "",
					1 => "",
				),
				"NOT_SHOW_TABLE" => array(
					0 => "",
					1 => "",
				),
				"IGNORE_CUSTOM_TEMPLATE" => "N",
				"USE_EXTENDED_ERRORS" => "N",
				"SEF_MODE" => "N",
				"AJAX_MODE" => "Y",
				"AJAX_OPTION_JUMP" => "N",
				"AJAX_OPTION_STYLE" => "Y",
				"AJAX_OPTION_HISTORY" => "N",
				"AJAX_OPTION_ADDITIONAL" => "form_12",
				"CACHE_TYPE" => "A",
				"CACHE_TIME" => "3600",
				"CHAIN_ITEM_TEXT" => "",
				"CHAIN_ITEM_LINK" => "",
				"SHOW_LICENCE" => "Y",
				"VARIABLE_ALIASES" => array(
					"action" => "action",
				)
			),
			false,
			Array("HIDE_ICONS"=>"Y")
		);?>
	
		<script>
		 $(function(){
			var strt = $('.featuresblock_form_start');
			var intobox = $('.featuresblock_form');
			if(intobox.length && strt.length){
			   strt.appendTo(intobox);
			}
		 });
		</script>
	</div>
</div>

<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section.list", 
	"featuresblock_for_business", 
	array(
		"ADD_SECTIONS_CHAIN" => "N",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "N",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"COUNT_ELEMENTS" => "N",
		"FILTER_NAME" => "",
		"IBLOCK_ID" => "41",
		"IBLOCK_TYPE" => "aspro_priority_content",
		"SECTION_CODE" => $arParams["SECTION_CODE"],
		"SECTION_FIELDS" => array(
			0 => "NAME",
			1 => "",
			2 => "",
			3 => "",
		),
		"SECTION_ID" => "",
		"SECTION_URL" => "",
		"SECTION_USER_FIELDS" => array(
			0 => "UF_FEATURES",
			1 => "UF_FEATURES_PIC",
			2 => "",
		),
		"SHOW_PARENT_NAME" => "Y",
		"TOP_DEPTH" => "2",
		"VIEW_MODE" => "LINE",
		"COMPONENT_TEMPLATE" => "featuresblock_for_business"
	),
	false
);?>