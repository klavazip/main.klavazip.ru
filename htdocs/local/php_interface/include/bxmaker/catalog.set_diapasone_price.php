<?
AddEventHandler("catalog", "OnSuccessCatalogImport1C", "BXMakerCatalogSetDiapasonePrice",7000);
//AddEventHandler("main", "OnBeforeProlog", "BXMakerCatalogSetDiapasonePrice", 50);


function BXMakerCatalogSetDiapasonePrice($arFields)
{
    
    if(CModule::IncludeModule('iblock') && CModule::IncludeModule("catalog"))
    {
        // основной каталог товаров - 8
        if($arFields['IBLOCK_ID'] == 8)
        {
            $arFields['IBLOCK_ID'] = 8;
            
            $ibs = new CIBlockSection();
            $ibe = new CIBlockElement();
            $pr =  new CCatalogGroup();
            
            $globSelection = array(); // добавляем для отбора
            $globSelectionProp = array(); //  масив для распознания свойств и сохранения тех, которые не пусты
            $arrFilterG = array();
            if(is_array($GLOBALS['______BXMAKER_GLOBAL_PARAMS_CATALOG_FILTER_SHOW_PROPERTY'][$arFields['IBLOCK_ID']]))
            {
                $arrFilterG = $GLOBALS['______BXMAKER_GLOBAL_PARAMS_CATALOG_FILTER_SHOW_PROPERTY'][$arFields['IBLOCK_ID']];
                for($i=0;$i<count($arrFilterG);$i++)
                {
                    $globSelectionProp[$arrFilterG[$i]] = 'PROPERTY_'.strtoupper($arrFilterG[$i]).'_VALUE';
                }
            }
            
            // получим базовую цену
            if($price = $pr->GetList(array(),array('BASE'=>'Y'))->Fetch())
            { // если есть цена, 
                $PRICE_ID = $price['ID'];
                $IBLOCK_ID = $arFields['IBLOCK_ID'];
                
                //********************
                // сначала соберем все разделы
                $arFilterSections = array();
                $arFilterSections['IBLOCK_ID'] = $arFields['IBLOCK_ID'];
                if(isset($arFields['SECTION_ID'])) $arFilterSections['ID'] = $arFields['SECTION_ID'];
                
                
                $dbrs = $ibs->GetList(array(),$arFilterSections);
                while($ars = $dbrs->Fetch())
                {
                    
                    $SECTION_ID = $ars['ID'];
                    
                    $arPropSecSet = array(
                        'UF_PRICE_MAX' => 0,
                        'UF_PRICE_MIN' => 10000000,
                        'UF_PRICE_INCSEC_MAX' => 0,
                        'UF_PRICE_INCSEC_MIN' => 1000000
                    );
                    
                    // теперь переходим к запросу товаров включая подразделы
                    $bElements = false;
                    $bIncElements = false;
                    $arSaveProperty = array(); // записываем свойства которые надо будет отобразить в фильтре раздела
                    $arFilter = array(
                        'IBLOCK_ID' =>$arFields['IBLOCK_ID'],
                        'ACTIVE' => 'Y',
                        'SECTION_ID' => $ars['ID'],
                        'INCLUDE_SUBSECTIONS' => 'Y',
                    );
                    $arSelection = array(
                        'ID','IBLOCK_ID','CATALOG_GROUP_'.$PRICE_ID,'IBLOCK_SECTION_ID'
                    );
                    $arSelection = array_merge($arSelection);
                    
                    
                    $dbre = $ibe->GetList(array(),$arFilter,false, false,$arSelection);
                    while($obElement = $dbre->GetNextElement())
                    {
                        
                        $arFields = $obElement->GetFields();
                        $arProperty = $obElement->GetProperties();
                        
                        $are = $arFields;
                        
                        foreach($globSelectionProp as $k=>$v)
                        {
                            if(isset($arProperty[$k]['VALUE']) && strlen($arProperty[$k]['VALUE']) > 0)
                            {
                                
                                if(!array_key_exists($k,$arProperty))
                                {
                                    $arSaveProperty[$k] = array();
                                }
                                
                                if($arProperty[$k]['PROPERTY_TYPE'] == 'L' && $arProperty[$k]['LIST_TYPE'] == 'L')
                                {
                                    if(strlen(trim($arProperty[$k]['VALUE_ENUM_ID'])) > 0 && !in_array($arProperty[$k]['VALUE_ENUM_ID'],$arSaveProperty[$k]))
                                    {
                                        $arSaveProperty[$k][] = $arProperty[$k]['VALUE_ENUM_ID'];
                                    }
                                }
                                elseif($arProperty[$k]['PROPERTY_TYPE'] == 'S')
                                {
                                    if(strlen(trim($arProperty[$k]['VALUE'])) > 0 && !in_array($arProperty[$k]['VALUE'],$arSaveProperty[$k]))
                                    {
                                        $arSaveProperty[$k][] = $arProperty[$k]['VALUE'];
                                    }
                                }
                                
                            }
                        }
                        
                        if($are['CATALOG_PRICE_'.$PRICE_ID] > $arPropSecSet['UF_PRICE_INCSEC_MAX']) $arPropSecSet['UF_PRICE_INCSEC_MAX'] = $are['CATALOG_PRICE_'.$PRICE_ID];
                        if($are['CATALOG_PRICE_'.$PRICE_ID] < $arPropSecSet['UF_PRICE_INCSEC_MIN']) $arPropSecSet['UF_PRICE_INCSEC_MIN'] = $are['CATALOG_PRICE_'.$PRICE_ID];
                        
                        if($are['IBLOCK_SECTION_ID'] == $ars['ID'])
                        {
                            
                            if($are['CATALOG_PRICE_'.$PRICE_ID] > $arPropSecSet['UF_PRICE_MAX']) $arPropSecSet['UF_PRICE_MAX'] = $are['CATALOG_PRICE_'.$PRICE_ID];
                            if($are['CATALOG_PRICE_'.$PRICE_ID] < $arPropSecSet['UF_PRICE_MIN']) $arPropSecSet['UF_PRICE_MIN'] = $are['CATALOG_PRICE_'.$PRICE_ID];
                            $bElements = true;
                        }
                        $bIncElements = true;               
                    }
                    if($bIncElements)
                    {
                        if(!$bElements)
                        {
                           $arPropSecSet['UF_PRICE_MAX'] = $arPropSecSet['UF_PRICE_INCSEC_MAX'];
                           $arPropSecSet['UF_PRICE_MIN'] = $arPropSecSet['UF_PRICE_INCSEC_MIN'];
                            
                        }
                        $arPropSecSet['UF_PROPERTY_SHOW'] = serialize($arSaveProperty);
                        
                       
                        $GLOBALS["USER_FIELD_MANAGER"]->Update( "IBLOCK_".$IBLOCK_ID."_SECTION",  $SECTION_ID, $arPropSecSet);
                    }
                } //while $dbrs
            } // $price
        }
    }    
    return true;
}

