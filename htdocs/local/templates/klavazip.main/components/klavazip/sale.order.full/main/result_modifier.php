<?	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$rs_Bakset = CSaleBasket::GetList(array(), array("FUSER_ID" => CSaleBasket::GetBasketUserID(), "LID" => SITE_ID, "ORDER_ID" => "NULL", "CAN_BUY" => 'Y'), false, false, array("ID"));
$arResult['BASKET_COUNT_ELEMENT'] = $rs_Bakset->SelectedRowsCount();

$rs_User = CUser::GetByID($USER->GetID());
$ar_User = $rs_User->Fetch();

$arResult['CURRENT_TYPE'] = (intval($ar_User['UF_PERSON_TYPE']) > 0) ? $ar_User['UF_PERSON_TYPE'] : 1;