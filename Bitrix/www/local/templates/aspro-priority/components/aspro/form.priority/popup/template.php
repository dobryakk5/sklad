<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
//\Bitrix\Main\UI\Extension::load('calendar');

file_put_contents(__DIR__ . '/log22.txt', print_r($arResult, true), FILE_APPEND);
?>
<? //$this->setFrameMode(false);
?>

<div class="form iblock_id_<?= $arParams["IBLOCK_ID"] ?> popup<?= ($arResult['isFormNote'] == 'Y' ? ' success' : '') ?><?= ($arResult['isFormErrors'] == 'Y' ? ' error' : '') ?>">
	<div class="wrap">
		<span class="jqmClose top-close fa fa-close dd"><?= CPriority::showIconSvg(SITE_TEMPLATE_PATH . '/images/include_svg/close.svg'); ?></span>

		<? if ($arResult["isFormNote"] == "Y") { ?>
			<?
			$successResult_title = GetMessage("SUCCESS_TITLE");
			$successResult_text = $arResult["FORM_NOTE"];
			if ($arParams["IBLOCK_ID"] == "30") {
				$successResult_title = "Спасибо!";
				$successResult_text = "Ваше сообщение отправлено службе по контролю качества.";
			}
			?>
			<div class="form-header">
				<div class="text">
					<div class="title"><?= $successResult_title ?></div>
					<div class="description">
						<svg class="success_icon" xmlns="http://www.w3.org/2000/svg" width="90" height="90" viewBox="0 0 90 90">
							<path id="Ellipse_273_copy" data-name="Ellipse 273 copy" class="clsp-1" d="M1550,151a45,45,0,1,1-45,45A45,45,0,0,1,1550,151Zm0,2a43,43,0,1,1-43,43A43,43,0,0,1,1550,153Z" transform="translate(-1505 -151)" />
							<path class="clsp-2" d="M1539.82,207.4a4.45,4.45,0,0,0,2.9,1.609c0.9,0.014,1.66-.434,2.93-1.854,1.53-1.692,23.35-24.3,23.35-24.3l-1-.852h-2l-2,2-20,21-10-10h-2l-2,1v2l2,4,4.9,3.372Z" transform="translate(-1505 -151)" />
							<path id="Rounded_Rectangle_840_copy_2" data-name="Rounded Rectangle 840 copy 2" class="clsp-1" d="M1545.41,212.678a4.006,4.006,0,0,1-4.33-.877h0l-10.91-10.88a4,4,0,0,1,5.66-5.646l8.08,8.057,20.23-21.172a4,4,0,0,1,5.66,5.646L1546.74,211.8A3.924,3.924,0,0,1,1545.41,212.678Zm22.97-26.283a2,2,0,1,0-2.83-2.823l-20.23,21.172-1.41,1.411-1.42-1.411-8.07-8.057a2,2,0,0,0-2.83,0,1.976,1.976,0,0,0,0,2.822l8.45,8.438,2.45,2.443a2.05,2.05,0,0,0,.66.438,2.005,2.005,0,0,0,2.17-.438l2.44-2.432Z" transform="translate(-1505 -151)" />
						</svg>
						<div class="success-text"><?= $successResult_text ?></div>
					</div>
				</div>
				<? if ($arParams["DISPLAY_CLOSE_BUTTON"] == "Y") { ?>
					<div class="button">
						<?= str_replace('class="', 'class="btn-lg ', $arResult["CLOSE_BUTTON"]) ?>
					</div>
				<? } ?>
			</div>

			<?
			//цели custom
			$targetName = "";
			if ($arParams["IBLOCK_ID"] == "30") {
				$targetName = "form_add_review";
			}
			if ($arParams["IBLOCK_ID"] == "20") {
				$targetName = "form_question";
			}
			if ($arParams["IBLOCK_ID"] == "21") {
				$targetName = "form_callback";
			}

			if (strlen($targetName) > 0) {
			?>
				<script>
					/*							
					gtag('event', 'send_form', {
					  'event_category' : '<?= $targetName ?>'
					});    
					yaCounter50400436.reachGoal('<?= $targetName ?>'); 
					*/
				</script>
			<?
			}

			/* Таргет Консалт Компани */
			$eventName = array(
				'30' => 'form_add_review', // Оставьте свой отзыв
				'20' => 'form_question', // Задать вопрос
				'21' => 'form_callback', // Обратный звонок
				'62' => 'storage_with_delivery', // Заказать хранение с доставкой
				'21' => 'form_callback', // Обратный звонок, отдельная станица
				'62' => 'turnkey_storage', // Заказать хранение "Под ключ"
			);
			if (isset($eventName[$arParams["IBLOCK_ID"]])):
				$json = json_encode(array(
					'event' => $eventName[$arParams["IBLOCK_ID"]],
					'formNumber' => $arParams["IBLOCK_ID"]
				));
			?>
				<script>
					dataLayer.push(<?= $json; ?>);
				</script>
			<? else: ?>



			<? endif; ?>
			<!--Цели -->

			<script>
				if (arPriorityOptions['THEME']['USE_FORMS_GOALS'] !== 'NONE') {
					var eventdata = {
						goal: 'goal_webform_success' + (arPriorityOptions['THEME']['USE_FORMS_GOALS'] === 'COMMON' ? '' : '_<?= $arParams["IBLOCK_ID"] ?>'),
						params: <?= CUtil::PhpToJSObject($arParams, false) ?>
					};
					BX.onCustomEvent('onCounterGoals', [eventdata]);
				}
				$(window).scroll();
			</script>
		<? } else { ?>
			<?= $arResult["FORM_HEADER"] ?>
			<div class="form-header" data-id="<?= $arParams["IBLOCK_ID"]; ?>">
				<div class="text">
					<? if ($arResult["isIblockTitle"] && $arParams["IBLOCK_ID"] != 21) { ?>
						<div class="title"><?= $arResult["IBLOCK_TITLE"] ?></div>
					<? } ?>
					<? if ($arResult["IBLOCK_DESCRIPTION"]): ?>
						<div class="description"><?= $arResult["IBLOCK_DESCRIPTION"] ?></div>
					<? endif; ?>
				</div>
			</div>
			<? if ($arResult['isFormErrors'] == 'Y'): ?>
				<div class="form-error alert alert-danger">
					<?= $arResult['FORM_ERRORS_TEXT'] ?>
				</div>
			<? endif; ?>
			<div class="form-body">
				<? if ($arResult["isUseCaptcha"] === "Y" && $arResult["isUseReCaptcha2"] === "Y"): ?>
					<div class="input <?= ($arResult['CAPTCHA_ERROR'] == 'Y' ? 'error' : '') ?>">
						<div class="g-recaptcha" data-sitekey="<?= RECAPTCHA_SITE_KEY ?>" data-callback="reCaptchaVerifyHidden" data-size="invisible"></div>
					</div>
				<? endif; ?>
				<? if (is_array($arResult["QUESTIONS"])): ?>

					<? foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion) { ?>
						<? if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden') {
							echo $arQuestion["HTML_CODE"];
						} else { ?>
							<div class="row priority_form_tpl" data-SID="<?= $FIELD_SID ?>">
								<div class="col-md-12">
									<div class="form-group <?= ($arQuestion['FIELD_TYPE'] != "file" ? "animated-labels" : ""); ?> <?= ($arQuestion['VALUE'] ? "input-filed" : ""); ?><?= ($arQuestion['FIELD_TYPE'] == 'list' ? "input-filed" : ""); ?>">
										<?= str_replace('for="', 'for="POPUP_', $arQuestion["CAPTION"]); ?>
										<div class="input">
											<? $arQuestion["HTML_CODE"] = str_replace('id="', 'id="POPUP_', $arQuestion["HTML_CODE"]) ?>
											<? if ($FIELD_SID == 'RATING'): ?>
												<div class="rating_wrap clearfix">
													<div class="rating">
														<span class="star" data-current_width="20" data-rating_value="1" data-message="<?= GetMessage('RATING_MESSAGE_1') ?>"></span>
														<span class="star" data-current_width="40" data-rating_value="2" data-message="<?= GetMessage('RATING_MESSAGE_2') ?>"></span>
														<span class="star" data-current_width="60" data-rating_value="3" data-message="<?= GetMessage('RATING_MESSAGE_3') ?>"></span>
														<span class="star" data-current_width="80" data-rating_value="4" data-message="<?= GetMessage('RATING_MESSAGE_4') ?>"></span>
														<span class="star" data-current_width="100" data-rating_value="5" data-message="<?= GetMessage('RATING_MESSAGE_5') ?>"></span>
														<span class="stars_current" data-rating="0"></span>
													</div>
													<div class="rating_message" data-message="<?= GetMessage('RATING_MESSAGE_0') ?>"><?= GetMessage('RATING_MESSAGE_0') ?></div>
												</div>
												<?= str_replace('type="text"', 'type="hidden"', $arQuestion["HTML_CODE"]) ?>
											<? else: ?>
												<?= $arQuestion["HTML_CODE"] ?>
											<? endif ?>
										</div>
										<? if (($arQuestion['FIELD_TYPE'] == "file" || $arQuestion['FIELD_TYPE'] == "text") && $arQuestion['MULTIPLE'] == 'Y'): ?>
											<div class="font_upper add_<?= $arQuestion['FIELD_TYPE'] ?>"><span>&plus; <?= GetMessage('JS_' . strtoupper($arQuestion['FIELD_TYPE']) . '_ADD'); ?></span></div>
										<? endif; ?>

										<? if (!empty($arQuestion["HINT"])) { ?>
											<div class="hint"><?= $arQuestion["HINT"] ?></div>
										<? } ?>
									</div>
								</div>
							</div>
					<? }
					} ?>
				<? endif; ?>
				<? if ($arResult["isUseCaptcha"] === "Y"): ?>
					<div class="captcha-row" style="position: initial;">
						<? if (false): ?>
							<?= $arResult["CAPTCHA_CAPTION"]; ?>
						<?php endif; ?>
						<div class="captcha_image" style="height:0">
							<img src="/bitrix/tools/captcha.php?captcha_sid=<?= htmlspecialcharsbx($arResult["CAPTCHACode"]) ?>" class="captcha_img" border="0" />
							<input type="hidden" name="captcha_sid" class="captcha_sid" value="<?= htmlspecialcharsbx($arResult["CAPTCHACode"]) ?>" />
							<? if (false): ?>
								<div class="captcha_reload"></div>
								<span class="refresh"><a href="javascript:;" rel="nofollow"><?= GetMessage("REFRESH") ?></a></span>
							<?php endif; ?>
						</div>
						<div class="captcha_input" style="position: initial;">
							<input type="text" class="inputtext form-control captcha" name="captcha_word" size="30" maxlength="50" value="" required />
						</div>
					</div>
				<? endif; ?>
			</div>
			<div class="form-footer clearfix">
				<? if ($arParams["SHOW_LICENCE"] == "Y"): ?>
					<div class="licence_block bx_filter">
						<input type="checkbox" id="licenses_popup" <?= (COption::GetOptionString("aspro.priority", "LICENCE_CHECKED", "N") == "Y" ? "checked" : ""); ?> name="licenses_popup" required value="Y">
						<label for="licenses_popup">
							<? $APPLICATION->IncludeFile(SITE_DIR . "include/licenses_text.php", array(), array("MODE" => "html", "NAME" => "LICENSES")); ?>
						</label>
					</div>
				<? endif; ?>
				<div class="">
					<?= str_replace('class="', 'class="btn-lg ', $arResult["SUBMIT_BUTTON"]) ?>
				</div>
			</div>
			<?= $arResult["FORM_FOOTER"] ?>
		<? } ?>
	</div>
