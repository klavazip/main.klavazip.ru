<? 	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
	$APPLICATION->SetTitle("Избранное");
	$APPLICATION->IncludeComponent("klavazip:catalog.favorites", ".default", array(), false);
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");