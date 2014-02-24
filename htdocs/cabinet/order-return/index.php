<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->IncludeComponent("klavazip.cabinet:navigation", ".default", array(), false);
$APPLICATION->IncludeComponent("klavazip.cabinet:order.return.list", ".default", array(), false);
$APPLICATION->IncludeComponent("klavazip.cabinet:footer.html", ".default", array(), false);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");