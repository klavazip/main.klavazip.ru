<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?

	if($_SERVER['REQUEST_METHOD'] == 'POST' && strlen($_POST['change_pwd']) > 0 )
	{
		$arResult['CH_ERROR'] = array();
		
		$s_Email	 		= trim($_POST['USER_LOGIN']);
		$s_Checkword 		= trim($_POST['USER_CHECKWORD']);
		$s_Password  		= trim($_POST['USER_PASSWORD']);
		$s_PasswordConfirm  = trim($_POST['USER_CONFIRM_PASSWORD']);
		
		
		if( strlen($s_Password) < 6 )
			$arResult['CH_ERROR'][] = 'Пароль жолжен быть не менее 6 символов';
		
		if($s_Password !== $s_PasswordConfirm)
			$arResult['CH_ERROR'][] = 'Пароли не совпадают';
		
		if( count($arResult['CH_ERROR']) == 0 )
		{
			$rs_User = CUser::GetList(($by=""), ($order=""), array('LOGIN_EQUAL' => $s_Email, '=CHECKWORD' => $s_Checkword));
			if($rs_User->SelectedRowsCount() == 1)
			{
				$ar_User = $rs_User->Fetch();
				$ob_User = new CUser;
				$ob_User->Update($ar_User['ID'], array("PASSWORD" => $s_Password));
				$USER->Authorize($ar_User['ID']);

				$arResult['RESULT'] = 'Вы успешно изменили свой пароль и авторизовались на сайте.';
				LocalRedirect('/aut/changepasswd/?aut=Y');
			}
			else
			{
				$arResult['CH_ERROR'][] = 'Не верная контрольная строка для смены пароля, попробуйте еще раз';
			}
		}
	}

$this->IncludeComponentTemplate();