<?
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

require_once( '../../lib/xml_to_array.php' );
require_once( '../../lib/class.KlavaIntegrationMain.php' );

$s_LogDir = $_SERVER['DOCUMENT_ROOT'].'/system/sale.1c.integration/import.user/logs/';

$b_Debug = true;

if($b_Debug)
{
	$s_DateTime = date("d.m.Y_H:i:s");
	mkdir($s_LogDir.$s_DateTime, 0777);
	$s_LogPatch = $s_LogDir.$s_DateTime.'/';
}


# !!!
($b_Debug) ? arraytofile($_POST, $s_LogPatch.'POST.txt', "post") : '';
($b_Debug) ? arraytofile($_FILES, $s_LogPatch.'FILES.txt', "file") : '';
# !!!


$ar_Status = array();


if( $data = KlavaIntegrationMain::xml() )
{
	# !!!
	($b_Debug) ? arraytofile(array('data' => $data), $s_LogPatch.'data.xml', "data") : '';
	# !!!
	
	$ar_XML = XML2Array::createArray($data);
			
	# Синхронизуем массив при 2 митуациях, когда в xml 1 элемент и несколько
	if(isset($ar_XML['Array']['Value']['Property']))
	{
		$ar_XML['Array']['Value'][] = array('@attributes' =>  $ar_XML['Array']['Value']['@attributes'], 'Property' => $ar_XML['Array']['Value']['Property']);
		unset($ar_XML['Array']['Value']['Property']);
		unset($ar_XML['Array']['Value']['@attributes']);
	}	
	
	
	foreach ($ar_XML['Array']['Value'] as $ar_Value)
	{
		# СтандартныеРеквизиты
			$ar_StandartnyeRekvizity = $ar_Value['Property'][0]['Value']['Property'];
			foreach ($ar_StandartnyeRekvizity as $ar_Val1)
			{
				$ar_Result[$ar_Val1['@attributes']['name']] = $ar_Val1['Value']['@value'];
			}
			
			
		# СтандартныеРеквизиты 
			$ar_Rekvizity = $ar_Value['Property'][1]['Value']['Value'];
			if( isset($ar_Rekvizity[0]) )
			{
				foreach ($ar_Rekvizity as $ar_Val2)
				{
					$ar_Result[$ar_Val2['Property']['@attributes']['name']] = $ar_Val2['Property']['Value']['@value'];
				}
			}	
			else
			{
				$ar_Result[$ar_Rekvizity['Property']['@attributes']['name']] = $ar_Rekvizity['Property']['Value']['@value'];
			}	
	
			
		# МассивКонтактов 
			$ar_ArrayContacts = $ar_Value['Property'][2]['Value']['Value'];
			
			$ar_Result['Адрес']    = $ar_ArrayContacts[0]['Property'][1]['Value']['@value'];
			$ar_Result['Страна']   = $ar_ArrayContacts[0]['Property'][2]['Value']['@value'];
			$ar_Result['Регион']   = $ar_ArrayContacts[0]['Property'][3]['Value']['@value'];
			$ar_Result['Город']    = $ar_ArrayContacts[0]['Property'][4]['Value']['@value'];
			$ar_Result['Телефон']  = $ar_ArrayContacts[1]['Property'][1]['Value']['@value'];
			$ar_Result['Email']    = $ar_ArrayContacts[2]['Property'][1]['Value']['@value'];
			
			$ar_UserResult[] = $ar_Result;
	}	
	
	# !!!
	($b_Debug) ? arraytofile($ar_UserResult, $s_LogPatch.'ar_UserResult.txt', "ar_UserResult") : '';
	# !!!
	
	$ob_User = new CUser;
	$ar_Status = array();
	foreach ($ar_UserResult as $ar_ValueUser)
	{
		$ar_Fields = array(
			'XML_ID'			=> $ar_ValueUser['Ссылка'],	
			'UF_1C_DATA_REG'	=> $ar_ValueUser['ДатаРегистрации'],
			'UF_1C_FULL_NAME'	=> $ar_ValueUser['НаименованиеПолное'],
			'UF_UR_FIZ_LIZO'	=> $ar_ValueUser['ЮрФизЛицо'],
			'UF_1C_ADRES'		=> $ar_ValueUser['Адрес'],
			'UF_1C_COUNTRY'		=> $ar_ValueUser['Страна'],
			'UF_1C_REGION'		=> $ar_ValueUser['Регион'],
			'UF_1C_CITY'		=> $ar_ValueUser['Город'],
			'UF_1C_PHONE'		=> $ar_ValueUser['Телефон'],
			'UF_1C_COD'			=> $ar_ValueUser['Код'],
			'UF_1C_DEL'			=> $ar_ValueUser['ПометкаУдаления']
		);
		
		if($ar_ValueUser['ЮрФизЛицо'] == 'Частное лицо')
		{
			$ar_UserName = explode(' ', $ar_ValueUser['НаименованиеПолное']);
				
			$ar_Fields['LAST_NAME'] = $ar_UserName[0];
			$ar_Fields['NAME'] = $ar_UserName[1];
			$ar_Fields['SECOND_NAME'] = $ar_UserName[2];
		}
		else
		{
			$ar_Fields['NAME'] = $ar_ValueUser['НаименованиеПолное'];
		}

		if( ! check_email($ar_ValueUser['ЛогинВходаНаСайт']) )
		{
			$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_ValueUser['Ссылка'], false, 'не валидный Email');
			continue;
		}
		
		
		# !!!
		($b_Debug) ? arraytofile($ar_Fields, $s_LogPatch.'ar_UserFields.txt', "ar_UserFields") : '';
		# !!!
		
		# Проверяем нет есть ли такой пользователь, если есть то обновляем, если нет то добавляем
		$rs_User = CUser::GetList(($by=""), ($order=""), array('=EMAIL' => $ar_ValueUser['ЛогинВходаНаСайт']));
		if($ar_User = $rs_User->Fetch())
		{
			# !!!
			($b_Debug) ? arraytofile(array('action' => 'update'), $s_LogPatch.'action.txt', "action") : '';
			# !!!
			
			if($ob_User->Update($ar_User['ID'],  $ar_Fields))
			{
				$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_ValueUser['Ссылка'], true);
			}	
			else
			{
				$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_ValueUser['Ссылка'], false, 'ошибка изменения данных пользователя ['.$ob_User->LAST_ERROR.']');
			}	
		}
		else
		{
			# !!!
			($b_Debug) ? arraytofile(array('action' => 'add'), $s_LogPatch.'action.txt', "action") : '';
			# !!!
			
			
			$s_Pass = randString(7);
			
			$ar_Fields['EMAIL'] = $ar_ValueUser['ЛогинВходаНаСайт'];
			$ar_Fields['LOGIN'] = $ar_ValueUser['ЛогинВходаНаСайт'];
			$ar_Fields['ACTIVE'] = 'Y';
			$ar_Fields['GROUP_ID'] = array(8);
			$ar_Fields['PASSWORD'] = $s_Pass;
			$ar_Fields['CONFIRM_PASSWORD'] = $s_Pass;
			
			if($i_UserID = $ob_User->Add($ar_Fields))
			{
				$ar_Status[] = array('1C_ID' => $ar_ValueUser['Ссылка'], 'STATUS' => true, 'REPORT' => 'Успешно');
			}
			else
			{
				$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_ValueUser['Ссылка'], false, 'ошибка добавления нового пользователя ['.$ob_User->LAST_ERROR.']');
			}
		}
	}	

	# !!!
	($b_Debug) ? arraytofile($ar_Status, $s_LogPatch.'ar_Status.txt', "ar_Status") : '';
	# !!!
	
	header('Content-Type: text/xml');
	echo KlavaIntegrationMain::getReturnXML($ar_Status);
}
else
{
	# !!!
	($b_Debug) ? arraytofile(array('data' => 'error'), $s_LogPatch.'error.txt', "error") : '';
	# !!!
}	
		