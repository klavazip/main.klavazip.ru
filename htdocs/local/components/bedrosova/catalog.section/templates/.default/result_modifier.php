<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();



$UF_PARAMS = array(
	'PRICE_MAX' 			=> '',
	'PRICE_MIN' 			=> '',
	'FILTER_PROPERTY_SHOW' 	=> ''
);

$TITLE_PREFIX = '';
$dbr = CIBlockSection::GetList(
	array(),
	array('ID' => $arResult['ID'], 'IBLOCK_ID' => 8), 
	false,
	array(
		'UF_TITLE', 
		'UF_TITLE2', 
		'UF_NEW_TITLE',
		'UF_PRICE_MAX',
		'UF_PRICE_MIN',
		'UF_PRICE_INCSEC_MAX',
		'UF_PRICE_INCSEC_MIN',
		'UF_PROPERTY_SHOW'
	)
);
if($ar = $dbr->Fetch())
{
	if($arParams['INCLUDE_SUBSECTIONS'] == 'Y' || $arParams['INCLUDE_SUBSECTIONS'] == 'A')
	{
		$UF_PARAMS['PRICE_MAX'] = $ar['UF_PRICE_INCSEC_MAX'];
		$UF_PARAMS['PRICE_MIN'] = $ar['UF_PRICE_INCSEC_MIN'];
	}
	else
	{
		$UF_PARAMS['PRICE_MAX'] = $ar['UF_PRICE_MAX'];
		$UF_PARAMS['PRICE_MIN'] = $ar['UF_PRICE_MIN'];
	}
	
	foreach ( unserialize($ar['UF_PROPERTY_SHOW']) as $s_Key => $ar_Value )
	{
		$arResult['FILTER_PROPERTY_SHOW'][] = $s_Key;
	}
	//$UF_PARAMS['FILTER_PROPERTY_SHOW'] = unserialize($ar['UF_PROPERTY_SHOW']);   // array_flip(explode(',',$ar['UF_PROPERTY_SHOW']));
}



$analogs_by_item  = array();
$analogs_by_item2 = array();
foreach($arResult['ITEMS'] as $val)
{
	$analogs_by_item[''.$val['ID']]['VALUE'] = $val['PROPERTIES']['CML2_TRAITS']['VALUE'];
	$analogs_by_item[''.$val['ID']]['DESCRIPTION'] = $val['PROPERTIES']['CML2_TRAITS']['DESCRIPTION'];
		
	$key_analog = -1;

	foreach ($analogs_by_item[''.$val['ID']]['DESCRIPTION'] as $key2=>$val2)
	{
		if (strpos($val2,'налог'))
		{
			$key_analog=$key2;
		}
	}


	if ($key_analog >= 0)
	{
		$analogs_by_item2[''.$val['ID']]=explode(',',$analogs_by_item[''.$val['ID']]['VALUE'][$key_analog]);
	}
}

$xml_filter=array();
foreach($analogs_by_item2 as $val)
{
	foreach($val as $val2)
	{
		if (!in_array($val2,$xml_filter))
		{
			$xml_filter[]=$val2;
		}
	}
}


if( count($xml_filter) > 0 )
{
	$dbre = CIBlockElement::GetList(
			array(),
			array('IBLOCK_ID' => 8, 'XML_ID' => $xml_filter, '>=CATALOG_QUANTITY' => 1),
			false,
			false,
			array('NAME','ID','DETAIL_PAGE_URL','CATALOG_QUANTITY','XML_ID', 'PREVIEW_PICTURE')
	);
	while($are = $dbre->GetNext())
	{
		$are['PRICES'] 	= CPrice::GetBasePrice($are['ID']);
		$are['IMG'] 	= CFile::GetPath($are['PREVIEW_PICTURE']);
		$arResult['ANALOGS'][''.$are['XML_ID']] = $are;
	}
	
	$analogs_by_item3 = array();
	
	foreach($analogs_by_item2 as $key=>$val)
	{
		foreach($val as $val2)
		{
			if ($arResult['ANALOGS'][''.$val2])
			{
				$analogs_by_item3[''.$key][]=$arResult['ANALOGS'][''.$val2];
			}
		}
	}
	
	$arResult['ITEMS_ANALOGS'] = $analogs_by_item3;
}	



//echo '<pre>', print_r($arResult).'</pre>';
