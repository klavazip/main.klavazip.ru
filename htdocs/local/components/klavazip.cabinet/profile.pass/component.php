<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?

if( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['buttonBuy']) && check_bitrix_sessid())
{
	$s_OldPass 			= trim($_POST['OLD_PASS']);
	$s_NewPass 			= trim($_POST['NEW_PASS']);
	$s_ConfirmNewPass 	= trim($_POST['CONFIRM_NEW_PASS']);

	if(strlen($s_OldPass) > 0 && strlen($s_NewPass) > 0 && strlen($s_ConfirmNewPass) > 0)
	{
		$ar_Result = $USER->Login($USER->GetLogin(), $s_OldPass);
		if($ar_Result['TYPE'] == 'ERROR')
			$arResult['ERROR'][] = 'Не верно указан текущий пароль';
		
	
		if($s_NewPass !== $s_ConfirmNewPass)
			$arResult['ERROR'][] = 'Новый пароль и подтверждение пароля не совпадают';
		
		
		if(count($arResult['ERROR']) == 0)
		{
			$ob_User = new CUser;
		 	if( ! $ob_User->Update($USER->GetID(), array('PASSWORD' => $s_NewPass, 'CONFIRM_PASSWORD'  => $s_ConfirmNewPass)) ) 
				$arResult['ERROR'][] = $ob_User->LAST_ERROR;
			else
			{
				$arResult['RESULT'] = 'Пароль успешно изменен';
				unset($_POST);
			}
		} 
	}
	else
	{
		$arResult['ERROR'][] = 'Все поля являются обязательными';
	}
}

$this->IncludeComponentTemplate();