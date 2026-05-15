<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<div class="module-form-block-wr lk-page border_block">
	<?global $arTheme;?>
	<div class="form">
		<?if($arResult["strProfileError"] || $arResult['DATA_SAVED'] == 'Y'):?>
			<div class="top-form messages">
				<?ShowError($arResult["strProfileError"]);?>
				<?if( $arResult['DATA_SAVED'] == 'Y' ) {?><?ShowNote(GetMessage('PROFILE_DATA_SAVED'))?><?; }?>
			</div>
		<?endif;?>
		<div class="top-form">
			<form name="form1" class="main-form">
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
										<label for="NAME"><?=GetMessage("PERSONAL_FIO")?></label>
										<div class="input">
											<input type="text" class="form-control" name="NAME" id="NAME" maxlength="50" value="<?=$strName;?>" disabled />
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?else:?>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group animated-labels <?=($arResult["arUser"]["LAST_NAME"] ? 'input-filed' : '');?>">
								<div class="wrap_md">
									<div class="iblock label_block">
										<label for="LAST_NAME"><?=GetMessage("PERSONAL_LASTNAME")?></label>
										<div class="input">
											<input type="text" class="form-control" name="LAST_NAME" id="LAST_NAME" maxlength="50" value="<?=$arResult["arUser"]["LAST_NAME"];?>" disabled />
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group animated-labels <?=($arResult["arUser"]["NAME"] ? 'input-filed' : '');?>">
								<div class="wrap_md">
									<div class="iblock label_block">
										<label for="NAME"><?=GetMessage("PERSONAL_NAME")?></label>
										<div class="input">
											<input type="text" class="form-control" name="NAME" id="NAME" maxlength="50" value="<?=$arResult["arUser"]["NAME"];?>" disabled />
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
											<input type="text" class="form-control" name="SECOND_NAME" id="SECOND_NAME" maxlength="50" value="<?=$arResult["arUser"]["SECOND_NAME"];?>" disabled />
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
										<label for="LOGIN"><?=GetMessage("PERSONAL_LOGIN")?></label>
										<div class="input">
											<input type="text" name="LOGIN" id="LOGIN" maxlength="50" class="form-control" value="<? echo $arResult["arUser"]["LOGIN"]?>" disabled />
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
									<label for="EMAIL"><?=GetMessage("PERSONAL_EMAIL")?></label>
									<div class="input">
										<input type="text" name="EMAIL" id="EMAIL" maxlength="50" class="form-control" value="<? echo $arResult["arUser"]["EMAIL"]?>" disabled />
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
				
				<? /* Раскомментировали телефон */ ?>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group animated-labels <?=($arResult["arUser"]["PERSONAL_PHONE"] ? 'input-filed' : '');?>">
							<div class="wrap_md">
								<div class="iblock label_block">
									<label for="PERSONAL_PHONE"><?=GetMessage("PERSONAL_PHONE")?></label>
									<div class="input">
										<input type="text" name="PERSONAL_PHONE" id="PERSONAL_PHONE" class="phone form-control" maxlength="255" value="<?=$arResult["arUser"]["PERSONAL_PHONE"]?>" disabled />
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

                <?
                //d($arResult["ADDITIONAL_FIELDS"]);
                ?>
				<?foreach($arResult["ADDITIONAL_FIELDS"] as $arField) {?>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group animated-labels <?=($arField["VALUE"] ? 'input-filed' : '');?>">
								<div class="wrap_md">
									<div class="iblock label_block">
										<label for="PERSONAL_PHONE"><?=$arField["PROP_NAME"]?></label>
										<div class="input">
											<input type="text" class="form-control" maxlength="255" value="<?=$arField["VALUE"]?>" disabled />
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>				
				<?}?>
				
				<div class="info">
					<br>
					<p>Если хотите отредактировать поля, то отправьте заявку менеджеру через форму <a href="#" title="Написать сообщение" data-event="jqm" data-param-id="20" data-name="question">«Написать сообщение»</a>.</p>
				</div>
				
			</form>
		</div>
	</div>
</div>