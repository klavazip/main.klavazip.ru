<? 	
define("NO_KEEP_STATISTIC", true);
define("NO_AGENT_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");


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
