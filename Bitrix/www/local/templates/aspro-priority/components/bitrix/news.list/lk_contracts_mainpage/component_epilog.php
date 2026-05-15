<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
\Bitrix\Main\Loader::includeModule("catalog");
global $USER;
global $DinamicData;
$DinamicData = Array();

//*******информация о балансе**********
/*
$rsUser = CUser::GetByID($USER->GetID());
$arUser = $rsUser->Fetch();
$DinamicData["BALANCE_INFO"] = '';
ob_start();  
?>
<div class="balance_info_mainpage">
	<div class="row">
		<div class="col-md-8 col-xs-12">
			<div class="info">
				Общий баланс <span><?=FormatCurrency($arUser["UF_USER_BALANCE"], "RUB")?></span> на <span><?=FormatDate("j F", date())?></span>
			</div>
		</div>
		<div class="col-md-4 col-xs-12">
			<div class="button">
				<a class="btn btn-default btn-transparent" href="/cabinet/balance/">Пополнить баланс</a>
			</div>
		</div>
	</div>
</div>
<?
$DinamicData["BALANCE_INFO"] .= @ob_get_contents();
ob_get_clean(); 
?>
<?$arResult["CACHED_TPL"] = preg_replace_callback(
    "/#BALANCE_INFO#/is".BX_UTF_PCRE_MODIFIER,
    create_function('$matches', 'ob_start();
    echo $GLOBALS["DinamicData"]["BALANCE_INFO"];   
    $retrunStr = @ob_get_contents();
    ob_get_clean();
    return $retrunStr;'),
    $arResult["CACHED_TPL"]);
*/
?>




<?// вывод
echo $arResult["CACHED_TPL"];
?>