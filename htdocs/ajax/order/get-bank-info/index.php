<? 	
define('NO_KEEP_STATISTIC', true);
define('NO_AGENT_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
define('NO_AGENT_CHECK', true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");


$s_BIK = $_POST['bik'];
if( strlen($s_BIK) == 9 )
{
	ob_start();
	
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
	while($ar_Element = $rs_Element->GetNext(true, false))
	{
		echo 'Название банка: '.$ar_Element['NAME']."\n".
		     'БИК: '.$ar_Element['PROPERTY_BIK_VALUE']."\n".
		 	 'Корр. счет: '.$ar_Element['PROPERTY_KS_VALUE']."\n".
		 	 'Индекс: '.$ar_Element['PROPERTY_INDEX_VALUE']."\n".
		 	 'Город: '.$ar_Element['PROPERTY_CITY_VALUE']."\n".
		 	 'Адрес: '.$ar_Element['PROPERTY_ADDRESS_VALUE']."\n".
		 	 'Телефон: '.$ar_Element['PROPERTY_PHONE_VALUE'];
	}
	
	$html = ob_get_contents();
	ob_end_clean();

	echo $html;
}