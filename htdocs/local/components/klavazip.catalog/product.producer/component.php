<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?

$ob_FilterLink = new KlavaCatalogFilter(true); //BEDROSOVA 23.05.2014 Включена поддержка ЧПУ для фильтра

$i_SectionID = intval($arParams['SECTION_ID']); 

$ar_Property = array();
$ar_PropertyName = array();


/*  global $USER;
if ($USER->GetID()=='7429')
{
print_r ($arParams['PROPERTY_SHOW']);
}  */

$PRODUCERS=array();
foreach($arParams['PROPERTY_SHOW'] as $Item)
{
	if ($Item["CODE"]=="CML2_MANUFACTURER")
	{
		$PRODUCERS = $Item["VALUES"];
	}
}

foreach($PRODUCERS as $key=>$PROD)
{
	$arResult['ITEMS'][] = array(
			'URL' 	=> //$APPLICATION->GetCurDir().'?filter=CML2_MANUFACTURER:['.$key.']',
			$ob_FilterLink->getUniqueUrlAction("CML2_MANUFACTURER", $key),
			'NAME' 	=> $PROD['VALUE'] 
			);			
}

/* if( count($arParams['PROPERTY_SHOW']['manufactur']) > 0 )
{
	$rs_PropertyEnum = CIBlockPropertyEnum::GetList(array(), array("IBLOCK_ID" => KlavaCatalog::IBLOCK_ID, 'ID' => $arParams['PROPERTY_SHOW']['manufactur']));
	while($ar_PropertyEnum = $rs_PropertyEnum->GetNext())
	{
		$arResult['ITEMS'][] = array(
			'URL' 	=> $APPLICATION->GetCurDir().'?filter=manufactur:['.$ar_PropertyEnum['ID'].']',
			'NAME' 	=> $ar_PropertyEnum['VALUE'] 	
		); 
	}
} */	






//echo '<pre>', print_r($arParams['PROPERTY_SHOW']).'</pre>';


/*
foreach ($ar_Property as $s_Code => $ar_Value)
{
	$ar_Params = $ar_PropertyName[$s_Code];
	$ar_Params['VALUE_LIST'] = $ar_Value;
	
	$arResult['ITEM'][$ar_Params['SORT']] = $ar_Params;
}	


sort($arResult['ITEM']);
*/

$this->IncludeComponentTemplate();