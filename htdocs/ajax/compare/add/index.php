<? require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");


if( intval($_POST['id']) > 0 )
{
	$_SESSION["CATALOG_COMPARE_LIST"][8]["ITEMS"][intval($_POST['id'])] = array();
	echo CUtil::PhpToJSObject(array('st' => 'ok'));
}
else
{
	echo CUtil::PhpToJSObject(array('st' => 'error', 'mess' => 'Ошибка' ));
}