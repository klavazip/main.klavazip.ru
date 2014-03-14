<?
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

/**
 * Обработчик для отчета в 1с по проверке кол-ва остатков
 */

require_once( './lib/xml_to_array.php' );
require_once( './lib/array_to_xml.php' );


set_time_limit(9999999);

if ( $_SERVER['HTTP_USER_AGENT'] != '1C+Enterprise/8.3')
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
		if($data = file_get_contents($s_TmpName))
		{
			$ar_XML = XML2Array::createArray( iconv("cp1251", "UTF-8", $data) );

			/*
			foreach ($ar_XML['ValueTable']['row'] as $ar_Value)
			{
				$ar_XMLElement[] = array('XML_ID' => $ar_Value['Value'][1]['@value'], 'COUNT' => intval($ar_Value['Value'][2]['@value']));
			}
			*/
			
			foreach ($ar_XML['ValueTable']['row'] as $ar_Value)
			{
				$ar_XMLElementCount[$ar_Value['Value'][1]['@value']] = intval($ar_Value['Value'][2]['@value']);
			}
			
			
			
			$rs_Element = CIBlockElement::GetList(array(), array('IBLOCK_ID' => 8, 'ACTIVE' => 'Y', '>CATALOG_QUANTITY' => 0 ), false, false, array('ID', 'XML_ID', 'CATALOG_QUANTITY'));
			while($ar_Element = $rs_Element->GetNext(true, false))
			{
				//$ar_ElementResult[$ar_Element['XML_ID']] = $ar_Element['CATALOG_QUANTITY'];
				
				$ar_Result['row'][] = array(
					'XML_ID' 	 => $ar_Element['XML_ID'],
					'COUNT' 	 => intval($ar_XMLElementCount[$ar_Element['XML_ID']]),
					'SITE_COUNT' => intval($ar_Element['CATALOG_QUANTITY']) 		
				);
			}
			
			/*
			foreach ($ar_XMLElement as $ar_Value)
			{
				$i_CountSite = $ar_ElementResult[$ar_Value['XML_ID']];
				//if($i_CountSite == 0 && $ar_Value['COUNT'] == 0)
					//continue;
			
				$ar_Value['SITE_COUNT'] = intval($i_CountSite);
				$ar_Result['row'][] = $ar_Value;
			} 
			*/
			
			//arraytofile($ar_Result, $_SERVER['DOCUMENT_ROOT']."/___dev/111111111111.txt" );
			
			
			$s_XML = '<ValueTable xmlns="http://v8.1c.ru/8.1/data/core" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
				$s_XML .= '<column><Name>Реквизит1</Name><ValueType><Type xmlns:d4p1="http://v8.1c.ru/8.1/data/enterprise/current-config">d4p1:CatalogRef.Номенклатура</Type><Type>Null</Type></ValueType><Title>Номенклатура</Title><Width>25</Width></column>';
				$s_XML .= '<column><Name>Реквизит2</Name><ValueType><Type>Null</Type><Type>xs:decimal</Type><NumberQualifiers><Digits>38</Digits><FractionDigits>3</FractionDigits><AllowedSign>Any</AllowedSign></NumberQualifiers></ValueType><Title>ОстатокВ1С</Title><Width>38</Width></column>';
				$s_XML .= '<column><Name>Реквизит3</Name><ValueType><Type>Null</Type><Type>xs:decimal</Type><NumberQualifiers><Digits>38</Digits><FractionDigits>3</FractionDigits><AllowedSign>Any</AllowedSign></NumberQualifiers></ValueType><Title>ОстатокНаСайте</Title><Width>38</Width></column>';

				foreach ($ar_Result['row'] as $ar_Val)
				{
					$s_XML .= '<row>';
						$s_XML .= '<Value xmlns:d3p1="http://v8.1c.ru/8.1/data/enterprise/current-config" xsi:type="d3p1:CatalogRef.Номенклатура">'.$ar_Val['XML_ID'].'</Value>';
						$s_XML .= '<Value xsi:type="xs:decimal">'.$ar_Val['COUNT'].'</Value>';
						$s_XML .= '<Value xsi:type="xs:decimal">'.$ar_Val['SITE_COUNT'].'</Value>';
					$s_XML .= '</row>';
				}
			
			
			$s_XML .= '</ValueTable>';
			
			arraytofile(array($s_XML), $_SERVER['DOCUMENT_ROOT']."/___dev/111111111111.txt" );

			
			header('Content-Type: charset=win-1251');
			header('Content-type: text/xml');
			echo $s_XML;
			
			/*
			header('Content-Type: charset=win-1251');
			header('Content-type: text/xml');
			
			echo '<ValueTable xmlns="http://v8.1c.ru/8.1/data/core" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
			echo '<column><Name>Реквизит1</Name><ValueType><Type xmlns:d4p1="http://v8.1c.ru/8.1/data/enterprise/current-config">d4p1:CatalogRef.Номенклатура</Type><Type>Null</Type></ValueType><Title>Номенклатура</Title><Width>25</Width></column>
				  <column><Name>Реквизит2</Name><ValueType><Type>Null</Type><Type>xs:decimal</Type><NumberQualifiers><Digits>38</Digits><FractionDigits>3</FractionDigits><AllowedSign>Any</AllowedSign></NumberQualifiers></ValueType><Title>ОстатокВ1С</Title><Width>38</Width></column>
				  <column><Name>Реквизит3</Name><ValueType><Type>Null</Type><Type>xs:decimal</Type><NumberQualifiers><Digits>38</Digits><FractionDigits>3</FractionDigits><AllowedSign>Any</AllowedSign></NumberQualifiers></ValueType><Title>ОстатокНаСайте</Title><Width>38</Width></column>';
			
			foreach ($ar_Result['row'] as $ar_Val)
			{
				echo '<row>';
					echo '<Value xmlns:d3p1="http://v8.1c.ru/8.1/data/enterprise/current-config" xsi:type="d3p1:CatalogRef.Номенклатура">'.$ar_Val['XML_ID'].'</Value>';
					echo '<Value xsi:type="xs:decimal">'.$ar_Val['COUNT'].'</Value>';
					echo '<Value xsi:type="xs:decimal">'.$ar_Val['SITE_COUNT'].'</Value>';
				echo '</row>';
			}
			
			echo '</ValueTable>';
			*/
			
		}
		else
		{
			die('file_get_contents error');
		}
	}
}