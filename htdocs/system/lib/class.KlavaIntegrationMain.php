<? 
class KlavaIntegrationMain
{
	

	public static function getReturnXML( $ar_Value )
	{
		$s_XMLString  = "<ValueTable xmlns=\"http://v8.1c.ru/8.1/data/core\" xmlns:xs=\"http://www.w3.org/2001/XMLSchema\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">";
		$s_XMLString .= "<column><Name>ID</Name><ValueType><Type>UUID</Type></ValueType></column>";
		$s_XMLString .= "<column><Name>".utf8win1251('Успех')."</Name><ValueType><Type>xs:boolean</Type></ValueType></column>";
		$s_XMLString .= "<column><Name>".utf8win1251('Причина')."</Name><ValueType><Type>xs:string</Type><StringQualifiers><Length>0</Length><AllowedLength>Variable</AllowedLength></StringQualifiers></ValueType></column>";

		foreach ($ar_Value as $ar_ItemValue)
		{
			$s_XMLItem  = "<row>"; 
			$s_XMLItem .= "<Value xsi:type=\"UUID\">".$ar_ItemValue['1C_ID']."</Value>";
			$s_XMLItem .= "<Value xsi:type=\"xs:boolean\">".($ar_ItemValue['STATUS'])."</Value>";
			$s_XMLItem .= "<Value xsi:type=\"xs:string\">".utf8win1251($ar_ItemValue['REPORT'])."</Value>";
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
	
	
	
	
}