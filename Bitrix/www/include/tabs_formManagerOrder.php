<div id="tabs_formManagerOrder"></div>
<div class="tabs <?if($arParams["SHADOW_BOX"] == "Y") {?>tabs-shadow-box<?}?>">
	<div class="nav-tabs-custom-style">
		<div class="maxwidth-theme">
			<ul class="nav nav-tabs">
				<li class="font_upper_md <?if($arParams["ACTIVE_TAB"] != "tab_2"){?>active<?}?> shadow border"><a href="#feedbackForm" data-toggle="tab">Аренда через менеджера</a></li>
				<li class="font_upper_md <?if($arParams["ACTIVE_TAB"] == "tab_2"){?>active<?}?> shadow border map_redraw"><a href="#order" data-toggle="tab">Арендовать бокс сейчас</a></li>
			</ul>
		</div>
	</div>
	<div class="maxwidth-theme">
		<?if($arParams["SHADOW_BOX"] == "Y") {?>
			<div class="shadow-box">
		<?}?>
			<div class="tab-content">
				<div class="tab-pane <?if($arParams["ACTIVE_TAB"] != "tab_2"){?>active<?}?>" id="feedbackForm">								
					<?$APPLICATION->IncludeComponent(
						"bitrix:form", 
						"formManagerOrder", 
						array(
							"COMPONENT_TEMPLATE" => "formManagerOrder",
							"START_PAGE" => "new",
							"SHOW_LIST_PAGE" => "N",
							"SHOW_EDIT_PAGE" => "N",
							"SHOW_VIEW_PAGE" => "N",
							"SUCCESS_URL" => "",
							"WEB_FORM_ID" => "11",
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
							"AJAX_OPTION_ADDITIONAL" => "form_11",
							"CACHE_TYPE" => "A",
							"CACHE_TIME" => "3600",
							"CHAIN_ITEM_TEXT" => "",
							"CHAIN_ITEM_LINK" => "",
							"PHONE" => "+7 (495) 154-40-98",
							"EMAIL" => "info@alfasklad.ru",
							"SHOW_LICENCE" => "Y",
							"VARIABLE_ALIASES" => array(
								"action" => "action",
							)
						),
						false
					);?>				
				</div>
				
				<div class="tab-pane <?if($arParams["ACTIVE_TAB"] == "tab_2"){?>active<?}?>" id="order">
					<?$APPLICATION->IncludeComponent(
						"bitrix:catalog.section.list", 
						"storage_select_withmap", 
						array(
							"ADD_SECTIONS_CHAIN" => "N",
							"CACHE_FILTER" => "N",
							"CACHE_GROUPS" => "N",
							"CACHE_TIME" => "36000000",
							"CACHE_TYPE" => "A",
							"COUNT_ELEMENTS" => "N",
							"FILTER_NAME" => "arRegionLinkUF",
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
								1 => "UF_RECEPTION",
								2 => "UF_DOSTUP_TIME",
								3 => "UF_PHONE",
								4 => "UF_MAP",
								5 => "UF_PHOTOGALLERY",
								6 => "UF_PRICE_ON_MAP",
								7 => "UF_RECEPTION_NAME",
							),
							"SHOW_PARENT_NAME" => "Y",
							"TOP_DEPTH" => "1",
							"VIEW_MODE" => "LINE",
							"COMPONENT_TEMPLATE" => "storage_select_withmap"
						),
						false
					);?>
				</div>
				
			</div>
		<?if($arParams["SHADOW_BOX"] == "Y") {?>
			</div>
		<?}?>		
	</div>
</div>