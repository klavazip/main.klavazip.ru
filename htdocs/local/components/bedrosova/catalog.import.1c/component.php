<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if (CModule::IncludeModule("iblock"));
CModule::IncludeModule('highloadblock');

function AddToHI($tableName='bedrosova_filter_sef',$parent_xml_id,$child_xml_id, $code){

	if (strlen($code)<1){
		return false;
	}
	
	if (!preg_match("#^[aA-zZ0-9_]+$#",$code)) {
			return false;
	} 

	$HLData = \Bitrix\Highloadblock\HighloadBlockTable::getList(array('filter'=>array('TABLE_NAME'=>$tableName)));
      if ($HLBlock = $HLData->fetch())
      {
       //found highloadiblock
           
       $HLBlock_entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($HLBlock);
	   
	   
	   if ($child_xml_id!="root"){
        

			   //Зададим параметры запроса, любой параметр можно опустить
			   

				$main_query = new \Bitrix\Main\Entity\Query($HLBlock_entity);
				$main_query->setSelect(array('*'));
				$main_query->setFilter(array('UF_CATEGORY_XML_ID'=> $parent_xml_id,"UF_SEF"=>$code));

				//Выполним запрос
				$res_query = $main_query->exec();

				//Получаем результат по привычной схеме
				$res_query = new CDBResult($res_query);   
				if (!$row = $res_query->Fetch())
				{
				 //create record in Highload IBlock
				 $HLBlock_entity_data_class = $HLBlock_entity->getDataClass();
				 $arBxData = array
				 (
				  'UF_CATEGORY_XML_ID' => $parent_xml_id,
				  'UF_VALUE_XML_ID' => $child_xml_id,
				  'UF_SEF'=> $code,
				 );
				 $res_query = $HLBlock_entity_data_class::add($arBxData);
				}
				else{
				//апдейтим
				
				 $HLBlock_entity_data_class = $HLBlock_entity->getDataClass();
				 $arBxData = array
				 (
				  'UF_CATEGORY_XML_ID' => $parent_xml_id,
				  'UF_VALUE_XML_ID' => $child_xml_id,
				  'UF_SEF'=> $code,
				 );
				 $res_query = $HLBlock_entity_data_class::update($row['ID'],$arBxData);
				
				}
		
			}
			else{
			
				$main_query2 = new \Bitrix\Main\Entity\Query($HLBlock_entity);
				$main_query2->setSelect(array('*'));
				$main_query2->setFilter(array("UF_SEF"=>$code));

				//Выполним запрос
				$res_query2 = $main_query2->exec();

				//Получаем результат по привычной схеме
				$res_query2 = new CDBResult($res_query2);   
				if (!$row2 = $res_query2->Fetch())
				{
							
							$main_query = new \Bitrix\Main\Entity\Query($HLBlock_entity);
							$main_query->setSelect(array('*'));
							$main_query->setFilter(array('UF_CATEGORY_XML_ID'=> $parent_xml_id));

							//Выполним запрос
							$res_query = $main_query->exec();

							//Получаем результат по привычной схеме
							$res_query = new CDBResult($res_query);   
							if (!$row = $res_query->Fetch())
							{
							 //create record in Highload IBlock
							 $HLBlock_entity_data_class = $HLBlock_entity->getDataClass();
							 $arBxData = array
							 (
							  'UF_CATEGORY_XML_ID' => $parent_xml_id,
							  'UF_VALUE_XML_ID' => $child_xml_id,
							  'UF_SEF'=> $code,
							 );
							 $res_query = $HLBlock_entity_data_class::add($arBxData);
							}
							else{
							//апдейтим
							
							 $HLBlock_entity_data_class = $HLBlock_entity->getDataClass();
							 $arBxData = array
							 (
							  'UF_CATEGORY_XML_ID' => $parent_xml_id,
							  'UF_VALUE_XML_ID' => $child_xml_id,
							  'UF_SEF'=> $code,
							 );
							 $res_query = $HLBlock_entity_data_class::update($row['ID'],$arBxData);
							
							}
						

						
				}
			
			
			}
       
      }
}

class CIBlockCMLCustomImport extends CIBlockCMLImport
{



function ImportMetaData($xml_root_id, $IBLOCK_TYPE, $IBLOCK_LID, $bUpdateIBlock = true)
	{
		global $DB;

		$rs = $this->_xml_file->GetList(
			array(),
			array("ID" => $xml_root_id, "NAME" => GetMessage("IBLOCK_XML2_COMMERCE_INFO")),
			array("ID", "ATTRIBUTES")
		);
		$ar = $rs->Fetch();
		if($ar && (strlen($ar["ATTRIBUTES"]) > 0))
		{
			$info = unserialize($ar["ATTRIBUTES"]);
			if(is_array($info) && array_key_exists(GetMessage("IBLOCK_XML2_SUM_FORMAT"), $info))
			{
				if(preg_match("#".GetMessage("IBLOCK_XML2_SUM_FORMAT_DELIM")."=(.);{0,1}#", $info[GetMessage("IBLOCK_XML2_SUM_FORMAT")], $match))
				{
					$this->next_step["sdp"] = $match[1];
				}
			}
		}

		$meta_data_xml_id = false;
		$XML_ELEMENTS_PARENT = false;
		$XML_SECTIONS_PARENT = false;
		$XML_PROPERTIES_PARENT = false;
		$XML_SECTIONS_PROPERTIES_PARENT = false;
		$XML_PRICES_PARENT = false;
		$XML_STORES_PARENT = false;
		$XML_SECTION_PROPERTIES = false;

		$this->next_step["bOffer"] = false;
		$rs = $this->_xml_file->GetList(
			array(),
			array("PARENT_ID" => $xml_root_id, "NAME" => GetMessage("IBLOCK_XML2_CATALOG")),
			array("ID", "ATTRIBUTES")
		);
		$ar = $rs->Fetch();

		//arraytofile($ar, $_SERVER['DOCUMENT_ROOT']."/upload/logs/log_import_meta.txt", "ar");  

		if(!$ar)
		{
			$rs = $this->_xml_file->GetList(
				array(),
				array("PARENT_ID" => $xml_root_id, "NAME" => GetMessage("IBLOCK_XML2_OFFER_LIST")),
				array("ID", "ATTRIBUTES")
			);
			$ar = $rs->Fetch();
			$this->next_step["bOffer"] = true;
		}

		if($ar)
		{
			if(strlen($ar["ATTRIBUTES"]) > 0)
			{
				$attrs = unserialize($ar["ATTRIBUTES"]);
				if(is_array($attrs))
				{
					if(array_key_exists(GetMessage("IBLOCK_XML2_UPDATE_ONLY"), $attrs))
						$this->next_step["bUpdateOnly"] = ($attrs[GetMessage("IBLOCK_XML2_UPDATE_ONLY")]=="true") || intval($attrs["IBLOCK_XML2_UPDATE_ONLY"])? true: false;
				}
			}

			//Information block fields with following Add/Update
			$arIBlock = array(
			);
			$rs = $this->_xml_file->GetList(
				array("ID" => "asc"),
				array("PARENT_ID" => $ar["ID"])
			);
			while($ar = $rs->Fetch())
			{

				if(isset($ar["VALUE_CLOB"]))
					$ar["VALUE"] = $ar["VALUE_CLOB"];
				if($ar["NAME"] == GetMessage("IBLOCK_XML2_ID"))
					$arIBlock["XML_ID"] = ($this->use_iblock_type_id? $IBLOCK_TYPE."-": "").$ar["VALUE"];
				elseif($ar["NAME"] == GetMessage("IBLOCK_XML2_CATALOG_ID"))
					$arIBlock["CATALOG_XML_ID"] = ($this->use_iblock_type_id? $IBLOCK_TYPE."-": "").$ar["VALUE"];
				elseif($ar["NAME"] == GetMessage("IBLOCK_XML2_NAME"))
					$arIBlock["NAME"] = $ar["VALUE"];
				elseif($ar["NAME"] == GetMessage("IBLOCK_XML2_DESCRIPTION"))
				{
					$arIBlock["DESCRIPTION"] = $ar["VALUE"];
					$arIBlock["DESCRIPTION_TYPE"] = "html";
				}
				elseif($ar["NAME"] == GetMessage("IBLOCK_XML2_POSITIONS") || $ar["NAME"] == GetMessage("IBLOCK_XML2_OFFERS"))
					$XML_ELEMENTS_PARENT = $ar["ID"];
				elseif($ar["NAME"] == GetMessage("IBLOCK_XML2_PRICE_TYPES"))
					$XML_PRICES_PARENT = $ar["ID"];
				elseif($ar["NAME"] == GetMessage("IBLOCK_XML2_STORES"))
					$XML_STORES_PARENT = $ar["ID"];
				elseif($ar["NAME"] == GetMessage("IBLOCK_XML2_METADATA_ID"))
					$meta_data_xml_id = $ar["VALUE"];
				elseif($ar["NAME"] == GetMessage("IBLOCK_XML2_UPDATE_ONLY"))
					$this->next_step["bUpdateOnly"] = ($ar["VALUE"]=="true") || intval($ar["VALUE"])? true: false;
				elseif($ar["NAME"] == GetMessage("IBLOCK_XML2_BX_CODE"))
					$arIBlock["CODE"] = $ar["VALUE"];
				elseif($ar["NAME"] == GetMessage("IBLOCK_XML2_BX_SORT"))
					$arIBlock["SORT"] = $ar["VALUE"];
				elseif($ar["NAME"] == GetMessage("IBLOCK_XML2_BX_LIST_URL"))
					$arIBlock["LIST_PAGE_URL"] = $ar["VALUE"];
				elseif($ar["NAME"] == GetMessage("IBLOCK_XML2_BX_DETAIL_URL"))
					$arIBlock["DETAIL_PAGE_URL"] = $ar["VALUE"];
				elseif($ar["NAME"] == GetMessage("IBLOCK_XML2_BX_SECTION_URL"))
					$arIBlock["SECTION_PAGE_URL"] = $ar["VALUE"];
				elseif($ar["NAME"] == GetMessage("IBLOCK_XML2_BX_INDEX_ELEMENTS"))
					$arIBlock["INDEX_ELEMENT"] = ($ar["VALUE"]=="true") || intval($ar["VALUE"])? "Y": "N";
				elseif($ar["NAME"] == GetMessage("IBLOCK_XML2_BX_INDEX_SECTIONS"))
					$arIBlock["INDEX_SECTION"] = ($ar["VALUE"]=="true") || intval($ar["VALUE"])? "Y": "N";
				elseif($ar["NAME"] == GetMessage("IBLOCK_XML2_BX_SECTIONS_NAME"))
					$arIBlock["SECTIONS_NAME"] = $ar["VALUE"];
				elseif($ar["NAME"] == GetMessage("IBLOCK_XML2_BX_SECTION_NAME"))
					$arIBlock["SECTION_NAME"] = $ar["VALUE"];
				elseif($ar["NAME"] == GetMessage("IBLOCK_XML2_BX_ELEMENTS_NAME"))
					$arIBlock["ELEMENTS_NAME"] = $ar["VALUE"];
				elseif($ar["NAME"] == GetMessage("IBLOCK_XML2_BX_ELEMENT_NAME"))
					$arIBlock["ELEMENT_NAME"] = $ar["VALUE"];
				elseif($ar["NAME"] == GetMessage("IBLOCK_XML2_BX_PICTURE"))
				{
					if(strlen($ar["VALUE"]) > 0)
						$arIBlock["PICTURE"] = $this->MakeFileArray($ar["VALUE"]);
					else
						$arIBlock["PICTURE"] = $this->MakeFileArray($this->_xml_file->GetAllChildrenArray($ar["ID"]));
				}
				elseif($ar["NAME"] == GetMessage("IBLOCK_XML2_BX_WORKFLOW"))
					$arIBlock["WORKFLOW"] = ($ar["VALUE"]=="true") || intval($ar["VALUE"])? "Y": "N";
				elseif($ar["NAME"] == GetMessage("IBLOCK_XML2_LABELS"))
				{
					$arLabels = $this->_xml_file->GetAllChildrenArray($ar["ID"]);
					foreach($arLabels as $key => $arLabel)
					{
						$id = $arLabel[GetMessage("IBLOCK_XML2_ID")];
						$label = $arLabel[GetMessage("IBLOCK_XML2_VALUE")];
						if(strlen($id) > 0 && strlen($label) > 0)
							$arIBlock[$id] = $label;
					}
				}
			}
			if($this->next_step["bOffer"] && !$this->use_offers)
			{
				if(strlen($arIBlock["CATALOG_XML_ID"]) > 0)
				{
					$arIBlock["XML_ID"] = $arIBlock["CATALOG_XML_ID"];
					$this->next_step["bUpdateOnly"] = true;
				}
			}

			$obIBlock = new CIBlock;
			$rsIBlocks = $obIBlock->GetList(array(), array("XML_ID"=>$arIBlock["XML_ID"]));
			$ar = $rsIBlocks->Fetch();

			//Also check for non bitrix xml file
			if(!$ar && !array_key_exists("CODE", $arIBlock))
			{
				if($this->next_step["bOffer"] && $this->use_offers)
					$rsIBlocks = $obIBlock->GetList(array(), array("XML_ID"=>"FUTURE-1C-OFFERS"));
				else
					$rsIBlocks = $obIBlock->GetList(array(), array("XML_ID"=>"FUTURE-1C-CATALOG"));
				$ar = $rsIBlocks->Fetch();
			}
			if($ar)
			{
				if($bUpdateIBlock && (!$this->next_step["bOffer"] || $this->use_offers))
				{
					if($obIBlock->Update($ar["ID"], $arIBlock))
						$arIBlock["ID"] = $ar["ID"];
					else
						return $obIBlock->LAST_ERROR;
				}
				else
				{
					$arIBlock["ID"] = $ar["ID"];
				}
			}
			else
			{
				$arIBlock["IBLOCK_TYPE_ID"] = $this->CheckIBlockType($IBLOCK_TYPE);
				if(!$arIBlock["IBLOCK_TYPE_ID"])
					return GetMessage("IBLOCK_XML2_TYPE_ADD_ERROR");
				$arIBlock["GROUP_ID"] = array(2=>"R");
				$arIBlock["LID"] = $this->CheckSites($IBLOCK_LID);
				$arIBlock["ACTIVE"] = "Y";
				$arIBlock["WORKFLOW"] = "N";
				$arIBlock["ID"] = $obIBlock->Add($arIBlock);
				if(!$arIBlock["ID"])
					return $obIBlock->LAST_ERROR;
			}

			//Make this catalog
			if($this->bCatalog && $this->next_step["bOffer"])
			{
				$obCatalog = new CCatalog();
				$intParentID = $this->GetIBlockByXML_ID($arIBlock["CATALOG_XML_ID"]);
				if (0 < intval($intParentID) && $this->use_offers)
				{
/*					$rs = CCatalog::GetList(array(),array("IBLOCK_ID"=>$arIBlock["ID"]));
					if($arOffer = $rs->Fetch())
					{
						CCatalog::Update($arIBlock["ID"],array('OFFERS' => 'Y'));
					}
					else
					{
						CCatalog::Add(array("IBLOCK_ID"=>$arIBlock["ID"], "YANDEX_EXPORT"=>"N", "SUBSCRIPTION"=>"N",'OFFERS' => 'Y','OFFERS_IBLOCK_ID' => 0));
					}

					$rs = CCatalog::GetList(array(),array('IBLOCK_ID' => $intParentID));
					if ($arParent = $rs->Fetch())
					{
						CCatalog::Update($arParent['ID'],array('OFFERS_IBLOCK_ID' => $arIBlock["ID"]));
					}
					else
					{
						CCatalog::Add(array("IBLOCK_ID"=>$intParentID, "YANDEX_EXPORT"=>"N", "SUBSCRIPTION"=>"N",'OFFERS' => 'N','OFFERS_IBLOCK_ID' => $arIBlock["ID"]));
					} */
					$mxSKUProp = $obCatalog->LinkSKUIBlock($intParentID,$arIBlock["ID"]);
					if (!$mxSKUProp)
					{
						if ($ex = $APPLICATION->GetException())
						{
							$result = $ex->GetString();
							return $result;
						}
					}
					else
					{
						$boolFlag = true;
						$rs = CCatalog::GetList(array(),array("IBLOCK_ID"=>$arIBlock["ID"]));
						if($arOffer = $rs->Fetch())
						{
							$boolFlag = $obCatalog->Update($arIBlock["ID"],array('PRODUCT_IBLOCK_ID' => $intParentID,'SKU_PROPERTY_ID' => $mxSKUProp));
						}
						else
						{
							$boolFlag = $obCatalog->Add(array("IBLOCK_ID"=>$arIBlock["ID"], "YANDEX_EXPORT"=>"N", "SUBSCRIPTION"=>"N",'PRODUCT_IBLOCK_ID' => $intParentID,'SKU_PROPERTY_ID' => $mxSKUProp));
						}
						if (!$boolFlag)
						{
							if ($ex = $APPLICATION->GetException())
							{
								$result = $ex->GetString();
								return $result;
							}
						}
					}
				}
				else
				{
					$rs = CCatalog::GetList(array(),array("IBLOCK_ID"=>$arIBlock["ID"]));
					if(!($rs->Fetch()))
					{
						$boolFlag = $obCatalog->Add(array("IBLOCK_ID"=>$arIBlock["ID"], "YANDEX_EXPORT"=>"N", "SUBSCRIPTION"=>"N"));
						if (!$boolFlag)
						{
							if ($ex = $APPLICATION->GetException())
							{
								$result = $ex->GetString();
								return $result;
							}
						}
					}
				}
			}

			//For non bitrix xml file
			//Check for mandatory properties and add them as necessary
			if(!array_key_exists("CODE", $arIBlock))
			{
				$arProperties = array(
					"CML2_BAR_CODE" => GetMessage("IBLOCK_XML2_BAR_CODE"),
					"CML2_ARTICLE" => GetMessage("IBLOCK_XML2_ARTICLE"),
					"CML2_ATTRIBUTES" => array(
						"NAME" => GetMessage("IBLOCK_XML2_ATTRIBUTES"),
						"MULTIPLE" => "Y",
						"WITH_DESCRIPTION" => "Y",
						"MULTIPLE_CNT" => 1,
					),
					"CML2_TRAITS" => array(
						"NAME" => GetMessage("IBLOCK_XML2_TRAITS"),
						"MULTIPLE" => "Y",
						"WITH_DESCRIPTION" => "Y",
						"MULTIPLE_CNT" => 1,
					),
					"CML2_BASE_UNIT" => GetMessage("IBLOCK_XML2_BASE_UNIT_NAME"),
					"CML2_TAXES" => array(
						"NAME" => GetMessage("IBLOCK_XML2_TAXES"),
						"MULTIPLE" => "Y",
						"WITH_DESCRIPTION" => "Y",
						"MULTIPLE_CNT" => 1,
					),
					"CML2_PICTURES" => array(
						"NAME" => GetMessage("IBLOCK_XML2_PICTURES"),
						"MULTIPLE" => "Y",
						"WITH_DESCRIPTION" => "Y",
						"MULTIPLE_CNT" => 1,
						"PROPERTY_TYPE" => "F",
						"CODE" => "MORE_PHOTO",
					),
					"CML2_FILES" => array(
						"NAME" => GetMessage("IBLOCK_XML2_FILES"),
						"MULTIPLE" => "Y",
						"WITH_DESCRIPTION" => "Y",
						"MULTIPLE_CNT" => 1,
						"PROPERTY_TYPE" => "F",
						"CODE" => "FILES",
					),
				);
				foreach($arProperties as $k=>$v)
				{
					$result = $this->CheckProperty($arIBlock["ID"], $k, $v);
					if($result!==true)
						return $result;
				}
				//For offers make special property: link to catalog
				if(isset($arIBlock["CATALOG_XML_ID"]) && $this->use_offers)
					$result = $this->CheckProperty($arIBlock["ID"], "CML2_LINK", array(
						"NAME" => GetMessage("IBLOCK_XML2_CATALOG_ELEMENT"),
						"PROPERTY_TYPE" => "E",
						"USER_TYPE" => "SKU",
						"LINK_IBLOCK_ID" => $this->GetIBlockByXML_ID($arIBlock["CATALOG_XML_ID"]),
						"FILTRABLE" => "Y",
					));
			}

			$this->next_step["IBLOCK_ID"] = $arIBlock["ID"];
			$this->next_step["XML_ELEMENTS_PARENT"] = $XML_ELEMENTS_PARENT;

		}

		if($meta_data_xml_id)
		{
			$rs = $this->_xml_file->GetList(
				array(),
				array("PARENT_ID" => $xml_root_id, "NAME" => GetMessage("IBLOCK_XML2_METADATA")),
				array("ID")
			);
			while($arMetadata = $rs->Fetch())
			{
				//Find referenced metadata
				$bMetaFound = false;
				$meta_roots = array();
				$rsMetaRoots = $this->_xml_file->GetList(
					array("ID" => "asc"),
					array("PARENT_ID" => $arMetadata["ID"])
				);
				while($arMeta = $rsMetaRoots->Fetch())
				{
					if(isset($arMeta["VALUE_CLOB"]))
						$arMeta["VALUE"] = $arMeta["VALUE_CLOB"];
					if($arMeta["NAME"] == GetMessage("IBLOCK_XML2_ID") && $arMeta["VALUE"] == $meta_data_xml_id)
						$bMetaFound = true;
					$meta_roots[] = $arMeta;
				}
				//Get xml parents of the properties and sections
				if($bMetaFound)
				{
					foreach($meta_roots as $arMeta)
					{
						if($arMeta["NAME"] == GetMessage("IBLOCK_XML2_GROUPS"))
							$XML_SECTIONS_PARENT = $arMeta["ID"];
						elseif($arMeta["NAME"] == GetMessage("IBLOCK_XML2_PROPERTIES"))
							$XML_PROPERTIES_PARENT = $arMeta["ID"];
						elseif($arMeta["NAME"] == GetMessage("IBLOCK_XML2_GROUPS_PROPERTIES"))
							$XML_SECTIONS_PROPERTIES_PARENT = $arMeta["ID"];
						elseif($arMeta["NAME"] == GetMessage("IBLOCK_XML2_SECTION_PROPERTIES"))
							$XML_SECTION_PROPERTIES = $arMeta["ID"];
					}
					break;
				}
			}
		}

		if($XML_PROPERTIES_PARENT)
		{
			$result = $this->ImportProperties($XML_PROPERTIES_PARENT, $arIBlock["ID"]);
			if($result!==true)
				return $result;
		}

		if($XML_SECTION_PROPERTIES)
		{
			$result = $this->ImportSectionProperties($XML_SECTION_PROPERTIES, $arIBlock["ID"]);
			if($result!==true)
				return $result;
		}

		if($XML_SECTIONS_PROPERTIES_PARENT)
		{
			$result = $this->ImportSectionsProperties($XML_SECTIONS_PROPERTIES_PARENT, $arIBlock["ID"]);
			if($result!==true)
				return $result;
		}

		if($XML_PRICES_PARENT)
		{
			if($this->bCatalog)
			{
				$result = $this->ImportPrices($XML_PRICES_PARENT, $arIBlock["ID"], $IBLOCK_LID);
				if($result!==true)
					return $result;
			}
		}

		if($XML_STORES_PARENT)
		{
			if($this->bCatalog)
			{
				$result = $this->ImportStores($XML_STORES_PARENT);
				if($result!==true)
					return $result;
			}
		}

		$this->next_step["section_sort"] = 100;
		$this->next_step["XML_SECTIONS_PARENT"] = $XML_SECTIONS_PARENT;

		return true;
	}

