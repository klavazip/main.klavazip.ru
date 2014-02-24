<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?

$rs_Element = CIBlockElement::GetList(
	array(), 
	array('IBLOCK_ID' => KlavaCabinet::RETURN_ORDER_IBLOCK_ID), 
	false, 
	false, 
	array('ID', 'PREVIEW_TEXT', 'PROPERTY_USER_ID', 'PROPERTY_ELEMENT_ID', 'PROPERTY_ORDER_ID', 'DATE_CREATE')
);
while($ar_Element = $rs_Element->GetNext(true, false))
{
	$ar_Element['DETAIL_URL'] = '/cabinet/order-return/detail/?id='.$ar_Element['ID'];

	$rs_User = CUser::GetByID($ar_Element['PROPERTY_USER_ID_VALUE']);
	$ar_User = $rs_User->Fetch();
	
	$s_UserName = '';
	if( strlen($ar_User['NAME']) > 0 )
		$s_UserName = $ar_User['NAME'];

	if( strlen($ar_User['LAST_NAME']) > 0 )
		$s_UserName .= ' '.$ar_User['LAST_NAME'];
	
	if( strlen($s_UserName) == 0 )
		$s_UserName = $ar_User['LOGIN'];
	
	$ar_Element['USER_NAME'] = $s_UserName; 
	
	$arResult['ITEMS'][] = $ar_Element; 
}


//echo '<pre>', print_r($arResult).'</pre>';


$APPLICATION->SetTitle("Персональный раздел / Заявки на возврат");


$this->IncludeComponentTemplate();