<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Профили пользователей");

$APPLICATION->IncludeComponent("klavazip.admin:user.profile", ".default", array(), false);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>