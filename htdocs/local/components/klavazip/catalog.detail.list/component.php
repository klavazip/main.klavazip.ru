<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?



# В популяные товары выводит аналоги, если их нет то популярные товары по разделу
if( count($arParams['ANALOGI']) > 0 )
{
	foreach ($arParams['ANALOGI'] as $ar_Value)
	{
		$arResult['RELATED_PRODUCT'][] = array(
			'NAME'  	 		=> $ar_Value['NAME'],
			'DETAIL_URL' 		=> $ar_Value['DETAIL_PAGE_URL'],
			'PRICE'		 		=> floatval($ar_Value['PRICES']['PRICE']),
			'IMG'		 		=> CFile::GetPath($ar_Value['PREVIEW_PICTURE']),
		);
	}	
}
else if(intval($arParams['SECTION_ID']) > 0)
{
	$rs_Catalog = CIBlockElement::GetList(
		array('SORT' => 'DESC'), 
		array('IBLOCK_ID' => KlavaCatalog::IBLOCK_ID, 'SECTION_ID' => intval($arParams['SECTION_ID']), 'INCLUDE_SUBSECTIONS' => 'Y', 'ACTIVE' => 'Y'), 
		false, 
		array('nTopCount' => 5), 
		array(
			'ID',
			'NAME',
			'CATALOG_GROUP_4',
			'PREVIEW_PICTURE',
			'DETAIL_PAGE_URL'
		)
	);
	while($ar_Product = $rs_Catalog->GetNext(true, false))
	{
		$arResult['RELATED_PRODUCT'][] = array(
			'NAME'  	 		=> $ar_Product['NAME'],
			'PRICE'		 		=> intval($ar_Product['CATALOG_PRICE_4']),
			'IMG'		 		=> CFile::GetPath($ar_Product['PREVIEW_PICTURE']),
			'DETAIL_URL' 		=> $ar_Product['DETAIL_PAGE_URL'],
		);
	}
	
}	



$rs_Catalog = CIBlockElement::GetList(
		array('SORT' => 'DESC'), 
		array('IBLOCK_ID' => KlavaCatalog::IBLOCK_ID, 'ACTIVE' => 'Y', '!PREVIEW_PICTURE' => false),
		false,
		array('nTopCount' => 5),
		array(
			'ID',
			'NAME',
			'CATALOG_GROUP_4',
			'PREVIEW_PICTURE',
			'DETAIL_PAGE_URL',
			'SECTION_ID',
			'SHOW_COUNTE'		
		)
);
while($ar_Product = $rs_Catalog->GetNext(true, false))
{
	$arResult['POPULAR_PRODUCT'][] = array(
		'NAME'  	 		=> $ar_Product['NAME'],
		'PRICE'		 		=> intval($ar_Product['CATALOG_PRICE_4']),
		'IMG'		 		=> CFile::GetPath($ar_Product['PREVIEW_PICTURE']),
		'DETAIL_URL' 		=> $ar_Product['DETAIL_PAGE_URL'],
		'SHOW_COUNTE' 		=> $ar_Product['SHOW_COUNTE'],
	);
}




//echo '<pre>', print_r($arResult['RELATED_PRODUCT']).'</pre>';

//echo '<pre>', print_r($arResult['POPULAR_PRODUCT']).'</pre>';



/*
$i_CountPageElement = 20;

const CATALOG_IBLOCK_ID	= 8;
const CATALOG_PRICE_ID = 4;


$ar_Filter = array(
	'IBLOCK_ID' 			=> CATALOG_IBLOCK_ID,
	'ACTIVE'				=> 'Y',
	'SECTION_ID'			=> 5317,
	'INCLUDE_SUBSECTIONS'	=> 'Y'	
);

# базовые свойства
$ar_BaseField = array(
	'ID',
	'NAME',
	'CATALOG_GROUP_'.CATALOG_PRICE_ID,
	'PROPERTY_NEWPRODUCT',
	'PROPERTY_SALELEADER',
	'PROPERTY_SPECIALOFFER',
	'PREVIEW_PICTURE',
	'PROPERTY_SSYLKA_NA_SAYTE',
	'DETAIL_PAGE_URL'					
);

$rs_Catalog = CIBlockElement::GetList(array('rand' => ''), $ar_Filter, false, array('nTopCount' => 12), $ar_BaseField);
while($ar_Product = $rs_Catalog->GetNext(true, false))
{
	$ar_Result['TITLE'] = $ar_Product['PROPERTY_B_TYPE2_DESCRIPTION'];
	$ar_Result['ITEAM'][] = array(
		'ID'				=> $ar_Product['ID'],
		'NAME'  	 		=> $ar_Product['NAME'],
		'PRICE'		 		=> intval($ar_Product['CATALOG_PRICE_4']),
		'QUANTITY'	 		=> (intval($ar_Product['CATALOG_QUANTITY']) > 0),
		'QUANTITY_COUNT'	=> intval($ar_Product['CATALOG_QUANTITY']),
		'IMG'		 		=> CFile::GetPath($ar_Product['PREVIEW_PICTURE']),
		'DETAIL_URL' 		=> $ar_Product['DETAIL_PAGE_URL'],
	);
}
					

$arResult['ITEAM'] = array_chunk($ar_Result['ITEAM'], 6);
*/






$this->IncludeComponentTemplate();