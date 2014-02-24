<? 	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
	$APPLICATION->SetTitle("Восстановление пароля");
   	$APPLICATION->IncludeComponent("klavazip:changepasswd", ".default", array());
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>