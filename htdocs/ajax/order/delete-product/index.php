<? 	
define("NO_KEEP_STATISTIC", true);
define("NO_AGENT_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$i_ProductID = intval($_POST['id']);

if($i_ProductID > 0 && check_bitrix_sessid())
{
	if (CSaleBasket::Delete($i_ProductID))
	{
		// Выведем актуальную корзину для текущего пользователя
		$rs_Basket = CSaleBasket::GetList(array(), array("FUSER_ID" => CSaleBasket::GetBasketUserID(), "LID" => SITE_ID, "ORDER_ID" => "NULL"), false, false, array("ID"));
		$s_BaksetIsEmpty = (KlavaMain::isBasketEmpty()) ? 'Y' : 'N';
		echo CUtil::PhpToJSObject(array('st' => 'ok', 'basket_is_empty' => $s_BaksetIsEmpty));
	}	
	else
	{
		echo CUtil::PhpToJSObject(array('st' => 'error'));
	}
}