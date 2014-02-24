<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Ссылки для редиректа");
$APPLICATION->IncludeComponent("klavazip.admin:redirect.link.add", "", array(), false);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>