<? require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

    $ar_Error = array();

    $s_Email = trim($_POST['mail']);
    $s_Pass  = trim($_POST['pass']);

    if( strlen($s_Email) == 0 )
        $ar_Error[] = 'Не введен E-mail';
    else
    {
        if( ! check_email($s_Email)  )
            $ar_Error[] = 'Не правильно введен E-mail';
    }


    if( strlen($s_Pass) == 0 )
        $ar_Error[] = 'Не введен пароль';


    if( count( $ar_Error ) == 0 )
    {
        $rs_User = CUser::GetList(($by=""), ($order=""), array('=EMAIL' => $s_Email));
        if( $rs_User->SelectedRowsCount() == 1 )
        {
        	$ar_User = $rs_User->Fetch();
        	
            $ar_AuthResult = $USER->Login($ar_User['LOGIN'], $s_Pass, ($_POST['ch'] == 'Y') ? 'Y' : 'N');
            if( $ar_AuthResult['TYPE'] == 'ERROR')
            {
                echo CUtil::PhpToJSObject(array('st' => 'error', 'mess' => 'Ошибка авторизации, проверьте правильность E-mail и пароля' ));
            }
            else
            {
                echo CUtil::PhpToJSObject(array('st' => 'ok', 'mess' => '', 'url' => $_POST['back_url']));
            }
        }
        else
        {
            echo CUtil::PhpToJSObject(array('st' => 'error', 'mess' => 'Нет такого пользователя' ));
        }
    }
    else
    {
        echo CUtil::PhpToJSObject(array('st' => 'error', 'mess' => implode(', ', $ar_Error) ));
    }