	function SetProductPrice($PRODUCT_ID, $arPrices, $arDiscounts = false)
	{


		$arDBPrices = array();
		$rsPrice = CPrice::GetList(array(), array("PRODUCT_ID" => $PRODUCT_ID));
		while($ar = $rsPrice->Fetch())
			$arDBPrices[$ar["CATALOG_GROUP_ID"].":".$ar["QUANTITY_FROM"].":".$ar["QUANTITY_TO"]] = $ar["ID"];

		if(!is_array($arPrices))
			$arPrices = array();

		foreach($arPrices as $key=>$price)
		{

			if(!isset($price[GetMessage("IBLOCK_XML2_CURRENCY")]))
				$price[GetMessage("IBLOCK_XML2_CURRENCY")] = $price["PRICE"]["CURRENCY"];

			$arPrice = Array(
				"PRODUCT_ID" => $PRODUCT_ID,
				"CATALOG_GROUP_ID" => $price["PRICE"]["ID"],
				"^PRICE" => $this->ToFloat($price[GetMessage("IBLOCK_XML2_PRICE_FOR_ONE")]),
				"CURRENCY" => $this->CheckCurrency($price[GetMessage("IBLOCK_XML2_CURRENCY")]),
			);

			if ($arPrice['CATALOG_GROUP_ID']=='4'){
					//Найдем группу в которую входит элемент для которого модифицируем цену
					$db_old_groups = CIBlockElement::GetElementGroups($arPrice['PRODUCT_ID'], true);
					while($ar_group = $db_old_groups->Fetch()){

						$_SESSION['prices'][''.$ar_group['ID'].''][]=$arPrice['^PRICE'];

					}
				}

			foreach($this->ConvertDiscounts($arDiscounts) as $arDiscount)
			{
				$arPrice["QUANTITY_FROM"] = $arDiscount["QUANTITY_FROM"];
				$arPrice["QUANTITY_TO"] = $arDiscount["QUANTITY_TO"];
				if($arDiscount["PERCENT"] > 0)
					$arPrice["PRICE"] = $arPrice["^PRICE"] - $arPrice["^PRICE"]/100*$arDiscount["PERCENT"];
				else
					$arPrice["PRICE"] = $arPrice["^PRICE"];

				$id = $arPrice["CATALOG_GROUP_ID"].":".$arPrice["QUANTITY_FROM"].":".$arPrice["QUANTITY_TO"];


				if(array_key_exists($id, $arDBPrices))
				{
					//arraytofile($arPrice,$_SERVER['DOCUMENT_ROOT']."/upload/arPrice".$arDBPrices[$id].".txt", "arPrice");
					CPrice::Update($arDBPrices[$id], $arPrice);
					unset($arDBPrices[$id]);
				}
				else
				{
					$pr_id=CPrice::Add($arPrice);

					//arraytofile($arPrice,$_SERVER['DOCUMENT_ROOT']."/upload/arPrice".$pr_id.".txt", "arPrice");
				}
			}
		}

		foreach($arDBPrices as $key=>$id)
			CPrice::Delete($id);
	}
	
	
	
	
		function ImportProperties($XML_PROPERTIES_PARENT, $IBLOCK_ID)
	{
		
		
			
		
		$obProperty = new CIBlockProperty;
		$sort = 100;

		$arElementFields = array(
			"CML2_ACTIVE" => GetMessage("IBLOCK_XML2_BX_ACTIVE"),
			"CML2_CODE" => GetMessage("IBLOCK_XML2_SYMBOL_CODE"),
			"CML2_SORT" => GetMessage("IBLOCK_XML2_SORT"),
			"CML2_ACTIVE_FROM" => GetMessage("IBLOCK_XML2_START_TIME"),
			"CML2_ACTIVE_TO" => GetMessage("IBLOCK_XML2_END_TIME"),
			"CML2_PREVIEW_TEXT" => GetMessage("IBLOCK_XML2_ANONS"),
			"CML2_DETAIL_TEXT" => GetMessage("IBLOCK_XML2_DETAIL"),
			"CML2_PREVIEW_PICTURE" => GetMessage("IBLOCK_XML2_PREVIEW_PICTURE"),
		);

		$rs = $this->_xml_file->GetList(
			array("ID" => "asc"),
			array("PARENT_ID" => $XML_PROPERTIES_PARENT),
			array("ID")
		);
		while($ar = $rs->Fetch())
		{
			$XML_ENUM_PARENT = false;
			$arProperty = array(
			);
			$rsP = $this->_xml_file->GetList(
				array("ID" => "asc"),
				array("PARENT_ID" => $ar["ID"])
			);
			
			
			$array_to_add=array();
			
			while($arP = $rsP->Fetch())
			{
				//Бедросова 27.05.2014
				//Тут у нас в массиве каждое из свойств свойства, в т.ч. символьный код
				//arraytofile($arP,$_SERVER['DOCUMENT_ROOT']."/upload/ImportLog.txt", "arP");
				/*
				$arP = 
				array(
				  'ID' => '146',
				  'PARENT_ID' => '145',
				  'LEFT_MARGIN' => '287',
				  'RIGHT_MARGIN' => '288',
				  'DEPTH_LEVEL' => '4',
				  'NAME' => 'Ид',
				  'VALUE' => '83875106-f414-11e0-83db-0023ae2ed3a2',
				  'ATTRIBUTES' => '');
				?>27.05.2014 / 12:26:07 <?
				$arP = 
				array(
				  'ID' => '147',
				  'PARENT_ID' => '145',
				  'LEFT_MARGIN' => '289',
				  'RIGHT_MARGIN' => '290',
				  'DEPTH_LEVEL' => '4',
				  'NAME' => 'Наименование',
				  'VALUE' => 'Поверхность',
				  'ATTRIBUTES' => '');
				?>27.05.2014 / 12:26:07 <?
				$arP = 
				array(
				  'ID' => '148',
				  'PARENT_ID' => '145',
				  'LEFT_MARGIN' => '291',
				  'RIGHT_MARGIN' => '292',
				  'DEPTH_LEVEL' => '4',
				  'NAME' => 'ТипЗначений',
				  'VALUE' => 'Справочник',
				  'ATTRIBUTES' => '');
				?>27.05.2014 / 12:26:07 <?
				$arP = 
				array(
				  'ID' => '149',
				  'PARENT_ID' => '145',
				  'LEFT_MARGIN' => '293',
				  'RIGHT_MARGIN' => '294',
				  'DEPTH_LEVEL' => '4',
				  'NAME' => 'СимвольныйКод',
				  'VALUE' => 'poverhnost',
				  'ATTRIBUTES' => '');
				?>
				*/
				
				
			
				if(isset($arP["VALUE_CLOB"]))
					$arP["VALUE"] = $arP["VALUE_CLOB"];

				if($arP["NAME"]==GetMessage("IBLOCK_XML2_ID"))
				{
					$arProperty["XML_ID"] = $arP["VALUE"];
					$array_to_add["XML_ID"] = $arP["VALUE"];
					if(array_key_exists($arProperty["XML_ID"], $arElementFields))
						break;
				}
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_NAME"))
					$arProperty["NAME"] = $arP["VALUE"];
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_MULTIPLE"))
					$arProperty["MULTIPLE"] = ($arP["VALUE"]=="true") || intval($arP["VALUE"])? "Y": "N";
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_BX_SORT"))
					$arProperty["SORT"] = $arP["VALUE"];
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_BX_CODE"))
					$arProperty["CODE"] = $arP["VALUE"];
				elseif($arP["NAME"]=='СимвольныйКод')
					$array_to_add["CODE"] = $arP["VALUE"];
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_BX_DEFAULT_VALUE"))
					$arProperty["DEFAULT_VALUE"] = $arP["VALUE"];
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_SERIALIZED"))
					$arProperty["SERIALIZED"] = ($arP["VALUE"]=="true") || intval($arP["VALUE"])? "Y": "N";
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_BX_PROPERTY_TYPE"))
					$arProperty["PROPERTY_TYPE"] = $arP["VALUE"];
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_BX_ROWS"))
					$arProperty["ROW_COUNT"] = $arP["VALUE"];
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_BX_COLUMNS"))
					$arProperty["COL_COUNT"] = $arP["VALUE"];
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_BX_LIST_TYPE"))
					$arProperty["LIST_TYPE"] = $arP["VALUE"];
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_BX_FILE_EXT"))
					$arProperty["FILE_TYPE"] = $arP["VALUE"];
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_BX_FIELDS_COUNT"))
					$arProperty["MULTIPLE_CNT"] = $arP["VALUE"];
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_BX_USER_TYPE"))
					$arProperty["USER_TYPE"] = $arP["VALUE"];
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_BX_WITH_DESCRIPTION"))
					$arProperty["WITH_DESCRIPTION"] = ($arP["VALUE"]=="true") || intval($arP["VALUE"])? "Y": "N";
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_BX_SEARCH"))
					$arProperty["SEARCHABLE"] = ($arP["VALUE"]=="true") || intval($arP["VALUE"])? "Y": "N";
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_BX_FILTER"))
					$arProperty["FILTRABLE"] = ($arP["VALUE"]=="true") || intval($arP["VALUE"])? "Y": "N";
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_BX_LINKED_IBLOCK"))
					$arProperty["LINK_IBLOCK_ID"] = $this->GetIBlockByXML_ID($arP["VALUE"]);
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_CHOICE_VALUES"))
					$XML_ENUM_PARENT = $arP["ID"];
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_BX_IS_REQUIRED"))
					$arProperty["IS_REQUIRED"] = ($arP["VALUE"]=="true") || intval($arP["VALUE"])? "Y": "N";
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_VALUES_TYPE"))
				{
					if($arP["VALUE"] == GetMessage("IBLOCK_XML2_TYPE_LIST"))
						$arProperty["PROPERTY_TYPE"] = "L";
					elseif($arP["VALUE"] == GetMessage("IBLOCK_XML2_TYPE_NUMBER"))
						$arProperty["PROPERTY_TYPE"] = "N";
				}
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_VALUES_TYPES"))
				{
					//This property metadata contains information about it's type
					$rsTypes = $this->_xml_file->GetList(
						array("ID" => "asc"),
						array("PARENT_ID" => $arP["ID"]),
						array("ID", "LEFT_MARGIN", "RIGHT_MARGIN", "NAME")
					);
					$arType = $rsTypes->Fetch();
					//We'll process only properties with NOT composing types
					//composed types will be supported only as simple string properties
					if($arType && !$rsTypes->Fetch())
					{
						$rsType = $this->_xml_file->GetList(
							array("ID" => "asc"),
							array("PARENT_ID" => $arType["ID"]),
							array("ID", "LEFT_MARGIN", "RIGHT_MARGIN", "NAME", "VALUE")
						);
						while($arType = $rsType->Fetch())
						{
							if($arType["NAME"] == GetMessage("IBLOCK_XML2_TYPE"))
							{
								if($arType["VALUE"] == GetMessage("IBLOCK_XML2_TYPE_LIST"))
									$arProperty["PROPERTY_TYPE"] = "L";
								elseif($arType["VALUE"] == GetMessage("IBLOCK_XML2_TYPE_NUMBER"))
									$arProperty["PROPERTY_TYPE"] = "N";
							}
							elseif($arType["NAME"] == GetMessage("IBLOCK_XML2_CHOICE_VALUES"))
							{
								$XML_ENUM_PARENT = $arType["ID"];
							}
						}
					}
				}
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_BX_USER_TYPE_SETTINGS"))
				{
					$arProperty["USER_TYPE_SETTINGS"] = unserialize($arP["VALUE"]);
				}
			
			
			
			}
			
			AddToHI('bedrosova_filter_sef',$array_to_add['XML_ID'],'root', $array_to_add['CODE']);
			
			
			

			if(array_key_exists($arProperty["XML_ID"], $arElementFields))
				continue;

			// Skip properties with no choice values
			// http://jabber.bx/view.php?id=30476
			$arEnumXmlNodes = array();
			if($XML_ENUM_PARENT)
			{
				$rsE = $this->_xml_file->GetList(
					array("ID" => "asc"),
					array("PARENT_ID" => $XML_ENUM_PARENT)
				);
				while($arE = $rsE->Fetch())
				{
					if(isset($arE["VALUE_CLOB"]))
						$arE["VALUE"] = $arE["VALUE_CLOB"];
					$arEnumXmlNodes[] = $arE;
				}

				if (empty($arEnumXmlNodes))
					continue;
			}

			if($arProperty["SERIALIZED"] == "Y")
				$arProperty["DEFAULT_VALUE"] = unserialize($arProperty["DEFAULT_VALUE"]);

			$rsProperty = $obProperty->GetList(array(), array("IBLOCK_ID"=>$IBLOCK_ID, "XML_ID"=>$arProperty["XML_ID"]));
			if($arDBProperty = $rsProperty->Fetch())
			{
				$bChanged = false;
				foreach($arProperty as $key=>$value)
				{
					if($arDBProperty[$key] !== $value)
					{
						$bChanged = true;
						break;
					}
				}
				if(!$bChanged)
					$arProperty["ID"] = $arDBProperty["ID"];
				elseif($obProperty->Update($arDBProperty["ID"], $arProperty))
					$arProperty["ID"] = $arDBProperty["ID"];
				else
					return $obProperty->LAST_ERROR;
			}
			else
			{
				$arProperty["IBLOCK_ID"] = $IBLOCK_ID;
				$arProperty["ACTIVE"] = "Y";
				if(!array_key_exists("PROPERTY_TYPE", $arProperty))
					$arProperty["PROPERTY_TYPE"] = "S";
				if(!array_key_exists("SORT", $arProperty))
					$arProperty["SORT"] = $sort;
				if(!array_key_exists("CODE", $arProperty))
				{
					$arProperty["CODE"] = CUtil::translit($arProperty["NAME"], LANGUAGE_ID, array(
						"max_len" => 50,
						"change_case" => 'U', // 'L' - toLower, 'U' - toUpper, false - do not change
						"replace_space" => '_',
						"replace_other" => '_',
						"delete_repeat_replace" => true,
					));
					if(preg_match('/^[0-9]/', $arProperty["CODE"]))
						$arProperty["CODE"] = '_'.$arProperty["CODE"];
				}
				$arProperty["ID"] = $obProperty->Add($arProperty);
				if(!$arProperty["ID"])
					return $obProperty->LAST_ERROR;
			}

			if($XML_ENUM_PARENT)
			{
				$arEnumMap = array();
				$arProperty["VALUES"] = array();
				$rsEnum = CIBlockProperty::GetPropertyEnum($arProperty["ID"]);
				while($arEnum = $rsEnum->Fetch())
				{
					$arProperty["VALUES"][$arEnum["ID"]] = $arEnum;
					$arEnumMap[$arEnum["XML_ID"]] = &$arProperty["VALUES"][$arEnum["ID"]];
				}
				foreach($arEnumXmlNodes as $i => $arE)
				{
					
					
					if(
						$arE["NAME"] == GetMessage("IBLOCK_XML2_CHOICE")
						|| $arE["NAME"] == GetMessage("IBLOCK_XML2_CHOICE_VALUE")
					)
					{
						$arE = $this->_xml_file->GetAllChildrenArray($arE);
						if(isset($arE[GetMessage("IBLOCK_XML2_ID")]))
						{
							$xml_id = $arE[GetMessage("IBLOCK_XML2_ID")];
							if(!array_key_exists($xml_id, $arEnumMap))
							{
								$arProperty["VALUES"]["n".$i] = array();
								$arEnumMap[$xml_id] = &$arProperty["VALUES"]["n".$i];
								$i++;
							}
							$arEnumMap[$xml_id]["CML2_EXPORT_FLAG"] = true;
							$arEnumMap[$xml_id]["XML_ID"] = $xml_id;
							if(isset($arE[GetMessage("IBLOCK_XML2_VALUE")]))
								$arEnumMap[$xml_id]["VALUE"] = $arE[GetMessage("IBLOCK_XML2_VALUE")];
							if(isset($arE[GetMessage("IBLOCK_XML2_BY_DEFAULT")]))
								$arEnumMap[$xml_id]["DEF"] = ($arE[GetMessage("IBLOCK_XML2_BY_DEFAULT")]=="true") || intval($arE[GetMessage("IBLOCK_XML2_BY_DEFAULT")])? "Y": "N";
							if(isset($arE[GetMessage("IBLOCK_XML2_SORT")]))
								$arEnumMap[$xml_id]["SORT"] = intval($arE[GetMessage("IBLOCK_XML2_SORT")]);
						}
					}
					elseif(
						$arE["NAME"] == GetMessage("IBLOCK_XML2_TYPE_LIST")
					)
					{
						$arE = $this->_xml_file->GetAllChildrenArray($arE);
						if(isset($arE[GetMessage("IBLOCK_XML2_VALUE_ID")]))
						{
							
							//print_r($arE);
							AddToHI('bedrosova_filter_sef',$arProperty['XML_ID'],$arE['ИдЗначения'], $arE['СимвольныйКод']);
			
							//Бедросова 27/05
							//Тут у нас в  массиве значения свойства, в том числе символьные коды значений
							//arraytofile($arE,$_SERVER['DOCUMENT_ROOT']."/upload/ImportLog.txt", "arE");
							/*
							
							$arE = 
								array(
								  'ИдЗначения' => '83875108-f414-11e0-83db-0023ae2ed3a2',
								  'Значение' => 'Матовая',
								  'СимвольныйКод' => 'matovaya');
								
							
							
							*/
							
							
							$xml_id = $arE[GetMessage("IBLOCK_XML2_VALUE_ID")];
							if(!array_key_exists($xml_id, $arEnumMap))
							{
								$arProperty["VALUES"]["n".$i] = array();
								$arEnumMap[$xml_id] = &$arProperty["VALUES"]["n".$i];
								$i++;
							}
							$arEnumMap[$xml_id]["CML2_EXPORT_FLAG"] = true;
							$arEnumMap[$xml_id]["XML_ID"] = $xml_id;
							if(isset($arE[GetMessage("IBLOCK_XML2_VALUE")]))
								$arEnumMap[$xml_id]["VALUE"] = $arE[GetMessage("IBLOCK_XML2_VALUE")];
						}
					}
				}

				$bUpdateOnly = array_key_exists("bUpdateOnly", $this->next_step) && $this->next_step["bUpdateOnly"];
				$sort = 100;


				
				foreach($arProperty["VALUES"] as $id=>$arEnum)
				{
					
					
					if(!isset($arEnum["CML2_EXPORT_FLAG"]))
					{
						//Delete value only when full exchange happened
						if(!$bUpdateOnly)
							$arProperty["VALUES"][$id]["VALUE"] = "";
					}
					elseif(isset($arEnum["SORT"]))
					{
						if($arEnum["SORT"] > $sort)
							$sort = $arEnum["SORT"] + 100;
					}
					else
					{
						$arProperty["VALUES"][$id]["SORT"] = $sort;
						$sort += 100;
					}
				}
				$obProperty->UpdateEnum($arProperty["ID"], $arProperty["VALUES"], false);
			}
			$sort += 100;
		}
		return true;
	}
}




