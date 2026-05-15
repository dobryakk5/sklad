<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


<?
global $DinamicData;
$DinamicData = Array();

/*
$DinamicData["BALANCE"] = "";
ob_start();  

	if(strlen($arParams["USER_ID"]) > 0) {
		$rsUser = CUser::GetByID($arParams["USER_ID"]);
		$arUser = $rsUser->Fetch();
		echo intval($arUser["UF_USER_BALANCE"]);
	}

$DinamicData["BALANCE"] .= @ob_get_contents();
ob_get_clean(); 
$arResult["CACHED_TPL"] = preg_replace_callback(
    "/#BALANCE#/is".BX_UTF_PCRE_MODIFIER,
    create_function('$matches', 'ob_start();
    echo $GLOBALS["DinamicData"]["BALANCE"];   
    $retrunStr = @ob_get_contents();
    ob_get_clean();
    return $retrunStr;'),
    $arResult["CACHED_TPL"]);
*/
?>

<?// вывод
echo $arResult["CACHED_TPL"];
?>