<? 	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

die();

$s_ElementIdHash =  $_COOKIE['KLAVA_FAVOTITES_ID'];
if(strlen($s_ElementIdHash) > 0)
{
	$ar_ElementID = explode(',', $s_ElementIdHash);
}
else
{
	$ar_ElementID = array();
}

//echo count($ar_ElementID);
 

echo '<pre>', print_r($_COOKIE).'</pre>';
