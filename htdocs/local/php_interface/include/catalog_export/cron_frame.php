#!/usr/bin/php -q
<?php
$_SERVER["DOCUMENT_ROOT"] = "/home/www/klavazip.ru/www"; //"/home/webmaster/www/vps88598.vps.tech-logol.ru/";

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS",true);
set_time_limit (0);
define("LANG","ru");
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

$profile_id = $argv[1];

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if (CModule::IncludeModule("catalog"))
{
	$profile_id = IntVal($profile_id);
	if ($profile_id<=0) die();

	$ar_profile = CCatalogExport::GetByID($profile_id);
	if (!$ar_profile) die();

	if ($ar_profile["DEFAULT_PROFILE"]!="Y")
	{
		parse_str($ar_profile["SETUP_VARS"]);
	}

	$strFile = CATALOG_PATH2EXPORTS.$ar_profile["FILE_NAME"]."_run.php";
	if (!file_exists($_SERVER["DOCUMENT_ROOT"].$strFile))
	{
		$strFile = CATALOG_PATH2EXPORTS_DEF.$ar_profile["FILE_NAME"]."_run.php";
		if (!file_exists($_SERVER["DOCUMENT_ROOT"].$strFile))
		{
			die();
		}
	}

	$GLOBALS['USER']->Authorize(1);
	include($_SERVER["DOCUMENT_ROOT"].$strFile);
	$GLOBALS['USER']->Logout();

	CCatalogExport::Update($profile_id, array(
		"=LAST_USE" => $DB->GetNowFunction()
		));
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php"); 


?>