<? 
class KlavaIntegrationMain
{
	# ID инфоблока с очередью выгрузки
	const ACTION_IBLOCK_ID = 43;
	

	public static function getReturnXML( $ar_Value )
	{
		$s_XMLString  = "<ValueTable xmlns=\"http://v8.1c.ru/8.1/data/core\" xmlns:xs=\"http://www.w3.org/2001/XMLSchema\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">";
		$s_XMLString .= "<column><Name>ID</Name><ValueType><Type>UUID</Type></ValueType></column>";
		//$s_XMLString .= "<column><Name>".utf8win1251('Успех')."</Name><ValueType><Type>xs:boolean</Type></ValueType></column>";
		//$s_XMLString .= "<column><Name>".utf8win1251('Причина')."</Name><ValueType><Type>xs:string</Type><StringQualifiers><Length>0</Length><AllowedLength>Variable</AllowedLength></StringQualifiers></ValueType></column>";
		$s_XMLString .= "<column><Name>Успех</Name><ValueType><Type>xs:boolean</Type></ValueType></column>";
		$s_XMLString .= "<column><Name>Причина</Name><ValueType><Type>xs:string</Type><StringQualifiers><Length>0</Length><AllowedLength>Variable</AllowedLength></StringQualifiers></ValueType></column>";

		foreach ($ar_Value as $ar_ItemValue)
		{
			$s_XMLItem  = "<row>"; 
			$s_XMLItem .= "<Value xsi:type=\"UUID\">".$ar_ItemValue['1C_ID']."</Value>";
			$s_XMLItem .= "<Value xsi:type=\"xs:boolean\">".($ar_ItemValue['STATUS'])."</Value>";
			$s_XMLItem .= "<Value xsi:type=\"xs:string\">".$ar_ItemValue['REPORT']."</Value>";
			$s_XMLItem .= "</row>";
			
			$s_XMLString .= $s_XMLItem; 
		}	
		
		$s_XMLString .= "</ValueTable>";
		
		return $s_XMLString;
		
		/* Ответ 1с о выполненных действиях
		<ValueTable xmlns="http://v8.1c.ru/8.1/data/core" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
			<column><Name>ID</Name><ValueType><Type>UUID</Type></ValueType></column>
			<column><Name>Успех</Name><ValueType><Type>xs:boolean</Type></ValueType></column>
			<column><Name>Причина</Name><ValueType><Type>xs:string</Type><StringQualifiers><Length>0</Length><AllowedLength>Variable</AllowedLength></StringQualifiers></ValueType></column>
			<row>
				<Value xsi:type="UUID">00000000-0000-0000-0000-000000000000</Value>
				<Value xsi:type="xs:boolean">false</Value>
				<Value xsi:type="xs:string">121312312312цуацуапеуца</Value>
			</row>
			<row>
				<Value xsi:type="UUID">00000000-0000-0000-0000-000000000000</Value>
				<Value xsi:type="xs:boolean">true</Value>
				<Value xsi:type="xs:string" />
			</row>
		</ValueTable>
		*/
	}
	
	
	public static function arrayItemStatus($s_XmlID, $b_Status, $s_Report)
	{
		return array('1C_ID'  => $s_XmlID, 'STATUS' => $b_Status, 'REPORT' => (!$b_Status) ? 'Ошибка: '.$s_Report : 'Успешно');
	}
	
	
	public static function xml($s_TestXMl = false)
	{
		if( strlen($s_TestXMl) > 0 )
		{
			return file_get_contents($s_TestXMl);
		}
		else if ( $_SERVER['HTTP_USER_AGENT'] != '1C+Enterprise/8.3')
		{
			@header('HTTP/1.0 403 Forbidden');
			die('Hacking attempt');
		}
		else
		{
			$ar_UploadFile = $_FILES['datafile'];
			$s_TmpName = $ar_UploadFile['tmp_name'];
			if ( ! is_uploaded_file($s_TmpName) )
			{
				die('Error loading file');
			}
			else
			{
				return iconv("cp1251", "UTF-8", file_get_contents($s_TmpName));
			}
		}
	}


