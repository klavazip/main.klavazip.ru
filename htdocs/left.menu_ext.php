<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $APPLICATION;
$aMenuLinksExt = array();

$aMenuLinksExt = $APPLICATION->IncludeComponent("klavazip:menu.sections", "", array(
				"IS_SEF" => "N",
				"SEF_BASE_URL" => "",
				"IBLOCK_TYPE" => "catalog",
				"IBLOCK_ID" => 48,
				"DEPTH_LEVEL" => "4",
				"CACHE_TYPE" => "N",
			), false, Array('HIDE_ICONS' => 'Y'));



$aMenuLinks = array_merge($aMenuLinks, $aMenuLinksExt);
?>