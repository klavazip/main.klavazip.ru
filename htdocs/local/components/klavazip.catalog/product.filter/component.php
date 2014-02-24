<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?

$i_SectionID = intval($arParams['SECTION_ID']); 

$ar_Property = array();
$ar_PropertyName = array();

foreach ($arParams['PROPERTY_SHOW'] as $s_Code => $ar_Value)
{
	$rs_PropertyEnum = CIBlockPropertyEnum::GetList(array(), array("IBLOCK_ID" => KlavaCatalog::IBLOCK_ID, 'ID' => $ar_Value));
	while($ar_PropertyEnum = $rs_PropertyEnum->GetNext())
	{
		$ar_Property[$s_Code][$ar_PropertyEnum['ID']] = $ar_PropertyEnum['VALUE'];
		$ar_PropertyName[$s_Code] = array(
			'NAME' 		=> $ar_PropertyEnum['PROPERTY_NAME'],
			'ID'   		=> $ar_PropertyEnum['PROPERTY_ID'], 		
			'SORT'   	=> $ar_PropertyEnum['PROPERTY_SORT'],
			'CODE'		=> $s_Code	 		
		); 
	}
}


foreach ($ar_Property as $s_Code => $ar_Value)
{
	$ar_Params = $ar_PropertyName[$s_Code];
	$ar_Params['VALUE_LIST'] = $ar_Value;
	$arResult['ITEM'][] = $ar_Params;
}	


function filter_cmp($a, $b)
{
	if ($a['SORT'] == $b['SORT']) {return 0;}
	return ($a['SORT'] < $b['SORT']) ? -1 : 1;
}

usort($arResult['ITEM'], "filter_cmp");


$this->IncludeComponentTemplate();