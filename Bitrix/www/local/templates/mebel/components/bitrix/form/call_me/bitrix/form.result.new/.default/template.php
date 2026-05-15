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
<?
if($arResult["isFormErrors"] == "Y"):
?>
<div class="form_result error">
	<?=$arResult["FORM_ERRORS_TEXT"];?>
</div>
<?
endif;
?>
<?
if(mb_strtoupper($_REQUEST['formresult']) == "ADDOK"):
?>
<div class="form_result success">
	<?=GetMessage("NOTE_ADDOK")?>
</div>
<?
endif;
?>
<?if ($arResult["isFormNote"] != "Y") {?>
				<?=$arResult["FORM_HEADER"]?>
				
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
				
					<?foreach ($arResult["QUESTIONS"] as $FIELD_SID=>$arQuestion) {?>
						<label class="input-label">
							<div class="input-border">
								<input <?=$arQuestion["STRUCTURE"][0]["FIELD_PARAM"]?>  data-sid="<?=$FIELD_SID?>" class="<?=($FIELD_SID == "PHONE")?'phone-number ':''?>input-field" value="<?=$arResult["arrVALUES"]["form_text_".$arQuestion["STRUCTURE"][0]["ID"]]?>" name="form_text_<?=$arQuestion["STRUCTURE"][0]["ID"]?>" type="<?=($FIELD_SID == "PHONE")?'tel':'text'?>" />
							</div>
						</label>
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
				<button class="btn <?=$arParams["BTN_CLASS"]?>" type="submit" name="web_form_submit"><?=$arResult["arForm"]["BUTTON"]?></button>
				<input type="hidden" name="web_form_apply" value="Y" />
				<?=$arResult["FORM_FOOTER"]?>		
	
<?}?>