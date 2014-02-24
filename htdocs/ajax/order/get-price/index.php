<? 	
define("NO_KEEP_STATISTIC", true);
define("NO_AGENT_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if(check_bitrix_sessid())
{
	ob_start();
		$APPLICATION->IncludeComponent("bitrix:store.sale.basket.basket", "string", array(), false, array("HIDE_ICONS" => "Y"));
		$s_InfoPrice = ob_get_contents();
	ob_end_clean();
	
	$ar_Params = explode('|', $s_InfoPrice);
	foreach ($ar_Params as $s_Val)
	{
		$ar = explode('=', $s_Val);
		$ar_Result[$ar[0]] = $ar[1]; 		
	}
	
	echo CUtil::PhpToJSObject(array('st' => 'ok', 'result' => $ar_Result));
}