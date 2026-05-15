<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>


<? if (strlen($arResult["FORM_NOTE"]) > 0) { ?>
	<? if ($arResult["isFormErrors"] != "Y") { ?>
		<?
		$arResult["FORM_NOTE"] = "Большое спасибо! Мы обязательно свяжемся с вами.";
		?>

		<script>
			/*			
			gtag('event', 'send_form', {
			  'event_category' : 'form_formManagerOrder'
			});    
			yaCounter50400436.reachGoal('form_formManagerOrder');       
			*/
			/* Таргет Консалт Компани */
			<? if ($arResult['arForm']['ID'] == 14): ?>
				dataLayer.push({
					'event': 'form_formManagerOrder'
				});
			<? else: ?>
				dataLayer.push({
					'event': 'formManagerOrder_4'
				});
			<? endif; ?>
		</script>
	<? } ?>
<? } ?>


<?

?>
<div class="greyline border_red">

	<?= $arResult["FORM_NOTE"] ?>

	<? if ($arResult["isFormNote"] != "Y") { ?>
		<div class="form-managerOrder4">
			<div class="row">
				<?
				$picSrc = "";
				if (strlen($arResult["FORM_IMAGE"]["ID"]) > 0) {
					$file = CFile::ResizeImageGet($arResult["FORM_IMAGE"]["ID"], array("width" => 410, "height" => 200), BX_RESIZE_IMAGE_PROPORTIONAL, false);
					$picSrc = $file["src"];
				}
				?>
				<div class="col-md-<?= (strlen($picSrc) > 0) ? '8' : '12' ?> col-xs-12">
					<? if (!empty($arParams["TITLE"])) {
						$arResult["FORM_TITLE"] = $arParams["TITLE"];
					} ?>
					<h3><?= $arResult["FORM_TITLE"] ?></h3>
					<div class="text">
						<?= $arResult["FORM_DESCRIPTION"] ?>
					</div>

					<div class="form-container">
						<?= $arResult["FORM_HEADER"] ?>
						<div class="form-fields">
							<? if ($arResult["isFormErrors"] == "Y") { ?>
								<div class="errors"><?= $arResult["FORM_ERRORS_TEXT"]; ?></div>
							<? } ?>
							<div class="row">
								<? foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion) { ?>
									<div class="col-md-4 col-xs-12">
										<div class="field">
											<? if ($arQuestion["STRUCTURE"][0]["FIELD_TYPE"] == "email") { ?>
												<input value="<?= $arResult["arrVALUES"]["form_email_" . $arQuestion["STRUCTURE"][0]["ID"]] ?>" name="form_email_<?= $arQuestion["STRUCTURE"][0]["ID"] ?>" placeholder="<?= $arQuestion["CAPTION"] ?><?= ($arQuestion["REQUIRED"] == "Y" ? '*' : '') ?>" type="email" />
											<? } else { ?>
												<input value="<?= $arResult["arrVALUES"]["form_text_" . $arQuestion["STRUCTURE"][0]["ID"]] ?>" name="form_text_<?= $arQuestion["STRUCTURE"][0]["ID"] ?>" placeholder="<?= $arQuestion["CAPTION"] ?><?= ($arQuestion["REQUIRED"] == "Y" ? '*' : '') ?>" type="<?= ($FIELD_SID == "PHONE") ? 'tel' : 'text' ?>" <?= $arQuestion["STRUCTURE"][0]["FIELD_PARAM"] ?> />
											<? } ?>
										</div>
									</div>
								<? } ?>
							</div>
							<div class="row">
								<? if ($arParams["SHOW_LICENCE"] == "Y") { ?>
									<div class="col-md-6 col-xs-12">
										<div class="form licence_custom">
											<div class="licence_block bx_filter">
												<input type="checkbox" id="licenses_inline_<?= $arResult["arForm"]["ID"]; ?>" <? if ($arResult["isFormErrors"] == "Y") { ?>checked<? } ?> name="licenses_popup" value="Y">
												<label for="licenses_inline_<?= $arResult["arForm"]["ID"]; ?>">
													<? $APPLICATION->IncludeFile(SITE_DIR . "include/licenses_text.php", array(), array("MODE" => "html", "NAME" => "LICENSES")); ?>
												</label>
											</div>
										</div>
										<script type="text/javascript">
											$(document).ready(function() {
												$('[name=licenses_popup]').onoff();
												$(document).on('submit', 'form[name=<?= $arResult["arForm"]["SID"] ?>]', function() {
													if ($('form[name=<?= $arResult["arForm"]["SID"] ?>] input[name=licenses_popup]').prop('checked')) {
														$('form[name=<?= $arResult["arForm"]["SID"] ?>] .licence_block .licence_error').remove();
														return true;
													} else {
														$('form[name=<?= $arResult["arForm"]["SID"] ?>] .licence_block .licence_error').remove();
														$('form[name=<?= $arResult["arForm"]["SID"] ?>] .licence_block').append('<div class="licence_error">Согласитесь с условиями</div>');
														return false;
													}
												});
												$('form[name=<?= $arResult["arForm"]["SID"] ?>] input[name=licenses_popup]').change(function() {
													$('form[name=<?= $arResult["arForm"]["SID"] ?>] .licence_block .licence_error').remove();
												});
											});
										</script>
									</div>
								<? } ?>

								<div class="col-md-4 col-xs-12">
									<div class="field button-container">
										<button class="btn btn-default" name="web_form_submit"><?= $arResult["arForm"]["BUTTON"] ?></button>
										<input type="hidden" name="web_form_apply" value="Y" />

										<? if ($arResult["isUseCaptcha"] == "Y") { ?>
											<div class="captcha-row clearfix">
												<div class="captcha_image">
													<img src="/bitrix/tools/captcha.php?captcha_sid=<?= htmlspecialcharsbx($arResult["CAPTCHACode"]) ?>" border="0" />
													<input type="hidden" name="captcha_sid" value="<?= htmlspecialcharsbx($arResult["CAPTCHACode"]) ?>" />
													<div class="captcha_reload"></div>
												</div>
												<div class="captcha_input">
													<input type="text" class="inputtext captcha" name="captcha_word" size="30" maxlength="50" value="" required />
												</div>
											</div>
										<? } ?>
									</div>
								</div>
							</div>
						</div>
						<?= $arResult["FORM_FOOTER"] ?>
					</div>
				</div>
				<? if (strlen($picSrc) > 0) { ?>
					<div class="col-md-4 hidden-xs">
						<div class="text-right pictureInForm">
							<div class="thumbnail"><img src="<?= $picSrc ?>" /></div>
						</div>
					</div>
				<? } ?>
			</div>
		</div>

		<script type="text/javascript">
			Recaptchafree.reset();
		</script>
	<? } ?>
</div>

<? //echo "<pre>"; print_r($arResult); echo "</pre>";
?>