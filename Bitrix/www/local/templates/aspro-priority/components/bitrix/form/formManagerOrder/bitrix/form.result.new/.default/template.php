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
			dataLayer.push({'event': 'form_formManagerOrder'});    
		</script>	
	<?}?>
<?}?>


<?=$arResult["FORM_NOTE"]?>

<?if ($arResult["isFormNote"] != "Y") {?>
	<div class="form-managerOrder">
		<div class="row">
			<div class="col-md-7 col-xs-12">
				<p class="style-h3"><?=$arResult["FORM_TITLE"]?></p>
				<div class="text">
					<?=$arResult["FORM_DESCRIPTION"]?>
				</div>
				
				<?if((strlen($arParams["PHONE"]) > 0) or (strlen($arParams["EMAIL"]) > 0)) {?>
					<div class="contacts">
						<div class="row">
							<?if(strlen($arParams["PHONE"]) > 0) {?>
								<?
								$phone = preg_replace('/[^\d+]/', '', $arParams["PHONE"]);
								?>
								<div class="col-md-6">
									<a class="phone" href="tel:<?=$phone?>"><?=$arParams["PHONE"]?></a>
								</div>
							<?}?>
							<?if(strlen($arParams["EMAIL"]) > 0) {?>
								<div class="col-md-6">
									<a class="email" href="mailto:<?=$arParams["EMAIL"]?>"><?=$arParams["EMAIL"]?></a>
								</div>
							<?}?>
						</div>
					</div>
				<?}?>
				
				<div class="form-container">
					<?=$arResult["FORM_HEADER"]?>
						<div class="title">Заявка через менеджера</div>
						<div class="form-fields">
							<?if ($arResult["isFormErrors"] == "Y") {?>
								<?=$arResult["FORM_ERRORS_TEXT"];?>
							<?}?>						
							<div class="row">
								<?foreach ($arResult["QUESTIONS"] as $FIELD_SID=>$arQuestion) {?>	
									<div class="col-md-6 col-xs-12" style="margin-bottom: 10px;">
										<input value="<?=$arResult["arrVALUES"]["form_text_".$arQuestion["STRUCTURE"][0]["ID"]]?>" name="form_text_<?=$arQuestion["STRUCTURE"][0]["ID"]?>" placeholder="<?=$arQuestion["CAPTION"]?>" type="<?=($FIELD_SID == "PHONE")?'tel':'text'?>" />
									</div>
								<?}?>								
								<?if($arParams["SHOW_LICENCE"] == "Y") {?>
									<div class="col-md-6 col-xs-12">
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
									<div class="col-md-6 col-xs-12">
										<div class="captcha-row clearfix">											
											<div class="captcha_image">
												<img src="/bitrix/tools/captcha.php?captcha_sid=<?=htmlspecialcharsbx($arResult["CAPTCHACode"])?>" border="0" />
												<input type="hidden" name="captcha_sid" value="<?=htmlspecialcharsbx($arResult["CAPTCHACode"])?>" />
												<div class="captcha_reload"></div>
											</div>
											<div class="captcha_input">
												<input type="text" class="inputtext captcha" name="captcha_word" size="30" maxlength="50" value="" required />
											</div>
										</div>
									</div>
								<?}?>									
								<div class="col-md-6 col-xs-12">
									<button class="btn btn-default btn-xs" name="web_form_submit"><?=$arResult["arForm"]["BUTTON"]?></button>
									<input type="hidden" name="web_form_apply" value="Y" />
								</div>								
							</div>
						</div>
					<?=$arResult["FORM_FOOTER"]?>
				</div>
			</div>
			<div class="col-md-5 hidden-xs">
				<div class="text-right pictureInForm">								
					<div class="thumbnail"><img src="<?=$arResult["FORM_IMAGE"]["URL"]?>" /></div>								
				</div>
			</div>
		</div>
	</div>
	
	<script type="text/javascript">
		Recaptchafree.reset();
	</script>	
<?}?>