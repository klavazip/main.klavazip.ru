<? 	
define('NO_KEEP_STATISTIC', true);
define('NO_AGENT_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
define('NO_AGENT_CHECK', true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");


$s_BIK = $_POST['bik'];
if( strlen($s_BIK) == 9 )
{
	$rs_Element = CIBlockElement::GetList(
		array(), 
		array('IBLOCK_ID' => KlavaMain::BANK_INFO_IBLOCK_ID, 'ACTIVE' => 'Y', 'PROPERTY_BIK' => $s_BIK), 
		false, 
		false, 
		array(
			'ID', 
			'NAME', 
			'PROPERTY_BIK', 
			'PROPERTY_KS', 
			'PROPERTY_INDEX', 
			'PROPERTY_CITY', 
			'PROPERTY_ADDRESS', 
			'PROPERTY_PHONE', 
			'PROPERTY_OKATO', 
			'PROPERTY_OKPO', 
			'PROPERTY_REGNUM' 
	));
	
	if( $rs_Element->SelectedRowsCount() > 0)
	{
		if($ar_Element = $rs_Element->GetNext(true, false))
		{
			echo CUtil::PhpToJSObject(array('st' => 'ok', 'result' => array(
				'BIK_BANK'  => $ar_Element['PROPERTY_KS_VALUE'],		
				'NAME_BANK' => $ar_Element['NAME'],
				'KS_BANK'	=> $ar_Element['PROPERTY_KS_VALUE'],
				'CITY_BANK' => $ar_Element['PROPERTY_CITY_VALUE']	 	
			)));
		}
	}
	else
	{
		echo CUtil::PhpToJSObject(array('st' => 'error'));
	}		
}
