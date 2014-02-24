<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = array(
	"PARAMETERS" => array(
		"ASD_MAIL_ID" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("ASD_MAIL_ID_L"),
			"TYPE" => "STRING",
			"DEFAULT" => '={$_REQUEST["mid"]}',
		),
		"ASD_MAIL_MD5" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("ASD_MAIL_MD5_L"),
			"TYPE" => "STRING",
			"DEFAULT" => '={$_REQUEST["mhash"]}',
		),
	),
);
?>
