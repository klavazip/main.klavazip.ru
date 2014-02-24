<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("NAME"),
	"DESCRIPTION" => GetMessage("DESCRIPTION"),
	"ICON" => "/images/detail.gif",
	"SORT" => 100,
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "service",
		"CHILD" => array(
			"ID" => "subscribe",
			"NAME" => GetMessage("DIR_NAME")
		),

	),
);

?>