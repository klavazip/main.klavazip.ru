<? 	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
	$APPLICATION->SetTitle("Восстановление пароля");
   	$APPLICATION->IncludeComponent("klavazip:forgotpasswd", ".default", array());
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");