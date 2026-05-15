<?global $USER;?>
<?Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("form-block".$arParams["WEB_FORM_ID"]);?>
<?if($USER->IsAuthorized()):?>
	<?
	$dbRes = CUser::GetList(($by = "id"), ($order = "asc"), array("ID" => $USER->GetID()), array("FIELDS" => array("ID", "PERSONAL_PHONE")));
	$arUser = $dbRes->Fetch();
	?>
	<script type="text/javascript">
	jQuery(document).ready(function() {
		try{
			jQuery('form[name=<?=$arResult["arForm"]["SID"]?>] input[data-sid=PHONE]').val('<?=$arUser['PERSONAL_PHONE']?>');			
		}
		catch(e){
			console.log(e);
		}
	});
	</script>
<?endif;?>
<script type="text/javascript">
BX.addCustomEvent('onAjaxSuccess', function(){
	Array.prototype.forEach.call(document.querySelectorAll('form[name=<?=$arResult["arForm"]["SID"]?>]'), function(el){
		el.classList.add('webform');
	});
})
document.addEventListener('DOMContentLoaded',function() { 
	Array.prototype.forEach.call(document.querySelectorAll('form[name=<?=$arResult["arForm"]["SID"]?>]'), function(el){
		el.classList.add('webform');
	});
});
</script>
<?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("form-block".$arParams["WEB_FORM_ID"], "");?>