</div>

<script>
	var bFormNote = <?= CUtil::PhpToJSObject($arResult['FORM_NOTE']); ?>;

	$(document).ready(function() {
		if (arPriorityOptions['THEME']['CAPTCHA_FORM_TYPE'] == 'RECAPTCHA' || arPriorityOptions['THEME']['CAPTCHA_FORM_TYPE'] == 'RECAPTCHA2') {
			reCaptchaRender();
		}
		$('.popup form[name="<?= $arResult["IBLOCK_CODE"] ?>"]').validate({
			ignore: ".ignore",
			highlight: function(element) {
				$(element).parent().addClass('error');
			},
			unhighlight: function(element) {
				$(element).parent().removeClass('error');
			},
			submitHandler: function(form) {
				if ($('.popup form[name="<?= $arResult["IBLOCK_CODE"] ?>"]').valid()) {
					$(form).find('button[type="submit"]').attr('disabled', 'disabled');
					var eventdata = {
						type: 'form_submit',
						form: form,
						form_name: '<?= $arResult["IBLOCK_CODE"] ?>'
					};
					BX.onCustomEvent('onSubmitForm', [eventdata]);
					if (!bFormNote) {
						$(form).prepend('<div class="overlay_form"><div class="loader"><div class="duo duo1"><div class="dot dot-a"></div><div class="dot dot-b"></div></div><div class="duo duo2"><div class="dot dot-a"></div><div class="dot dot-b"></div></div></div></div>');
					}
				}
			},
			errorPlacement: function(error, element) {
				if ($(element).hasClass('captcha')) {
					$(element).closest('.captcha-row').append(error);
				} else if ($(element).closest('.licence_block').length) {
					$(element).closest('.licence_block').append(error);
				} else if ($(element).closest('[data-sid=FILE]')) {
					$(element).closest('.form-group').append(error);
				} else {
					if ($(element).closest('.licence_block').length) {
						$(element).closest('.licence_block').append(error);
					} else if ($(element).closest('[data-sid=FILE]')) {
						$(element).closest('.form-group').append(error);
					} else {
						error.insertAfter(element);
					}
				}
			},
			messages: {
				licenses_popup: {
					required: BX.message('JS_REQUIRED_LICENSES')
				}
			}
		});

		if (arPriorityOptions['THEME']['PHONE_MASK'].length) {
			var base_mask = arPriorityOptions['THEME']['PHONE_MASK'].replace(/(\d)/g, '_');
			$('.popup form[name="<?= $arResult["IBLOCK_CODE"] ?>"] input.phone').inputmask('mask', {
				'mask': arPriorityOptions['THEME']['PHONE_MASK'],
				'showMaskOnHover': false
			});
			$('.popup form[name="<?= $arResult["IBLOCK_CODE"] ?>"] input.phone').blur(function() {
				if ($(this).val() == base_mask || $(this).val() == '') {
					if ($(this).hasClass('required')) {
						$(this).parent().find('div.error').html(BX.message('JS_REQUIRED'));
					}
				}
			});
		}

		if (arPriorityOptions['THEME']['DATE_MASK'].length)
			$('.popup form[name="<?= $arResult["IBLOCK_CODE"] ?>"] input.date').inputmask(arPriorityOptions['THEME']['DATE_MASK'], {
				'placeholder': arPriorityOptions['THEME']['DATE_PLACEHOLDER'],
				'showMaskOnHover': false
			});

		$('.jqmClose').closest('.jqmWindow').jqmAddClose('.jqmClose');

		<? if ($arParams["IBLOCK_ID"] == 62): ?>
			$('input[type=file]').uniform({
				fileButtonHtml: BX.message('JS_FILE_BUTTON_NAME'),
				fileDefaultHtml: BX.message('JS_FILE_DEFAULT_STORAGE')
			});
		<? else: ?>
			$('input[type=file]').uniform({
				fileButtonHtml: BX.message('JS_FILE_BUTTON_NAME'),
				fileDefaultHtml: BX.message('JS_FILE_DEFAULT')
			});
		<? endif; ?>
		$(document).on('change', 'input[type=file]', function() {
			if ($(this).val()) {
				$(this).closest('.uploader').addClass('files_add');
			} else {
				$(this).closest('.uploader').removeClass('files_add');
			}
		})
		$('.form .add_file').on('click', function() {
			<? if ($arParams["IBLOCK_ID"] == 62): ?>
				if (document.querySelectorAll('.form .uploader').length >= 5)
					return;
			<? endif; ?>

			var index = document.querySelectorAll('.form .uploader').length + 1;

			$(this).closest('.form-group').find('.input').append('<input type="file" id="POPUP_FILE" name="FILE_n' + index + '"   class="inputfile" value="" />');
			//$('<input type="file" id="POPUP_FILE" name="FILE_n'+index+'"   class="inputfile" value="" />').closest()($(this));
			<? if ($arParams["IBLOCK_ID"] == 62): ?>
				$('input[type=file]').uniform({
					fileButtonHtml: BX.message('JS_FILE_BUTTON_NAME'),
					fileDefaultHtml: BX.message('JS_FILE_DEFAULT_STORAGE')
				});
			<? else: ?>
				$('input[type=file]').uniform({
					fileButtonHtml: BX.message('JS_FILE_BUTTON_NAME'),
					fileDefaultHtml: BX.message('JS_FILE_DEFAULT')
				});
			<? endif; ?>

			<? if ($arParams["IBLOCK_ID"] == 62): ?>
				if (document.querySelectorAll('.form .uploader').length >= 5)
					$(this).hide();
			<? endif; ?>
		});

		$('.form .add_text').on('click', function() {
			var input = $(this).closest('.form-group').find('input[type=text]').first(),
				index = $(this).closest('.form-group').find('input[type=text]').length,
				name = input.attr('id').split('POPUP_')[1];

			$(this).closest('.form-group').find('.input').append('<input type="text" id="POPUP_' + name + '" name="' + name + '[' + index + ']"  class="form-control " value="" />');
		});

		//if(!addFormScript){
		/*setTimeout(function(){
			$('.fly_forms .button span.disabled').on('click', function(){
				console.log(123);
				$('.jqmWindow').jqmHide();
			});
		}, 800);*/
		//}
		addFormScript = true;
	});
</script>