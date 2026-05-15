<?global $USER;?>
<?if($USER->IsAuthorized()):?>
	<?
	$dbRes = CUser::GetList(($by = "id"), ($order = "asc"), array("ID" => $USER->GetID()), array("FIELDS" => array("ID", "PERSONAL_PHONE")));
	$arUser = $dbRes->Fetch();
	?>
	<script type="text/javascript">
	$(document).ready(function() {
		$('#one_click_buy_id_FIO').val('<?=$USER->GetFullName()?>');
		$('#one_click_buy_id_PHONE').val('<?=$arUser['PERSONAL_PHONE']?>');
		$('#one_click_buy_id_EMAIL').val('<?=$USER->GetEmail()?>');
        $('.phone').inputmask('mask', {'mask': arPriorityOptions['THEME']['PHONE_MASK'], 'showMaskOnHover': false });

        $('#POPUP_FIO').val('<?=$USER->GetFullName()?>').closest('.animated-labels').addClass('input-filed');
        $('#POPUP_PHONE').val('<?=$arUser['PERSONAL_PHONE']?>').closest('.animated-labels').addClass('input-filed');
        $('#POPUP_EMAIL').val('<?=$USER->GetEmail()?>').closest('.animated-labels').addClass('input-filed');
        $('.phone').inputmask('mask', {'mask': arPriorityOptions['THEME']['PHONE_MASK'], 'showMaskOnHover': false });
	});
	</script>
<?endif;?>