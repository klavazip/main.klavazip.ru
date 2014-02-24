<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_account_info']))
{
	$arResult['CH_ERROR'] = array();

	$s_Email = $_POST['USER_EMAIL'];

	if( strlen($s_Email)  == 0 )
	{
		$arResult['CH_ERROR'][] = 'Не введен E-mail';
	}
	else
	{
		if( ! check_email($s_Email)  )
			$arResult['CH_ERROR'][] = 'Не правильно введен E-mail';
	}


	if( count($arResult['CH_ERROR']) == 0 )
	{
		$rs_User = CUser::GetList(($by=""), ($order=""), array('=EMAIL' => $s_Email));
		if( $rs_User->SelectedRowsCount() == 1 )
		{
			$ar_User = $rs_User->Fetch();

			$s_Code = md5(randString(6));

			$USER = new CUser;
			$USER->Update($ar_User['ID'], array('CONFIRM_PASSWORD' => $s_Code));

			CEvent::Send("BXM_USER_PASS_REQUEST", 's1', array(
				'CHECKWORD' => $s_Code, 
				'EMAIL' 	=> $s_Email, 
				'NAME' 		=> $ar_User['NAME'],
				'LAST_NAME' => $ar_User['LAST_NAME'],
				'LOGIN' 	=> $ar_User['LOGIN'],
				'USER_ID' 	=> $ar_User['ID'] 		 	
				)
			);
			CEvent::CheckEvents();
			$arResult['CH_RESULT'] = 'Вам отправлено письмо проверьте свою почту.';
		}
		else
		{
			$arResult['CH_ERROR'][] = 'Эмм... к сожалению нет пользователя с таким E-mail';
		}
	}
}


$this->IncludeComponentTemplate();