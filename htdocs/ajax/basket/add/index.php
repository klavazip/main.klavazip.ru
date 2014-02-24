<? require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule("catalog");

$i_ProductID = intval($_POST['id']);
$i_Count = (intval($_POST['cn']) > 0) ? intval($_POST['cn']) : 1;


if( $i_ProductID > 0 && $i_Count > 0 )
{
	if( ! Add2BasketByProductID( $i_ProductID, $i_Count) )
		echo CUtil::PhpToJSObject(array('st' => 'error', 'mess' => 'Ошибка добавления товара в корзину' ));
	else
		echo CUtil::PhpToJSObject(array('st' => 'ok'));
}