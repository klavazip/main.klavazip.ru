<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Персональный раздел / Подписка на рассылку");
$APPLICATION->IncludeComponent("klavazip.cabinet:navigation", ".default", array(), false);

//$APPLICATION->IncludeComponent("klavazip.cabinet:profile.subs", ".default", array(), false);

$APPLICATION->IncludeComponent("bitrix:subscribe.edit", "cabinet", Array(
	"AJAX_MODE" => "N",	// Включить режим AJAX
	"SHOW_HIDDEN" => "N",	// Показать скрытые рубрики подписки
	"ALLOW_ANONYMOUS" => "N",	// Разрешить анонимную подписку
	"SHOW_AUTH_LINKS" => "N",	// Показывать ссылки на авторизацию при анонимной подписке
	"CACHE_TYPE" => "A",	// Тип кеширования
	"CACHE_TIME" => "3600",	// Время кеширования (сек.)
	"SET_TITLE" => "N",	// Устанавливать заголовок страницы
	"AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
	"AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
	"AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
	),
	false
);

$APPLICATION->IncludeComponent("klavazip.cabinet:footer.html", ".default", array(), false);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");