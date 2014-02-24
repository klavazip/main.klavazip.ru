<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?

$ar_ElementID = array();

foreach ($_SESSION['CATALOG_COMPARE_LIST'][8]['ITEMS'] as $i_ElementID => $v)
{
	$ar_ElementID[] = $i_ElementID;
}

if(count($ar_ElementID) > 0)
{
	$rs_Catalog = CIBlockElement::GetList(
		array(), 
		array('IBLOCK_ID' => 8, 'ID' => $ar_ElementID ), 
		false, 
		false, 
		array('ID', 'NAME', 'CATALOG_GROUP_4', 'DETAIL_PAGE_URL')
	);
	$ar_Result = array();
	while($ar_Product = $rs_Catalog->GetNext(true, false))
	{
		$ar_Result[] = array(
			'ID'				=> $ar_Product['ID'],
			'NAME'  	 		=> $ar_Product['NAME'],
			'PRICE'		 		=> intval($ar_Product['CATALOG_PRICE_4']),
			'DETAIL_URL' 		=> $ar_Product['DETAIL_PAGE_URL'],
			'QUANTITY'			=> $ar_Product['CATALOG_QUANTITY']	
		);
	}
}

$arResult['ITEAM'] = array_reverse($ar_Result);

$this->IncludeComponentTemplate();