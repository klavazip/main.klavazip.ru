<? 	
define("NO_KEEP_STATISTIC", true);
define("NO_AGENT_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if(check_bitrix_sessid())
{
	ob_start();
	
		$APPLICATION->IncludeComponent("bitrix:store.sale.basket.basket", "basket2013", array(
				"COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
				"COLUMNS_LIST" 					=> array("NAME", "PROPS", "PRICE", "QUANTITY", "DELETE", "DELAY"),
				"HIDE_COUPON" 					=> "N",
				"QUANTITY_FLOAT" 				=> "N",
				"PRICE_VAT_SHOW_VALUE" 			=> "N",
				"SET_TITLE" 					=> "N",
				"AJAX_OPTION_ADDITIONAL" 		=> ""
			),
			false,
			array("HIDE_ICONS" => "Y")
		);
	
		$html = ob_get_contents();
	ob_end_clean();
	
	echo CUtil::PhpToJSObject(array('st' => 'ok', 'html' => $html));
}