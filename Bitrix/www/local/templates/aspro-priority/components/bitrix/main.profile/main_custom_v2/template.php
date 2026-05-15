<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<div class="module-form-block-wr lk-page border_block">

<script>
	$(document).ready(function()
	{
		$("form.main-form").validate({rules:{ EMAIL: { email: true }}	});
		if(arPriorityOptions['THEME']['PHONE_MASK'].length){
			var base_mask = arPriorityOptions['THEME']['PHONE_MASK'].replace( /(\d)/g, '_' );
			$('.lk-page input.phone').inputmask('mask', {'mask': arPriorityOptions['THEME']['PHONE_MASK'], 'showMaskOnHover': false });
			$('.lk-page input.phone').blur(function(){
				if( $(this).val() == base_mask || $(this).val() == '' ){
					if( $(this).hasClass('required') ){
						$(this).parent().find('label.error').html(BX.message('JS_REQUIRED'));
					}
				}
			});
		}
	})
</script>
	<?global $arTheme;?>
	<div class="form profile_info">
		<?if($arResult["strProfileError"] || $arResult['DATA_SAVED'] == 'Y'):?>
			<div class="top-form messages">
				<?ShowError($arResult["strProfileError"]);?>
				<?if( $arResult['DATA_SAVED'] == 'Y' ) {?><?ShowNote(GetMessage('PROFILE_DATA_SAVED'))?><?; }?>
			</div>
		<?endif;?>
		<div class="top-form">
			<div class="main-form">
				<?=$arResult["BX_SESSION_CHECK"]?>
				<input type="hidden" name="LOGIN" maxlength="50" value="<? echo $arResult["arUser"]["LOGIN"]?>" />
				<input type="hidden" name="lang" value="<?=LANG?>" />
				<input type="hidden" name="ID" value=<?=$arResult["ID"]?> />
				<?if($arTheme['CABINET']['DEPENDENT_PARAMS']['PERSONAL_ONEFIO']['VALUE'] != 'N'):?>
					<?
					$arName = array();
					$strName = '';
					if($arResult["arUser"]["LAST_NAME"]){
						$arName[] = $arResult["arUser"]["LAST_NAME"];
					}
					if($arResult["arUser"]["NAME"]){
						$arName[] = $arResult["arUser"]["NAME"];
					}
					if($arResult["arUser"]["SECOND_NAME"]){
						$arName[] = $arResult["arUser"]["SECOND_NAME"];
					}
					$strName = implode(' ', $arName);
					?>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group animated-labels <?=($strName ? 'input-filed' : '');?>">
								<div class="wrap_md">
									<div class="iblock label_block">
										<label for="NAME"><?=GetMessage("PERSONAL_FIO")?><span class="required-star">*</span></label>
										<div class="input">
											<input required type="text" class="form-control" name="NAME" id="NAME" maxlength="50" value="<?=$strName;?>" />
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?else:?>
					<div class="row">
						<div class="col-md-6">							
							<div class="field_name"><?=GetMessage("PERSONAL_LASTNAME")?>:</div>
						</div>					
						<div class="col-md-6">
							<div class="input">
								<?=$arResult["arUser"]["LAST_NAME"]?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group animated-labels <?=($arResult["arUser"]["NAME"] ? 'input-filed' : '');?>">
								<div class="wrap_md">
									<div class="iblock label_block">
										<label for="NAME"><?=GetMessage("PERSONAL_NAME")?><span class="required-star">*</span></label>
										<div class="input">
											<input required type="text" class="form-control" name="NAME" id="NAME" maxlength="50" value="<?=$arResult["arUser"]["NAME"];?>" />
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group animated-labels <?=($arResult["arUser"]["SECOND_NAME"] ? 'input-filed' : '');?>">
								<div class="wrap_md">
									<div class="iblock label_block">
										<label for="SECOND_NAME"><?=GetMessage("PERSONAL_FATHERNAME")?></label>
										<div class="input">
											<input type="text" class="form-control" name="SECOND_NAME" id="SECOND_NAME" maxlength="50" value="<?=$arResult["arUser"]["SECOND_NAME"];?>" />
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?endif;?>
				<?if($arTheme["LOGIN_EQUAL_EMAIL"]["VALUE"] != "Y"):?>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group animated-labels <?=($arResult["arUser"]["LOGIN"] ? 'input-filed' : '');?>">
								<div class="wrap_md">
									<div class="iblock label_block">
										<label for="LOGIN"><?=GetMessage("PERSONAL_LOGIN")?><span class="required-star">*</span></label>
										<div class="input">
											<input required type="text" name="LOGIN" id="LOGIN" maxlength="50" class="form-control" value="<? echo $arResult["arUser"]["LOGIN"]?>" />
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?endif;?>
				
				<div class="row">
					<div class="col-md-6">
						<div class="form-group animated-labels <?=($arResult["arUser"]["EMAIL"] ? 'input-filed' : '');?>">
							<div class="wrap_md">
								<div class="iblock label_block">
									<label for="EMAIL"><?=GetMessage("PERSONAL_EMAIL")?><span class="required-star">*</span></label>
									<div class="input">
										<input required type="text" name="EMAIL" id="EMAIL" maxlength="50" class="form-control" value="<? echo $arResult["arUser"]["EMAIL"]?>" />
									</div>
								</div>
							</div>
						</div>
					</div>
					<?if($arTheme["LOGIN_EQUAL_EMAIL"]["VALUE"] == "Y"):?>
						<div class="col-md-6">
							<div class="text_block"><?=GetMessage('PERSONAL_EMAIL_DESCRIPTION');?></div>
						</div>
					<?endif;?>					
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group animated-labels <?=($arResult["arUser"]["PERSONAL_PHONE"] ? 'input-filed' : '');?>">
							<div class="wrap_md">
								<div class="iblock label_block">
									<label for="PERSONAL_PHONE"><?=GetMessage("PERSONAL_PHONE")?><span class="required-star">*</span></label>
									<div class="input">
										<input required type="text" name="PERSONAL_PHONE" id="PERSONAL_PHONE" class="phone form-control" maxlength="255" value="<?=$arResult["arUser"]["PERSONAL_PHONE"]?>" />
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="but-r">
					<span class="btn btn-default btn-lg">Отправить заявку на редактирование</span>
				</div>
				
			</div>
		</div>
		<? if($arResult["SOCSERV_ENABLED"]){ $APPLICATION->IncludeComponent("bitrix:socserv.auth.split", "main", array("SUFFIX"=>"form", "SHOW_PROFILES" => "Y","ALLOW_DELETE" => "Y"),false);}?>
	</div>
</div>