class CIBlockCMLCustomImport1 extends CIBlockCMLImport
{

//	function ImportElement is called from function ImportElements($start_time, $interval)

	function ImportElement($arXMLElement, &$counter, $bWF, $arParent)
	{
		//arraytofile($arXMLElement,$_SERVER['DOCUMENT_ROOT']."/upload/Element".".txt", "$arXMLElement");

		global $USER;
		$USER_ID = is_object($USER)? intval($USER->GetID()): 0;

		$arElement = array(
			"ACTIVE" => "Y",
			"TMP_ID" => $this->GetElementCRC($arXMLElement),
			"PROPERTY_VALUES" => array(),
		);
		if(isset($arXMLElement[GetMessage("IBLOCK_XML2_ID")]))
			$arElement["XML_ID"] = $arXMLElement[GetMessage("IBLOCK_XML2_ID")];

		$obElement = new CIBlockElement;
		$obElement->CancelWFSetMove();
		$rsElement = $obElement->GetList(
			Array("ID"=>"asc"),
			Array("=XML_ID" => $arElement["XML_ID"], "IBLOCK_ID" => $this->next_step["IBLOCK_ID"]),
			false, false,
			Array("ID", "TMP_ID", "ACTIVE", "CODE")
		);

		$bMatch = false;
		if($arDBElement = $rsElement->Fetch())
			$bMatch = ($arElement["TMP_ID"] == $arDBElement["TMP_ID"]);

		if($bMatch && $this->use_crc)
		{
			//Check Active flag in XML is not set to false
			if($this->CheckIfElementIsActive($arXMLElement))
			{
				//In case element is not active in database we have to activate it and its offers
				if($arDBElement["ACTIVE"] != "Y")
				{
					$obElement->Update($arDBElement["ID"], array("ACTIVE"=>"Y"), $bWF);
					$this->ChangeOffersStatus($arDBElement["ID"], "Y", $bWF);
					$counter["UPD"]++;
				}
			}
			$arElement["ID"] = $arDBElement["ID"];
		}
		else
		{
			if($arDBElement)
			{
				$rsProperties = $obElement->GetProperty($this->next_step["IBLOCK_ID"], $arDBElement["ID"], "sort", "asc");
				while($arProperty = $rsProperties->Fetch())
				{
					if(!array_key_exists($arProperty["ID"], $arElement["PROPERTY_VALUES"]))
						$arElement["PROPERTY_VALUES"][$arProperty["ID"]] = array(
							"bOld" => true,
						);

					$arElement["PROPERTY_VALUES"][$arProperty["ID"]][$arProperty['PROPERTY_VALUE_ID']] = array(
						"VALUE"=>$arProperty['VALUE'],
						"DESCRIPTION"=>$arProperty["DESCRIPTION"]
					);
				}
			}

			if($this->bCatalog && $this->next_step["bOffer"])
			{
				$p = strpos($arXMLElement[GetMessage("IBLOCK_XML2_ID")], "#");
				if($p !== false)
					$link_xml_id = substr($arXMLElement[GetMessage("IBLOCK_XML2_ID")], 0, $p);
				else
					$link_xml_id = $arXMLElement[GetMessage("IBLOCK_XML2_ID")];
				$arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_LINK"]] = $this->GetElementByXML_ID($this->arProperties[$this->PROPERTY_MAP["CML2_LINK"]]["LINK_IBLOCK_ID"], $link_xml_id);
			}

			if(isset($arXMLElement[GetMessage("IBLOCK_XML2_NAME")]))
				$arElement["NAME"] = $arXMLElement[GetMessage("IBLOCK_XML2_NAME")];
			if(array_key_exists(GetMessage("IBLOCK_XML2_BX_TAGS"), $arXMLElement))
				$arElement["TAGS"] = $arXMLElement[GetMessage("IBLOCK_XML2_BX_TAGS")];
			if(array_key_exists(GetMessage("IBLOCK_XML2_DESCRIPTION"), $arXMLElement))
			{
				if(strlen($arXMLElement[GetMessage("IBLOCK_XML2_DESCRIPTION")]) > 0)
					$arElement["DETAIL_TEXT"] = $arXMLElement[GetMessage("IBLOCK_XML2_DESCRIPTION")];
				else
					$arElement["DETAIL_TEXT"] = "";

				if(preg_match('/<[a-zA-Z0-9]+.*?>/', $arElement["DETAIL_TEXT"]))
					$arElement["DETAIL_TEXT_TYPE"] = "html";
				else
					$arElement["DETAIL_TEXT_TYPE"] = "text";
			}
			if(array_key_exists(GetMessage("IBLOCK_XML2_FULL_TITLE"), $arXMLElement))
			{
				if(strlen($arXMLElement[GetMessage("IBLOCK_XML2_FULL_TITLE")]) > 0)
					$arElement["PREVIEW_TEXT"] = $arXMLElement[GetMessage("IBLOCK_XML2_FULL_TITLE")];
				else
					$arElement["PREVIEW_TEXT"] = "";

				if(preg_match('/<[a-zA-Z0-9]+.*?>/', $arElement["PREVIEW_TEXT"]))
					$arElement["PREVIEW_TEXT_TYPE"] = "html";
				else
					$arElement["PREVIEW_TEXT_TYPE"] = "text";
			}
			if(array_key_exists(GetMessage("IBLOCK_XML2_BAR_CODE"), $arXMLElement))
				$arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_BAR_CODE"]] = $arXMLElement[GetMessage("IBLOCK_XML2_BAR_CODE")];
			if(array_key_exists(GetMessage("IBLOCK_XML2_BAR_CODE2"), $arXMLElement))
				$arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_BAR_CODE"]] = $arXMLElement[GetMessage("IBLOCK_XML2_BAR_CODE2")];
			if(array_key_exists(GetMessage("IBLOCK_XML2_ARTICLE"), $arXMLElement))
				$arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_ARTICLE"]] = $arXMLElement[GetMessage("IBLOCK_XML2_ARTICLE")];

			//BEDROSOVA 10-06-2014
			if(array_key_exists(GetMessage("IBLOCK_XML2_MANUFACTURER"), $arXMLElement))
			{
				$arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_MANUFACTURER"]] = array(
					"n0" => array(
						"VALUE" => $this->CheckManufacturer($arXMLElement[GetMessage("IBLOCK_XML2_MANUFACTURER")]),
						"DESCRIPTION" => false,
					),
				);
			}

			if(array_key_exists(GetMessage("IBLOCK_XML2_PICTURE"), $arXMLElement))
			{
				$rsFiles = $this->_xml_file->GetList(
					array("ID" => "asc"),
					array("PARENT_ID" => $arParent["ID"], "NAME" => GetMessage("IBLOCK_XML2_PICTURE"))
				);
				$arFile = $rsFiles->Fetch();
				if($arFile)
				{
					$description = false;
					if(strlen($arFile["ATTRIBUTES"]))
					{
						$arAttributes = unserialize($arFile["ATTRIBUTES"]);
						if(is_array($arAttributes) && array_key_exists(GetMessage("IBLOCK_XML2_DESCRIPTION"), $arAttributes))
							$description = $arAttributes[GetMessage("IBLOCK_XML2_DESCRIPTION")];
					}

					if(strlen($arFile["VALUE"]) > 0)
					{
						$file = $this->MakeFileArray($arFile["VALUE"]);
						$arElement["DETAIL_PICTURE"] = $this->ResizePicture($arFile["VALUE"], $this->detail);

						if($description !== false && is_array($arElement["DETAIL_PICTURE"]))
							$arElement["DETAIL_PICTURE"]["description"] = $description;

						if(is_array($this->preview))
						{
							$arElement["PREVIEW_PICTURE"] = $this->ResizePicture($arFile["VALUE"], $this->preview);
							if($description !== false && is_array($arElement["PREVIEW_PICTURE"]))
								$arElement["PREVIEW_PICTURE"]["description"] = $description;
						}
					}
					else
					{
						$arElement["DETAIL_PICTURE"] = $this->MakeFileArray($this->_xml_file->GetAllChildrenArray($arFile["ID"]));

						if($description !== false && is_array($arElement["DETAIL_PICTURE"]))
							$arElement["DETAIL_PICTURE"]["description"] = $description;
					}

					$prop_id = $this->PROPERTY_MAP["CML2_PICTURES"];
					if($prop_id > 0)
					{
						$i = 1;
						while($arFile = $rsFiles->Fetch())
						{
							$description = false;
							if(strlen($arFile["ATTRIBUTES"]))
							{
								$arAttributes = unserialize($arFile["ATTRIBUTES"]);
								if(is_array($arAttributes) && array_key_exists(GetMessage("IBLOCK_XML2_DESCRIPTION"), $arAttributes))
									$description = $arAttributes[GetMessage("IBLOCK_XML2_DESCRIPTION")];
							}

							if(strlen($arFile["VALUE"]) > 0)
								$arFile = $this->ResizePicture($arFile["VALUE"], $this->detail);
							else
								$arFile = $this->MakeFileArray($this->_xml_file->GetAllChildrenArray($arFile["ID"]));

							if($description !== false && is_array($arFile))
								$arFile = array(
									"VALUE" => $arFile,
									"DESCRIPTION" => $description,
								);
							$arElement["PROPERTY_VALUES"][$prop_id]["n".$i] = $arFile;
							$i++;
						}

						if(is_array($arElement["PROPERTY_VALUES"][$prop_id]))
						{
							foreach($arElement["PROPERTY_VALUES"][$prop_id] as $PROPERTY_VALUE_ID => $PROPERTY_VALUE)
							{
								if(!$PROPERTY_VALUE_ID)
									unset($arElement["PROPERTY_VALUES"][$prop_id][$PROPERTY_VALUE_ID]);
								elseif(substr($PROPERTY_VALUE_ID, 0, 1)!=="n")
									$arElement["PROPERTY_VALUES"][$prop_id][$PROPERTY_VALUE_ID] = array(
										"tmp_name" => "",
										"del" => "Y",
									);
							}
							unset($arElement["PROPERTY_VALUES"][$prop_id]["bOld"]);
						}
					}
				}
			}
//************************** begin item 1 ******************************** 02.07.2013

			if($arXMLElement["delpic"] === "Y")
			{
				$arFilter = Array(
					"XML_ID" => $arXMLElement[GetMessage("IBLOCK_XML2_ID")], 
					);
				$res = CIBlockElement::GetList(Array(), $arFilter, false, false, Array());
				if($arTmp = $res->GetNext())
				{
					if($arTmp["PREVIEW_PICTURE"])
					{
						CFile::Delete($arTmp["PREVIEW_PICTURE"]);
						$arElement["PREVIEW_PICTURE"] = array("del" => "Y");
					}
					if($arTmp["DETAIL_PICTURE"])
					{
						CFile::Delete($arTmp["DETAIL_PICTURE"]);
						$arElement["DETAIL_PICTURE"] = array("del" => "Y");
					}
				}
				
				$prop_id = $this->PROPERTY_MAP["CML2_PICTURES"];
				if($prop_id > 0)
				{
					if(is_array($arElement["PROPERTY_VALUES"][$prop_id]))
					{
						foreach($arElement["PROPERTY_VALUES"][$prop_id] as $PROPERTY_VALUE_ID => $PROPERTY_VALUE)
						{
							if(!$PROPERTY_VALUE_ID)
								unset($arElement["PROPERTY_VALUES"][$prop_id][$PROPERTY_VALUE_ID]);
							elseif(substr($PROPERTY_VALUE_ID, 0, 1)!=="n")
								$arElement["PROPERTY_VALUES"][$prop_id][$PROPERTY_VALUE_ID] = array(
									"tmp_name" => "",
									"del" => "Y",
								);
						}
						unset($arElement["PROPERTY_VALUES"][$prop_id]["bOld"]);
					}
				}
			}

//**************************** end item 1 ********************************

			if(
				array_key_exists(GetMessage("IBLOCK_XML2_FILE"), $arXMLElement)
				&& strlen($this->PROPERTY_MAP["CML2_FILES"]) > 0
			)
			{
				$prop_id = $this->PROPERTY_MAP["CML2_FILES"];
				$rsFiles = $this->_xml_file->GetList(
					array("ID" => "asc"),
					array("PARENT_ID" => $arParent["ID"], "NAME" => GetMessage("IBLOCK_XML2_FILE"))
				);
				$i = 1;
				while($arFile = $rsFiles->Fetch())
				{

					if(strlen($arFile["VALUE"]) > 0)
						$file = $this->MakeFileArray($arFile["VALUE"]);
					else
						$file = $this->MakeFileArray($this->_xml_file->GetAllChildrenArray($arFile["ID"]));

					$arElement["PROPERTY_VALUES"][$prop_id]["n".$i] = array(
						"VALUE" => $file,
						"DESCRIPTION" => $file["description"],
					);
					if(strlen($arFile["ATTRIBUTES"]))
					{
						$desc = unserialize($arFile["ATTRIBUTES"]);
						if(is_array($desc) && array_key_exists(GetMessage("IBLOCK_XML2_DESCRIPTION"), $desc))
							$arElement["PROPERTY_VALUES"][$prop_id]["n".$i]["DESCRIPTION"] = $desc[GetMessage("IBLOCK_XML2_DESCRIPTION")];
					}
					$i++;
				}

				if(is_array($arElement["PROPERTY_VALUES"][$prop_id]))
				{
					foreach($arElement["PROPERTY_VALUES"][$prop_id] as $PROPERTY_VALUE_ID => $PROPERTY_VALUE)
					{
						if(!$PROPERTY_VALUE_ID)
							unset($arElement["PROPERTY_VALUES"][$prop_id][$PROPERTY_VALUE_ID]);
						elseif(substr($PROPERTY_VALUE_ID, 0, 1)!=="n")
							$arElement["PROPERTY_VALUES"][$prop_id][$PROPERTY_VALUE_ID] = array(
								"tmp_name" => "",
								"del" => "Y",
							);
					}
					unset($arElement["PROPERTY_VALUES"][$prop_id]["bOld"]);
				}
			}

			if(isset($arXMLElement[GetMessage("IBLOCK_XML2_GROUPS")]))
			{
				$arElement["IBLOCK_SECTION"] = array();
				foreach($arXMLElement[GetMessage("IBLOCK_XML2_GROUPS")] as $key=>$value)
				{
					if(array_key_exists($value, $this->SECTION_MAP))
						$arElement["IBLOCK_SECTION"][] = $this->SECTION_MAP[$value];
				}
			}
			if(isset($arXMLElement[GetMessage("IBLOCK_XML2_PRICES")]))
			{//Collect price information for future use
				$arElement["PRICES"] = array();
				foreach($arXMLElement[GetMessage("IBLOCK_XML2_PRICES")] as $key=>$price)
				{
					if(isset($price[GetMessage("IBLOCK_XML2_PRICE_TYPE_ID")]) && array_key_exists($price[GetMessage("IBLOCK_XML2_PRICE_TYPE_ID")], $this->PRICES_MAP))
					{
						$price["PRICE"] = $this->PRICES_MAP[$price[GetMessage("IBLOCK_XML2_PRICE_TYPE_ID")]];
						$arElement["PRICES"][] = $price;
					}
				}

				$arElement["DISCOUNTS"] = array();
				if(isset($arXMLElement[GetMessage("IBLOCK_XML2_DISCOUNTS")]))
				{
					foreach($arXMLElement[GetMessage("IBLOCK_XML2_DISCOUNTS")] as $key=>$discount)
					{
						if(
							isset($discount[GetMessage("IBLOCK_XML2_DISCOUNT_CONDITION")])
							&& $discount[GetMessage("IBLOCK_XML2_DISCOUNT_CONDITION")]===GetMessage("IBLOCK_XML2_DISCOUNT_COND_VOLUME")
						)
						{
							$discount_value = $this->ToInt($discount[GetMessage("IBLOCK_XML2_DISCOUNT_COND_VALUE")]);
							$discount_percent = $this->ToFloat($discount[GetMessage("IBLOCK_XML2_DISCOUNT_COND_PERCENT")]);
							if($discount_value > 0 && $discount_percent > 0)
								$arElement["DISCOUNTS"][$discount_value] = $discount_percent;
						}
					}
				}
			}

			$arElement["CAN_BUY_ZERO"]="D";

			if(isset($arXMLElement[GetMessage("IBLOCK_XML2_AMOUNT")]))
				$arElement["QUANTITY"] = $this->ToFloat($arXMLElement[GetMessage("IBLOCK_XML2_AMOUNT")]);
			else
				$arElement["QUANTITY"] = 0;

			if(isset($arXMLElement[GetMessage("IBLOCK_XML2_ITEM_ATTRIBUTES")]))
			{
				$arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_ATTRIBUTES"]] = array();
				$i = 0;
				foreach($arXMLElement[GetMessage("IBLOCK_XML2_ITEM_ATTRIBUTES")] as $key => $value)
				{
					$arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_ATTRIBUTES"]]["n".$i] = array(
						"VALUE" => $value[GetMessage("IBLOCK_XML2_VALUE")],
						"DESCRIPTION" => $value[GetMessage("IBLOCK_XML2_NAME")],
					);
					$i++;
				}
			}
			if(isset($arXMLElement[GetMessage("IBLOCK_XML2_TRAITS_VALUES")]))
			{
				$arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_TRAITS"]] = array();
				$i = 0;
				foreach($arXMLElement[GetMessage("IBLOCK_XML2_TRAITS_VALUES")] as $key => $value)
				{
					if(
						!array_key_exists("PREVIEW_TEXT", $arElement)
						&& $value[GetMessage("IBLOCK_XML2_NAME")] == GetMessage("IBLOCK_XML2_FULL_TITLE2")
					)
					{
						$arElement["PREVIEW_TEXT"] = $value[GetMessage("IBLOCK_XML2_VALUE")];
						if(strpos($arElement["PREVIEW_TEXT"], "<")!==false)
							$arElement["PREVIEW_TEXT_TYPE"] = "html";
						else
							$arElement["PREVIEW_TEXT_TYPE"] = "text";
					}
					elseif(
						$value[GetMessage("IBLOCK_XML2_NAME")] == GetMessage("IBLOCK_XML2_HTML_DESCRIPTION")
					)
					{
						if(strlen($value[GetMessage("IBLOCK_XML2_VALUE")]) > 0)
						{
							$arElement["DETAIL_TEXT"] = $value[GetMessage("IBLOCK_XML2_VALUE")];
							$arElement["DETAIL_TEXT_TYPE"] = "html";
						}
					}
					else
					{
						if($value[GetMessage("IBLOCK_XML2_NAME")] == GetMessage("IBLOCK_XML2_WEIGHT"))
						{
							$arElement["BASE_WEIGHT"] = $this->ToFloat($value[GetMessage("IBLOCK_XML2_VALUE")])*1000;
						}

						$arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_TRAITS"]]["n".$i] = array(
							"VALUE" => $value[GetMessage("IBLOCK_XML2_VALUE")],
							"DESCRIPTION" => $value[GetMessage("IBLOCK_XML2_NAME")],
						);
						$i++;
					}
				}
			}
			if(isset($arXMLElement[GetMessage("IBLOCK_XML2_TAXES_VALUES")]))
			{
				$arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_TAXES"]] = array();
				$i = 0;
				foreach($arXMLElement[GetMessage("IBLOCK_XML2_TAXES_VALUES")] as $key => $value)
				{
					$arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_TAXES"]]["n".$i] = array(
						"VALUE" => $value[GetMessage("IBLOCK_XML2_TAX_VALUE")],
						"DESCRIPTION" => $value[GetMessage("IBLOCK_XML2_NAME")],
					);
					$i++;
				}
			}

			if(isset($arXMLElement[GetMessage("IBLOCK_XML2_BASE_UNIT")]))
			{
				$arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_BASE_UNIT"]] = $arXMLElement[GetMessage("IBLOCK_XML2_BASE_UNIT")];
			}

			if(isset($arXMLElement[GetMessage("IBLOCK_XML2_PROPERTIES_VALUES")]))
			{
				foreach($arXMLElement[GetMessage("IBLOCK_XML2_PROPERTIES_VALUES")] as $key=>$value)
				{
					if(!array_key_exists(GetMessage("IBLOCK_XML2_ID"), $value))
						continue;

					$prop_id = $value[GetMessage("IBLOCK_XML2_ID")];
					unset($value[GetMessage("IBLOCK_XML2_ID")]);

					//Handle properties which is actually element fields
					if(!array_key_exists($prop_id, $this->PROPERTY_MAP))
					{
						if($prop_id == "CML2_CODE")
							$arElement["CODE"] = isset($value[GetMessage("IBLOCK_XML2_VALUE")])? $value[GetMessage("IBLOCK_XML2_VALUE")]: "";
						elseif($prop_id == "CML2_ACTIVE")
						{
							$value = array_pop($value);
							$arElement["ACTIVE"] = ($value=="true") || intval($value)? "Y": "N";
						}
						elseif($prop_id == "CML2_SORT")
							$arElement["SORT"] = array_pop($value);
						elseif($prop_id == "CML2_ACTIVE_FROM")
							$arElement["ACTIVE_FROM"] = CDatabase::FormatDate(array_pop($value), "YYYY-MM-DD HH:MI:SS", CLang::GetDateFormat("FULL"));
						elseif($prop_id == "CML2_ACTIVE_TO")
							$arElement["ACTIVE_TO"] = CDatabase::FormatDate(array_pop($value), "YYYY-MM-DD HH:MI:SS", CLang::GetDateFormat("FULL"));
						elseif($prop_id == "CML2_PREVIEW_TEXT")
						{
							if(array_key_exists(GetMessage("IBLOCK_XML2_VALUE"), $value))
							{
								if(isset($value[GetMessage("IBLOCK_XML2_VALUE")]))
									$arElement["PREVIEW_TEXT"] = $value[GetMessage("IBLOCK_XML2_VALUE")];
								else
									$arElement["PREVIEW_TEXT"] = "";

								if(isset($value[GetMessage("IBLOCK_XML2_TYPE")]))
									$arElement["PREVIEW_TEXT_TYPE"] = $value[GetMessage("IBLOCK_XML2_TYPE")];
								else
									$arElement["PREVIEW_TEXT_TYPE"] = "html";
							}
						}
						elseif($prop_id == "CML2_DETAIL_TEXT")
						{
							if(array_key_exists(GetMessage("IBLOCK_XML2_VALUE"), $value))
							{
								if(isset($value[GetMessage("IBLOCK_XML2_VALUE")]))
									$arElement["DETAIL_TEXT"] = $value[GetMessage("IBLOCK_XML2_VALUE")];
								else
									$arElement["DETAIL_TEXT"] = "";

								if(isset($value[GetMessage("IBLOCK_XML2_TYPE")]))
									$arElement["DETAIL_TEXT_TYPE"] = $value[GetMessage("IBLOCK_XML2_TYPE")];
								else
									$arElement["DETAIL_TEXT_TYPE"] = "html";
							}
						}
						elseif($prop_id == "CML2_PREVIEW_PICTURE")
						{
							if(!is_array($this->preview) || !$arElement["PREVIEW_PICTURE"])
							{
								$arElement["PREVIEW_PICTURE"] = $this->MakeFileArray($value[GetMessage("IBLOCK_XML2_VALUE")]);
								$arElement["PREVIEW_PICTURE"]["COPY_FILE"] = "Y";
							}
						}

						continue;
					}

					$prop_id = $this->PROPERTY_MAP[$prop_id];
					$prop_type = $this->arProperties[$prop_id]["PROPERTY_TYPE"];

					if(!array_key_exists($prop_id, $arElement["PROPERTY_VALUES"]))
						$arElement["PROPERTY_VALUES"][$prop_id] = array();

					//check for bitrix extended format
					if(array_key_exists(GetMessage("IBLOCK_XML2_PROPERTY_VALUE"), $value))
					{
						$i = 1;
						$strPV = GetMessage("IBLOCK_XML2_PROPERTY_VALUE");
						$lPV = strlen($strPV);
						foreach($value as $k=>$prop_value)
						{
							if(substr($k, 0, $lPV) === $strPV)
							{
								if(array_key_exists(GetMessage("IBLOCK_XML2_SERIALIZED"), $prop_value))
									$prop_value[GetMessage("IBLOCK_XML2_VALUE")] = $this->Unserialize($prop_value[GetMessage("IBLOCK_XML2_VALUE")]);
								if($prop_type=="F")
								{
									$prop_value[GetMessage("IBLOCK_XML2_VALUE")] = $this->MakeFileArray($prop_value[GetMessage("IBLOCK_XML2_VALUE")]);
								}
								elseif($prop_type=="G")
									$prop_value[GetMessage("IBLOCK_XML2_VALUE")] = $this->GetSectionByXML_ID($this->arProperties[$prop_id]["LINK_IBLOCK_ID"], $prop_value[GetMessage("IBLOCK_XML2_VALUE")]);
								elseif($prop_type=="E")
									$prop_value[GetMessage("IBLOCK_XML2_VALUE")] = $this->GetElementByXML_ID($this->arProperties[$prop_id]["LINK_IBLOCK_ID"], $prop_value[GetMessage("IBLOCK_XML2_VALUE")]);
								elseif($prop_type=="L")
									$prop_value[GetMessage("IBLOCK_XML2_VALUE")] = $this->GetEnumByXML_ID($this->arProperties[$prop_id]["ID"], $prop_value[GetMessage("IBLOCK_XML2_VALUE")]);

								if(array_key_exists("bOld", $arElement["PROPERTY_VALUES"][$prop_id]))
								{
									if($prop_type=="F")
									{
										foreach($arElement["PROPERTY_VALUES"][$prop_id] as $PROPERTY_VALUE_ID => $PROPERTY_VALUE)
											$arElement["PROPERTY_VALUES"][$prop_id][$PROPERTY_VALUE_ID] = array(
												"tmp_name" => "",
												"del" => "Y",
											);
										unset($arElement["PROPERTY_VALUES"][$prop_id]["bOld"]);
									}
									else
										$arElement["PROPERTY_VALUES"][$prop_id] = array();
								}

								$arElement["PROPERTY_VALUES"][$prop_id]["n".$i] = array(
									"VALUE" => $prop_value[GetMessage("IBLOCK_XML2_VALUE")],
									"DESCRIPTION" => $prop_value[GetMessage("IBLOCK_XML2_DESCRIPTION")],
								);
							}
							$i++;
						}
					}
					else
					{
						if($prop_type == "L" && !array_key_exists(GetMessage("IBLOCK_XML2_VALUE_ID"), $value))
							$l_key = GetMessage("IBLOCK_XML2_VALUE");
						else
							$l_key = GetMessage("IBLOCK_XML2_VALUE_ID");

						foreach($value as $k=>$prop_value)
						{
							if(array_key_exists("bOld", $arElement["PROPERTY_VALUES"][$prop_id]))
							{
								if($prop_type=="F")
								{
									foreach($arElement["PROPERTY_VALUES"][$prop_id] as $PROPERTY_VALUE_ID => $PROPERTY_VALUE)
										$arElement["PROPERTY_VALUES"][$prop_id][$PROPERTY_VALUE_ID] = array(
											"tmp_name" => "",
											"del" => "Y",
										);
									unset($arElement["PROPERTY_VALUES"][$prop_id]["bOld"]);
								}
								else
								{
									$arElement["PROPERTY_VALUES"][$prop_id] = array();
								}
							}

							if($prop_type == "L" && $k == $l_key)
							{
								$prop_value = $this->GetEnumByXML_ID($this->arProperties[$prop_id]["ID"], $prop_value);
							}
							elseif($prop_type == "N" && isset($this->next_step["sdp"]))
							{
								$prop_value = $this->ToFloat($prop_value);
							}

							$arElement["PROPERTY_VALUES"][$prop_id][] = $prop_value;
						}
					}
				}
			}

			//If there is no BaseUnit specified check prices for it
			if(
				(
					!array_key_exists($this->PROPERTY_MAP["CML2_BASE_UNIT"], $arElement["PROPERTY_VALUES"])
					|| (
						is_array($arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_BASE_UNIT"]])
						&& array_key_exists("bOld", $arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_BASE_UNIT"]])
					)
				)
				&& isset($arXMLElement[GetMessage("IBLOCK_XML2_PRICES")])
			)
			{
				foreach($arXMLElement[GetMessage("IBLOCK_XML2_PRICES")] as $key => $price)
				{
					if(
						isset($price[GetMessage("IBLOCK_XML2_PRICE_TYPE_ID")])
						&& array_key_exists($price[GetMessage("IBLOCK_XML2_PRICE_TYPE_ID")], $this->PRICES_MAP)
						&& array_key_exists(GetMessage("IBLOCK_XML2_MEASURE"), $price)
					)
					{
						$arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_BASE_UNIT"]] = $price[GetMessage("IBLOCK_XML2_MEASURE")];
						break;
					}
				}
			}

			if($arDBElement)
			{
				foreach($arElement["PROPERTY_VALUES"] as $prop_id=>$prop)
				{
					if(is_array($arElement["PROPERTY_VALUES"][$prop_id]) && array_key_exists("bOld", $arElement["PROPERTY_VALUES"][$prop_id]))
					{
						if($this->arProperties[$prop_id]["PROPERTY_TYPE"]=="F")
							unset($arElement["PROPERTY_VALUES"][$prop_id]);
						else
							unset($arElement["PROPERTY_VALUES"][$prop_id]["bOld"]);
					}
				}

				if(intval($arElement["MODIFIED_BY"]) <= 0 && $USER_ID > 0)
					$arElement["MODIFIED_BY"] = $USER_ID;

				if(!array_key_exists("CODE", $arElement) && is_array($this->translit_on_update))
				{
					$arElement["CODE"] = CUtil::translit($arElement["NAME"], LANGUAGE_ID, $this->translit_on_update);
					//Check if name was not changed in a way to update CODE
					if(substr($arDBElement["CODE"], 0, strlen($arElement["CODE"])) === $arElement["CODE"])
						unset($arElement["CODE"]);
					else
						$arElement["CODE"] = $this->CheckElementCode($this->next_step["IBLOCK_ID"], $arElement["CODE"]);
				}

				$obElement->Update($arDBElement["ID"], $arElement, $bWF, true, $this->iblock_resize);
				//In case element was not active in database we have to activate its offers
				if($arDBElement["ACTIVE"] != "Y")
				{
					$this->ChangeOffersStatus($arDBElement["ID"], "Y", $bWF);
				}
				$arElement["ID"] = $arDBElement["ID"];
				if($arElement["ID"])
				{
					$counter["UPD"]++;
				}
				else
				{
					$this->LAST_ERROR = $obElement->LAST_ERROR;
					$counter["ERR"]++;
				}
			}
			else
			{
				if(!array_key_exists("CODE", $arElement) && is_array($this->translit_on_add))
					$arElement["CODE"] = $this->CheckElementCode($this->next_step["IBLOCK_ID"], CUtil::translit($arElement["NAME"], LANGUAGE_ID, $this->translit_on_add));

				$arElement["IBLOCK_ID"] = $this->next_step["IBLOCK_ID"];
				$arElement["ID"] = $obElement->Add($arElement, $bWF, true, $this->iblock_resize);
				if($arElement["ID"])
				{
					$counter["ADD"]++;
				}
				else
				{
					$this->LAST_ERROR = $obElement->LAST_ERROR;
					$counter["ERR"]++;
				}
			}

			if($arElement["ID"] && $this->bCatalog && $this->next_step["bOffer"])
			{
				$CML_LINK = $this->PROPERTY_MAP["CML2_LINK"];

				$arProduct = array(
					"ID" => $arElement["ID"],
				);
				if(isset($arElement["QUANTITY"]))
					$arProduct["QUANTITY"] = $arElement["QUANTITY"];

				if(isset($arElement["BASE_WEIGHT"]))
				{
					$arProduct["WEIGHT"] = $arElement["BASE_WEIGHT"];
				}
				else
				{
					$rsWeight = CIBlockElement::GetProperty($this->arProperties[$CML_LINK]["LINK_IBLOCK_ID"], $arElement["PROPERTY_VALUES"][$CML_LINK], array(), array("CODE" => "CML2_TRAITS"));
					while($arWheight = $rsWeight->Fetch())
					{
						if($arWheight["DESCRIPTION"] == GetMessage("IBLOCK_XML2_WEIGHT"))
							$arProduct["WEIGHT"] = $this->ToFloat($arWheight["VALUE"])*1000;
					}
				}

				if(isset($arElement["PRICES"]))
				{
					//Here start VAT handling

					//Check if all the taxes exists in BSM catalog
					$arTaxMap = array();
					$rsTaxProperty = CIBlockElement::GetProperty($this->arProperties[$CML_LINK]["LINK_IBLOCK_ID"], $arElement["PROPERTY_VALUES"][$CML_LINK], "sort", "asc", array("CODE" => "CML2_TAXES"));
					while($arTaxProperty = $rsTaxProperty->Fetch())
					{
						if(
							strlen($arTaxProperty["VALUE"]) > 0
							&& strlen($arTaxProperty["DESCRIPTION"]) > 0
							&& !array_key_exists($arTaxProperty["DESCRIPTION"], $arTaxMap)
						)
						{
							$arTaxMap[$arTaxProperty["DESCRIPTION"]] = array(
								"RATE" => $this->ToFloat($arTaxProperty["VALUE"]),
								"ID" => $this->CheckTax($arTaxProperty["DESCRIPTION"], $this->ToFloat($arTaxProperty["VALUE"])),
							);
						}
					}

					//First find out if all the prices have TAX_IN_SUM true
					$TAX_IN_SUM = "Y";
					foreach($arElement["PRICES"] as $key=>$price)
					{
						if($price["PRICE"]["TAX_IN_SUM"] !== "true")
						{
							$TAX_IN_SUM = "N";
							break;
						}
					}
					//If there was found not insum tax we'll make shure
					//that all prices has the same flag
					if($TAX_IN_SUM === "N")
					{
						foreach($arElement["PRICES"] as $key=>$price)
						{
							if($price["PRICE"]["TAX_IN_SUM"] !== "false")
							{
								$TAX_IN_SUM = "Y";
								break;
							}
						}
						//Check if there is a mix of tax in sum
						//and correct it by recalculating all the prices
						if($TAX_IN_SUM === "Y")
						{
							foreach($arElement["PRICES"] as $key=>$price)
							{
								if($price["PRICE"]["TAX_IN_SUM"] !== "true")
								{
									$TAX_NAME = $price["PRICE"]["TAX_NAME"];
									if(array_key_exists($TAX_NAME, $arTaxMap))
									{
										$PRICE_WO_TAX = $this->ToFloat($price[GetMessage("IBLOCK_XML2_PRICE_FOR_ONE")]);
										$PRICE = $PRICE_WO_TAX + ($PRICE_WO_TAX / 100.0 * $arTaxMap[$TAX_NAME]["RATE"]);
										$arElement["PRICES"][$key][GetMessage("IBLOCK_XML2_PRICE_FOR_ONE")] = $PRICE;
									}
								}
							}
						}
					}
					foreach($arElement["PRICES"] as $key=>$price)
					{
						$TAX_NAME = $price["PRICE"]["TAX_NAME"];
						if(array_key_exists($TAX_NAME, $arTaxMap))
						{
							$arProduct["VAT_ID"] = $arTaxMap[$TAX_NAME]["ID"];
							break;
						}
					}
					$arProduct["VAT_INCLUDED"] = $TAX_IN_SUM;
				}

				CCatalogProduct::Add($arProduct);

				if(isset($arElement["PRICES"]))
					$this->SetProductPrice($arElement["ID"], $arElement["PRICES"], $arElement["DISCOUNTS"]);

			}
		}

		if(isset($arXMLElement[GetMessage("IBLOCK_XML2_STORE_AMOUNT_LIST")]))
		{
			$arElement["STORE_AMOUNT"]=array();
			foreach($arXMLElement[GetMessage("IBLOCK_XML2_STORE_AMOUNT_LIST")] as $key=>$storeAmount)
			{
				if(isset($storeAmount[GetMessage("IBLOCK_XML2_STORE_ID")]))
				{
					$storeXMLID = $storeAmount[GetMessage("IBLOCK_XML2_STORE_ID")];
					$amount = $storeAmount[GetMessage("IBLOCK_XML2_AMOUNT")];
					$arElement["STORE_AMOUNT"][]=array($storeXMLID => $amount);
				}
			}
		}
		if(isset($arElement["STORE_AMOUNT"]))
			$this->ImportStoresAmount($arElement["STORE_AMOUNT"], $arElement["ID"], $counter);

		return $arElement["ID"];
	}
	
}
	
	
class CIBlockCustomPImport extends CIBlockCMLImport
{
	
	
	
