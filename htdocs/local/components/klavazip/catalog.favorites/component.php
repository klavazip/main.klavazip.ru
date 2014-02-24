<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?


$s_ElementIdHash = $_COOKIE['KLAVA_FAVOTITES_ID'];
if(strlen($s_ElementIdHash) > 0)
{
	$ar_ElementID = explode('_', $s_ElementIdHash);
}
const CATALOG_IBLOCK_ID	= 8;


if(count($ar_ElementID) > 0)
{
	$ar_Filter = array('IBLOCK_ID' => CATALOG_IBLOCK_ID, 'ID' => $ar_ElementID);
	
	# базовые свойства
	$ar_BaseField = array(
		'ID',
		'NAME',
		'CATALOG_GROUP_4',
		'PREVIEW_PICTURE',
		'DETAIL_PAGE_URL',
		'PROPERTY_*',
		'PREVIEW_TEXT'	
	); 
	
	$rs_Catalog = CIBlockElement::GetList(
		array(), 
		array('IBLOCK_ID' => CATALOG_IBLOCK_ID, 'ID' => $ar_ElementID), 
		false, 
		false, 
		$ar_BaseField
	);
	while($ob_Product = $rs_Catalog->GetNextElement(true, false))
	{
		$ar_Filed = $ob_Product->GetFields();
		$ar_Property = $ob_Product->GetProperties();
		
		unset(
			$ar_Property['ANALOGS_NAMES'], 
			$ar_Property['SSYLKA_NA_SAYTE'], 
			$ar_Property['SSYLKA_NA_SAYTE_DATA_OBNOVLENIYA'], 
			$ar_Property['KARTINKA_NA_SAYTE_0'], 
			$ar_Property['CML2_TRAITS'], 
			$ar_Property['MORE_PHOTO'], 
			$ar_Property['ANALOGS_NAMES'], 
			$ar_Property['URL_PREDSTAVLENIE'], 
			$ar_Property['KARTINKA_NA_SAYTE_1'], 
			$ar_Property['KARTINKA_NA_SAYTE_0_']
		);
		
		$ar_PropValue = array();
		foreach ($ar_Property as $s_Code => $ar_Value )
		{
			if( !empty($ar_Value['VALUE']) )
			{
				if( is_array($ar_Value['VALUE']) && count($ar_Value['VALUE']) > 0 && strlen($ar_Value['VALUE'][0]) > 0 )
				{
					$ar_PropValue[] = implode(', ', $ar_Value['VALUE']);
				}
				else
				{
					$ar_PropValue[] = $ar_Value['VALUE'];
				}	
			}
		}
		
		
		//echo '<pre>', print_r($ar_PropValue).'</pre>';
				
		$ar_Result['TITLE'] = $ar_Product['PROPERTY_B_TYPE2_DESCRIPTION'];
		$arResult['ITEAM'][] = array(
			'ID'				=> $ar_Filed['ID'],
			'NAME'  	 		=> $ar_Filed['NAME'],
			'PRICE'		 		=> intval($ar_Filed['CATALOG_PRICE_4']),
			'QUANTITY'	 		=> (intval($ar_Filed['CATALOG_QUANTITY']) > 0),
			'QUANTITY_COUNT'	=> intval($ar_Filed['CATALOG_QUANTITY']),
			'IMG'		 		=> CFile::GetPath($ar_Filed['PREVIEW_PICTURE']),
			'DETAIL_URL' 		=> $ar_Filed['DETAIL_PAGE_URL'],
			'ARTICLE'			=> $ar_Property['CML2_ARTICLE']['VALUE'],
			//'PROPERTY'			=> implode('&nbsp; / &nbsp;', $ar_PropValue),
			'PROPERTY'			=> implode('&nbsp; / &nbsp;', $ar_PropValue),
				
				
		);
	}
}

$this->IncludeComponentTemplate();