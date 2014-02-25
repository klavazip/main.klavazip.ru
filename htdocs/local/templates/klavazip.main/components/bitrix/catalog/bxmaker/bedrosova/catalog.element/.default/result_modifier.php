<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arResult['COMMENT_COUN'] = KlavaCatalogProductComment::getCommentCount($arResult['ID']);

if( intval($arResult['CATALOG_QUANTITY']) <= 0 )
{
	foreach ($arResult['PROPERTIES']['CML2_TRAITS']['DESCRIPTION'] as $key => $s_Value)
	{
		if($s_Value == 'Аналоги (XML_ID)')
		{
			$ar_AnalogiXMLID = explode(',', $arResult['PROPERTIES']['CML2_TRAITS']['VALUE'][$key]);
		}	
	}	
	
	if( count($ar_AnalogiXMLID) > 0 && strlen($ar_AnalogiXMLID[0]) > 0 )
	{
		$rs_Element = CIBlockElement::GetList(
			array('SORT' => 'DESC'),
			array('IBLOCK_ID' => KlavaCatalog::IBLOCK_ID, 'XML_ID' => $ar_AnalogiXMLID, '>=CATALOG_QUANTITY' => 1),
			false,
			array('nTopCount' => 1),
			array('NAME','ID', 'PREVIEW_PICTURE', 'DETAIL_PAGE_URL','CATALOG_QUANTITY','XML_ID')
		);
		while($ar_Element = $rs_Element->GetNext(true, false))
		{
			$ar_Element['PRICES'] 	= CPrice::GetBasePrice($ar_Element['ID']);
			$ar_Element['IMG'] 		= CFile::GetPath($ar_Element['PREVIEW_PICTURE']);
			$ar_Result[] = $ar_Element;
		}
	}
	
	$arResult['ANALOGS'] = $ar_Result;
}