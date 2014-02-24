<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
	$APPLICATION->SetTitle("Оформление заказа");
	$APPLICATION->IncludeComponent("klavazip:sale.order.light", "", array(), false);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");