	function ImportProperties($XML_PROPERTIES_PARENT, $IBLOCK_ID)
	{
		$obProperty = new CIBlockProperty;
		$sort = 100;

		$arElementFields = array(
			"CML2_ACTIVE" => GetMessage("IBLOCK_XML2_BX_ACTIVE"),
			"CML2_CODE" => GetMessage("IBLOCK_XML2_SYMBOL_CODE"),
			"CML2_SORT" => GetMessage("IBLOCK_XML2_SORT"),
			"CML2_ACTIVE_FROM" => GetMessage("IBLOCK_XML2_START_TIME"),
			"CML2_ACTIVE_TO" => GetMessage("IBLOCK_XML2_END_TIME"),
			"CML2_PREVIEW_TEXT" => GetMessage("IBLOCK_XML2_ANONS"),
			"CML2_DETAIL_TEXT" => GetMessage("IBLOCK_XML2_DETAIL"),
			"CML2_PREVIEW_PICTURE" => GetMessage("IBLOCK_XML2_PREVIEW_PICTURE"),
		);

		$rs = $this->_xml_file->GetList(
			array("ID" => "asc"),
			array("PARENT_ID" => $XML_PROPERTIES_PARENT),
			array("ID")
		);
		while($ar = $rs->Fetch())
		{
			$XML_ENUM_PARENT = false;
			$arProperty = array(
			);
			$rsP = $this->_xml_file->GetList(
				array("ID" => "asc"),
				array("PARENT_ID" => $ar["ID"])
			);
			while($arP = $rsP->Fetch())
			{
			
				arraytofile($arP,$_SERVER['DOCUMENT_ROOT']."/upload/ImportLog.txt", "_SESSION");
			
			
				if(isset($arP["VALUE_CLOB"]))
					$arP["VALUE"] = $arP["VALUE_CLOB"];

				if($arP["NAME"]==GetMessage("IBLOCK_XML2_ID"))
				{
					$arProperty["XML_ID"] = $arP["VALUE"];
					if(array_key_exists($arProperty["XML_ID"], $arElementFields))
						break;
				}
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_NAME"))
					$arProperty["NAME"] = $arP["VALUE"];
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_MULTIPLE"))
					$arProperty["MULTIPLE"] = ($arP["VALUE"]=="true") || intval($arP["VALUE"])? "Y": "N";
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_BX_SORT"))
					$arProperty["SORT"] = $arP["VALUE"];
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_BX_CODE"))
					$arProperty["CODE"] = $arP["VALUE"];
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_BX_DEFAULT_VALUE"))
					$arProperty["DEFAULT_VALUE"] = $arP["VALUE"];
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_SERIALIZED"))
					$arProperty["SERIALIZED"] = ($arP["VALUE"]=="true") || intval($arP["VALUE"])? "Y": "N";
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_BX_PROPERTY_TYPE"))
					$arProperty["PROPERTY_TYPE"] = $arP["VALUE"];
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_BX_ROWS"))
					$arProperty["ROW_COUNT"] = $arP["VALUE"];
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_BX_COLUMNS"))
					$arProperty["COL_COUNT"] = $arP["VALUE"];
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_BX_LIST_TYPE"))
					$arProperty["LIST_TYPE"] = $arP["VALUE"];
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_BX_FILE_EXT"))
					$arProperty["FILE_TYPE"] = $arP["VALUE"];
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_BX_FIELDS_COUNT"))
					$arProperty["MULTIPLE_CNT"] = $arP["VALUE"];
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_BX_USER_TYPE"))
					$arProperty["USER_TYPE"] = $arP["VALUE"];
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_BX_WITH_DESCRIPTION"))
					$arProperty["WITH_DESCRIPTION"] = ($arP["VALUE"]=="true") || intval($arP["VALUE"])? "Y": "N";
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_BX_SEARCH"))
					$arProperty["SEARCHABLE"] = ($arP["VALUE"]=="true") || intval($arP["VALUE"])? "Y": "N";
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_BX_FILTER"))
					$arProperty["FILTRABLE"] = ($arP["VALUE"]=="true") || intval($arP["VALUE"])? "Y": "N";
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_BX_LINKED_IBLOCK"))
					$arProperty["LINK_IBLOCK_ID"] = $this->GetIBlockByXML_ID($arP["VALUE"]);
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_CHOICE_VALUES"))
					$XML_ENUM_PARENT = $arP["ID"];
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_BX_IS_REQUIRED"))
					$arProperty["IS_REQUIRED"] = ($arP["VALUE"]=="true") || intval($arP["VALUE"])? "Y": "N";
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_VALUES_TYPE"))
				{
					if($arP["VALUE"] == GetMessage("IBLOCK_XML2_TYPE_LIST"))
						$arProperty["PROPERTY_TYPE"] = "L";
					elseif($arP["VALUE"] == GetMessage("IBLOCK_XML2_TYPE_NUMBER"))
						$arProperty["PROPERTY_TYPE"] = "N";
				}
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_VALUES_TYPES"))
				{
					//This property metadata contains information about it's type
					$rsTypes = $this->_xml_file->GetList(
						array("ID" => "asc"),
						array("PARENT_ID" => $arP["ID"]),
						array("ID", "LEFT_MARGIN", "RIGHT_MARGIN", "NAME")
					);
					$arType = $rsTypes->Fetch();
					//We'll process only properties with NOT composing types
					//composed types will be supported only as simple string properties
					if($arType && !$rsTypes->Fetch())
					{
						$rsType = $this->_xml_file->GetList(
							array("ID" => "asc"),
							array("PARENT_ID" => $arType["ID"]),
							array("ID", "LEFT_MARGIN", "RIGHT_MARGIN", "NAME", "VALUE")
						);
						while($arType = $rsType->Fetch())
						{
							if($arType["NAME"] == GetMessage("IBLOCK_XML2_TYPE"))
							{
								if($arType["VALUE"] == GetMessage("IBLOCK_XML2_TYPE_LIST"))
									$arProperty["PROPERTY_TYPE"] = "L";
								elseif($arType["VALUE"] == GetMessage("IBLOCK_XML2_TYPE_NUMBER"))
									$arProperty["PROPERTY_TYPE"] = "N";
							}
							elseif($arType["NAME"] == GetMessage("IBLOCK_XML2_CHOICE_VALUES"))
							{
								$XML_ENUM_PARENT = $arType["ID"];
							}
						}
					}
				}
				elseif($arP["NAME"]==GetMessage("IBLOCK_XML2_BX_USER_TYPE_SETTINGS"))
				{
					$arProperty["USER_TYPE_SETTINGS"] = unserialize($arP["VALUE"]);
				}
			}

			if(array_key_exists($arProperty["XML_ID"], $arElementFields))
				continue;

			// Skip properties with no choice values
			// http://jabber.bx/view.php?id=30476
			$arEnumXmlNodes = array();
			if($XML_ENUM_PARENT)
			{
				$rsE = $this->_xml_file->GetList(
					array("ID" => "asc"),
					array("PARENT_ID" => $XML_ENUM_PARENT)
				);
				while($arE = $rsE->Fetch())
				{
					if(isset($arE["VALUE_CLOB"]))
						$arE["VALUE"] = $arE["VALUE_CLOB"];
					$arEnumXmlNodes[] = $arE;
				}

				if (empty($arEnumXmlNodes))
					continue;
			}

			if($arProperty["SERIALIZED"] == "Y")
				$arProperty["DEFAULT_VALUE"] = unserialize($arProperty["DEFAULT_VALUE"]);

			$rsProperty = $obProperty->GetList(array(), array("IBLOCK_ID"=>$IBLOCK_ID, "XML_ID"=>$arProperty["XML_ID"]));
			if($arDBProperty = $rsProperty->Fetch())
			{
				$bChanged = false;
				foreach($arProperty as $key=>$value)
				{
					if($arDBProperty[$key] !== $value)
					{
						$bChanged = true;
						break;
					}
				}
				if(!$bChanged)
					$arProperty["ID"] = $arDBProperty["ID"];
				elseif($obProperty->Update($arDBProperty["ID"], $arProperty))
					$arProperty["ID"] = $arDBProperty["ID"];
				else
					return $obProperty->LAST_ERROR;
			}
			else
			{
				$arProperty["IBLOCK_ID"] = $IBLOCK_ID;
				$arProperty["ACTIVE"] = "Y";
				if(!array_key_exists("PROPERTY_TYPE", $arProperty))
					$arProperty["PROPERTY_TYPE"] = "S";
				if(!array_key_exists("SORT", $arProperty))
					$arProperty["SORT"] = $sort;
				if(!array_key_exists("CODE", $arProperty))
				{
					$arProperty["CODE"] = CUtil::translit($arProperty["NAME"], LANGUAGE_ID, array(
						"max_len" => 50,
						"change_case" => 'U', // 'L' - toLower, 'U' - toUpper, false - do not change
						"replace_space" => '_',
						"replace_other" => '_',
						"delete_repeat_replace" => true,
					));
					if(preg_match('/^[0-9]/', $arProperty["CODE"]))
						$arProperty["CODE"] = '_'.$arProperty["CODE"];
				}
				$arProperty["ID"] = $obProperty->Add($arProperty);
				if(!$arProperty["ID"])
					return $obProperty->LAST_ERROR;
			}

			if($XML_ENUM_PARENT)
			{
				$arEnumMap = array();
				$arProperty["VALUES"] = array();
				$rsEnum = CIBlockProperty::GetPropertyEnum($arProperty["ID"]);
				while($arEnum = $rsEnum->Fetch())
				{
					$arProperty["VALUES"][$arEnum["ID"]] = $arEnum;
					$arEnumMap[$arEnum["XML_ID"]] = &$arProperty["VALUES"][$arEnum["ID"]];
				}
				foreach($arEnumXmlNodes as $i => $arE)
				{
					if(
						$arE["NAME"] == GetMessage("IBLOCK_XML2_CHOICE")
						|| $arE["NAME"] == GetMessage("IBLOCK_XML2_CHOICE_VALUE")
					)
					{
						$arE = $this->_xml_file->GetAllChildrenArray($arE);
						if(isset($arE[GetMessage("IBLOCK_XML2_ID")]))
						{
							$xml_id = $arE[GetMessage("IBLOCK_XML2_ID")];
							if(!array_key_exists($xml_id, $arEnumMap))
							{
								$arProperty["VALUES"]["n".$i] = array();
								$arEnumMap[$xml_id] = &$arProperty["VALUES"]["n".$i];
								$i++;
							}
							$arEnumMap[$xml_id]["CML2_EXPORT_FLAG"] = true;
							$arEnumMap[$xml_id]["XML_ID"] = $xml_id;
							if(isset($arE[GetMessage("IBLOCK_XML2_VALUE")]))
								$arEnumMap[$xml_id]["VALUE"] = $arE[GetMessage("IBLOCK_XML2_VALUE")];
							if(isset($arE[GetMessage("IBLOCK_XML2_BY_DEFAULT")]))
								$arEnumMap[$xml_id]["DEF"] = ($arE[GetMessage("IBLOCK_XML2_BY_DEFAULT")]=="true") || intval($arE[GetMessage("IBLOCK_XML2_BY_DEFAULT")])? "Y": "N";
							if(isset($arE[GetMessage("IBLOCK_XML2_SORT")]))
								$arEnumMap[$xml_id]["SORT"] = intval($arE[GetMessage("IBLOCK_XML2_SORT")]);
						}
					}
					elseif(
						$arE["NAME"] == GetMessage("IBLOCK_XML2_TYPE_LIST")
					)
					{
						$arE = $this->_xml_file->GetAllChildrenArray($arE);
						if(isset($arE[GetMessage("IBLOCK_XML2_VALUE_ID")]))
						{
							$xml_id = $arE[GetMessage("IBLOCK_XML2_VALUE_ID")];
							if(!array_key_exists($xml_id, $arEnumMap))
							{
								$arProperty["VALUES"]["n".$i] = array();
								$arEnumMap[$xml_id] = &$arProperty["VALUES"]["n".$i];
								$i++;
							}
							$arEnumMap[$xml_id]["CML2_EXPORT_FLAG"] = true;
							$arEnumMap[$xml_id]["XML_ID"] = $xml_id;
							if(isset($arE[GetMessage("IBLOCK_XML2_VALUE")]))
								$arEnumMap[$xml_id]["VALUE"] = $arE[GetMessage("IBLOCK_XML2_VALUE")];
						}
					}
				}

				$bUpdateOnly = array_key_exists("bUpdateOnly", $this->next_step) && $this->next_step["bUpdateOnly"];
				$sort = 100;

				foreach($arProperty["VALUES"] as $id=>$arEnum)
				{
					if(!isset($arEnum["CML2_EXPORT_FLAG"]))
					{
						//Delete value only when full exchange happened
						if(!$bUpdateOnly)
							$arProperty["VALUES"][$id]["VALUE"] = "";
					}
					elseif(isset($arEnum["SORT"]))
					{
						if($arEnum["SORT"] > $sort)
							$sort = $arEnum["SORT"] + 100;
					}
					else
					{
						$arProperty["VALUES"][$id]["SORT"] = $sort;
						$sort += 100;
					}
				}
				$obProperty->UpdateEnum($arProperty["ID"], $arProperty["VALUES"], false);
			}
			$sort += 100;
		}
		return true;
	}


} 


