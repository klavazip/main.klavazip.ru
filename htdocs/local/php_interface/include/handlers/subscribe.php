<?
AddEventHandler('subscribe', 'BeforePostingSendMail', array('CSubscribeHandlers', 'BeforePostingSendMailHandler'));

class CSubscribeHandlers {

	const MAIL_SALT = 'orn3j4FJA2fd2L';

	public static function BeforePostingSendMailHandler($arFields) {
		if ($arFields['EMAIL_EX']['USER_ID'] > 0) {
			if ($arUser = CUser::GetByID($arFields['EMAIL_EX']['USER_ID'])->Fetch()) {
				if (strlen(trim($arUser['WORK_COMPANY']))) {
					$USER_NAME = $arUser['WORK_COMPANY'];
				} else {
					$USER_NAME = trim($arUser['NAME'] . ' ' . $arUser['LAST_NAME']);
					if (!strlen($USER_NAME)) {
						$USER_NAME = $arUser['LOGIN'];
					}
				}
			}
		} elseif ($arFields['EMAIL_EX']['SUBSCRIPTION_ID'] > 0) {
			$arInfo = $GLOBALS['DB']->Query("SELECT * FROM b_klava_subscriptions_add WHERE ID=".intval($arFields['EMAIL_EX']['SUBSCRIPTION_ID']))->Fetch();
			$USER_NAME = $arInfo['NAME'];
		}
		if (!strlen($USER_NAME)) {
			$USER_NAME = 'подписчик';
		}
        
        
        $mpPATH = $_SERVER['DOCUMENT_ROOT'].'/bxmaker/mail/.mail.msg.epilog.html';
        if(file_exists($mpPATH))
        {
            $mpHTML = file_get_contents($mpPATH);
            $arFields["BODY"] = str_replace("#MSG_EPILOG#", $mpHTML, $arFields["BODY"]);
        }

		$arFields['BODY'] = str_replace('#POSTING_ID#', $arFields['POSTING_ID'], $arFields['BODY']);
		$arFields['BODY'] = str_replace('#USER_NAME#', $USER_NAME, $arFields['BODY']);
		$arFields['BODY'] = str_replace('#MAIL_ID#', urlencode($arFields['EMAIL']), $arFields['BODY']);
		$arFields['BODY'] = str_replace('#MAIL_MD5#', self::GetMailHash($arFields['EMAIL']), $arFields['BODY']);

		return $arFields;
	}

	public static function GetMailHash($email) {
		return md5(md5($email).self::MAIL_SALT);
	}
}
?>