	public static function getDeliveryName($s_XML_ID)
	{
		if(strlen($s_XML_ID) <= 0)
			return; 
		
		$rs_Element = CIBlockElement::GetList(
			array(), 
			array('IBLOCK_ID' => 38, 'PROPERTY_LINK' => $s_XML_ID), 
			false, 
			false, 
			array('ID', 'NAME')
		);
		if($ar_Element = $rs_Element->Fetch(true, false))
		{
			return $ar_Element['NAME'];
		}
	}

	
	/**
	 * Функция добавляет в очередь элемент операции 
	 * @param unknown_type $s_Name - наименование операции
	 * @param unknown_type $s_Code - символьный код операции
	 * @param unknown_type $ar_Params - массив параметров (будет сериализован в строку) 
	 */
	public static function addAction($s_Name, $s_Code, $ar_Params)
	{
		$ob_Element = new CIBlockElement;
		$ob_Element->Add(array(
			'IBLOCK_ID' 	  => self::ACTION_IBLOCK_ID,
			'CODE'			  => $s_Code,	
			'PREVIEW_TEXT' 	  => serialize($ar_Params),
			'PREVIEW_TYPE' 	  => 'text',
			'NAME' 			  => $s_Name
		));
	}
	
	
	public static function getAction($i_Limit = false)
	{
		$ar_Nav = false;
		if(intval($i_Limit) > 0)
			$ar_Nav = array('nPageSize' => intval($i_Limit))
		
		$rs_Element = CIBlockElement::GetList(array('ID' => 'ASC'), array('IBLOCK_ID' => self::ACTION_IBLOCK_ID, 'ACTIVE' => 'Y'), false, $ar_Nav, array('ID', 'CODE', 'PREVIEW_TEXT'));
		while($ar_Element = $rs_Element->Fetch())
		{
			$ar_Result[] = $ar_Element; 			
		}
		
		return $ar_Result; 
	}
	
	/*
	public static function setStartTimeAction($i_ElementID)
	{
		$ob_Element = new CIBlockElement; 
		$ob_Element->Update($i_ElementID, array('DATE_ACTIVE_FROM' => ConvertTimeStamp(time(), 'FULL')));
	}
	
	
	public static function setEndTimeAction($i_ElementID)
	{
		$ob_Element = new CIBlockElement;
		$ob_Element->Update($i_ElementID, array('DATE_ACTIVE_TO' => ConvertTimeStamp(time(), 'FULL'), 'ACTIVE' => 'N'));
		
	}
	*/
	
	public static function updateUserField($i_UserID, $ar_Fields)
	{
		$ID = intval($i_UserID);
	
		if(intval($ID) <= 0 || count($ar_Fields) == 0)
			return false;
	
		global $DB;
		$res = $DB->Query("UPDATE b_user SET ".$DB->PrepareUpdate("b_user", $ar_Fields)." WHERE ID=".$ID, true);
		return (!$res) ? false : true;
	}






	public static function exportReport($i_ElementID, $ar_Report)
	{
		if(!is_array($ar_Report) || count($ar_Report) == 0)
			$b_Error = true;
		
		$b_Status 	  = ($b_Error) ? 'false' : $ar_Report['ValueTable']['row']['Value'][2]['@value'];
		$s_StatusText = ($b_Error) ? 'Критическая ошибка' : $ar_Report['ValueTable']['row']['Value'][3]['@value'];
		
		if($b_Status == 'true')
		{
			$s_StatusText = 'Успешно';
			$ob_Element = new CIBlockElement;
			$ob_Element->Update($i_ElementID, array('ACTIVE' => 'N', 'PROPERTY_VALUES' => array('REPORT' => $s_StatusText)));
		}
		else
		{
			CIBlockElement::SetPropertyValueCode($i_ElementID, 'ACTIVE' => 'N', 'REPORT', $s_StatusText);
		}
		
		return array($b_Status, $s_StatusText);
	}



}