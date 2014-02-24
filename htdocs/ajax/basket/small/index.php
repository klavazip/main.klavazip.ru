<? 	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");


ob_start();

	$APPLICATION->IncludeComponent("bitrix:sale.basket.basket.small", "footer_bascet",
		array(
				"PATH_TO_BASKET" => "/personal/basket.php",
				"PATH_TO_ORDER"  => "/personal/order.php"
		),
		false
	);

$html = ob_get_contents();
ob_end_clean();

echo CUtil::PhpToJSObject(array('html' => $html));
