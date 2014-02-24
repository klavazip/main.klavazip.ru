<? 	
define("NO_KEEP_STATISTIC", true);
define("NO_AGENT_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$b_Result = false;

$s_Mail = $_POST['email'];
$s_Pass = $_POST['pass'];

if (isset( $s_Mail ) && strlen( $_POST['pass'] ) > 0)
{
	$rs_UserOrder = CUser::GetList(($by=""), ($order=""), array('=EMAIL' => $s_Mail));
	if( $ar_User = $rs_UserOrder->Fetch() )
	{
		if(strlen($ar_User["PASSWORD"]) > 32)
		{
			$salt = substr($ar_User["PASSWORD"], 0, strlen($ar_User["PASSWORD"]) - 32);
			$db_password = substr($ar_User["PASSWORD"], -32);
		}
		else
		{
			$salt = "";
			$db_password = $ar_User["PASSWORD"];
		}

		$user_password =  md5($salt.$s_Pass);

		if ( $user_password == $db_password )
		{
			$b_Result = true;
		}
	}
	
	echo ($b_Result) ? 'Y' : 'N';
}