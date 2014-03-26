<?
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

require_once( '../../lib/xml_to_array.php' );
require_once( '../../lib/class.KlavaIntegrationMain.php' );
require_once( '../../lib/class.KlavaUserProfile.php' );


$s_LogDir = $_SERVER['DOCUMENT_ROOT'].'/system/sale.1c.integration/import.profile.user/logs/';

$b_Debug = INTEGRATION_DEBUG_LOG;

if($b_Debug)
{
	$s_DateTime = date("d.m.Y_H:i:s");
	mkdir( $s_LogDir.$s_DateTime, 0777);
	$s_LogPatch = $s_LogDir.$s_DateTime.'/';
}	

# !!!
($b_Debug) ? arraytofile($_POST, $s_LogPatch.'массив_POST.txt', "post") : '';
($b_Debug) ? arraytofile($_FILES, $s_LogPatch.'массив_FILES.txt', "file") : '';
# !!!


 

# 'test.xml/xml1.xml'
if( $data = KlavaIntegrationMain::xml() )
{
	# !!!
	($b_Debug) ? arraytofile(array('data' => $data), $s_LogPatch.'полученный_xml_файл_из_1с.xml', "data") : '';
	# !!!
	
	$ar_XML = XML2Array::createArray($data);
			
	if(isset($ar_XML['Array']['Value']['Property']))
	{
		$ar_XML['Array']['Value'][] = array('@attributes' => $ar_XML['Array']['Value']['@attributes'], 'Property' => $ar_XML['Array']['Value']['Property']);
		unset($ar_XML['Array']['Value']['Property']);
		unset($ar_XML['Array']['Value']['@attributes']);
	}
			
			
	foreach ($ar_XML['Array']['Value'] as $ar_Value)
	{
		$ar_Params = array();
		foreach ($ar_Value['Property'] as $ar_Val)
		{
			$s_Type = $ar_Val['Value']['@attributes']['type'];
	
			if( $s_Type == 'xs:string' || $s_Type == 'd4p1:CatalogRef.Партнеры' || $s_Type == 'd4p1:EnumRef.ЮрФизЛицо' || $s_Type == 'd4p1:CatalogRef.Контрагенты')
				$ar_Params[$ar_Val['@attributes']['name']] = $ar_Val['Value']['@value'];
	
	
			if($s_Type == 'Array')
			{
				$ar_RecValue = array();
				foreach ($ar_Val['Value']['Value'] as $ar_RecVal)
					$ar_RecValue[$ar_RecVal['Property']['@attributes']['name']] = $ar_RecVal['Property']['Value']['@value'];
	
				$ar_Params[$ar_Val['@attributes']['name']] = $ar_RecValue;
			}
	
	
			if($s_Type == 'Structure')
			{
				$ar_Params1 = array();
				foreach ($ar_Val['Value']['Property'] as $ar_Val1)
					$ar_Params1[$ar_Val1['@attributes']['name']] = $ar_Val1['Value']['@value'];
					
				$ar_Params[$ar_Val['@attributes']['name']] = $ar_Params1;
			}
		}
	
		$ar_UserResult[] = $ar_Params; 
	}
			
	# !!!
	($b_Debug) ? arraytofile($ar_UserResult, $s_LogPatch.'массив_поле_разбора_xml_ar_UserResult.xml', "ar_UserResult") : '';
	# !!!
	
	
	foreach ($ar_UserResult as $ar_Value)
	{
		if( strlen($ar_Value['Партнер']) > 0 )
		{
			# ищем пользователя
			$rs_Users = CUser::GetList(($by=""), ($order=""), array('XML_ID' => $ar_Value['Партнер']), array("SELECT" => array("UF_*")));
			if($ar_Users = $rs_Users->NavNext(true, false))
			{
				$i_UserType = false;
				switch ($ar_Value['ЮрФизЛицо'])
				{
					case 'ФизЛицо': $i_UserType = 1; break;
					case 'ЮрЛицо' : $i_UserType = 2; break;
					case 'ИндивидуальныйПредприниматель': $i_UserType = 3; break;
				}
					
				if( intval($i_UserType) > 0 )
				{
					# Значения для любого типа плательщика
					$s_City = '';
					if(strlen($ar_Value['ФактическийАдрес']['Город']) > 0)
						$s_City = $ar_Value['ФактическийАдрес']['Город'];
					elseif(strlen($ar_Value['ФактическийАдрес']['НаселПункт']) > 0)
						$s_City = $ar_Value['ФактическийАдрес']['НаселПункт'];
					elseif(strlen($ar_Value['ФактическийАдрес']['СубъектРФ']) > 0)
						$s_City = $ar_Value['ФактическийАдрес']['СубъектРФ'];

					$ar_IntegratValue = array(
						'CITY'   		=> $s_City,
						'STREET' 		=> $ar_Value['ФактическийАдрес']['Улица'],
						'INDEX'  		=> $ar_Value['ФактическийАдрес']['Индекс'],
						'HOME'   		=> $ar_Value['ФактическийАдрес']['Дом'],
						'KORPUS' 		=> $ar_Value['ФактическийАдрес']['Корпус'],
						'FLAT'   		=> $ar_Value['ФактическийАдрес']['Кв'],
						'PHONE'  		=> $ar_Value['НомерТелефона'],
						'EMAIL'  		=> $ar_Value['АдресПочты'],
						'NAME'        	=> $ar_Value['Реквизиты']['Имя'],
						'LAST_NAME'   	=> $ar_Value['Реквизиты']['Фамилия'],
						'SECOND_NAME' 	=> $ar_Value['Реквизиты']['Отчество'],
						'DELIVERY' 		=> $ar_Value['Реквизиты']['АдресСлужбыДоставки'],
					);
	
					if( strpbrk(';', $ar_Value['НомерТелефона']) !== false )
					{
						$ar_Tel = explode(';', $ar_Value['НомерТелефона']);
						$ar_Value['НомерТелефона'] = $ar_Tel[0];
					}
						
					switch ($i_UserType)
					{
						case 1:
								
							$s_FIO = '';
							if( strlen($ar_Value['Реквизиты']['Фамилия']) > 0 )
								$s_FIO .= $ar_Value['Реквизиты']['Фамилия'];
							
							if(strlen($ar_Value['Реквизиты']['Имя']) > 0)
								$s_FIO .= ' '.$ar_Value['Реквизиты']['Имя'];
							
							if(strlen($ar_Value['Реквизиты']['Отчество']) > 0)
								$s_FIO .= ' '.$ar_Value['Реквизиты']['Отчество'];
							
							if(strlen($s_FIO) == 0 && strlen($ar_Value['Наименование']) > 0)
								$s_FIO = $ar_Value['Наименование'];
							
							$ar_IntegratValue['FIO'] = $s_FIO;
								
							break;
	
						default: # Для 2 и 3
								
							$ar_IntegratValue['INN'] = $ar_Value['ИНН'];
							$ar_IntegratValue['OGRN'] = $ar_Value['КодПоОКПО'];
							$ar_IntegratValue['RASCHET_SCHET'] = $ar_Value['СчетаБанка']['НомерСчета'];
							$ar_IntegratValue['COMPANY_NAME'] = $ar_Value['Наименование'];
							$ar_IntegratValue['UCITY'] = $ar_Value['ЮрАдрес']['Город'];
							$ar_IntegratValue['USTREET'] = $ar_Value['ЮрАдрес']['Улица'];
							$ar_IntegratValue['UHOME'] = $ar_Value['ЮрАдрес']['Дом'];
							$ar_IntegratValue['UKORPUS'] = $ar_Value['ЮрАдрес']['Корпус'];
							$ar_IntegratValue['UFLAT'] = $ar_Value['ЮрАдрес']['Кв'];
							$ar_IntegratValue['UINDEX'] = $ar_Value['ЮрАдрес']['Индекс'];
							$ar_IntegratValue['BIK_BANK'] = $ar_Value['СчетаБанка']['БИКБанка'];
							$ar_IntegratValue['KS_BANK'] = $ar_Value['СчетаБанка']['КоррСчетБанка'];
							$ar_IntegratValue['BANK_NAME'] = $ar_Value['СчетаБанка']['Банк'];
							$ar_IntegratValue['CITY_BANK'] = $ar_Value['СчетаБанка']['ГородБанка'];
								
							if($i_UserType == 2) # Для ЮР
								$ar_IntegratValue['KPP'] = $ar_Value['КПП'];
								
							break;
					}
	
					# !!!
					($b_Debug) ? arraytofile($ar_IntegratValue, $s_LogPatch.'массив_свойств_профиля_ar_IntegratValue.txt', "ar_IntegratValue") : '';
					# !!!
								
					
					#  Выбираем все свойства для профиля типа пользователя $i_UserType
					$rs_SaleProperty = CSaleOrderProps::GetList(array(), array("PERSON_TYPE_ID" => $i_UserType));
					$ar_PropertyParams = array();
					while ($ar_SaleProperty = $rs_SaleProperty->Fetch())
						$ar_PropertyParams[$ar_SaleProperty['CODE']] = array('ID' => $ar_SaleProperty['ID'], 'CODE' => $ar_SaleProperty['CODE'], 'NAME' => $ar_SaleProperty['NAME']) ;
									
	
					# ищем ID профиля, если нет то добавляем профиль
					if( $i_ProfileID = KlavaUserProfile::getProfileID($ar_Value['Ссылка']) )
					{
						# !!!
						($b_Debug) ? arraytofile(array('action' => 'add'), $s_LogPatch.'действие_над_элементом_[ДОБАВЛЕНИЕ]', "") : '';
						# !!!
						
						if ($ar_Profile = CSaleOrderUserProps::GetByID($i_ProfileID))
							$i_CurrentPersonalType = $ar_Profile['PERSON_TYPE_ID'];
							
						# Если тип плательщика не поменялся, просто обновляем поля
						if( $i_CurrentPersonalType == $i_UserType)
						{
							$rs_PropertyValue = CSaleOrderUserPropsValue::GetList(($b=""), ($o=""), array("USER_PROPS_ID" => $i_ProfileID));
							while ($ar_PropertyValue = $rs_PropertyValue->Fetch())
							{
								$value = $ar_IntegratValue[$ar_PropertyValue['CODE']];
									
								if($ar_PropertyValue['CODE'] == 'PROFILE_ID')
									$value = $i_ProfileID;
								
								
								CSaleOrderUserPropsValue::Update(
									$ar_PropertyValue['ID'], 
									array(
										'USER_PROPS_ID' 	=> $i_ProfileID, 
										'ORDER_PROPS_ID' 	=> $ar_PropertyValue['ORDER_PROPS_ID'], 
										'VALUE' 			=> $value
									)
								);
							}
						}
						else
						{
							CSaleOrderUserProps::Update($i_ProfileID, array('PERSON_TYPE_ID' => $i_UserType));
	
							# Если меняется тип плательщика то удаляем все свойства и заполняем заного
							CSaleOrderUserPropsValue::DeleteAll($i_ProfileID);
								
							# Добавлем свойства
							foreach ($ar_PropertyParams as $s_Code => $ar_PropParamVal)
							{
								$value = $ar_IntegratValue[$s_Code];
									
								if($s_Code == 'PROFILE_ID')
									$value = $i_NewProfileID;
								
								CSaleOrderUserPropsValue::Add(array(
										'USER_PROPS_ID' 	=> $i_ProfileID, 
										'ORDER_PROPS_ID' 	=> $ar_PropParamVal['ID'], 
										'NAME' 				=> $ar_PropParamVal['NAME'], 
										'VALUE' 			=> $value
									)
								);
							}
						}
	
						$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_Value['Ссылка'], true);
					}
					else
					{
						# !!!
						($b_Debug) ? arraytofile(array('action' => 'update'), $s_LogPatch.'действие_над_элементом_[ОБНОВЛЕНИЕ]', "") : '';
						# !!!
						
						if( strlen($ar_IntegratValue['PHONE']) > 0 || strlen($ar_IntegratValue['EMAIL']) > 0 )
						{
							# Создаем профиль
							if($i_NewProfileID = CSaleOrderUserProps::Add(array('NAME' => 'Новый профиль', 'USER_ID' => $ar_Users['ID'], 'PERSON_TYPE_ID' => $i_UserType)))
							{
								# Добавляем новый ID профиля для сопоставления
								KlavaUserProfile::addProfile($ar_Value['Ссылка'], $i_NewProfileID);

								# К созданному профиля добавлем свойства
								foreach ($ar_PropertyParams as $s_Code => $ar_PropParamVal)
								{
									$value = $ar_IntegratValue[$s_Code];
									
									if($s_Code == 'PROFILE_ID')
										$value = $i_NewProfileID;
									
									CSaleOrderUserPropsValue::Add(array(
										'USER_PROPS_ID' 	=> $i_NewProfileID, 
										'ORDER_PROPS_ID' 	=> $ar_PropParamVal['ID'], 
										'NAME' 				=> $ar_PropParamVal['NAME'], 
										'VALUE' 			=> $value
										)
									);
								}
	
								$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_Value['Ссылка'], true);
							}
							else
							{
								$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_Value['Ссылка'], false, 'не удалось создать новый профиль');
							}
						}
						else
						{
							$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_Value['Ссылка'], false, 'не указан email, не указан телефон');
						}
					}
				}
				else
				{
					$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_Value['Ссылка'], false, 'нет определен тип пользователя');
				}
				}
				else
				{
					$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_Value['Ссылка'], false, 'не найден партнер '.$ar_Value['Партнер']);
				}
			}
			else
			{
				$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_Value['Ссылка'], false, 'нет значения поля Партнер');
			}
		}

		# !!!
		($b_Debug) ? arraytofile($ar_Status, $s_LogPatch.'массив_статусов_по_каждому_элементу_ar_Status.txt', "ar_Status") : '';
		# !!!
		
		header('Content-Type: text/xml');
		echo KlavaIntegrationMain::getReturnXML($ar_Status);
}	
		

