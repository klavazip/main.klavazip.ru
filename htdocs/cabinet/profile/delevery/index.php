<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Персональный раздел / Профили покупателей");
$APPLICATION->IncludeComponent("klavazip.cabinet:navigation", ".default", array(), false);
$APPLICATION->IncludeComponent("klavazip.cabinet:profile.delevery", ".default", array(), false);
$APPLICATION->IncludeComponent("klavazip.cabinet:footer.html", ".default", array(), false);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");