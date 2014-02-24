<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Условия Доставки");
$APPLICATION->SetTitle("Условия доставки");


$APPLICATION->IncludeComponent("klavazip:info.paydelivery", ".default", array(), false);


require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>