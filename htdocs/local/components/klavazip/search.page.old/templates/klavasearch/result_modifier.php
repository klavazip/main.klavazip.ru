<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arResult["TAGS_CHAIN"] = array();
if($arResult["REQUEST"]["~TAGS"])
{
	$res = array_unique(explode(",", $arResult["REQUEST"]["~TAGS"]));
	$url = array();
	foreach ($res as $key => $tags)
	{
		$tags = trim($tags);
		if(!empty($tags))
		{
			$url_without = $res;
			unset($url_without[$key]);
			$url[$tags] = $tags;
			$result = array(
				"TAG_NAME" => htmlspecialcharsex($tags),
				"TAG_PATH" => $APPLICATION->GetCurPageParam("tags=".urlencode(implode(",", $url)), array("tags")),
				"TAG_WITHOUT" => $APPLICATION->GetCurPageParam((count($url_without) > 0 ? "tags=".urlencode(implode(",", $url_without)) : ""), array("tags")),
			);
			$arResult["TAGS_CHAIN"][] = $result;
		}
	}
}

// некоторые параметры
$PRICE_ID = 4;
$IBLOCK_ID = 8;

$obIBE = new CIBlockElement();
$obIBES = new CIBlockSection();
$arTMP_SECTION_PROP = array();


for($i=0,$c = count($arResult['SEARCH']); $i<$c;$i++)
{
     $item =& $arResult['SEARCH'][$i];
     if(isset($item['PARAM2']) && $item['PARAM2'] == $IBLOCK_ID )
     {
         
         
        
        $dbr = $obIBE->GetList(array(),array('ID'=>$item['ITEM_ID']),false,false,array(
            'ID','IBLOCK_ID','IBLOCK_SECTION_ID','NAME','PROPERTY_CML2_ARTICLE','DETAIL_PICTURE',
            'CATALOG_GROUP_'.$PRICE_ID,'PROPERTY_SALELEADER','PROPERTY_NEWPRODUCT',
            'PREVIEW_TEXT','ADD_URL'
            
        ));
        if($obEl = $dbr->GetNextElement())
        {
            $ar = $obEl->GetFields();
            $ar['PROPERTIES'] =  $obEl->GetProperties();
            
            
            $ar['PRICE'] = 0;
            if(isset($ar['CATALOG_PRICE_'.$PRICE_ID]))
            {
                $ar['PRICE'] = $ar['CATALOG_PRICE_'.$PRICE_ID];
            }
            
            $item['_BXM_EX_'] = $ar;
            
            if($ar['IBLOCK_ID'] == '8'  && intval($ar['IBLOCK_SECTION_ID']) > 0)
            {
                $arValue = array(
                    'SNAME' => '',
                    'ETITLE' => '',
                );
                // если значение уже записано, не будетм повторно запрашивтаь свойства
                if(isset($arTMP_SECTION_PROP[intval($ar['IBLOCK_SECTION_ID'])]))
                {
                    $arValue = $arTMP_SECTION_PROP[intval($ar['IBLOCK_SECTION_ID'])];
                }
                else
                {
                    
                    $arParentSection = $obIBES->GetList(array(), array('ID' => $ar['IBLOCK_SECTION_ID'], 'IBLOCK_ID' => $ar['IBLOCK_ID']), false, array('UF_*'))->Fetch();
                    if ($arParentSection !== false && !empty($arParentSection['UF_NEW_TITLE'])) 
                    {
                    	$arValue['SNAME'] = $arParentSection['NAME'];
                        $arValue['ETITLE'] = $arParentSection['UF_NEW_TITLE'];
                    }
                    $arTMP_SECTION_PROP[intval($ar['IBLOCK_SECTION_ID'])] = $arValue;
                }
                
                // записываем для вывода
                if(strlen($arValue['ETITLE']) > 10)
                {
                    $item['TITLE'] = str_replace(array('#NAME#', '#SECTION_NAME#'), array($ar['NAME'], $arValue['SNAME']), $arValue['ETITLE']);
                    $item['TITLE_FORMATED'] = $item['TITLE'];
                }
                //аналоги
                
                // для вывода аналогов
                if($ar['CATALOG_QUANTITY'] <= 0)
                {
                    // помимо наименований 
                   $cont = '';
                   ob_start();
                    $item["ANALOGI_COUNT"]=$APPLICATION->IncludeComponent("bxmaker:bxmaker.catalog.analogi", ".default", array (
                   	    "CACHE_TYPE" => "A",
                       	"CACHE_TIME" => "36000000",
                       	"IBLOCK_TYPE" => '',
                       	"IBLOCK_ID" => $ar["IBLOCK_ID"],
                       	"ELEMENT_ID" => $ar['ID'],
                        "ELEMENT_COUNT" => 100,
                        "SHOW_QUANTITY" => 'Y',
                        'QUANTITY_MORE' => 'Y'
                       	),
                       	false
                    );
                    $cont = ob_get_contents();
                    ob_end_clean();
                   
                    $item["ANALOGI"] = $cont;
                }
            }
         }
    }
}


 
	$ar_SectionResult = array();
	$ar_SectionID = array();
	$ar_SectionMainID = array();
	foreach ($arResult['SEARCH'] as $ar_Value)
	{
		//$arResult['SECTION_RESULT'][$ar_Value['_BXM_EX_']['IBLOCK_SECTION_ID']][] = $ar_Value;
		$ar_SectionID[] = $ar_Value['_BXM_EX_']['IBLOCK_SECTION_ID'];
	}

   

	$rs_Section = CIBlockSection::GetList(array(), array('IBLOCK_ID' => 8, 'ID' => array_unique($ar_SectionID)), false);
	while($ar_Section = $rs_Section->Fetch())
	{
		$arResult['SECTION_NAME_SEARCH'][$ar_Section['ID']] = array(
			'NAME' 			=> $ar_Section['NAME'], 
			'SECTION_ID' 	=> $ar_Section['IBLOCK_SECTION_ID'],
                        'SECTION_ID_CUR' => $ar_Section['ID'],
		);
		if(intval($ar_Section['IBLOCK_SECTION_ID']) > 0)
                    $ar_SectionMainID[] = $ar_Section['IBLOCK_SECTION_ID'];
                else
                    $ar_SectionMainID[] = $ar_Section['ID'];
	}

	$ar_ElementSearch = array();
	foreach ($arResult['SEARCH'] as $ar_Value )
	{
                if(intval($arResult['SECTION_NAME_SEARCH'][$ar_Value['_BXM_EX_']['IBLOCK_SECTION_ID']]['SECTION_ID']) > 0)
                {
                    $ar_Value['_BXM_EX_']['G_SECTION_ID'] = $arResult['SECTION_NAME_SEARCH'][$ar_Value['_BXM_EX_']['IBLOCK_SECTION_ID']]['SECTION_ID'];
		}
                else
                {
                    $ar_Value['_BXM_EX_']['G_SECTION_ID'] = $arResult['SECTION_NAME_SEARCH'][$ar_Value['_BXM_EX_']['IBLOCK_SECTION_ID']]['SECTION_ID_CUR'];
		}
		$ar_ElementSearch[] = $ar_Value;
	}
	
	
	

	$rs_MainSection = CIBlockSection::GetList(array(), array('IBLOCK_ID' => 8, 'ID' => array_unique($ar_SectionMainID)), false);
	while($ar_MainSection = $rs_MainSection->Fetch())
	{
		$arResult['SECTION_NAME_SEARCH_MAIN'][$ar_MainSection['ID']] = $ar_MainSection['NAME'];
	}
	
	//echo '<pre>', print_r($arResult['SECTION_NAME_SEARCH_MAIN']).'</pre>';
	
	$arResult['SECTION_RESULT_MAIN'] = array();
	foreach ($ar_ElementSearch as $ar_Value)
	{
		$arResult['SECTION_RESULT_MAIN'][$ar_Value['_BXM_EX_']['G_SECTION_ID']][] = $ar_Value;
		
	}
	
	
	
	
	
	
	
	
	
	
	//echo '<pre>', print_r($ar_ElementSearch['G_SECTION_ID']).'</pre>';

?>