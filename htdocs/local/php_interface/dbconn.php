<?
define("NO_BITRIX_AUTOLOAD",true);
define('BX_CACHE_TYPE', 'apc');
define('BX_CACHE_SID', $_SERVER["DOCUMENT_ROOT"].'#01');
define('BX_MEMCACHE_HOST', '127.0.0.1');
define('BX_MEMCACHE_PORT', '11211');


define("DBPersistent", true);
$DBType = "mysql";

$DBHost = "localhost";

$DBLogin = "klavazip";
$DBPassword = "4jPK9ZQX";

$DBName = "klavazip";

$DBDebug = false;
$DBDebugToFile = false;

@set_time_limit(60);

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
define("BX_FILE_PERMISSIONS", 0777);
define("BX_DIR_PERMISSIONS", 0777);
@umask(~BX_DIR_PERMISSIONS);
@ini_set("memory_limit", "384M");
define("BX_DISABLE_INDEX_PAGE", true);

define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/bitrix_log.txt");

define("BX_COMP_MANAGED_CACHE", true);

ini_set('realpath_cache_size', '8M');

define('BX_CACHE_TYPE', 'memcache');
define('BX_CACHE_SID', $_SERVER["DOCUMENT_ROOT"].'#01');
define('BX_MEMCACHE_HOST', '127.0.0.1');
define('BX_MEMCACHE_PORT', '11211');
/*
define("DBPersistent",false);
define("MYSQL_TABLE_TYPE", "InnoDB"); */
