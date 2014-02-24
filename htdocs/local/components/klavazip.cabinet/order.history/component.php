<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?



$ar_Sort = array('ID' => 'desc');

if( isset($_GET['order']) && isset($_GET['by']) )
{
	$ar_Sort = array();
	
	switch ($_GET['order'])
	{
		case 'id': $ar_Sort['ID'] = $_GET['by']; break;
		case 'date': $ar_Sort['DATE_INSERT'] = $_GET['by']; break;
		case 'price': $ar_Sort['PRICE'] = $_GET['by']; break;
	}
}


$ar_OrdersFields = array();
$rs_Orders = CSaleOrder::GetList($ar_Sort, array('USER_ID' => $USER->GetID()), false, false, array('ID', 'DATE_INSERT', 'PRICE', 'DELIVERY_ID', 'STATUS_ID'));
$rs_Orders->NavStart(5, false);
while ($ar_Orders = $rs_Orders->Fetch())
{
	$ar_Orders['DELIVERY'] 	= CSaleDelivery::GetByID($ar_Orders['DELIVERY_ID']);
	$ar_Orders['DETAIL_URL'] 	= '/cabinet/order-detail/?id='.$ar_Orders['ID'];
	$arResult['ETEMS'][] 	= $ar_Orders;
}    

$arResult["NAV_STRING"] = $rs_Orders->GetPageNavStringEx($navComponentObject, '', 'myarrows');


$APPLICATION->SetTitle("Персональный раздел / История заказов");


$this->IncludeComponentTemplate();