<? 	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
	$APPLICATION->SetTitle("Регистрация нового пользователя");

	//$APPLICATION->IncludeComponent("klavazip:registration", "", array());
	
	$APPLICATION->IncludeComponent("bitrix:main.register", "main", 
		array(
			"SHOW_FIELDS" 		 => "",	// Поля, которые показывать в форме
			"REQUIRED_FIELDS" 	 => array(	// Поля, обязательные для заполнения
				0 => "EMAIL"
			),
			"AUTH" 				 => "Y",	// Автоматически авторизовать пользователей
			"USE_BACKURL" 		 => "Y",	// Отправлять пользователя по обратной ссылке, если она есть
			"SUCCESS_PAGE" 		 => "",	// Страница окончания регистрации
			"SET_TITLE" 		 => "Y",	// Устанавливать заголовок страницы
			"USER_PROPERTY" 	 => "",	// Показывать доп. свойства
			"USER_PROPERTY_NAME" => "",	// Название блока пользовательских свойств
		),
		false
	);
	
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>