/*************************************************************************
	Processing of received parameters
*************************************************************************/

$arParams["IBLOCK_TYPE"] = trim($arParams["IBLOCK_TYPE"]);
$arParams["INTERVAL"] = intval($arParams["INTERVAL"]);

if(!is_array($arParams["GROUP_PERMISSIONS"]))
	$arParams["GROUP_PERMISSIONS"] = array(1);

if(!is_array($arParams["SITE_LIST"]))
	$arParams["SITE_LIST"] = array();

if($arParams["ELEMENT_ACTION"]!="N" && $arParams["ELEMENT_ACTION"]!="A")
	$arParams["ELEMENT_ACTION"] = "D";
if($arParams["SECTION_ACTION"]!="N" && $arParams["SECTION_ACTION"]!="A")
	$arParams["SECTION_ACTION"] = "D";

$arParams["FILE_SIZE_LIMIT"] = intval($arParams["FILE_SIZE_LIMIT"]);
if($arParams["FILE_SIZE_LIMIT"] < 1)
	$arParams["FILE_SIZE_LIMIT"] = 200*1024; //200KB

$arParams["USE_CRC"] = $arParams["USE_CRC"]!="N";
$arParams["USE_ZIP"] = $arParams["USE_ZIP"]!="N";
$arParams["USE_OFFERS"] = $arParams["USE_OFFERS"]=="Y";
$arParams["USE_IBLOCK_TYPE_ID"] = $arParams["USE_IBLOCK_TYPE_ID"]=="Y";
$arParams["USE_IBLOCK_PICTURE_SETTINGS"] = $arParams["USE_IBLOCK_PICTURE_SETTINGS"]=="Y";
$arParams["SKIP_ROOT_SECTION"] = $arParams["SKIP_ROOT_SECTION"]=="Y";