function BXMakerCatalogSetDiapasonePriceSetProperty($IBLOCK_ID)
{
    $obUserField  = new CUserTypeEntity;
    
    // максимальная цена
    $arFields = Array(
        "ENTITY_ID" => "IBLOCK_".$IBLOCK_ID."_SECTION",
        "FIELD_NAME" => "UF_PRICE_MAX",
        "USER_TYPE_ID" => "string",
        "EDIT_FORM_LABEL" => Array(
            "ru"=>"Максимальная цена товара в разделе", 
            "en"=>"Максимальная цена товара в разделе"
        )
    );
    $obUserField->Add($arFields);
    // максимальная цена
    $arFields = Array(
        "ENTITY_ID" => "IBLOCK_".$IBLOCK_ID."_SECTION",
        "FIELD_NAME" => "UF_PRICE_MIN",
        "USER_TYPE_ID" => "string",
        "EDIT_FORM_LABEL" => Array(
            "ru"=>"Минимальная цена товара в разделе", 
            "en"=>"Минимальная цена товара в разделе"
        )
    );
    $obUserField->Add($arFields);
    // максимальная цена
    $arFields = Array(
        "ENTITY_ID" => "IBLOCK_".$IBLOCK_ID."_SECTION",
        "FIELD_NAME" => "UF_PRICE_INCSEC_MAX",
        "USER_TYPE_ID" => "string",
        "EDIT_FORM_LABEL" => Array(
            "ru"=>"Максимальная цена товара в разделе + подразделы", 
            "en"=>"Максимальная цена товара в разделе + подразделы"
        )
    );
    $obUserField->Add($arFields);
    // максимальная цена
    $arFields = Array(
        "ENTITY_ID" => "IBLOCK_".$IBLOCK_ID."_SECTION",
        "FIELD_NAME" => "UF_PRICE_INCSEC_MIN",
        "USER_TYPE_ID" => "string",
        "EDIT_FORM_LABEL" => Array(
            "ru"=>"Минимальная цена товара в разделе + подразделы", 
            "en"=>"Минимальная цена товара в разделе + подразделы"
        )
    );
    $obUserField->Add($arFields);
    //свойства которые нужно отобразить в филтре раздела
    $arFields = Array(
        "ENTITY_ID" => "IBLOCK_".$IBLOCK_ID."_SECTION",
        "FIELD_NAME" => "UF_PROPERTY_SHOW",
        "USER_TYPE_ID" => "string",
        "EDIT_FORM_LABEL" => Array(
            "ru"=>"Свойства раздела для отображения в фильтре", 
            "en"=>"Свойства раздела для отображения в фильтре"
        )
    );
    $obUserField->Add($arFields);
    
}

   


?>