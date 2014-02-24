<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?


$s_FilterTopProperty = ( ! isset($arParams['MODE']) ) ? 'PROPERTY_SPECIALOFFER_VALUE' : 'PROPERTY_'.$arParams['MODE'].'_VALUE';

$ob_Cache = new CPHPCache;
if($ob_Cache->InitCache(0, $s_FilterTopProperty, "/catalog-index/"))
{
	$vars = $ob_Cache->GetVars();
	$ar_Result = $vars["ar_Result"];
}
else
{
	if($ob_Cache->StartDataCache())
	{
		$ar_Filter = array(
			'IBLOCK_ID' 			=> KlavaCatalog::IBLOCK_ID,
			'ACTIVE'				=> 'Y',
			$s_FilterTopProperty 	=> 'да' 
		);

		# базовые свойства
		$ar_BaseField = array(
			'ID',
			'NAME',
			'CATALOG_GROUP_4',
			'PROPERTY_NEWPRODUCT',
			'PROPERTY_SALELEADER',
			'PROPERTY_SPECIALOFFER',
			'PREVIEW_PICTURE',
			'PROPERTY_SSYLKA_NA_SAYTE',
			'DETAIL_PAGE_URL',
			'DATA_TRANZITA'	
		);

		$rs_Catalog = CIBlockElement::GetList(array('ASC' => 'DESC'), $ar_Filter, false, false, $ar_BaseField);
		while($ar_Product = $rs_Catalog->GetNext(true, false))
		{
			$rs_Property = CIBlockElement::GetProperty(KlavaCatalog::IBLOCK_ID, $ar_Product['ID'], "sort", "asc", array("CODE" => "CML2_TRAITS"));
			while ($ar_Property = $rs_Property->GetNext())
			{
				if( $ar_Property['DESCRIPTION'] == 'Аналоги (XML_ID)' && strlen($ar_Property['VALUE']) > 0 )
					$b_PropertyAnalogi = true;
			}

			$ar_Result['TITLE'] = $ar_Product['PROPERTY_B_TYPE2_DESCRIPTION'];
			$ar_Result['ITEAM'][] = array(
				'ID'				=> $ar_Product['ID'],
				'NAME'  	 		=> $ar_Product['NAME'],
				'PRICE'		 		=> intval($ar_Product['CATALOG_PRICE_4']),
				'QUANTITY'	 		=> (intval($ar_Product['CATALOG_QUANTITY']) > 0),
				'QUANTITY_COUNT'	=> intval($ar_Product['CATALOG_QUANTITY']),
				'IMG'		 		=> CFile::GetPath($ar_Product['PREVIEW_PICTURE']),
				'DETAIL_URL' 		=> $ar_Product['DETAIL_PAGE_URL'],
				'IS_ANALOGI'		=> $b_PropertyAnalogi,
				'DATA_TRANZITA'		=> $ar_Product['PROPERTY_DATA_TRANZITA_VALUE']		
			);
		}
					
		$ob_Cache->EndDataCache(array("ar_Result" => $ar_Result));
	}
}

$arResult['ITEAM'] = $ar_Result['ITEAM'];

$this->IncludeComponentTemplate();