if($arParams["USE_IBLOCK_PICTURE_SETTINGS"])
{
	$preview = true;
	$detail = true;
}
else
{
	$arParams["GENERATE_PREVIEW"] = $arParams["GENERATE_PREVIEW"]!="N";
	if($arParams["GENERATE_PREVIEW"])
	{
		$preview = array(
			intval($arParams["PREVIEW_WIDTH"]) > 1? intval($arParams["PREVIEW_WIDTH"]): 100,
			intval($arParams["PREVIEW_HEIGHT"]) > 1? intval($arParams["PREVIEW_HEIGHT"]): 100,
		);
	}
	else
	{
		$preview = false;
	}

	$arParams["DETAIL_RESIZE"] = $arParams["DETAIL_RESIZE"]!="N";
	if($arParams["DETAIL_RESIZE"])
	{
		$detail = array(
			intval($arParams["DETAIL_WIDTH"]) > 1? intval($arParams["DETAIL_WIDTH"]): 300,
			intval($arParams["DETAIL_HEIGHT"]) > 1? intval($arParams["DETAIL_HEIGHT"]): 300,
		);
	}
	else
	{
		$detail = false;
	}
}

$arParams["TRANSLIT_MAX_LEN"] = intval($arParams["TRANSLIT_MAX_LEN"]);
if($arParams["TRANSLIT_MAX_LEN"] <= 0)
	$arParams["TRANSLIT_MAX_LEN"] = 100;
