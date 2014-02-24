<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

	$APPLICATION->SetTitle("Подтверждение регистрация нового пользователя");
    $APPLICATION->IncludeComponent("bitrix:system.auth.confirmation", "", 
    	array(
			"USER_ID" 		=> "confirm_user_id",
	    	"CONFIRM_CODE" 	=> "confirm_code",
	    	"LOGIN" 		=> "login"
    	)
    );

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>