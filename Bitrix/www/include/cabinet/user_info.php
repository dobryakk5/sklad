<?
global $USER;
?>
<?if($USER->IsAuthorized()) {?>
	<div class="lk_user_info" style="margin-bottom: 30px; font-size: 1.5em;">
		<? /*Ваш E-mail: */ ?><span style="color: #333;"><?=$USER->GetFirstName()?> <?=$USER->GetSecondName()?> <?=$USER->GetLastName()?></span>
	</div>
<?}?>