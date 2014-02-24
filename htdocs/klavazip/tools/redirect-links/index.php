<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Ссылки для редиректа");
$APPLICATION->IncludeComponent("klavazip.admin:highloadblock.list", "",
	Array(
		"BLOCK_ID" => "1",
		"DETAIL_URL" => ""
	),
false
);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>