<?
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

//require_once( '../../lib/xml_to_array.php' );
//require_once( '../../lib/class.KlavaIntegrationMain.php' );
//require_once( '../../lib/class.KlavaUserProfile.php' );

//$s_LogPatch = $_SERVER['DOCUMENT_ROOT']."/system/profile.user/logs/";

//arraytofile($_POST, $s_LogPatch, "");
//arraytofile($_FILES, $s_LogPatch, "");
//arraytofile($_SERVER, $s_LogPatch, "");


// remitmaster xml_id be83f4f0-5820-11e3-9df4-3085a9a61e12
// ID 9364
//


//CSaleOrderUserPropsValue::DeleteAll(7988);



// Выберем все профили покупателя для текущего пользователя,
// упорядочив результат по дате последнего изменения

//CSaleOrderUserProps::Delete(7988);

//CSaleOrderUserProps::Delete(15047);
//CSaleOrderUserProps::Delete(15048);

/*

$db_sales = CSaleOrderUserProps::GetList(array(), array("USER_ID" => 16154));
while ($ar_sales = $db_sales->Fetch())
{
	
	$db_propVals = CSaleOrderUserPropsValue::GetList(($b="SORT"), ($o="ASC"), array("USER_PROPS_ID" => $ar_sales['ID']));
	while ($arPropVals = $db_propVals->Fetch())
	{
		$ar_sales['PROEPRTY'][$arPropVals['CODE']] = $arPropVals['VALUE'];
	}
	
	echo '<pre>', print_r($ar_sales).'</pre>';
}
*/

// http://dev2.klavazip.ru/system/sale.1c.integration/import.profile.user/test.scripts/profile_list.php



if ($ar = CSaleOrderUserProps::GetByID(15053))
{
	echo '<pre>', print_r($ar).'</pre>';
}

//15053