<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->IncludeComponent("klavazip.cabinet:navigation", ".default", array(), false);
$APPLICATION->IncludeComponent("klavazip.cabinet:pre.orders", ".default", array(), false);
$APPLICATION->IncludeComponent("klavazip.cabinet:footer.html", ".default", array(), false);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");