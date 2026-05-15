<?
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$USER_ID = 7634;
$data = getUtmByUserId($USER_ID);
print_r($data);
die;

$statDB = CDatabase::GetModuleConnection('statistic');

/*
$guest_id = 636033;
if ($rs = CGuest::GetByID($guest_id))
{
    $ar = $rs->Fetch();
    // выведем параметры посетителя
    echo "<pre>"; print_r($ar); echo "</pre>";
}

die;
*/
$arFilter = array("USER_ID" => "7606");
$arFilter = array("FIRST_REFERER1" => "organic");
$arFilter = array("ID" => "636033");
$arFilter = array("USER_ID" => "7606");
$arFilter = array(
	"USER_ID" => $_GET['USER_ID'],
	"CHECK_PERMISSIONS" => "N"
);
// получим список записей
$rs = CGuest::GetList(($by = "ID"), ($order = "DESC"),  $arFilter, $is_filtered);

// выведем все записи
$csv = array(
/*
	array(
		'ID' => 'ID',
		'USER_ID' => 'USER_ID',
		'NAME' => 'NAME',
		'UTM_MEDIUM' => 'utm_medium',
		'UTM_SOURCE' => 'utm_source',
		'UTM_CAMPAIGN' => 'utm_campaign',
		'UTM_CONTENT' => 'utm_content',
		'UTM_TERM' => 'utm_term',
		'FIRST_REFERER1' => 'FIRST_REFERER1',
		'FIRST_REFERER2' => 'FIRST_REFERER2',
	)
	*/
);

while ($ar = $rs->Fetch()) {
	//die;
	//LAST_USER_ID
	$url = $ar['FIRST_URL_TO'];
	parse_str(parse_url($url, PHP_URL_QUERY), $GET);
	//if ($ar['LAST_USER_ID'])
	$csv[] = array(
		'ID' => $ar['ID'],
		'USER_ID' => $ar['LAST_USER_ID'],
		'USER_NAME' => $ar['USER_NAME'], 
		'UTM_MEDIUM' => $GET['utm_medium'] ? $GET['utm_medium'] : $ar['FIRST_REFERER1'],
		'UTM_SOURCE' => $GET['utm_source'] ? $GET['utm_source'] : $ar['FIRST_REFERER2'],
		'UTM_CAMPAIGN' => $GET['utm_campaign'] ? $GET['utm_campaign'] : $ar['FIRST_REFERER3'],
		'UTM_CONTENT' => $GET['utm_content'],
		'UTM_TERM' => $GET['utm_term'],
		'FIRST_REFERER1' => $ar['FIRST_REFERER1'],
		'FIRST_REFERER2' => $ar['FIRST_REFERER2'],
	);
}


print_r(serialize($csv));
die;

$fh = fopen('php://output', 'w');

ob_start();
foreach ($csv as $row) {
	fputcsv($fh, $row);

}
$string = ob_get_clean();

$filename = date('d.m.Y H:i:s').'.csv';
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=$filename");
exit($string);





?>