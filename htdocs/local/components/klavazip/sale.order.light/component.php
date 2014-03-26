<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?

if( ! KlavaMain::isBasketEmpty() )
	LocalRedirect('/personal/basket/');	

if( $USER->IsAuthorized() )
{
	$rs_User = CUser::GetByID($USER->GetID());
	$ar_User = $rs_User->Fetch();
	$arResult['USER'] = $ar_User;
	
	# Профили пользователя
	$rs_Profile = CSaleOrderUserProps::GetList(array("DATE_UPDATE" => "DESC"), array("USER_ID" => $USER->GetID()));
	while ($ar_Profile = $rs_Profile->Fetch())
	{
		switch ($ar_Profile['PERSON_TYPE_ID'])
		{
			case 1: $s_UserType = 'Физ. лицо'; break;
			case 2: $s_UserType = 'Юр. лицо'; break;
			case 3: $s_UserType = 'ИП'; break;
		}
		
		$arResult['USER_PROFILE'][] = array(
			'ID' 	=> $ar_Profile['ID'],		
			'NAME' 	=> $ar_Profile['NAME'],
			'TYPE' 	=> $s_UserType			
		);
	}
}


# Платежные системы
$rs_PaySystem = CSalePaySystem::GetList(array("SORT" => "ASC"), array("ACTIVE" => "Y", 'PERSON_TYPE_ID' => 1));
while ($ar_PaySystem = $rs_PaySystem->Fetch())
{
	$arResult['PAYMENT'][] = $ar_PaySystem;
}


