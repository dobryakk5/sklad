<?global $USER;?>
<?Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("form-iblock".$arParams["IBLOCK_ID"]);?>
<?if($USER->IsAuthorized()):?>
    <?
    $dbRes = CUser::GetList(($by = "id"), ($order = "asc"), array("ID" => $USER->GetID()), array("FIELDS" => array("ID", "PERSONAL_PHONE")));
    $arUser = $dbRes->Fetch();
    ?>
    <script type="text/javascript">
    $(document).ready(function() {
        try {
			//NAME
			if($('.form.iblock_id_<?=$arParams["IBLOCK_ID"]?> input[name=CLIENT_NAME]').length > 0) {
				$('.form.iblock_id_<?=$arParams["IBLOCK_ID"]?> input[name=CLIENT_NAME]').val('<?=$USER->GetFullName()?>');
				$('.form.iblock_id_<?=$arParams["IBLOCK_ID"]?> input[name=CLIENT_NAME]').parents('.form-group').addClass('input-filed');
			}
			if($('.form.iblock_id_<?=$arParams["IBLOCK_ID"]?> input[name=FIO]').length > 0) {
				$('.form.iblock_id_<?=$arParams["IBLOCK_ID"]?> input[name=FIO]').val('<?=$USER->GetFullName()?>');
				$('.form.iblock_id_<?=$arParams["IBLOCK_ID"]?> input[name=FIO]').parents('.form-group').addClass('input-filed');
			}	
			if($('.form.iblock_id_<?=$arParams["IBLOCK_ID"]?> input[name=NAME]').length > 0) {
				$('.form.iblock_id_<?=$arParams["IBLOCK_ID"]?> input[name=NAME]').val('<?=$USER->GetFullName()?>');
				$('.form.iblock_id_<?=$arParams["IBLOCK_ID"]?> input[name=NAME]').parents('.form-group').addClass('input-filed');
			}
			//PHONE
			if($('.form.iblock_id_<?=$arParams["IBLOCK_ID"]?> input[name=PHONE]').length > 0) {
				$('.form.iblock_id_<?=$arParams["IBLOCK_ID"]?> input[name=PHONE]').val('<?=$arUser["PERSONAL_PHONE"]?>');
				$('.form.iblock_id_<?=$arParams["IBLOCK_ID"]?> input[name=PHONE]').parents('.form-group').addClass('input-filed');
			}
			//EMAIL
			if($('.form.iblock_id_<?=$arParams["IBLOCK_ID"]?> input[name=EMAIL]').length > 0) {
				$('.form.iblock_id_<?=$arParams["IBLOCK_ID"]?> input[name=EMAIL]').val('<?=$USER->GetEmail()?>');
				$('.form.iblock_id_<?=$arParams["IBLOCK_ID"]?> input[name=EMAIL]').parents('.form-group').addClass('input-filed');
			}	
			
			/*
            $('.form.iblock_id_<?=$arParams["IBLOCK_ID"]?> input[name=CLIENT_NAME], .form.iblock_id_<?=$arParams["IBLOCK_ID"]?> input[name=FIO], .form.iblock_id_<?=$arParams["IBLOCK_ID"]?> input[name=NAME]').val('<?=$USER->GetFullName()?>');
            $('.form.iblock_id_<?=$arParams["IBLOCK_ID"]?> input[name=PHONE]').val('<?=$arUser['PERSONAL_PHONE']?>');
            $('.form.iblock_id_<?=$arParams["IBLOCK_ID"]?> input[name=EMAIL]').val('<?=$USER->GetEmail()?>');
			*/
        }
        catch(e){
        }
    });
    </script>
<?endif;?>
<?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("form-iblock".$arParams["IBLOCK_ID"], "");?> 