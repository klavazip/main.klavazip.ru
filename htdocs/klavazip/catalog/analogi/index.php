<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Аналоги для товара");

$APPLICATION->IncludeComponent("klavazip.admin:product.analogs", ".default", array(), false);


require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>

