<? require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");


function __getOrderPropertyParams($s_Code, $i_PersonType)
{
	$rs_Property = CSaleOrderProps::GetList(array(), array('PERSON_TYPE_ID' => $i_PersonType, 'CODE' => $s_Code), false, false, array('ID', 'NAME'));
	if ($ar_Property = $rs_Property->Fetch())
	{
		return $ar_Property;
	}
} 


$ar_Property = $_POST['PROPERTY']; 
$i_ProfileID = intval($_POST['PROFILE_ID']);

if( count($ar_Property) > 0 )
{
	foreach ($ar_Property as $s_Code => $ar_PropertyValue)
	{
		if( intval($ar_PropertyValue['ID']) > 0 )
		{
			CSaleOrderUserPropsValue::Update($ar_PropertyValue['ID'], array('VALUE' => $ar_PropertyValue['VALUE']));
			echo 'Edit OK';
		}
		else
		{
			$ar_PropParams = __getOrderPropertyParams($s_Code, intval($_POST['PERSON_TYPE_ID']));
			$ar_PropFields = array(
				"USER_PROPS_ID" 	=> $i_ProfileID, 
				"ORDER_PROPS_ID" 	=> $ar_PropParams['ID'],
				"NAME" 				=> $ar_PropParams['NAME'],
				"VALUE" 			=> $ar_PropertyValue['VALUE']
			);
			CSaleOrderUserPropsValue::Add($ar_PropFields);
		}	
	}
	
	$s_ProfileName = trim($_POST['PROFILE_NAME']);
	if(strlen($s_ProfileName) > 0)
	{
		CSaleOrderUserProps::Update($i_ProfileID, array('NAME' => $s_ProfileName));
	}
}