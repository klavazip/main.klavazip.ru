<? 	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$i_BasketProductID = intval($_POST['bid']);
$i_Count = intval($_POST['cn'])  ;

if($i_Count > 0 && $i_BasketProductID > 0 )
{
	if( ! CSaleBasket::Update($i_BasketProductID, array('QUANTITY' => $i_Count)))
		echo CUtil::PhpToJSObject(array('st' => 'error', 'mess' => 'Ошибка добавления товара в корзину' ));
	else
		echo CUtil::PhpToJSObject(array('st' => 'ok'));
}