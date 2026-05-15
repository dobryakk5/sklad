<?
global $USER;

$rsUser = CUser::GetByID($USER->GetID());
$arUser = $rsUser->Fetch();
?>
<style>
.notify_switch_block .switch_item .onoffswitch {
	float: left;
}
.notify_switch_block .switch_item label {
	margin-top: -3px;
	margin-left: 10px;
}
</style>

<h3>Управление рассылками</h3>

<div class="notify_switch_block">
	<div class="bx_filter switch_item">
		<div class="onoffswitch">
			<input type="checkbox" id="email_notify" value="Y" class="onoffswitch-checkbox" <?if($arUser["UF_EMAIL_NOTIFY"]=="1"){?>checked="checked"<?}?>>
			<label for="email_notify" class="onoffswitch-label">
				<span class="onoffswitch-inner"></span>
				<span class="onoffswitch-switch"></span>
			</label>
		</div>
		<label for="email_notify">
			Получать E-mail рассылку
		</label>
	</div>

	<div class="bx_filter switch_item">
		<div class="onoffswitch">
			<input type="checkbox" id="sms_notify" value="Y" class="onoffswitch-checkbox" <?if($arUser["UF_SMS_NOTIFY"]=="1"){?>checked="checked"<?}?>>
			<label for="sms_notify" class="onoffswitch-label">
				<span class="onoffswitch-inner"></span>
				<span class="onoffswitch-switch"></span>
			</label>
		</div>
		<label for="sms_notify">
			Получать SMS рассылку
		</label>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('html').on('change', '.notify_switch_block input[type="checkbox"]', function() {
		var btn = $(this);
		var action = '';
		var value = 1;
		if($(btn).attr('id') == 'email_notify') {
			action = 'EMAIL_NOTIFY';
		}
		if($(btn).attr('id') == 'sms_notify') {
			action = 'SMS_NOTIFY';
		}
		if($(btn).prop('checked')) {
			value = 1;
		} else {
			value = 0;
		}
		
		if($(btn).attr("data-ctrl") != "Y") {
			$(btn).attr("data-ctrl", "Y");
            $.post("/ajax/notify_switch.php", {ACTION:action, "VALUE":value}, function(data){
                $(btn).attr("data-ctrl", "N");

            });			
		}
	});
});
</script>


