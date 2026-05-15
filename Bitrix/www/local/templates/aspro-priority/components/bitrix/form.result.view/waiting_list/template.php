<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="item lk_table">
	<table class="table">
		<thead>
			<tr>
				<th colspan="2">
					<span class="name">Заявка №<?=$arResult["RESULT_ID"]?> от <?=$arResult["RESULT_DATE_CREATE"]?></span>
					<span class="delete" title="Удалить заявку" data-id="<?=$arResult["RESULT_ID"]?>" data-action="<?=$templateFolder?>/ajax.php">&#10006;</span>
				</th>
			</tr>
		</thead>
		<tbody>
			<?
			foreach ($arResult["RESULT"] as $FIELD_SID => $arQuestion) {?>
				<tr>
					<td>
						<?=$arQuestion["CAPTION"]?>	
					</td>
					<td>
						<?if (is_array($arQuestion['ANSWER_VALUE'])) {
							foreach ($arQuestion['ANSWER_VALUE'] as $key => $arAnswer) {?>
								<?if(strlen($arAnswer["USER_TEXT"]) > 0) {?>
									<?=$arAnswer["USER_TEXT"]?>
								<?}?>
							<?}
						}?>
					</td>
				</tr>
			<?}?>
		</tbody>
	</table>
</div>	