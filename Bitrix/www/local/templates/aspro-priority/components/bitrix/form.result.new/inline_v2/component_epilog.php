<?global $USER;?>
<?Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("form-block".$arParams["WEB_FORM_ID"]);?>
<?if($USER->IsAuthorized()):?>
	<?
	$dbRes = CUser::GetList(($by = "id"), ($order = "asc"), array("ID" => $USER->GetID()), array("FIELDS" => array("ID", "SECOND_NAME", "PERSONAL_PHONE")));
	$arUser = $dbRes->Fetch();
	?>
	<script type="text/javascript">
	$(document).ready(function() {
		try{
			//LAST_NAME
			if($('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=LAST_NAME]').length > 0) {
				$('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=LAST_NAME]').val('<?=$USER->GetLastName()?>');
				$('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=LAST_NAME]').parents('.form-group').addClass('input-filed');
			}
			//NAME
			if($('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=NAME]').length > 0) {
				$('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=NAME]').val('<?=$USER->GetFirstName()?>');
				$('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=NAME]').parents('.form-group').addClass('input-filed');
			}
			//SECOND_NAME
			if($('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=SECOND_NAME]').length > 0) {
				$('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=SECOND_NAME]').val('<?=$arUser["SECOND_NAME"]?>');
				$('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=SECOND_NAME]').parents('.form-group').addClass('input-filed');
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
		}
		catch(e){
		}
	});
	</script>
<?endif;?>
<?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("form-block".$arParams["WEB_FORM_ID"], "");?>