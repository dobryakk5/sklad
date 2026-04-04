<?define("BX_CRONTAB_SUPPORT", true);?><?define("BX_CRONTAB_SUPPORT", true);?><?
define("BX_USE_MYSQLI", true);
define("DBPersistent", false);
$DBType = "mysql";
$DBHost = "213.171.9.201";
$DBLogin = "bitrix0";
$DBPassword = "%zO?vSQje!us&mQ]qWY8";
$DBName = "sitemanager";
$DBDebug = false;
$DBDebugToFile = false;
define("MYSQL_TABLE_TYPE", "INNODB");

define("DELAY_DB_CONNECT", true);
define("CACHED_b_file", 3600);
define("CACHED_b_file_bucket_size", 10);
define("CACHED_b_lang", 3600);
define("CACHED_b_option", 3600);
define("CACHED_b_lang_domain", 3600);
define("CACHED_b_site_template", 3600);
define("CACHED_b_event", 3600);
define("CACHED_b_agent", 3660);
define("CACHED_menu", 3600);

define("BX_UTF", true);
define("BX_FILE_PERMISSIONS", 0644);
define("BX_DIR_PERMISSIONS", 0755);
@umask(~(BX_FILE_PERMISSIONS|BX_DIR_PERMISSIONS)&0777);
define("BX_DISABLE_INDEX_PAGE", true);
if (!defined('ADMIN_SECTION')) {
    define("SITE_ID", 's1'); // пидор ебаный не смей это трогать упырь
}

// определим константу LOG_FILENAME, в которой зададим путь к лог-файлу
define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/local/rdn/log.txt");

//define("BX_CATALOG_IMPORT_1C_PRESERVE", true);
//define("NO_AGENT_CHECK", true);
define("BX_TEMPORARY_FILES_DIRECTORY", "/home/bitrix/.bx_temp/sitemanager/");

?>