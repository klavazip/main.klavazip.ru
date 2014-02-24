<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?




if( $_SERVER['REQUEST_METHOD'] == 'GET' && strlen($_GET['ARTICUL']) > 0 )
{
	$rs_Element = CIBlockElement::GetList(
			array(),
			array('IBLOCK_ID' => KlavaCatalog::IBLOCK_ID, 'PROPERTY_CML2_ARTICLE' => trim($_GET['ARTICUL']) ),
			false,
			false,
			array('ID', 'NAME', 'PROPERTY_CML2_ARTICLE', 'PROPERTY_CML2_TRAITS', 'CATALOG_QUANTITY', 'DETAIL_PAGE_URL', 'PREVIEW_PICTURE', 'SORT')
	);
	if($ar_Element = $rs_Element->GetNext(true, false))
	{
		$ar_Element['IMG'] 		= CFile::GetPath($ar_Element['PREVIEW_PICTURE']);
		
		$arResult['PRODUCT'] = $ar_Element;
		
		$rs_Property = CIBlockElement::GetProperty(KlavaCatalog::IBLOCK_ID, $ar_Element['ID'], "sort", "asc", array("CODE" => "CML2_TRAITS"));
		while ($ar_Property = $rs_Property->GetNext(true, false))
		{
			if($ar_Property['DESCRIPTION'] == 'Аналоги (XML_ID)')
			{
				$ar_AnalogiXMLID = explode(',', $ar_Property['VALUE']);
				
				break;
			}
		}
		
		//echo '<pre>', print_r($ar_AnalogiXMLID).'</pre>';
		
		if( count($ar_AnalogiXMLID) > 0 && strlen($ar_AnalogiXMLID[0]) > 0 )
		{
			$rs_Element = CIBlockElement::GetList(
					array('CATALOG_QUANTITY' => 'DESC'),
					array('IBLOCK_ID' => KlavaCatalog::IBLOCK_ID, 'XML_ID' => $ar_AnalogiXMLID),
					false,
					false,
					array('NAME','ID', 'DETAIL_PAGE_URL', 'CATALOG_QUANTITY','XML_ID', 'PROPERTY_CML2', 'PROPERTY_CML2_ARTICLE', 'PREVIEW_PICTURE', 'SORT')
			);
			while($ar_Element = $rs_Element->GetNext(true, false))
			{
				$ar_Element['IMG'] 		= CFile::GetPath($ar_Element['PREVIEW_PICTURE']);
				
				$arResult['ANALOGI'][] = $ar_Element;
			}
		}
	}
	
	
	//echo '<pre>', print_r($arResult).'</pre>';
	
	
}


//01.02.05051







$this->IncludeComponentTemplate();