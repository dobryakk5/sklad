<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if (count($arResult['FORMS']) <= 0)
{
	ShowNote(GetMessage('FRLM_NO_RESULTS'));
	return;
}
?>
<div class="waiting_list">
	<?
	if(count($arResult["FORMS"]) > 0) {
		foreach ($arResult["FORMS"] as $FORM_ID=>$arForm) {
			foreach($arResult["RESULTS"][$FORM_ID] as $arRes) {
				?>	
				<?$APPLICATION->IncludeComponent(
					"bitrix:form.result.view", 
					"waiting_list", 
					array(
						"COMPONENT_TEMPLATE" => "waiting_list",
						"RESULT_ID" => $arRes["ID"],
						"SEF_MODE" => "N",
						"SEF_FOLDER" => "",
						"SHOW_ADDITIONAL" => "N",
						"SHOW_ANSWER_VALUE" => "N",
						"SHOW_STATUS" => "N",
						"EDIT_URL" => "",
						"CHAIN_ITEM_TEXT" => "",
						"CHAIN_ITEM_LINK" => "",
						"SEF_URL_TEMPLATES" => array()
					),
					false
				);?>
				<?
			}
		}
	} else {
		?>
		<p>Заявки не найдены<p>
		<?
	}
	?>
</div>