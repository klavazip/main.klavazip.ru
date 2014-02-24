<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$ar_LocationID = array();
$rs_UserProfile = CSaleOrderUserProps::GetList(array('ID' => 'DESC'), array('USER_ID' => $USER->GetID()));
while ($ar_UserProfile = $rs_UserProfile->GetNext())
{
	$ar_UserProfile['PERSONAL_TYPE'] = CSalePersonType::GetByID($ar_UserProfile['PERSON_TYPE_ID']); 
	
	$rs_UserProfileProperty = CSaleOrderUserPropsValue::GetList(($b='SORT'), ($o='ASC'), array('USER_PROPS_ID' => $ar_UserProfile['ID']));
	while ($ar_UserProfileProperty = $rs_UserProfileProperty->GetNext())
	{
		if($ar_UserProfileProperty['CODE'] == 'LOCATION')
			$ar_LocationID[] = $ar_UserProfileProperty['VALUE']; 
		
		$ar_UserProfile['PROPERTY'][] = $ar_UserProfileProperty;
	}
	
	$arResult['ITEMS'][] = $ar_UserProfile;
}


/*
$ar_LocationID = array_unique($ar_LocationID);
foreach ($ar_LocationID as $i_LocationID)
{
	$rs_Delevery = CSaleDelivery::GetList(array('SORT' => 'ASC'), array('ACTIVE' => 'Y', 'LOCATION' => $i_LocationID));
	while ($ar_Delevery = $rs_Delevery->Fetch())
	{
		$arResult['DELIVERY'][$i_LocationID][] = $ar_Delevery; 
	}
} 
*/



$this->IncludeComponentTemplate();