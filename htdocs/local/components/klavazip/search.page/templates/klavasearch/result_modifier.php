<?	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


$ar_ElementID = array();
foreach ($arResult['SEARCH'] as $ar_Value)
{
	if( $ar_Value['MODULE_ID'] == 'iblock' && $ar_Value['PARAM2'] == 8 )
	{
		$ar_ElementID[] = $ar_Value['ITEM_ID'];
	}
}

if( count($ar_ElementID) > 0 )
{
	$rs_Element = CIBlockElement::GetList(
		array(), 
		array('IBLOCK_ID' => 8, 'ACTIVE' => 'Y', 'ID' => $ar_ElementID), 
		false, 
		false,
		array(	 
			'ID',
	        'IBLOCK_ID',
	        'IBLOCK_SECTION_ID',
	        'NAME',
	        'DETAIL_PICTURE',  
	        'PREVIEW_PICTURE',
	        'CATALOG_GROUP_4',
	        'PREVIEW_TEXT',
	        'DETAIL_PAGE_URL',
	        'PROPERTY_*'
		)
	);
	
	$ar_SectionID = array();
	$ar_ElementFiled = array();
	while($ob_Element = $rs_Element->GetNextElement())
	{
		$ar_Fields     = $ob_Element->GetFields();
		$ar_Propeties  = $ob_Element->GetProperties();

		unset(
			$ar_Propeties['ANALOGS_NAMES'],
			$ar_Propeties['SSYLKA_NA_SAYTE'],
			$ar_Propeties['SSYLKA_NA_SAYTE_DATA_OBNOVLENIYA'],
			$ar_Propeties['KARTINKA_NA_SAYTE_0'],
			$ar_Propeties['CML2_TRAITS'],
			$ar_Propeties['MORE_PHOTO'],
			$ar_Propeties['ANALOGS_NAMES'],
			$ar_Propeties['URL_PREDSTAVLENIE'],
			$ar_Propeties['KARTINKA_NA_SAYTE_1'],
			$ar_Propeties['KARTINKA_NA_SAYTE_0_']
		);

		
		$ar_PropValue = array();
		foreach ($ar_Propeties as $s_Code => $ar_Value )
		{
			if( ! empty($ar_Value['VALUE']) )
			{
				if( is_array($ar_Value['VALUE']) && count($ar_Value['VALUE']) > 0 && strlen($ar_Value['VALUE'][0]) > 0 )
					$ar_PropValue[] = implode(', ', $ar_Value['VALUE']);
				else
					$ar_PropValue[] = $ar_Value['VALUE'];
			}
		}

		$ar_Fields['PROPERTY_STRING'] = implode('&nbsp; / &nbsp;', $ar_PropValue);
		$ar_Fields['PROPERTIES'] = $ar_Propeties;
		
		$arResult['ITEM'][] = $ar_Fields;
		
		//$ar_ElementFiled[$ar_Fields['IBLOCK_SECTION_ID']][] = $ar_Fields;
		//$ar_SectionID[] = $ar_Fields['IBLOCK_SECTION_ID'];
	}
}	
	
	/*
	$ar_ResultSection = array();
	$ar_SectionID = array_unique($ar_SectionID);
	
	if( count($ar_SectionID) > 0 )
	{
		$ar_ParentSectionID = array();
		$rs_Section = CIBlockSection::GetList(array(), array('IBLOCK_ID' => 8, 'ACTIVE' => 'Y', 'ID' => $ar_SectionID), false, array('UF_*'));
		while($ar_Section = $rs_Section->GetNext(true, false))
		{
			if( $ar_Section['DEPTH_LEVEL'] > 1 )
			{
				$ar_ParentSectionID[] = $ar_Section['ID'];
			}	
			
			$ar_ResultSection[] = array(
				'SECTION_ID' 			=> $ar_Section['ID'],
				'SECTION_NAME' 			=> $ar_Section['NAME'],
				'SECTION_COUNT_ELEMENT' => count($ar_ElementFiled[ $ar_Section['ID'] ]),
				'ELEMENT' 				=> $ar_ElementFiled[ $ar_Section['ID'] ]	
			);
			
		}

		if(count($ar_ParentSectionID) > 0)
		{
			$ar_SectionName = array();
			foreach ($ar_ParentSectionID as $i_SecID)
			{
				$rs_SectionParent = CIBlockSection::GetNavChain(8, $i_SecID, array('ID', 'NAME'));
				while ($ar_SectionParent = $rs_SectionParent->GetNext(true, false))
				{
					$ar_SectionName[$i_SecID][] = $ar_SectionParent['NAME'];
				}
			}
			
			$ar_Result = array();
			
			foreach ($ar_ResultSection as $ar_SectionValue)
			{
				if( array_key_exists($ar_SectionValue['SECTION_ID'], $ar_SectionName) )
				{
					$ar_SectionValue['SECTION_NAME'] = implode(' -> ', $ar_SectionName[$ar_SectionValue['SECTION_ID']]);
					$ar_Result[] = $ar_SectionValue;
				}
			}
		}
		else
		{
			$ar_Result = $ar_ResultSection;
		}
	}
	
	$arResult['ITEM'] = $ar_Result;
}

*/



