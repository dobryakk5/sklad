<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>


<?if(strlen($arResult["FORM_NOTE"]) > 0) {?>
	<?if($arResult["isFormErrors"] != "Y") {?>
		<script>	
			/*						
			gtag('event', 'send_form', {
			  'event_category' : 'form_formManagerOrder'
			});    
			yaCounter50400436.reachGoal('form_formManagerOrder');     
			*/
			/* Таргет Консалт Компани */ 
			dataLayer.push({'event': 'formManagerOrder_2'});
			console.log({'event': 'formManagerOrder_2'});
		</script>	
	<?}?>
<?}?>


<?=$arResult["FORM_NOTE"]?>

<?if ($arResult["isFormNote"] != "Y") {?>
	<div class="form-managerOrder2">
		<div class="row">
			<div class="col-md-12 col-xs-12">
				<span class="h5-replacer"><?=$arResult["FORM_TITLE"]?></span>
				<div class="text">
					<?=$arResult["FORM_DESCRIPTION"]?>
				</div>

				<div class="form-container">
					<?=$arResult["FORM_HEADER"]?>
						<div class="form-fields">
							<?if ($arResult["isFormErrors"] == "Y") {?>
								<div class="errors"><?=$arResult["FORM_ERRORS_TEXT"];?></div>
							<?}?>						
							<div class="row">
								<?foreach ($arResult["QUESTIONS"] as $FIELD_SID=>$arQuestion) {?>
									<?if($FIELD_SID == "OPROSNIK") {?>
										<textarea id="oprosnik_result" style="display:none;" name="form_textarea_<?=$arQuestion["STRUCTURE"][0]["ID"]?>"></textarea>
									<?} else {?>
										<div class="col-md-4 col-xs-12">
											<div class="field">
												<?if($arQuestion["STRUCTURE"][0]["FIELD_TYPE"] == "email") {?>
													<input value="<?=$arResult["arrVALUES"]["form_email_".$arQuestion["STRUCTURE"][0]["ID"]]?>" name="form_email_<?=$arQuestion["STRUCTURE"][0]["ID"]?>" placeholder="<?=$arQuestion["CAPTION"]?><?=($arQuestion["REQUIRED"]=="Y"?'*':'')?>" type="email" />
												<?} else {?>
													<input value="<?=$arResult["arrVALUES"]["form_text_".$arQuestion["STRUCTURE"][0]["ID"]]?>" name="form_text_<?=$arQuestion["STRUCTURE"][0]["ID"]?>" placeholder="<?=$arQuestion["CAPTION"]?><?=($arQuestion["REQUIRED"]=="Y"?'*':'')?>" type="<?=($FIELD_SID == "PHONE")?'tel':'text'?>" <?=$arQuestion["STRUCTURE"][0]["FIELD_PARAM"]?> />
												<?}?>
											</div>
										</div>
									<?}?>
								<?}?>
								<?if($arParams["SHOW_LICENCE"] == "Y") {?>
									<div class="col-md-8 col-xs-12">
										<div class="form licence_custom">
											<div class="licence_block bx_filter">
												<input type="checkbox" id="licenses_inline_<?=$arResult["arForm"]["ID"];?>" <?if($arResult["isFormErrors"]=="Y"){?>checked<?}?> name="licenses_popup" value="Y">
												<label for="licenses_inline_<?=$arResult["arForm"]["ID"];?>">
													<?$APPLICATION->IncludeFile(SITE_DIR."include/licenses_text.php", Array(), Array("MODE" => "html", "NAME" => "LICENSES")); ?>
												</label>
											</div>
										</div>									
										<script type="text/javascript">
											$(document).ready(function(){
												$('[name=licenses_popup]').onoff();
												$(document).on('submit','form[name=<?=$arResult["arForm"]["SID"]?>]',function(){
													if($('form[name=<?=$arResult["arForm"]["SID"]?>] input[name=licenses_popup]').prop('checked')) {
														$('form[name=<?=$arResult["arForm"]["SID"]?>] .licence_block .licence_error').remove();
														return true;
													} else {
														$('form[name=<?=$arResult["arForm"]["SID"]?>] .licence_block .licence_error').remove();
														$('form[name=<?=$arResult["arForm"]["SID"]?>] .licence_block').append('<div class="licence_error">Согласитесь с условиями</div>');
														return false;
													}
												});
												$('form[name=<?=$arResult["arForm"]["SID"]?>] input[name=licenses_popup]').change(function() {
													$('form[name=<?=$arResult["arForm"]["SID"]?>] .licence_block .licence_error').remove();
												});													
											});
										</script>											
									</div>
								<?}?>
								<?if($arResult["isUseCaptcha"] == "Y") {?>
									<div class="col-md-4 col-xs-12">
										<div class="captcha-row clearfix">											
											<div class="captcha_image">
												<img src="/bitrix/tools/captcha.php?captcha_sid=<?=htmlspecialcharsbx($arResult["CAPTCHACode"])?>" border="0" />
												<input type="hidden" name="captcha_sid" value="<?=htmlspecialcharsbx($arResult["CAPTCHACode"])?>" />
												<div class="captcha_reload"></div>
											</div>
											<div class="captcha_input">
												<input type="text" class="inputtext captcha" name="captcha_word" size="30" maxlength="50" required />
											</div>
										</div>
									</div>
								<?}?>								
								<div class="col-md-4 col-xs-12">
									<div class="field button-container">
										<button class="btn btn-default btn-xs btn-transparent" name="web_form_submit"><?=$arResult["arForm"]["BUTTON"]?></button>
										<input type="hidden" name="web_form_apply" value="Y" />
									</div>
								</div>											
							</div>
						</div>
					<?=$arResult["FORM_FOOTER"]?>
				</div>
			</div>
		</div>
	</div>
	
	<script type="text/javascript">
		Recaptchafree.reset();
	</script>
<?}?>

<?//echo "<pre style='display:none;'>"; print_r($arResult); echo "</pre>";?>