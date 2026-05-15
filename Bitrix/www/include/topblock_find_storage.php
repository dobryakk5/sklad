<div class="topblock-backround">
	<section class="page-top maxwidth-theme">
		<div class="row">
			<div class="col-md-12">
				<?$APPLICATION->IncludeComponent(
					"bitrix:breadcrumb", 
					"corp", 
					array(
						"COMPONENT_TEMPLATE" => "corp",
						"START_FROM" => "0",
						"PATH" => "",
						"SITE_ID" => "s1"
					),
					false
				);?>
				<br>
				<div class="page-top-main">
					<h1 id="pagetitle"><?$APPLICATION->ShowTitle(false)?></h1>
				</div>
				<br>
				<?$APPLICATION->IncludeComponent(
					"bitrix:main.include",
					"",
					Array(
						"AREA_FILE_SHOW" => "file",
						"AREA_FILE_SUFFIX" => "inc",
						"EDIT_TEMPLATE" => "",
						"PATH" => "/include/topblock_find_storage_text.php"
					),
					false,
					Array("HIDE_ICONS" => "N")
				);?>

				<?$APPLICATION->IncludeComponent(
					"bitrix:catalog.section.list", 
					"topblock_find_storage", 
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
						"SECTION_CODE" => $_REQUEST["SECTION_CODE"],
						"SECTION_FIELDS" => array(
							0 => "NAME",
							1 => "PICTURE",
							2 => "",
						),
						"SECTION_ID" => "",
						"SECTION_URL" => "#CODE#/",
						"SECTION_USER_FIELDS" => array(
							0 => "UF_PHOTOGALLERY",
							1 => "",
						),
						"SHOW_PARENT_NAME" => "Y",
						"TOP_DEPTH" => "1",
						"VIEW_MODE" => "LINE",
						"COMPONENT_TEMPLATE" => "topblock_find_storage"
					),
					false
				);?>				
			</div>
		</div>
	</section>
</div>