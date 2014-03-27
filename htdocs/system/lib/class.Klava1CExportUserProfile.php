<? 
class Klava1CExportUserProfile
{
	public static function addActionAddProfile($arFields)
	{
		KlavaIntegrationMain::addAction('Добавление нового профиля пользователя', 'USER_PROFILE_ADD', $arFields);
	}

	
	public static function addActionUpdateProfile($arFields)
	{
		KlavaIntegrationMain::addAction('Обновление данных профиля пользователя', 'USER_PROFILE_UPDATE', $arFields);
	}

	
	public static function addActionDeleteProfile($i_UserID)
	{
		//KlavaIntegrationMain::addAction('Удаление профиля пользователя', 'USER_PROFILE_DELETE', CUser::GetByID($i_UserID)->Fetch());
	}

	
	private static function _getProfileProperty($i_ProfileID)
	{
		if(intval($i_ProfileID) <= 0)
			return; 
			
		$rs_Proeprty = CSaleOrderUserPropsValue::GetList(($b=""), ($o=""), array('USER_PROPS_ID' => $i_ProfileID));
		while ($ar_Property = $rs_Proeprty->Fetch())
		{
			$ar_Result[$ar_Property['CODE']] = $ar_Property['VALUE'];
		}
		
		return $ar_Result;
	}
	
	
	private static function _handler($arFields, $s_Action = 'add')
	{
		$s_LogDir = $_SERVER['DOCUMENT_ROOT'].'/system/sale.1c.integration/export.user.profile/logs/';
		$b_Debug = true;
		if($b_Debug)
		{
			$s_DateTime = date("d.m.Y_H:i:s");
			mkdir($s_LogDir.$s_DateTime, 0777);
			$s_LogPatch = $s_LogDir.$s_DateTime.'/';
		}
		
		$ar_User = CUser::GetByID($arFields['USER_ID'])->Fetch();
		
		switch ($arFields['PERSON_TYPE_ID'])
		{
			case 1: $s_UserType = 'ФизЛицо'; break;
			case 2: $s_UserType = 'ЮрЛицо' ; break;
			case 3: $s_UserType = 'ИндивидуальныйПредприниматель'; break;
		}
		

		
		$rs_ProfValue = CSaleOrderUserPropsValue::GetList(array(), array("USER_PROPS_ID" => $arFields['ID']));
		$ar_FIO = array();
		while ($ar_ProfValue = $rs_ProfValue->Fetch())
		{
			switch ($ar_ProfValue['PROP_CODE'])
			{
				case 'LAST_NAME': 	$ar_FIO['LAST_NAME'] = $ar_ProfValue['VALUE']; break;
				case 'NAME': 		$ar_FIO['NAME'] = $ar_ProfValue['VALUE']; break;
				case 'SECOND_NAME': $ar_FIO['SECOND_NAME'] = $ar_ProfValue['VALUE']; break;
			}
		}
		
		
		$s_XML_ID = ($s_Action == 'update') ? KlavaUserProfile::getProfileXMLID($arFields['ID']) : '00000000-0000-0000-0000-000000000000';
		
		$ar_Property = self::_getProfileProperty($arFields['ID']);	
		
		$s_XML = '<Array xmlns="http://v8.1c.ru/8.1/data/core" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
			$s_XML .= '<Value xsi:type="Structure">';
				
				$s_XML .= '<Property name="Наименование"><Value xsi:type="xs:string">'.$arFields['NAME'].'</Value></Property>';
				
				$s_XML .= '<Property name="ДополнительныеРеквизиты">';
					$s_XML .= '<Value xsi:type="Structure">';
						$s_XML .= '<Property name="Фамилия"><Value xsi:type="xs:string">'.$ar_FIO['LAST_NAME'].'</Value></Property>';
						$s_XML .= '<Property name="Имя"><Value xsi:type="xs:string">'.$ar_FIO['NAME'].'</Value></Property>';
						$s_XML .= '<Property name="Отчество"><Value xsi:type="xs:string">'.$ar_FIO['SECOND_NAME'].'</Value></Property>';
					$s_XML .= '</Value>';
				$s_XML .= '</Property>';
				 
				$s_XML .= '<Property name="ИНН"><Value xsi:type="xs:string">'.$ar_Property['INN'].'</Value></Property>';
				$s_XML .= '<Property name="Партнер"><Value xmlns:d4p1="http://v8.1c.ru/8.1/data/enterprise/current-config" xsi:type="d4p1:CatalogRef.Партнеры">'.$ar_User['XML_ID'].'</Value></Property>';
				$s_XML .= '<Property name="КодПоОКПО"><Value xsi:type="xs:string">'.$ar_Property['OGRN'].'</Value></Property>';
				$s_XML .= '<Property name="КПП"><Value xsi:type="xs:string">'.$ar_Property['KPP'].'</Value></Property>';
				$s_XML .= '<Property name="Ссылка"><Value xmlns:d4p1="http://v8.1c.ru/8.1/data/enterprise/current-config" xsi:type="d4p1:CatalogRef.Контрагенты">'.$s_XML_ID.'</Value></Property>';
				$s_XML .= '<Property name="ЮрФизЛицо"><Value xmlns:d4p1="http://v8.1c.ru/8.1/data/enterprise/current-config" xsi:type="d4p1:EnumRef.ЮрФизЛицо">'.$s_UserType.'</Value></Property>'; 
				$s_XML .= '<Property name="IDСайта"><Value xsi:type="xs:string">'.$arFields['ID'].'</Value></Property>';
				
				$s_XML .= '<Property name="ДанныеБанка">';
					$s_XML .= '<Value xsi:type="Structure">';
						$s_XML .= '<Property name="БИКбанка"><Value xsi:type="xs:string">'.$ar_Property['BIK_BANK'].'</Value></Property>';
						$s_XML .= '<Property name="КоррСчетБанка"><Value xsi:type="xs:string">'.$ar_Property['KS_BANK'].'</Value></Property>';
						$s_XML .= '<Property name="НаименованиеБанка"><Value xsi:type="xs:string">'.$ar_Property['NAME_BANK'].'</Value></Property>';
						$s_XML .= '<Property name="ГородБанка"><Value xsi:type="xs:string">'.$ar_Property['CITY_BANK'].'</Value></Property>';
					$s_XML .= '</Value>';
				$s_XML .= '</Property>';
				
				$s_XML .= '<Property name="ФактическийАдрес">';
					$s_XML .= '<Value xsi:type="Structure">';
						$s_XML .= '<Property name="ВнутригРайон"><Value xsi:nil="true"/></Property>';
						$s_XML .= '<Property name="ГОРОД"><Value xsi:type="xs:string">'.$ar_Property['CITY'].'</Value></Property>';
						$s_XML .= '<Property name="Местоположение"><Value xsi:nil="true"/></Property>';
						$s_XML .= '<Property name="НАСЕЛЕННЫЙПУНКТ"><Value xsi:type="xs:string"/></Property>';
						$s_XML .= '<Property name="Округ"><Value xsi:nil="true"/></Property>';
						$s_XML .= '<Property name="РЕГИОН"><Value xsi:type="xs:string"></Value></Property>';
						$s_XML .= '<Property name="УЛИЦА"><Value xsi:type="xs:string">'.$ar_Property['STREET'].'</Value></Property>';
						$s_XML .= '<Property name="ДОМ"><Value xsi:type="xs:string">'.$ar_Property['HOME'].'</Value></Property>';
						$s_XML .= '<Property name="КОРПУС"><Value xsi:type="xs:string">'.$ar_Property['KORPUS'].'</Value></Property>';
						$s_XML .= '<Property name="КВАРТИРА"><Value xsi:type="xs:string">'.$ar_Property['FLAT'].'</Value></Property>';
						$s_XML .= '<Property name="РАЙОН"><Value xsi:type="xs:string"/></Property>';
						$s_XML .= '<Property name="ИНДЕКС"><Value xsi:type="xs:string">'.$ar_Property['INDEX'].'</Value></Property>';
						$s_XML .= '<Property name="Представление"><Value xsi:type="xs:string"></Value></Property>';
						$s_XML .= '<Property name="Комментарий"><Value xsi:type="xs:string">'.$ar_Property['COMMENT'].'</Value></Property>';
						$s_XML .= '<Property name="СТРАНА"><Value xsi:type="xs:string">РОССИЯ</Value></Property>';
						$s_XML .= '<Property name="ПредставлениеОсновное"><Value xsi:type="xs:string"></Value></Property>';
					$s_XML .= '</Value>';
				$s_XML .= '</Property>';
				
				$s_XML .= '<Property name="ЮрАдрес">';
					$s_XML .= '<Value xsi:type="Structure">';
						$s_XML .= '<Property name="ВнутригРайон"><Value xsi:nil="true"/></Property>';
						$s_XML .= '<Property name="ГОРОД"><Value xsi:type="xs:string">'.$ar_Property['UCITY'].'</Value></Property>';
						$s_XML .= '<Property name="Местоположение"><Value xsi:nil="true"/></Property>';
						$s_XML .= '<Property name="НАСЕЛЕННЫЙПУНКТ"><Value xsi:type="xs:string"/></Property>';
						$s_XML .= '<Property name="Округ"><Value xsi:nil="true"/></Property>';
						$s_XML .= '<Property name="РЕГИОН"><Value xsi:type="xs:string"></Value></Property>';
						$s_XML .= '<Property name="УЛИЦА"><Value xsi:type="xs:string">'.$ar_Property['USTREET'].'</Value></Property>';
						$s_XML .= '<Property name="РАЙОН"><Value xsi:type="xs:string"/></Property>';
						$s_XML .= '<Property name="ДОМ"><Value xsi:type="xs:string">'.$ar_Property['UHOME'].'</Value></Property>';
						$s_XML .= '<Property name="КОРПУС"><Value xsi:type="xs:string">'.$ar_Property['UKORPUS'].'</Value></Property>';
						$s_XML .= '<Property name="КВАРТИРА"><Value xsi:type="xs:string">'.$ar_Property['UFLAT'].'</Value></Property>';
						$s_XML .= '<Property name="ИНДЕКС"><Value xsi:type="xs:string">'.$ar_Property['UINDEX'].'</Value></Property>';
						$s_XML .= '<Property name="Представление"><Value xsi:type="xs:string"></Value></Property>';
						$s_XML .= '<Property name="Комментарий"><Value xsi:type="xs:string"/></Property>';
						$s_XML .= '<Property name="СТРАНА"><Value xsi:type="xs:string">РОССИЯ</Value></Property>';
						$s_XML .= '<Property name="ПредставлениеОсновное"><Value xsi:type="xs:string"></Value></Property>';
					$s_XML .= '</Value>';
				$s_XML .= '</Property>';
				$s_XML .= '<Property name="НомерТелефона"><Value xsi:type="xs:string">'.$ar_Property['PHONE'].'</Value></Property>';
				$s_XML .= '<Property name="СчетаБанка"><Value xsi:type="xs:string">'.$ar_Property['RASCHET_SCHET'].'</Value></Property>';
				$s_XML .= '<Property name="АдресПочты"><Value xsi:type="xs:string">'.$ar_Property['EMAIL'].'</Value></Property>';
			$s_XML .= '</Value>';
		$s_XML .= '</Array>';
		
		($b_Debug) ? arraytofile(array('data' => $s_XML), $s_LogPatch.'выгрузка.xml', "data") : '';
		
		$client = new SoapClient('http://88.198.65.46:45454/TestBase/ws/Obmen?wsdl', array('login' => "Obmen", 'password' => "Obmen", 'exceptions' => 1));
		$data = $client->MainFunc(array('data' => $s_XML, 'type' => 'СправочникСсылка.Контрагенты' ))->return;
		
		return $data;
	}
	
	
	public static function add($arFields)
	{
		$data = self::_handler($arFields);
		if(strlen($data) > 0 && $data !== 'Ошибка десериализации')
		{
			require_once( $_SERVER['DOCUMENT_ROOT'].'/system/lib/xml_to_array.php' );
			$ar_XML = XML2Array::createArray($data);
			$s_XML_ID = $ar_XML['ValueTable']['row']['Value'][0]['@value'];
				
			if(strlen($s_XML_ID) > 0)
			{
				# Добавляем в таблицу соответствия
				KlavaUserProfile::addProfile($s_XML_ID, $arFields['ID']);
			}
			
			return $ar_XML;	
		}
	}


	public static function update($arFields)
	{
		$data = self::_handler($arFields, 'update');
		
		if(strlen($data) > 0 && $data !== 'Ошибка десериализации')
		{
			require_once( $_SERVER['DOCUMENT_ROOT'].'/system/lib/xml_to_array.php' );
			return XML2Array::createArray($data);
		}
	}
}