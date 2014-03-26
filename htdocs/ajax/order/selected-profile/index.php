<? 	
define("NO_KEEP_STATISTIC", true);
define("NO_AGENT_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");



$i_ProfileID = intval($_POST['id']);

if($i_ProfileID > 0)
{
	
	# Профили пользователя
	$rs_Profile = CSaleOrderUserProps::GetList(array(), array("ID" => $i_ProfileID));
	if ($ar_Profile = $rs_Profile->Fetch())
	{
		$ar_Result['PROFILE'] = $ar_Profile; 
	}
	
	
	
	$rs_PropertyProfile = CSaleOrderUserPropsValue::GetList(($b=""), ($o=""), array("USER_PROPS_ID" => $i_ProfileID));
	while ($ar_PropertyProfile = $rs_PropertyProfile->Fetch())
	{
		$ar_Result['VALUE'][$ar_PropertyProfile['CODE']] = $ar_PropertyProfile['VALUE']; 
	}
	
	echo CUtil::PhpToJSObject(array('st' => 'ok', 'result' => $ar_Result));
}	





/*
if( $USER->IsAuthorized() )
{
	echo CUtil::PhpToJSObject(array('st' => 'aut'));
	die();	
}


$s_Email = $_POST['email'];
if( check_email($s_Email) )
{
	$rs_UserOrder = CUser::GetList(($by=""), ($order=""), array('=EMAIL' => $s_Email));
	if( $rs_UserOrder->SelectedRowsCount() > 0 )
	{
		echo CUtil::PhpToJSObject(array('st' => 'ok', 'result' => 'Y'));
	}
	else
	{
		echo CUtil::PhpToJSObject(array('st' => 'ok', 'result' => 'N'));
	}	
}	
else
{
	echo CUtil::PhpToJSObject(array('st' => 'error', 'mes' => 'Не верно указан E-mail'));
}	
*/