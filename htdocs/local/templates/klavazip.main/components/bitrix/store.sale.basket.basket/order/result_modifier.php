<?	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

	$ar_ElementID = array();
	foreach ($arResult['ITEMS']['AnDelCanBuy'] as $ar_Value)
	{
		$ar_ElementID[] = $ar_Value['PRODUCT_ID'];
	}
	
	if(count($ar_ElementID) > 0)
	{
		$rs_Element = CIBlockElement::GetList(
			array(), 
			array('IBLOCK_ID' => KlavaCatalog::IBLOCK_ID, 'ID' => $ar_ElementID), 
			false, 
			false, 
			array('ID', 'PREVIEW_PICTURE', 'PROPERTY_CML2_ARTICLE', 'PROPERTY_manufactur', 'PROPERTY_color', 'CATALOG_QUANTITY')
		);
		
		while($ar_Element = $rs_Element->GetNext(true))
		{
			$ar_Proeprty = array();
			
			$ar_Proeprty[] = 'Артикул: '.$ar_Element['PROPERTY_CML2_ARTICLE_VALUE'];
			if(strlen($ar_Element['PROPERTY_manufactur_VALUE']) > 0)
				$ar_Proeprty[] = '<span>'.$ar_Element['PROPERTY_manufactur_VALUE'].'</span>';
			
			if(strlen($ar_Element['PROPERTY_color_VALUE']) > 0)
				$ar_Proeprty[] = '<span>'.$ar_Element['PROPERTY_color_VALUE'].'</span>';
				
			$arResult['ELEMENT'][$ar_Element['ID']] = array(
				'IMG' 			=> intval($ar_Element['PREVIEW_PICTURE']) > 0 ? CFile::GetPath( $ar_Element['PREVIEW_PICTURE'] ) : '/bitrix/templates/klavazip.main/img/no-pic-big.jpg',
				'PROPERTY' 		=> implode('/', $ar_Proeprty),
				'CURRENT_COUNT' => (intval($ar_Element['CATALOG_QUANTITY']) == 0) ? 1 : $ar_Element['CATALOG_QUANTITY']  				 
			);
		}
	}