if(!array_key_exists("TRANSLIT_CHANGE_CASE", $arParams))
	$arParams["TRANSLIT_CHANGE_CASE"] = 'L'; // 'L' - toLower, 'U' - toUpper, false - do not change
if(!array_key_exists("TRANSLIT_REPLACE_SPACE", $arParams))
	$arParams["TRANSLIT_REPLACE_SPACE"] = '_';
if(!array_key_exists("TRANSLIT_REPLACE_OTHER", $arParams))
	$arParams["TRANSLIT_REPLACE_OTHER"] = '_';
$arParams["TRANSLIT_DELETE_REPEAT_REPLACE"] = $arParams["TRANSLIT_DELETE_REPEAT_REPLACE"] !== "N";

$arTranslitParams = array(
	"max_len" => $arParams["TRANSLIT_MAX_LEN"],
	"change_case" => $arParams["TRANSLIT_CHANGE_CASE"],
	"replace_space" => $arParams["TRANSLIT_REPLACE_SPACE"],
	"replace_other" => $arParams["TRANSLIT_REPLACE_OTHER"],
	"delete_repeat_replace" => $arParams["TRANSLIT_DELETE_REPEAT_REPLACE"],
);

$arParams["TRANSLIT_ON_ADD"] = $arParams["TRANSLIT_ON_ADD"] === "Y";
$arParams["TRANSLIT_ON_UPDATE"] = $arParams["TRANSLIT_ON_UPDATE"] === "Y";

if($arParams["INTERVAL"] <= 0)
	@set_time_limit(0);

$start_time = time();

$bUSER_HAVE_ACCESS = false;
if(isset($GLOBALS["USER"]) && is_object($GLOBALS["USER"]))
{
	$bUSER_HAVE_ACCESS = $GLOBALS["USER"]->IsAdmin();
	if(!$bUSER_HAVE_ACCESS)
	{
		$arUserGroupArray = $GLOBALS["USER"]->GetUserGroupArray();
		foreach($arParams["GROUP_PERMISSIONS"] as $PERM)
		{
			if(in_array($PERM, $arUserGroupArray))
			{
				$bUSER_HAVE_ACCESS = true;
				break;
			}
		}
	}
}

$bDesignMode = $GLOBALS["APPLICATION"]->GetShowIncludeAreas()
		&& !isset($_GET["mode"])
		&& is_object($GLOBALS["USER"])
		&& $GLOBALS["USER"]->IsAdmin();

if(!$bDesignMode)
{
	if(!isset($_GET["mode"]))
		return;
	$APPLICATION->RestartBuffer();
	header("Pragma: no-cache");
}

$DIR_NAME = "";

ob_start();

if($_GET["mode"] == "checkauth" && $USER->IsAuthorized())
{
	if(
		(COption::GetOptionString("main", "use_session_id_ttl", "N") == "Y")
		&& (COption::GetOptionInt("main", "session_id_ttl", 0) > 0)
		&& !defined("BX_SESSION_ID_CHANGE")
	)
	{
		echo "failure\n",GetMessage("CC_BSC1_ERROR_SESSION_ID_CHANGE");
	}
	else
	{
		echo "success\n";
		echo session_name()."\n";
		echo session_id() ."\n";
	}
}
elseif(!$USER->IsAuthorized())
{
	echo "failure\n",GetMessage("CC_BSC1_ERROR_AUTHORIZE");
}
elseif(!$bUSER_HAVE_ACCESS)
{
	echo "failure\n",GetMessage("CC_BSC1_PERMISSION_DENIED");
}
elseif(!CModule::IncludeModule('iblock'))
{
	echo "failure\n",GetMessage("CC_BSC1_ERROR_MODULE");
}
else
{
	//We have to strongly check all about file names at server side
	$DIR_NAME = "/".COption::GetOptionString("main", "upload_dir", "upload")."/1c_catalog";
	$ABS_FILE_NAME = false;
	$WORK_DIR_NAME = false;
	if(isset($_GET["filename"]) && (strlen($_GET["filename"])>0))
	{
		//This check for 1c server on linux
		$filename = preg_replace("#^(/tmp/|upload/1c/webdata)#", "", $_GET["filename"]);
		$filename = trim(str_replace("\\", "/", trim($filename)), "/");

		$io = CBXVirtualIo::GetInstance();
		$bBadFile = HasScriptExtension($filename)
			|| IsFileUnsafe($filename)
			|| !$io->ValidatePathString("/".$filename)
		;

		if(!$bBadFile)
		{
			$FILE_NAME = rel2abs($_SERVER["DOCUMENT_ROOT"].$DIR_NAME, "/".$filename);
			if((strlen($FILE_NAME) > 1) && ($FILE_NAME === "/".$filename))
			{
				$ABS_FILE_NAME = $_SERVER["DOCUMENT_ROOT"].$DIR_NAME.$FILE_NAME;
				$WORK_DIR_NAME = substr($ABS_FILE_NAME, 0, strrpos($ABS_FILE_NAME, "/")+1);
			}
		}
	}

	if(($_GET["mode"] == "file") && $ABS_FILE_NAME)
	{
		//Read http data
		if(function_exists("file_get_contents"))
			$DATA = file_get_contents("php://input");
		elseif(isset($GLOBALS["HTTP_RAW_POST_DATA"]))
			$DATA = &$GLOBALS["HTTP_RAW_POST_DATA"];
		else
			$DATA = false;

		$DATA_LEN = defined("BX_UTF")? mb_strlen($DATA, 'latin1'): strlen($DATA);
		//And save it the file
		if($DATA_LEN > 0 || $_GET["method"]='ftp')
		{
			CheckDirPath($ABS_FILE_NAME);
			if($fp = fopen($ABS_FILE_NAME, "ab"))
			{
				$result = fwrite($fp, $DATA);
				if($result === $DATA_LEN)
				{
					echo "success\n";
					if($_SESSION["BX_CML2_IMPORT"]["zip"])
						$_SESSION["BX_CML2_IMPORT"]["zip"] = $ABS_FILE_NAME;
				}
				else
				{
					echo "failure\n",GetMessage("CC_BSC1_ERROR_FILE_WRITE", array("#FILE_NAME#"=>$FILE_NAME));
				}
			}
			else
			{
				echo "failure\n",GetMessage("CC_BSC1_ERROR_FILE_OPEN", array("#FILE_NAME#"=>$FILE_NAME));
			}
		}
		else
		{
			echo "failure\n",GetMessage("CC_BSC1_ERROR_HTTP_READ");
		}
	}
	elseif(($_GET["mode"] == "import") && $_SESSION["BX_CML2_IMPORT"]["zip"])
	{
		if(!array_key_exists("last_zip_entry", $_SESSION["BX_CML2_IMPORT"]))
			$_SESSION["BX_CML2_IMPORT"]["last_zip_entry"] = "";

		$result = CIBlockXMLFile::UnZip($_SESSION["BX_CML2_IMPORT"]["zip"], $_SESSION["BX_CML2_IMPORT"]["last_zip_entry"]);
		if($result===false)
		{
			echo "failure\n",GetMessage("CC_BSC1_ZIP_ERROR");
		}
		elseif($result===true)
		{
			$_SESSION["BX_CML2_IMPORT"]["zip"] = false;
			echo "progress\n".GetMessage("CC_BSC1_ZIP_DONE");
		}
		else
		{
			$_SESSION["BX_CML2_IMPORT"]["last_zip_entry"] = $result;
			echo "progress\n".GetMessage("CC_BSC1_ZIP_PROGRESS");
		}
	}
	elseif(($_GET["mode"] == "import") && $ABS_FILE_NAME)
	{
		$NS = &$_SESSION["BX_CML2_IMPORT"]["NS"];
		$strError = "";
		$strMessage = "";

		if($NS["STEP"] < 1)
		{
			CIBlockXMLFile::DropTemporaryTables();
			$strMessage = GetMessage("CC_BSC1_TABLES_DROPPED");
			$NS["STEP"] = 1;
		}
		elseif($NS["STEP"] == 1)
		{
			if(CIBlockXMLFile::CreateTemporaryTables())
			{
				$strMessage = GetMessage("CC_BSC1_TABLES_CREATED");
				$NS["STEP"] = 2;
			}
			else
			{
				$strError = GetMessage("CC_BSC1_TABLE_CREATE_ERROR");
			}
		}
		elseif($NS["STEP"] == 2)
		{
			$fp = fopen($ABS_FILE_NAME, "rb");
			$total = filesize($ABS_FILE_NAME);

			if(($total > 0) && is_resource($fp))
			{
				$obXMLFile = new CIBlockXMLFile;
				if($obXMLFile->ReadXMLToDatabase($fp, $NS, $arParams["INTERVAL"]))
				{
					$NS["STEP"] = 3;
					$strMessage = GetMessage("CC_BSC1_FILE_READ");
				}
				else
				{
					$strMessage = GetMessage("CC_BSC1_FILE_PROGRESS", array("#PERCENT#"=>$total > 0? round($obXMLFile->GetFilePosition()/$total*100, 2): 0));
				}
				fclose($fp);
			}
			else
			{
				$strError = GetMessage("CC_BSC1_FILE_ERROR");
			}
		}
		elseif($NS["STEP"] == 3)
		{
			if(CIBlockXMLFile::IndexTemporaryTables())
			{

				if (CModule::IncludeModule("iblock")){
		$arrSections=array();
		$arFilter=array("IBLOCK_ID"=>8,"ACTIVE"=>"Y");
		$db_list = CIBlockSection::GetList(Array(), $arFilter, false, array("UF_PRICE_MIN","UF_PRICE_MAX","UF_PRICE_INCSEC_MIN","UF_PRICE_INCSEC_MAX"));

		while($ar_result = $db_list->GetNext())
		  {
			$arrSections[''.$ar_result['ID'].'']=array("min"=>$ar_result["UF_PRICE_MIN"],"max"=>$ar_result["UF_PRICE_MAX"],"min_vs"=>$ar_result["UF_PRICE_INCSEC_MIN"],"max_vs"=>$ar_result["UF_PRICE_INCSEC_MAX"],"parent"=>$ar_result["IBLOCK_SECTION_ID"]);
		  }

		$_SESSION['arrSections']=$arrSections;
		//$_SESSION['prices']=array();
		//arraytofile($_SESSION,$_SERVER['DOCUMENT_ROOT']."/upload/SESSION.txt", "SESSION");


		}


				$strMessage = GetMessage("CC_BSC1_INDEX_CREATED");
				$NS["STEP"] = 4;
			}
			else
				$strError = GetMessage("CC_BSC1_INDEX_CREATE_ERROR");
		}
		elseif($NS["STEP"] == 4)
		{
			$obCatalog = new CIBlockCMLCustomImport;
			$obCatalog->InitEx($NS, array(
				"files_dir" => $WORK_DIR_NAME,
				"use_crc" => $arParams["USE_CRC"],
				"preview" => $preview,
				"detail" => $detail,
				"use_offers" => $arParams["USE_OFFERS"],
				"use_iblock_type_id" => $arParams["USE_IBLOCK_TYPE_ID"],
				"translit_on_add" => $arParams["TRANSLIT_ON_ADD"],
				"translit_on_update" => $arParams["TRANSLIT_ON_UPDATE"],
				"translit_params" => $arTranslitParams,
				"skip_root_section" => $arParams["SKIP_ROOT_SECTION"],
			));
			$result = $obCatalog->ImportMetaData(1, $arParams["IBLOCK_TYPE"], $arParams["SITE_LIST"]);
			if($result === true)
			{
				$strMessage = GetMessage("CC_BSC1_METADATA_IMPORTED");
				$NS["STEP"] = 5;
			}
			else
			{
				$strError = GetMessage("CC_BSC1_METADATA_ERROR").implode("\n", $result);
			}
		}
		elseif($NS["STEP"] == 5)
		{
			$obCatalog = new CIBlockCMLCustomImport;
			$obCatalog->InitEx($NS, array(
				"files_dir" => $WORK_DIR_NAME,
				"use_crc" => $arParams["USE_CRC"],
				"preview" => $preview,
				"detail" => $detail,
				"use_offers" => $arParams["USE_OFFERS"],
				"use_iblock_type_id" => $arParams["USE_IBLOCK_TYPE_ID"],
				"translit_on_add" => $arParams["TRANSLIT_ON_ADD"],
				"translit_on_update" => $arParams["TRANSLIT_ON_UPDATE"],
				"translit_params" => $arTranslitParams,
				"skip_root_section" => $arParams["SKIP_ROOT_SECTION"],
			));
			$result = $obCatalog->ImportSections();
			$strMessage = GetMessage("CC_BSC1_SECTIONS_IMPORTED");
			$NS["STEP"] = 6;
		}
		elseif($NS["STEP"] == 6)
		{
			$obCatalog = new CIBlockCMLCustomImport;
			$obCatalog->InitEx($NS, array(
				"files_dir" => $WORK_DIR_NAME,
				"use_crc" => $arParams["USE_CRC"],
				"preview" => $preview,
				"detail" => $detail,
				"use_offers" => $arParams["USE_OFFERS"],
				"use_iblock_type_id" => $arParams["USE_IBLOCK_TYPE_ID"],
				"translit_on_add" => $arParams["TRANSLIT_ON_ADD"],
				"translit_on_update" => $arParams["TRANSLIT_ON_UPDATE"],
				"translit_params" => $arTranslitParams,
				"skip_root_section" => $arParams["SKIP_ROOT_SECTION"],
			));
			$result = $obCatalog->DeactivateSections($arParams["SECTION_ACTION"]);
			$obCatalog->SectionsResort();
			$strMessage = GetMessage("CC_BSC1_SECTION_DEA_DONE");
			$NS["STEP"] = 7;
		}
		elseif($NS["STEP"] == 7)
		{
			if(($NS["DONE"]["ALL"] <= 0) && $NS["XML_ELEMENTS_PARENT"])
			{
				$rs = $DB->Query("select count(*) C from b_xml_tree where PARENT_ID = ".intval($NS["XML_ELEMENTS_PARENT"]));
				$ar = $rs->Fetch();
				$NS["DONE"]["ALL"] = $ar["C"];
			}

			//$obCatalog = new CIBlockCMLImport;
			$obCatalog = new CIBlockCMLCustomImport1;
			$obCatalog->InitEx($NS, array(
				"files_dir" => $WORK_DIR_NAME,
				"use_crc" => $arParams["USE_CRC"],
				"preview" => $preview,
				"detail" => $detail,
				"use_offers" => $arParams["USE_OFFERS"],
				"use_iblock_type_id" => $arParams["USE_IBLOCK_TYPE_ID"],
				"translit_on_add" => $arParams["TRANSLIT_ON_ADD"],
				"translit_on_update" => $arParams["TRANSLIT_ON_UPDATE"],
				"translit_params" => $arTranslitParams,
				"skip_root_section" => $arParams["SKIP_ROOT_SECTION"],
			));
			$obCatalog->ReadCatalogData($_SESSION["BX_CML2_IMPORT"]["SECTION_MAP"], $_SESSION["BX_CML2_IMPORT"]["PRICES_MAP"]);
			$result = $obCatalog->ImportElements($start_time, $arParams["INTERVAL"]);

			$counter = 0;
			foreach($result as $key=>$value)
			{
				$NS["DONE"][$key] += $value;
				$counter+=$value;
			}

			if(!$counter)
			{
				$strMessage = GetMessage("CC_BSC1_DONE");
				$NS["STEP"] = 8;
			}
			elseif(strlen($obCatalog->LAST_ERROR))
			{
				$strError = $obCatalog->LAST_ERROR;
			}
			else
			{
				$strMessage = GetMessage("CC_BSC1_PROGRESS", array("#TOTAL#"=>$NS["DONE"]["ALL"],"#DONE#"=>intval($NS["DONE"]["CRC"])));
			}
		}
		elseif($NS["STEP"] == 8)
		{
			$obCatalog = new CIBlockCMLImport;
			$obCatalog->Init($NS);
			$result = $obCatalog->DeactivateElement($arParams["ELEMENT_ACTION"], $start_time, $arParams["INTERVAL"]);

			$counter = 0;
			foreach($result as $key=>$value)
			{
				$NS["DONE"][$key] += $value;
				$counter+=$value;
			}

			if(!$counter)
			{
				$strMessage = GetMessage("CC_BSC1_DEA_DONE");
				$NS["STEP"] = 9;
			}
			else
			{
				$strMessage = GetMessage("CC_BSC1_PROGRESS", array("#TOTAL#"=>$NS["DONE"]["ALL"],"#DONE#"=>$NS["DONE"]["NON"]));
			}
		}
		else
		{
			$NS["STEP"]++;
		}

		if($strError)
		{
			echo "failure\n";
			echo str_replace("<br>", "", $strError);
		}
		elseif($NS["STEP"] < 10)
		{
			echo "progress\n",$strMessage;
		}
		else
		{
			//foreach(GetModuleEvents("catalog", "OnSuccessCatalogImport1C", true) as $arEvent)
			//ExecuteModuleEventEx($arEvent);
			
				//ExecuteModuleEventEx($arEvent);
			
			$rs_ec = CIBlockElement::GetList(
			array(), 
			array("IBLOCK_ID"=>8, '!%CODE' => '/'), 
			false, 
			false, 
			array('ID', 'NAME', 'CODE', 'PROPERTY_CML2_ARTICLE', 'PROPERTY_CML2_BAR_CODE', 'DATE_CREATE', 'TIMESTAMP_X')
			);
			if($rs_ec->SelectedRowsCount() > 0)
			{
				while($ar_ec = $rs_ec->GetNext(true, false))
				{
					$oElement_ec = new CIBlockElement();
					$oElement_ec->Update($ar_ec["ID"], array("CODE" => "recalculate"));
				}
			}

$potomki=array();
				$arrSections=$_SESSION['arrSections'];
				foreach($arrSections as $key=>$val){
				//Найдем предков раздела

						if($val['parent']){
							$potomki[''.$val['parent'].''][]=$key;
						}

				}

				$predki=array();

					foreach($potomki as $key=>$val){
						foreach($val as $key2=>$val2){
							$predki[''.$val2.''][]=$key;
						}

					}


				$arrSections=$_SESSION['arrSections'];
				$exchange_min_max=array();
				foreach($_SESSION['prices'] as $section=>$prices){
					foreach($prices as $key=>$price){
						if ($price<$arrSections[''.$section.'']['min'] || !$arrSections[''.$section.'']['min'] ){
							$exchange_min_max[''.$section.'']['min']=$price;
							$arrSections[''.$section.'']['min']=$price;
						}
						
						if ($price>$arrSections[''.$section.'']['max'] || !$arrSections[''.$section.'']['max'] ){
							$exchange_min_max[''.$section.'']['max']=$price;
							$arrSections[''.$section.'']['max']=$price;
						}
						
						if ($price<$arrSections[''.$section.'']['min_vs'] || !$arrSections[''.$section.'']['min_vs'] ){
							$exchange_min_max[''.$section.'']['min_vs']=$price;
							$arrSections[''.$section.'']['min_vs']=$price;
						}
						
						if ($price>$arrSections[''.$section.'']['max_vs'] || !$arrSections[''.$section.'']['max_vs'] ){
							$exchange_min_max[''.$section.'']['max_vs']=$price;
							$arrSections[''.$section.'']['max_vs']=$price;
						}
						
						
						$section_parent=$arrSections[''.$section.'']['parent'];
						if ($section_parent>0){
							if ($price<$arrSections[''.$section_parent.'']['min_vs'] || !$arrSections[''.$section_parent.'']['min_vs'] ){
								$exchange_min_max[''.$section_parent.'']['min_vs']=$price;
								$arrSections[''.$section_parent.'']['min_vs']=$price;
							}
							
							if ($price>$arrSections[''.$section_parent.'']['max_vs'] || !$arrSections[''.$section_parent.'']['max_vs'] ){
								$exchange_min_max[''.$section_parent.'']['max_vs']=$price;
								$arrSections[''.$section_parent.'']['max_vs']=$price;
							} 
						}
					}
				
				}
				
				//arraytofile($exchange_min_max,$_SERVER['DOCUMENT_ROOT']."/upload/exchange_min_max.txt", "exchange_min_max");
				
			foreach($exchange_min_max as $key=>$val){
				
					
					if ($val['max']) $GLOBALS["USER_FIELD_MANAGER"]->Update( "IBLOCK_8_SECTION",  $key, array("UF_PRICE_MAX"=>$val['max']));
					if ($val['min']) $GLOBALS["USER_FIELD_MANAGER"]->Update("IBLOCK_8_SECTION", $key, array("UF_PRICE_MIN"=>$val['min']));
					if ($val['max_vs']) $GLOBALS["USER_FIELD_MANAGER"]->Update("IBLOCK_8_SECTION", $key, array("UF_PRICE_INCSEC_MAX"=>$val['max_vs']));
					if ($val['min_vs']) $GLOBALS["USER_FIELD_MANAGER"]->Update("IBLOCK_8_SECTION", $key, array("UF_PRICE_INCSEC_MIN"=>$val['min_vs']));
				
}
				
				
				
				
				//arraytofile($potomki,$_SERVER['DOCUMENT_ROOT']."/upload/potomki.txt", "potomki");
				//arraytofile($predki,$_SERVER['DOCUMENT_ROOT']."/upload/predki.txt", "predki");
				
				
				unset($_SESSION['prices']);


			echo "success\n",GetMessage("CC_BSC1_IMPORT_SUCCESS");
			$_SESSION["BX_CML2_IMPORT"] = array(
				"zip" => $_SESSION["BX_CML2_IMPORT"]["zip"], //save from prev load
				"NS" => array(
					"STEP" => 0,
				),
				"SECTION_MAP" => false,
				"PRICES_MAP" => false,
			);
		}
	}
	elseif($_GET["mode"]=="init")
	{
		DeleteDirFilesEx($DIR_NAME);
		CheckDirPath($_SERVER["DOCUMENT_ROOT"].$DIR_NAME."/");
		if(!is_dir($_SERVER["DOCUMENT_ROOT"].$DIR_NAME))
		{
			echo "failure\n",GetMessage("CC_BSC1_ERROR_INIT");
		}
		else
		{
			$_SESSION["BX_CML2_IMPORT"] = array(
				"zip" => $arParams["USE_ZIP"] && function_exists("zip_open"),
				"NS" => array(
					"STEP" => 0,
				),
				"SECTION_MAP" => false,
				"PRICES_MAP" => false,
			);
			echo "zip=".($_SESSION["BX_CML2_IMPORT"]["zip"]? "yes": "no")."\n";
			echo "file_limit=".$arParams["FILE_SIZE_LIMIT"]."\n";
		}
	}
	else
	{
		echo "failure\n",GetMessage("CC_BSC1_ERROR_UNKNOWN_COMMAND");
	}
}

