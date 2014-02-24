<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


$rs_User = CUser::GetByID($USER->GetID());
$ar_User = $rs_User->Fetch();
$arResult['USER'] = $ar_User; 

if( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['buttonBuy']) )
{
	$arResult['ERROR'] = array();
	
	if( strlen($_POST['PERSONAL_PHONE']) > 0 && $_POST['PERSONAL_PHONE'] != '+7 ( _ _ _ ) _ _ _ - _ _ - _ _')
		$ar_Update['PERSONAL_PHONE'] = $_POST['PERSONAL_PHONE'];
	
	if( strlen($_POST['LAST_NAME']) > 0 )
		$ar_Update['LAST_NAME'] = $_POST['LAST_NAME'];

	if( strlen($_POST['NAME']) > 0 )
		$ar_Update['NAME'] = $_POST['NAME'];

	if( strlen($_POST['SECOND_NAME']) > 0 )
		$ar_Update['SECOND_NAME'] = $_POST['SECOND_NAME'];

	
	if( strlen($_POST['EMAIL']) > 0 && ! check_email($_POST['EMAIL']) )
	{
		$arResult['ERROR'][] = 'Не верный Email'; 
	}
	else
	{
		$ar_Update['EMAIL'] = $_POST['EMAIL'];
	}
	 
	if( count($arResult['ERROR']) == 0 )
	{
		$ob_User = new CUser;
		if($ob_User->Update($ar_User['ID'], $ar_Update))
			LocalRedirect('/cabinet/profile/info/');
		else
			$arResult['ERROR'][] = $ob_User->LAST_ERROR;
	}
}


$this->IncludeComponentTemplate();