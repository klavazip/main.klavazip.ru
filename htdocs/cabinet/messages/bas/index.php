<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Персональный раздел / Сообщения / Корзина");
$APPLICATION->IncludeComponent("klavazip.cabinet:navigation", ".default", array(), false);
$APPLICATION->IncludeComponent("klavazip.cabinet:messages.bas", ".default", array(), false);
$APPLICATION->IncludeComponent("klavazip.cabinet:footer.html", ".default", array(), false);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");