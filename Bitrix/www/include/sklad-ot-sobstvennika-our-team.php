<hr class="tall">
<div class="info-our-team">
	<h3>Наша команда</h3>
	<?$APPLICATION->IncludeComponent(
		"bitrix:main.include",
		"",
		Array(
			"AREA_FILE_SHOW" => "file",
			"AREA_FILE_SUFFIX" => "inc",
			"EDIT_TEMPLATE" => "",
			"PATH" => "/include/sklad-ot-sobstvennika-our-team-text.php"
		),
		false,
		Array("HIDE_ICONS" => "N")
	);?>

	<div class="row">
		<div class="col-md-4 col-xs-12">
			<div class="info phone">
				<?$APPLICATION->IncludeComponent(
					"bitrix:main.include",
					"",
					Array(
						"AREA_FILE_SHOW" => "file",
						"AREA_FILE_SUFFIX" => "inc",
						"EDIT_TEMPLATE" => "",
						"PATH" => "/include/sklad-ot-sobstvennika-our-team-phone.php"
					),
					false,
					Array("HIDE_ICONS" => "N")
				);?>
				<span class="order-call" data-event="jqm" data-param-id="21" data-name="callback">заказать звонок</span>
			</div>
		</div>
		<div class="col-md-4 col-xs-12">
			<div class="info email">
				<?$APPLICATION->IncludeComponent(
					"bitrix:main.include",
					"",
					Array(
						"AREA_FILE_SHOW" => "file",
						"AREA_FILE_SUFFIX" => "inc",
						"EDIT_TEMPLATE" => "",
						"PATH" => "/include/sklad-ot-sobstvennika-our-team-email.php"
					),
					false,
					Array("HIDE_ICONS" => "N")
				);?>
			</div>
		</div>
		<div class="col-md-4 col-xs-12">
			<div class="info address">
				<?$APPLICATION->IncludeComponent(
					"bitrix:main.include",
					"",
					Array(
						"AREA_FILE_SHOW" => "file",
						"AREA_FILE_SUFFIX" => "inc",
						"EDIT_TEMPLATE" => "",
						"PATH" => "/include/sklad-ot-sobstvennika-our-team-address.php"
					),
					false,
					Array("HIDE_ICONS" => "N")
				);?>
			</div>
		</div>
	</div>
</div>
<hr class="tall">