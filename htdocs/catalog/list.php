<?  require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
	$APPLICATION->SetTitle("Каталог");
	$APPLICATION->IncludeComponent("klavazip:catalog.list", ".default", array(), false);
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");