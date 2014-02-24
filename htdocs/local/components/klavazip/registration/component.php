<? 	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
	
	if( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['bt']))
	{
		$arResult['REG_ERROR'] = array();
	
		$s_Email 		= trim($_POST['email']);
		$s_Pass1  		= trim($_POST['pas']);
		$s_Pass2  		= trim($_POST['pass']);
		
		if( strlen($s_Pass1) == 0 )
			$arResult['REG_ERROR'][] = 'Не введен пароль';
		
		if( strlen($s_Pass2) == 0 )
			$arResult['REG_ERROR'][] = 'Не введено подтверждение пароля';
	
		if( strlen($s_Pass1) < 6 )
			$arResult['REG_ERROR'][] = 'Пароль должен быть минимум 6 символов';	
		
		if($s_Pass1 !== $s_Pass2)
			$arResult['REG_ERROR'][] = 'Пароли не совпадают';
	
		if( strlen($s_Email)  == 0 )
		{
			$arResult['REG_ERROR'][] = 'Не введен E-mail';
		}
		else
		{
			if( ! check_email($s_Email)  )
				$arResult['REG_ERROR'][] = 'Не правильно введен E-mail';
		}
	
		if( count( $arResult['REG_ERROR'] ) == 0 )
		{
			$rs_User = CUser::GetByLogin( $s_Email );
			if( $rs_User->SelectedRowsCount() == 1 )
			{
				$arResult['REG_ERROR'][] = 'Пользователь с таким E-mail уже существует';
			}
			else
			{
				global $USER;
				$ar_RegResult = $USER->Register($s_Email, $s_Name, $s_LastName, $s_Pass1, $s_Pass2, $s_Email);
				if( $ar_RegResult['TYPE'] != 'ERROR' )
					LocalRedirect('/aut/registration/?registr=ok');
					
			}
		}
	}
	
	$this->IncludeComponentTemplate();