# Добавление заказа
if( $_SERVER['REQUEST_METHOD'] == 'POST' && check_bitrix_sessid() )
{
	# если пользователь не авторизован то добавляем его, если находим по Email
	# то привязываем заказ к этому email
	if( ! $USER->IsAuthorized() )
	{
		if( check_email($_POST['USER']['EMAIL']) )
		{
			$rs_UserOrder = CUser::GetList(($by=""), ($order=""), array('=EMAIL' => $_POST['USER']['EMAIL']));
			if( $rs_UserOrder->SelectedRowsCount() > 0 )
			{
				$ar_UserOrder = $rs_UserOrder->Fetch();
				if( $USER->Login($ar_UserOrder['LOGIN'], $_POST['PASSWORD'], "Y") === true )
				{
					$i_UserID = $USER->GetID();
				}	
			}
			else
			{
				# Уря! Новый пользователь :) Регим
				$s_Pass = randString(7);
			
				global $USER; 
				$ar_RegResult = $USER->Register($_POST['USER']['EMAIL'], '', '', $s_Pass, $s_Pass, $_POST['USER']['EMAIL'], 'no');
				
				$USER->Update($USER->GetID(), array(
					'PERSONAL_PHONE' => $_POST['USER']['PHONE'],
					'NAME' 			 => htmlspecialchars($_POST['USER']['NAME']),
					'LAST_NAME'		 => htmlspecialchars($_POST['USER']['LAST_NAME']),	
					'SECOND_NAME'	 => htmlspecialchars($_POST['USER']['SECOND_NAME']),	
				));
				
				$i_UserID = $USER->GetID();
				
				Klava1CExportUser::addActionAddUser( CUser::GetByID($i_UserID)->Fetch() );
				
				$ar_EventFields = array(
					'USER_NAME' 	=> $_POST['USER']['LAST_NAME'].' '.$_POST['USER']['NAME'].' '.$_POST['USER']['SECOND_NAME'],
					'USER_PASS' 	=> $s_Pass,
					'USER_LOGIN' 	=> $_POST['USER']['EMAIL'],
					'USER_EMAIL' 	=> $_POST['USER']['EMAIL']		
				);
				CEvent::Send('KLAVA_SEND_PASS', array('s1'), $ar_EventFields);
				CEvent::CheckEvents();
			}
		}
	}
	else
	{
		$i_UserID = $USER->GetID();
	}
	
	# Если нет пользователя то и нет заказа
	if( intval($i_UserID) > 0 )
	{
		$i_UserType = intval($_POST["USER_TYPE"]);
		$i_AllSumm  = floatval($_POST['ALL_SUMM']);
		
		$ar_Fields = array(
			'LID' 				=> SITE_ID,
			'PERSON_TYPE_ID' 	=> $i_UserType,
			'PAYED' 			=> 'N',
			'CANCELED' 			=> 'N',
			'STATUS_ID' 		=> 'N',
			'PRICE' 			=> $i_AllSumm,
			'CURRENCY' 			=> 'RUB',
			'USER_ID' 			=> intval($i_UserID),
			'PAY_SYSTEM_ID' 	=> intval($_POST['PAYMENT']),
			'PRICE_DELIVERY' 	=> floatval($_POST['DELIVERY_SUMM']),
			'DELIVERY_ID' 		=> intval($_POST['DELEVERY']),
			'DISCOUNT_VALUE' 	=> intval($_POST['DISCONT_SUMM']),
			'USER_DESCRIPTION' 	=> $_POST["DESCRIPTION"]
		);
		
		
		if (CModule::IncludeModule("statistic"))
			$ar_Fields["STAT_GID"] = CStatistic::GetEventParam();
		
		if( $i_NewOrderID = CSaleOrder::Add($ar_Fields) )
		{
			CSaleBasket::OrderBasket($i_NewOrderID, CSaleBasket::GetBasketUserID(), SITE_ID, false);
			CSaleOrder::Update($i_NewOrderID, array('COMMENTS' => 'ИМ13-0'.$i_NewOrderID));
			
			
			
			$rs_SaleProperty = CSaleOrderProps::GetList(array(), array("PERSON_TYPE_ID" => $i_UserType), false, false, array('ID', 'CODE', 'NAME'));
			$ar_ProeprtyID = array();
			while ($ar_SaleProperty = $rs_SaleProperty->Fetch())
			{
				$ar_ProeprtyID[$ar_SaleProperty['CODE']] = array('ID' => $ar_SaleProperty['ID'], 'NAME' => $ar_SaleProperty['NAME']);
			}
			
			$ar_PropertyValue = array();
			foreach ($_POST['USER'] as $s_Code => $s_Value)
			{
				$ar_PropertyValue[] = array(
					"ORDER_ID" 			=> $i_NewOrderID,
					"ORDER_PROPS_ID" 	=> $ar_ProeprtyID[$s_Code]['ID'],
					"NAME" 				=> $ar_ProeprtyID[$s_Code]['NAME'],
					"CODE" 				=> $s_Code,
					"VALUE" 			=> ($s_Code == 'PHONE') ? str_replace(array('-', '(', ')', ' '), array('', '', '', ''), $s_Value) : $s_Value
				);
			}
			
			foreach ($_POST['DELIVERY_ADRES'] as $s_Code => $s_Value)
			{
				$ar_PropertyValue[] = array(
					"ORDER_ID" 			=> $i_NewOrderID,
					"ORDER_PROPS_ID" 	=> $ar_ProeprtyID[$s_Code]['ID'],
					"NAME" 				=> $ar_ProeprtyID[$s_Code]['NAME'],
					"CODE" 				=> $s_Code,
					"VALUE" 			=> $s_Value
				);
			}
			
			if($i_UserType !== 1)
			{
				foreach ( $_POST['COMPANY_'.$i_UserType] as $s_Code => $s_Value )
				{
					$ar_PropertyValue[] = array(
						"ORDER_ID" 			=> $i_NewOrderID,
						"ORDER_PROPS_ID" 	=> $ar_ProeprtyID[$s_Code]['ID'],
						"NAME" 				=> $ar_ProeprtyID[$s_Code]['NAME'],
						"CODE" 				=> $s_Code,
						"VALUE" 			=> $s_Value
					);
				}
				
				foreach ( $_POST['COMPANY_BANK_'.$i_UserType] as $s_Code => $s_Value )
				{
					$ar_PropertyValue[] = array(
						"ORDER_ID" 			=> $i_NewOrderID,
						"ORDER_PROPS_ID" 	=> $ar_ProeprtyID[$s_Code]['ID'],
						"NAME" 				=> $ar_ProeprtyID[$s_Code]['NAME'],
						"CODE" 				=> $s_Code,
						"VALUE" 			=> $s_Value
					);
				}
				
				foreach ( $_POST['U_ADRES_'.$i_UserType] as $s_Code => $s_Value )
				{
					$ar_PropertyValue[] = array(
						"ORDER_ID" 			=> $i_NewOrderID,
						"ORDER_PROPS_ID" 	=> $ar_ProeprtyID[$s_Code]['ID'],
						"NAME" 				=> $ar_ProeprtyID[$s_Code]['NAME'],
						"CODE" 				=> $s_Code,
						"VALUE" 			=> $s_Value
					);
				}
			}	
			
			
			# Добавим данне по доставке в полном виде вместе с описанием
			$ar_Delivery = CSaleDelivery::GetByID(intval($_POST['DELEVERY']));
			$ar_PropertyValue[] = array(
				"ORDER_ID" 			=> $i_NewOrderID,
				"ORDER_PROPS_ID" 	=> $ar_ProeprtyID['DELIVERY']['ID'],
				"NAME" 				=> $ar_ProeprtyID['DELIVERY']['NAME'],
				"CODE" 				=> 'DELIVERY',
				"VALUE" 			=> $ar_Delivery['NAME']."\n".$ar_Delivery['DESCRIPTION']
			);

			$ar_PropertyValue[] = array(
				"ORDER_ID" 			=> $i_NewOrderID,
				"ORDER_PROPS_ID" 	=> $ar_ProeprtyID['DELIVERY_ID']['ID'],
				"NAME" 				=> $ar_ProeprtyID['DELIVERY_ID']['NAME'],
				"CODE" 				=> 'DELIVERY_ID',
				"VALUE" 			=> intval($_POST['DELEVERY'])
			);
			
			$ar_PropertyValue[] = array(
				"ORDER_ID" 			=> $i_NewOrderID,
				"ORDER_PROPS_ID" 	=> $ar_ProeprtyID['COMMENT']['ID'],
				"NAME" 				=> $ar_ProeprtyID['COMMENT']['NAME'],
				"CODE" 				=> 'COMMENT',
				"VALUE" 			=> $_POST['DESCRIPTION']
			);
			
			
			# Добавить ФИО для выгрузки в 1с. Только для Физлиц
			if($i_UserType == 1)
			{
				$ar_PropertyValue[] = array(
					"ORDER_ID" 			=> $i_NewOrderID,
					"ORDER_PROPS_ID" 	=> $ar_ProeprtyID['FIO']['ID'],
					"NAME" 				=> $ar_ProeprtyID['FIO']['NAME'],
					"CODE" 				=> 'FIO',
					"VALUE" 			=> $_POST['USER']['LAST_NAME'].' '.$_POST['USER']['NAME'].' '.$_POST['USER']['SECOND_NAME']
				);
			}	
			
			$i_UserProfileID = intval($_POST['USER_PROFILE_ID']);
			if($i_UserProfileID == 0)
			{
				$ar_ProfileParams = array(
					'USER_ID' 		 => $i_UserID,
					'NAME' 	  		 => (strlen($_POST['NEW_PROFILE_NAME']) > 0) ? $_POST['NEW_PROFILE_NAME'] : 'Новый профиль',
					'PERSON_TYPE_ID' => $i_UserType	
				);
				if($i_UserNewProfileID = CSaleOrderUserProps::Add($ar_ProfileParams))
				{
					Klava1CExportUserProfile::addActionAddProfile(CSaleOrderUserProps::GetByID($i_UserNewProfileID));
					foreach ($ar_PropertyValue as $ar_PropValue)
					{
						CSaleOrderUserPropsValue::Add(array(
							'USER_PROPS_ID' 	=> $i_UserNewProfileID,
							'ORDER_PROPS_ID' 	=> $ar_PropValue['ORDER_PROPS_ID'],
							'NAME' 				=> $ar_PropValue['NAME'],
							'VALUE' 			=> $ar_PropValue['VALUE']
						));
					}
					
					# Добавляем ID профиля в свойство заказа (нужно для экспорта в 1с)					
					$ar_PropertyValue[] = array(
						"ORDER_ID" 			=> $i_NewOrderID,
						"ORDER_PROPS_ID" 	=> $ar_ProeprtyID['PROFILE_ID']['ID'],
						"NAME" 				=> $ar_ProeprtyID['PROFILE_ID']['NAME'],
						"CODE" 				=> 'PROFILE_ID',
						"VALUE" 			=> $i_UserNewProfileID
					);
				}	
			}	
			else
			{
				# обновляем профиль и значения его свойтсв
				$ar_ProfileParams = array(
					'USER_ID' 		 => $i_UserID,
					//'NAME' 	  		 => (strlen($_POST['NEW_PROFILE_NAME']) > 0) ? $_POST['NEW_PROFILE_NAME'] : 'Новый профиль',
					'PERSON_TYPE_ID' => $i_UserType
				);
				if($i_UserNewProfileID = CSaleOrderUserProps::Update($i_UserProfileID, $ar_ProfileParams))
				{
					Klava1CExportUserProfile::addActionUpdateProfile(CSaleOrderUserProps::GetByID($i_UserNewProfileID));
					
					$rs_ProfProperty = CSaleOrderUserPropsValue::GetList(($b=""), ($o=""), Array("USER_PROPS_ID" => $i_UserNewProfileID));
					while ($ar_ProfProperty = $rs_ProfProperty->Fetch())
					{
						$ar_ProfileProprtyID[$ar_ProfProperty['CODE']] = $ar_ProfProperty['ID']; 
					}
					
					
					# Добавляем ID профиля в свойство заказа (нужно для экспорта в 1с)
					$ar_PropertyValue[] = array(
							"ORDER_ID" 			=> $i_NewOrderID,
							"ORDER_PROPS_ID" 	=> $ar_ProeprtyID['PROFILE_ID']['ID'],
							"NAME" 				=> $ar_ProeprtyID['PROFILE_ID']['NAME'],
							"CODE" 				=> 'PROFILE_ID',
							"VALUE" 			=> $i_UserNewProfileID
					);
					
					foreach ($ar_PropertyValue as $ar_PropValue)
					{
						CSaleOrderUserPropsValue::Update($ar_ProfileProprtyID[$ar_PropValue['CODE']], array(
							'USER_PROPS_ID' 	=> $i_UserNewProfileID,
							'ORDER_PROPS_ID' 	=> $ar_PropValue['ORDER_PROPS_ID'],
							'NAME' 				=> $ar_PropValue['NAME'],
							'VALUE' 			=> $ar_PropValue['VALUE']
						));
					}
				}
			}	
			
			
			# Добавляем значения свойств в заказ
			foreach ($ar_PropertyValue as $ar_Value)
			{
				CSaleOrderPropsValue::Add($ar_Value);
			}
			
			
			
			// mail message
			$strOrderList = "";
			$dbBasketItems = CSaleBasket::GetList(array("NAME" => "ASC"), array("ORDER_ID" => $i_NewOrderID), false, false, array("ID", "NAME", "QUANTITY"));
			while ($arBasketItems = $dbBasketItems->Fetch())
			{
				$strOrderList .= $arBasketItems["NAME"]." - ".$arBasketItems["QUANTITY"]." шт.";
				$strOrderList .= "\n";
			}
		
			$arFields = array(
				"ORDER_ID" 		=> $i_NewOrderID,
				"ORDER_DATE" 	=> Date($DB->DateFormatToPHP(CLang::GetDateFormat("SHORT", SITE_ID))),
				"ORDER_USER" 	=> $USER->GetFullName(),
				"PRICE" 		=> SaleFormatCurrency($i_AllSumm, 'RUB'),
				"BCC" 			=> COption::GetOptionString("sale", "order_email", "order@".$SERVER_NAME),
				"EMAIL" 		=> $USER->GetEmail(),
				"ORDER_LIST" 	=> $strOrderList,
				"SALE_EMAIL" 	=> COption::GetOptionString("sale", "order_email", "order@".$SERVER_NAME)
			);
		
			$eventName = "SALE_NEW_ORDER";
		
			$bSend = true;
			foreach(GetModuleEvents("sale", "OnOrderNewSendEmail", true) as $arEvent)
				if (ExecuteModuleEventEx($arEvent, Array($i_NewOrderID, &$eventName, &$arFields))===false)
				$bSend = false;
		 
			if($bSend)
			{
				$event = new CEvent;
				$event->Send($eventName, SITE_ID, $arFields, "N");
				CEvent::CheckEvents();
			}
		
			Klava1CExporOrder::addActionAddOrder(CSaleOrder::GetByID($i_NewOrderID));
			
			LocalRedirect('/personal/order/final/?ID='.$i_NewOrderID);
		}
		else
		{
			if($ex = $APPLICATION->GetException())
				$arResult["ERROR_MESSAGE"] .= $ex->GetString();
		}
		
	}
	else
	{
		$arResult['ERROR_ORDER'] = 'Ошибка при оформлении заказа, проверьте введенные данные.';
	}	
}	


$this->IncludeComponentTemplate();