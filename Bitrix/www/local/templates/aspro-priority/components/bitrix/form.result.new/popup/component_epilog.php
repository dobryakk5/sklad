<?global $USER;?>
<?Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("form-block".$arParams["WEB_FORM_ID"]);?>
<?if($USER->IsAuthorized()) {?>
	<?
	$dbRes = CUser::GetList(($by = "id"), ($order = "asc"), array("ID" => $USER->GetID()), array("FIELDS" => array("ID", "PERSONAL_PHONE")));
	$arUser = $dbRes->Fetch();
	?>
	<script type="text/javascript">
	$(document).ready(function() {
		try{
			//NAME
			if($('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=CLIENT_NAME]').length > 0) {
				$('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=CLIENT_NAME]').val('<?=$USER->GetFullName()?>');
				$('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=CLIENT_NAME]').parents('.form-group').addClass('input-filed');
			}
			if($('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=FIO]').length > 0) {
				$('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=FIO]').val('<?=$USER->GetFullName()?>');
				$('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=FIO]').parents('.form-group').addClass('input-filed');
			}
			if($('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=NAME]').length > 0) {
				$('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=NAME]').val('<?=$USER->GetFullName()?>');
				$('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=NAME]').parents('.form-group').addClass('input-filed');
			}			
			//PHONE
			if($('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=PHONE]').length > 0) {
				$('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=PHONE]').val('<?=$arUser["PERSONAL_PHONE"]?>');
				$('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=PHONE]').parents('.form-group').addClass('input-filed');
			}
			//EMAIL
			if($('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=EMAIL]').length > 0) {
				$('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=EMAIL]').val('<?=$USER->GetEmail()?>');
				$('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=EMAIL]').parents('.form-group').addClass('input-filed');
			}	
			//DATE_SEND
			if($('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=DATE_SEND]').length > 0) {
				$('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=DATE_SEND]').parents('.form-group').addClass('input-filed');
				$('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=DATE_SEND]').parents('.form-group').find('label').attr('for', $('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=DATE_SEND]').attr('id'));
				$('html').on('change', $('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=DATE_SEND]'), function() {
					$('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=DATE_SEND]').parents('.form-group').addClass('input-filed');
				})
			}			
			//DATE_CANCEL
			if($('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=DATE_CANCEL]').length > 0) {
				//$('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=DATE_CANCEL]').parents('.form-group').addClass('input-filed');
				$('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=DATE_CANCEL]').parents('.form-group').find('label').attr('for', $('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=DATE_CANCEL]').attr('id'));
				$('html').on('change', $('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=DATE_CANCEL]'), function() {
					$('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=DATE_CANCEL]').parents('.form-group').addClass('input-filed');
				})
			}
			//DATE
			if($('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=DATE]').length > 0) {
				$('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=DATE]').parents('.form-group').addClass('input-filed');
				$('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=DATE]').parents('.form-group').find('label').attr('for', $('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=DATE]').attr('id'));
				$('html').on('change', $('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=DATE]'), function() {
					$('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=DATE]').parents('.form-group').addClass('input-filed');
				})
			}			
		}
		catch(e){
		}
	});
	</script>
<?} else {?>
	<script type="text/javascript">
	$(document).ready(function() {
		try{
			//DATE_SEND
			if($('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=DATE_SEND]').length > 0) {
				$('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=DATE_SEND]').parents('.form-group').addClass('input-filed');
				$('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=DATE_SEND]').parents('.form-group').find('label').attr('for', $('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=DATE_SEND]').attr('id'));
				$('html').on('change', $('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=DATE_SEND]'), function() {
					$('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=DATE_SEND]').parents('.form-group').addClass('input-filed');
				})
			}			
			//DATE_CANCEL
			if($('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=DATE_CANCEL]').length > 0) {
				//$('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=DATE_CANCEL]').parents('.form-group').addClass('input-filed');
				$('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=DATE_CANCEL]').parents('.form-group').find('label').attr('for', $('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=DATE_CANCEL]').attr('id'));
				$('html').on('change', $('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=DATE_CANCEL]'), function() {
					$('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=DATE_CANCEL]').parents('.form-group').addClass('input-filed');
				})
			}		
			//DATE
			if($('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=DATE]').length > 0) {
				$('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=DATE]').parents('.form-group').addClass('input-filed');
				$('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=DATE]').parents('.form-group').find('label').attr('for', $('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=DATE]').attr('id'));
				$('html').on('change', $('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=DATE]'), function() {
					$('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=DATE]').parents('.form-group').addClass('input-filed');
				})
			}			
		}
		catch(e){
		}
	});
	</script>
<?}?>
<?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("form-block".$arParams["WEB_FORM_ID"], "");?>