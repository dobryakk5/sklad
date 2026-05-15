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
	"bitrix:news.detail", 
	"featuresblock_mainpage", 
	array(
		"COMPONENT_TEMPLATE" => "featuresblock_mainpage",
		"IBLOCK_TYPE" => "aspro_priority_content",
		"IBLOCK_ID" => "46",
		"ELEMENT_ID" => "332",
		"ELEMENT_CODE" => "",
		"CHECK_DATES" => "Y",
		"FIELD_CODE" => array(
			0 => "NAME",
			1 => "PREVIEW_PICTURE",
			2 => "",
		),
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "FEATURES",
			2 => "",
		),
		"IBLOCK_URL" => "",
		"DETAIL_URL" => "",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_GROUPS" => "N",
		"SET_TITLE" => "N",
		"SET_CANONICAL_URL" => "N",
		"SET_BROWSER_TITLE" => "N",
		"BROWSER_TITLE" => "-",
		"SET_META_KEYWORDS" => "N",
		"META_KEYWORDS" => "-",
		"SET_META_DESCRIPTION" => "N",
		"META_DESCRIPTION" => "-",
		"SET_LAST_MODIFIED" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"ADD_ELEMENT_CHAIN" => "N",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"USE_PERMISSIONS" => "N",
		"STRICT_SECTION_CHECK" => "N",
		"DISPLAY_DATE" => "N",
		"DISPLAY_NAME" => "N",
		"DISPLAY_PICTURE" => "N",
		"DISPLAY_PREVIEW_TEXT" => "N",
		"USE_SHARE" => "N",
		"PAGER_TEMPLATE" => ".default",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"PAGER_TITLE" => "Страница",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SET_STATUS_404" => "N",
		"SHOW_404" => "N",
		"MESSAGE_404" => ""
	),
	false
);?>