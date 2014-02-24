<? 	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

die();

$i_ElementID = intval($_POST['id']);

if($i_ElementID > 0)
{
	global $APPLICATION;
	
	$s_ElementIdHash =  $_COOKIE['KLAVA_FAVOTITES_ID'];
	if(strlen($s_ElementIdHash) > 0)
	{
		$ar_ElementID = explode(',', $s_ElementIdHash);
	}
	else
	{
		$ar_ElementID = array();
	}
	
	$ar_ElementID[] = $i_ElementID;
	
	$APPLICATION->set_cookie("KLAVA_FAVOTITES_ID", implode(',', $ar_ElementID));
}