$contents = ob_get_contents();
ob_end_clean();

if($DIR_NAME != "")
{
	$ht_name = $_SERVER["DOCUMENT_ROOT"].$DIR_NAME."/.htaccess";
	file_put_contents($ht_name, "Deny from All");
	@chmod($ht_name, BX_FILE_PERMISSIONS);
}

if(!$bDesignMode)
{
	if(toUpper(LANG_CHARSET) != "WINDOWS-1251")
		$contents = $APPLICATION->ConvertCharset($contents, LANG_CHARSET, "windows-1251");
	header("Content-Type: text/html; charset=windows-1251");

	echo $contents;
	die();
}
else
{
	$this->IncludeComponentLang(".parameters.php");
	$arAction = array(
		"N" => GetMessage("CC_BCI1_NONE"),
		"A" => GetMessage("CC_BCI1_DEACTIVATE"),
		"D" => GetMessage("CC_BCI1_DELETE"),
	);

	if(
		(COption::GetOptionString("main", "use_session_id_ttl", "N") == "Y")
		&& (COption::GetOptionInt("main", "session_id_ttl", 0) > 0)
		&& !defined("BX_SESSION_ID_CHANGE")
	)
		ShowError(GetMessage("CC_BSC1_ERROR_SESSION_ID_CHANGE"));
	?><table class="data-table">
	<tr><td><?echo GetMessage("CC_BCI1_IBLOCK_TYPE")?></td><td><?echo $arParams["IBLOCK_TYPE"]?></td></tr>
	<tr><td><?echo GetMessage("CC_BCI1_SITE_LIST")?></td><td><?echo implode(", ", $arParams["SITE_LIST"])?></td></tr>
	<tr><td><?echo GetMessage("CC_BCI1_INTERVAL")?></td><td><?echo $arParams["INTERVAL"]?></td></tr>
	<tr><td><?echo GetMessage("CC_BCI1_ELEMENT_ACTION")?></td><td><?echo $arAction[$arParams["ELEMENT_ACTION"]]?></td></tr>
	<tr><td><?echo GetMessage("CC_BCI1_SECTION_ACTION")?></td><td><?echo $arAction[$arParams["SECTION_ACTION"]]?></td></tr>
	<tr><td><?echo GetMessage("CC_BCI1_FILE_SIZE_LIMIT")?></td><td><?echo $arParams["FILE_SIZE_LIMIT"]?></td></tr>
	<tr><td><?echo GetMessage("CC_BCI1_USE_CRC")?></td><td><?echo $arParams["USE_CRC"]? GetMessage("MAIN_YES"): GetMessage("MAIN_NO")?></td></tr>
	<tr><td><?echo GetMessage("CC_BCI1_USE_ZIP")?></td><td><?echo $arParams["USE_ZIP"]? GetMessage("MAIN_YES"): GetMessage("MAIN_NO")?></td></tr>
	<tr><td><?echo GetMessage("CC_BCI1_USE_IBLOCK_PICTURE_SETTINGS")?></td><td><?echo $arParams["USE_IBLOCK_PICTURE_SETTINGS"]? GetMessage("MAIN_YES"): GetMessage("MAIN_NO")?></td></tr>
	<?if(!$arParams["USE_IBLOCK_PICTURE_SETTINGS"]):?>
		<tr><td><?echo GetMessage("CC_BCI1_GENERATE_PREVIEW")?></td><td><?echo $arParams["GENERATE_PREVIEW"]? GetMessage("MAIN_YES")." ".$arParams["PREVIEW_WIDTH"]."x".$arParams["PREVIEW_HEIGHT"]: GetMessage("MAIN_NO")?></td></tr>
		<tr><td><?echo GetMessage("CC_BCI1_DETAIL_RESIZE")?></td><td><?echo $arParams["DETAIL_RESIZE"]? GetMessage("MAIN_YES")." ".$arParams["DETAIL_WIDTH"]."x".$arParams["DETAIL_HEIGHT"]: GetMessage("MAIN_NO")?></td></tr>
	<?endif?>
	<tr><td><?echo GetMessage("CC_BCI1_TRANSLIT_ON_ADD")?></td><td><?echo $arParams["TRANSLIT_ON_ADD"]? GetMessage("MAIN_YES"): GetMessage("MAIN_NO")?></td></tr>
	<tr><td><?echo GetMessage("CC_BCI1_TRANSLIT_ON_UPDATE")?></td><td><?echo $arParams["TRANSLIT_ON_UPDATE"]? GetMessage("MAIN_YES"): GetMessage("MAIN_NO")?></td></tr>
	<?if($arParams["TRANSLIT_ON_ADD"] || $arParams["TRANSLIT_ON_UPDATE"]):?>
		<tr><td><?echo GetMessage("CC_BCI1_TRANSLIT_MAX_LEN")?></td><td><?echo $arParams["TRANSLIT_MAX_LEN"]?></td></tr>
		<tr><td><?echo GetMessage("CC_BCI1_TRANSLIT_CHANGE_CASE")?></td><td><?
			if($arParams["TRANSLIT_CHANGE_CASE"] == "L" || $arParams["TRANSLIT_CHANGE_CASE"] == "l")
				echo GetMessage("CC_BCI1_TRANSLIT_CHANGE_CASE_LOWER");
			elseif($arParams["TRANSLIT_CHANGE_CASE"] == "U" || $arParams["TRANSLIT_CHANGE_CASE"] == "u")
				echo GetMessage("CC_BCI1_TRANSLIT_CHANGE_CASE_UPPER");
			else
				echo GetMessage("CC_BCI1_TRANSLIT_CHANGE_CASE_PRESERVE");
		?></td></tr>
		<tr><td><?echo GetMessage("CC_BCI1_TRANSLIT_REPLACE_SPACE")?></td><td><?echo $arParams["TRANSLIT_REPLACE_SPACE"]?></td></tr>
		<tr><td><?echo GetMessage("CC_BCI1_TRANSLIT_REPLACE_OTHER")?></td><td><?echo $arParams["TRANSLIT_REPLACE_OTHER"]?></td></tr>
		<tr><td><?echo GetMessage("CC_BCI1_TRANSLIT_DELETE_REPEAT_REPLACE")?></td><td><?echo $arParams["TRANSLIT_DELETE_REPEAT_REPLACE"]? GetMessage("MAIN_YES"): GetMessage("MAIN_NO")?></td></tr>
	<?endif?>
	<tr><td><?echo GetMessage("CC_BCI1_USE_OFFERS")?></td><td><?echo $arParams["USE_OFFERS"]? GetMessage("MAIN_YES"): GetMessage("MAIN_NO")?></td></tr>
	</table>
	<?
}
?>