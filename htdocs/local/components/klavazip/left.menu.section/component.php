<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();


if($this->StartResultCache(false, ($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups())))
{

	$delSection = array(5217, 5218, 5219, 5220, 5221, 5222, 5223, 5224, 5225, 5226, 5129, 5378, 194, 2159);
	
	$rs_Section = CIBlockSection::GetList(
		array('DEPTH_LEVEL' => 'desc'), 
		array(
			'IBLOCK_ID' 	=> KlavaCatalog::IBLOCK_ID, 
			'GLOBAL_ACTIVE' => 'Y',
		), 
		false,
		array('ID', 'NAME', 'SECTION_PAGE_URL', 'IBLOCK_SECTION_ID', 'DEPTH_LEVEL', 'SORT')	
	);
	$ar_SectionList = array();
	$ar_DetchLavel = array();
	while($ar_Section = $rs_Section->GetNext(true, false))
	{
		if( ! in_array($ar_Section['ID'], $delSection) && ! in_array($ar_Section['IBLOCK_SECTION_ID'], $delSection) )
		{
			$ar_SectionList[$ar_Section['ID']] = $ar_Section;
			$ar_DetchLavel[] = $ar_Section['DEPTH_LEVEL'];
		}
	}
	
	$ar_DetchLavelResult = array_unique($ar_DetchLavel);
	rsort($ar_DetchLavelResult);

	$i_MaxDepthLevel = $ar_DetchLavelResult[0];

	
	for( $i = $i_MaxDepthLevel; $i > 1; $i-- )
	{
		foreach ( $ar_SectionList as $i_SectionID => $ar_Value )
		{
			if( $ar_Value['DEPTH_LEVEL'] == $i )
			{
				$ar_SectionList[$ar_Value['IBLOCK_SECTION_ID']]['CHILDS'][] = $ar_Value; 
				unset( $ar_SectionList[$i_SectionID] );
			}	
		}	
	}
	
	function __sectionSort($a, $b)
	{
		if ($a['SORT'] == $b['SORT']) {
			return 0;
		}
		return ($a['SORT'] < $b['SORT']) ? -1 : 1;
	}
	
	
	usort($ar_SectionList, "__sectionSort");
	
	$arResult['SECTION'] = $ar_SectionList;
	

	$this->SetResultCacheKeys(array(
		"SECTION",
	));

	$this->IncludeComponentTemplate();
}
