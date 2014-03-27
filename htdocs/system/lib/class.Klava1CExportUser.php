<? 
class Klava1CExportUser
{
	public static function addActionAddUser($arFields)
	{
		KlavaIntegrationMain::addAction('Добавление нового пользователя', 'USER_ADD', $arFields);
	}

	
	public static function addActionUpdateUser($arFields)
	{
		KlavaIntegrationMain::addAction('Обновление данных пользователя', 'USER_UPDATE', $arFields);
	}

	
	public static function addActionDeleteUser($i_UserID)
	{
		KlavaIntegrationMain::addAction('Удаление пользователя', 'USER_DELETE', CUser::GetByID($i_UserID)->Fetch());
	}


	private static function _handler($arFields, $s_Action = 'add')
	{
		$s_LogDir = $_SERVER['DOCUMENT_ROOT'].'/system/sale.1c.integration/export.user/logs/';

		$b_Debug = true;

		if($b_Debug)
		{
			$s_DateTime = date("d.m.Y_H:i:s");
			mkdir($s_LogDir.$s_DateTime, 0777);
			$s_LogPatch = $s_LogDir.$s_DateTime.'/';
		}


		//($b_Debug) ? arraytofile($arFields, $s_LogPatch.'arFields.xml', "arFields") : '';

		$s_XML = '<Array xmlns="http://v8.1c.ru/8.1/data/core" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
			$s_XML .= '<Value xsi:type="Structure">';
			
				$s_XML .= '<Property name="СтандартныеРеквизиты">';
					$s_XML .= '<Value xsi:type="Structure">';
					
						if( strlen($arFields['NAME']) == 0 && strlen($arFields['LAST_NAME']) == 0 && strlen($arFields['SECOND_NAME']) == 0 )
							$s_Name = 'Новый пользователь';
						else
							$s_Name = $arFields['NAME'].' '.$arFields['LAST_NAME'].' '.$arFields['SECOND_NAME'];
					
						$s_XML .= '<Property name="НаименованиеПолное"><Value xsi:type="xs:string">'.$s_Name.'</Value></Property>';
						$s_XML .= '<Property name="ЮрФизЛицо"><Value xmlns:d6p1="http://v8.1c.ru/8.1/data/enterprise/current-config" xsi:type="d6p1:EnumRef.КомпанияЧастноеЛицо">ЧастноеЛицо</Value></Property>';
						$s_XML .= '<Property name="Пол"><Value xmlns:d6p1="http://v8.1c.ru/8.1/data/enterprise/current-config" xsi:type="d6p1:EnumRef.ПолФизическогоЛица"></Value></Property>';

						$_Del = ($s_Action == 'delete') ? 'true' : 'false';
						$s_XML .= '<Property name="ПометкаУдаления"><Value xsi:type="xs:boolean">'.$_Del.'</Value></Property>';
				
						$s_XML .= '<Property name="IDСайта"><Value xsi:type="xs:string">'.$arFields['ID'].'</Value></Property>';
				
						$s_XML .= '<Property name="ДатаРождения"><Value xsi:type="xs:dateTime">0001-01-01T00:00:00</Value></Property>';
						$s_XML .= '<Property name="Код"><Value xsi:type="xs:string"></Value></Property>';
						$s_XML .= '<Property name="ДатаРегистрации"><Value xsi:type="xs:dateTime">'.date("Y-m-d\Th:i:s+04:00", strtotime($arFields['DATE_REGISTER'])).'</Value></Property>';

						$s_XML_ID = ($s_Action == 'add') ? '00000000-0000-0000-0000-000000000000' : $arFields['XML_ID'];
						 
						$s_XML .= '<Property name="Ссылка"><Value xmlns:d6p1="http://v8.1c.ru/8.1/data/enterprise/current-config" xsi:type="d6p1:CatalogRef.Партнеры">'.$s_XML_ID.'</Value></Property>';
						

					$s_XML .= '</Value>';
				
				$s_XML .= '</Property>';
				
				$s_XML .= '<Property name="Реквизиты">';
					$s_XML .= '<Value xsi:type="Array">';
						
						$s_XML .= '<Value xsi:type="Structure">';
							$s_XML .= '<Property name="ВидКлиента"><Value xsi:type="xs:string"></Value></Property>';
						$s_XML .= '</Value>';
					
						$s_XML .= '<Value xsi:type="Structure">';
							$s_XML .= '<Property name="ЛогинВходаНаСайт"><Value xsi:type="xs:string">'.$arFields['LOGIN'].'</Value></Property>';
						$s_XML .= '</Value>';
					
					
					$s_XML .= '</Value>';
				$s_XML .= '</Property>';
			
			
				$s_XML .= '<Property name="МассивКонтактов">';
					$s_XML .= '<Value xsi:type="Array">';
					
						$s_XML .= '<Value xsi:type="Structure">';
							$s_XML .= '<Property name="Тип"><Value xmlns:d7p1="http://v8.1c.ru/8.1/data/enterprise/current-config" xsi:type="d7p1:EnumRef.ТипыКонтактнойИнформации">Адрес</Value></Property>';
							$s_XML .= '<Property name="Вид"><Value xsi:type="xs:string"></Value></Property>';
							$s_XML .= '<Property name="Представление"><Value xsi:type="xs:string"></Value></Property>';
							$s_XML .= '<Property name="Страна"><Value xsi:type="xs:string"></Value></Property>';
							$s_XML .= '<Property name="Регион"><Value xsi:type="xs:string"></Value></Property>';
							$s_XML .= '<Property name="Город"><Value xsi:type="xs:string"/></Property>';
						$s_XML .= '</Value>';
						
						$s_XML .= '<Value xsi:type="Structure">';
							$s_XML .= '<Property name="Тип"><Value xmlns:d7p1="http://v8.1c.ru/8.1/data/enterprise/current-config" xsi:type="d7p1:EnumRef.ТипыКонтактнойИнформации">Телефон</Value></Property>';
							$s_XML .= '<Property name="Вид"><Value xsi:type="xs:string">Телефон</Value></Property>';
							$s_XML .= '<Property name="Представление"><Value xsi:type="xs:string">'.$arFields['PERSONAL_PHONE'].'</Value></Property>';
							$s_XML .= '<Property name="Страна"><Value xsi:type="xs:string"/></Property>';
							$s_XML .= '<Property name="Регион"><Value xsi:type="xs:string"/></Property>';
							$s_XML .= '<Property name="Город"><Value xsi:type="xs:string"/></Property>';
						$s_XML .= '</Value>';
				
					$s_XML .= '</Value>';
				$s_XML .= '</Property>';
			
			$s_XML .= '</Value>';

		
		$s_XML .= '</Array>';

		$client = new SoapClient('http://88.198.65.46:45454/TestBase/ws/Obmen?wsdl', array('login' => "Obmen", 'password' => "Obmen", 'exceptions' => 0));

		$data = $client->MainFunc(array('data' => $s_XML, 'type' => 'СправочникСсылка.Партнеры' ))->return;

		//($b_Debug) ? arraytofile(array('s_XML' => $s_XML), $s_LogPatch.'soapResult.xml', "s_XML") : '';
		($b_Debug) ? arraytofile(array('data' => $data), $s_LogPatch.'ответ_1с.xml', "") : '';
		($b_Debug) ? arraytofile(array('action' => $s_XML), $s_LogPatch.'выгрузка.xml', "") : '';

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
				$USER = new CUser();
				$USER->Update($arFields['ID'], array('XML_ID' => $s_XML_ID));
			}
			
			return $ar_XML;
		}
	}


	public static function update($arFields)
	{
		$_ar = CUser::GetByID($arFields['ID'])->Fetch();
		$arFields['XML_ID'] = $_ar['XML_ID'];
		$data = self::_handler($arFields, 'update');
		
		if(strlen($data) > 0 && $data !== 'Ошибка десериализации')
		{
			require_once( $_SERVER['DOCUMENT_ROOT'].'/system/lib/xml_to_array.php' );
			return XML2Array::createArray($data);
		}
	}

 
	# не используется, но если нужно то пожалуйста
	public static function delete($arFields)
	{	
		self::_handler($arFields, 'delete'); 
	}

}