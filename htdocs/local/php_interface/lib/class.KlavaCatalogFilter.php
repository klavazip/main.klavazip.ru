<? 
class KlavaCatalogFilter
{
	private $s_UrlLinkParam;
	
	private $ar_CurrentParams; 
	
	//BEDROSOVA 23.05.2014
	private $_SEFArray=array();
	private $_SEFProperties=array();
	private $_USE_SEF=false;
	public $SEOArray=array();
	//END BEDROSOVA

	
	//BEDROSOVA 23.05.2014 Необходимо ли использовать ЧПУ для фильтра. По-умолчанию - отключен. 
	public function __construct($useSEF=false)  								
	{
		if ($useSEF===true)
		{
			$this->_USE_SEF=true;
		}
		
		if(isset($_GET['filter']) && strlen($_GET['filter']) > 0)
		{
			$this->s_UrlLinkParam = $_GET['filter'];
			$this->ar_CurrentParams = $this->_parseUrlLinkParam();
		}
		
		
		//BEDROSOVA 23.05.2014
		//Разбираем ЧПУ фильтра новыми функциями и возвращаем в старом формате для совместимости:
		if ($this->_USE_SEF)
		{
			global $APPLICATION;	
			$obBedrosovaSEFCache = new CPHPCache;
			$life_time = 3600*24; //1 day cache
			$cache_id = "BedrosovaSEFCache".$APPLICATION->GetCurPageParam();
			
			if($obBedrosovaSEFCache->InitCache($life_time, $cache_id, "/bedrosovaSEF/"))
			{
				// получаем закешированные переменные
				$vars = $obBedrosovaSEFCache->GetVars();
				$this->_SEFArray=$vars["SEFArray"];
				$this->_SEFProperties=$vars["SEFProperties"];
			}
			elseif ($obBedrosovaSEFCache->StartDataCache()) 
			{ 
				$this->_SEFArray = $this->_fillSEFArray("bedrosova_filter_sef");
				$this->_SEFProperties = $this->_fillSEFProperties();

				$obBedrosovaSEFCache->EndDataCache(array("SEFArray"=>$this->_SEFArray, "SEFProperties"=>$this->_SEFProperties));
			}

			
			$url=$APPLICATION->GetCurPageParam();
			if ($p = strpos($url,'?'))
			{
				$url = substr($url,0,$p);
			}		
			if ($p = strpos($url,'/filter/'))
			{
				$this->s_UrlLinkParam = substr($url,$p+8);
				
				$this->ar_CurrentParams = $this->_parseUrlSEFParam();
			}

		}

		$this->SEOArray = $this->_getSEOKeywords();
		
		//END BEDROSOVA
	}
	//BEDROSOVA 23.05.2014
	//Новые функции по работе с ЧПУ фильтра
	
	private function _getSEOKeywords()
	{
		$arr=array();
		
		foreach ($this->ar_CurrentParams as $param_key=>$param)
		{
			foreach($this->_SEFProperties as $props_key=>$props)
			{
				if($param_key==$props["CODE"])
				{
					$arr[$props["NAME"]] = array();
					$arr[$props["NAME"]]["CODE"]=$props["CODE"];
					foreach($param as $param_val)
					{
						foreach($props["VALUES"] as $val)
						{
							if($param_val==$val["ID"])$arr[$props["NAME"]]["VALUES"][]=$val["VALUE"];
						}
					}	
				}
			}
		}
		return $arr;
	}
	

	private function _getURL($ar_Result, $ar_GETResult="")
	{
		//print_r($ar_Result);
		//print_r($ar_GETResult);
		
		global $APPLICATION;
				
		if (is_array($ar_GETResult))
		{
			if (count($ar_GETResult)>0)$url=$APPLICATION->GetCurPageParam("filter=".implode('|', $ar_GETResult), array('filter'));
			else $url=$APPLICATION->GetCurPageParam("", array('filter'));
		}
		else
		{
			$url=$APPLICATION->GetCurPageParam();
		}		
		$get="";
		if ($p = strpos($url,'?'))
		{
			$get = substr($url,$p);
			$url=substr($url,0,$p);
		}
		if ($p = strpos($url,'/filter/'))
		{
			$url = substr($url,0,$p+1);
		}
		if(is_array($ar_Result))	
		{
			if (count($ar_Result)>0)	return $url.'filter/'.implode('/',$ar_Result).'/'.$get;
			else return $url.$get;
		}
		else 
		{
			return $url.$get;
		}
	}
	
	private function _fillSEFArray($tableName)
	{
		CModule::IncludeModule('highloadblock');
		$arr = array();	
 		$HLData = \Bitrix\Highloadblock\HighloadBlockTable::getList(array('filter'=>array('TABLE_NAME'=>$tableName)));
		if ($HLBlock = $HLData->fetch())
		{
			$HLBlock_entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($HLBlock);
			$main_query = new \Bitrix\Main\Entity\Query($HLBlock_entity);
			$main_query->setSelect(array('*'));
			$main_query->setFilter(array());
			$res_query = $main_query->exec();
			$res_query = new CDBResult($res_query);		
			while ($row = $res_query->Fetch())
			{
				if ($row["UF_VALUE_XML_ID"]=="root")
				{
					$arr[$row["UF_CATEGORY_XML_ID"]]["NAME"]=$row["UF_SEF"];
				}
				else
				{
					$arr[$row["UF_CATEGORY_XML_ID"]]["CHILDS"][]=array("NAME"=>$row["UF_SEF"], "XML_ID"=>$row["UF_VALUE_XML_ID"]);
				}
			}	
		} 
		
		return $arr;
	}
	
	private function _fillSEFProperties()
	{
		$arr=array();
		$enums=array();
		$IBLOCK_ID=8;
		
		
		$property_enums = CIBlockPropertyEnum::GetList(Array(), Array("IBLOCK_ID"=>$IBLOCK_ID));
		while($enum_fields = $property_enums->GetNext())
		{
		  $enums[$enum_fields["PROPERTY_ID"]][]=array("XML_ID"=>$enum_fields["XML_ID"], "ID"=>$enum_fields["ID"], "VALUE"=>$enum_fields["VALUE"]);
		}
		
		$properties = CIBlockProperty::GetList(array(), Array("IBLOCK_ID"=>$IBLOCK_ID));
		while ($prop_fields = $properties->GetNext())
		{		
			$arr[$prop_fields["XML_ID"]]=array("ID"=>$prop_fields["ID"], "XML_ID"=>$prop_fields["XML_ID"], "CODE"=>$prop_fields["CODE"], "NAME"=>$prop_fields["NAME"], "VALUES"=>$enums[$prop_fields["ID"]]);		
		}
		return $arr;
	}
	
	private function _getSEFCategoryCode($name)
	{
		$arResult = array();
		foreach ($this->_SEFArray as $el_key => $el)
		{
			if ($name==$el["NAME"])
			{
				$arResult["CODE"]=$this->_SEFProperties[$el_key]["CODE"];
				$arResult["XML_ID"]=$el_key;
			}
		}
		return $arResult;
	}
	
	private function _getCategorySEF($code)
	{
 		$arResult = array();
		
	 	foreach($this->_SEFProperties as $key=>$val)
		{
			if ($code==$val["CODE"])
			{
				$arResult["NAME"]=$this->_SEFArray[$key]["NAME"];
				if ($arResult["NAME"]!="")$arResult["XML_ID"]=$key;
				else $arResult["XML_ID"]="";
			}			
		} 
		return $arResult;
	}
	
	private function _getSEFValueID($name, $category)
	{
		$res="";
		$value_xml_id="";		
		foreach($this->_SEFArray[$category]["CHILDS"] as $child)
		{
			if ($name==$child["NAME"]) $value_xml_id=$child["XML_ID"];
		}
		
		if ($value_xml_id!="")
		{
			foreach($this->_SEFProperties[$category]["VALUES"] as $val)
			{
				if ($value_xml_id==$val["XML_ID"]) $res=$val["ID"];
			}
		}
		
		return $res;
	}
	
	private function _getValueSEF($id, $category)
	{
		$res="";
		$value_xml_id="";		
		foreach($this->_SEFProperties[$category]["VALUES"] as $child)
		{
			if ($id==$child["ID"]) $value_xml_id=$child["XML_ID"];
		}
		if ($value_xml_id!="")
		{
			foreach($this->_SEFArray[$category]["CHILDS"] as $val)
			{
				if ($value_xml_id==$val["XML_ID"]) $res=$val["NAME"];
			}
		}
		
		return $res;
	}
	
	
 	private function _parseUrlSEFParam()
	{
		if( ! empty($this->s_UrlLinkParam) )
		{	

			$ar_Result = $this->ar_CurrentParams;
			foreach ( explode('/', $this->s_UrlLinkParam) as $s_Value )
			{
				$ar_Property = explode('-', $s_Value, 2);
				$ar_Property_values = explode('-', $ar_Property[1]);  
				if ($ar_Property[0]!="")
				{
					//restore category code by name
					$res_category = $this->_getSEFCategoryCode($ar_Property[0]);
					
					foreach($ar_Property_values as $key=>$value)
					{
						$ar_Property_values[$key]= $this->_getSEFValueID($value, $res_category["XML_ID"]);
					}
					if (isset($ar_Result[$res_category["CODE"]]))
					{
						$ar_Result[$res_category["CODE"]] = array_merge($ar_Result[$res_category["CODE"]], $ar_Property_values);
					}
					else 
					{
						$ar_Result[$res_category["CODE"]] = $ar_Property_values;
					}

				}
			} 
			return $ar_Result;   
		}
	}
	//END BEDROSOVA

	
	private function _parseUrlLinkParam()
	{
		if( ! empty($this->s_UrlLinkParam) )
		{
			$ar_Result = array();
			
			foreach ( explode('|', $this->s_UrlLinkParam) as $s_Value )
			{
				$ar_Property = explode(':', $s_Value);
				$ar_Result[$ar_Property[0]] = explode(',', str_replace(array('[', ']'), array('', ''), $ar_Property[1]));  
			}
			return $ar_Result;   
		}
	}
	
	public function isValue($s_PropertyCode, $s_Value)
	{
		if( strlen($s_PropertyCode) == 0 || strlen($s_Value) == 0)
			return;
		
		return ( in_array($s_Value, (array)$this->ar_CurrentParams[$s_PropertyCode])  );
	}
	
	private function _addParam($s_PropertyCode, $s_Value)
	{
		$ar_Params = $this->ar_CurrentParams;
		$ar_Params[$s_PropertyCode][] = $s_Value;
		
		
		$ar_Result = array();
		$ar_GETResult = array();
		foreach ($ar_Params as $s_Code => $ar_Value)
		{
			if ($this->_USE_SEF)
			{
				$ar_Value_SEF=array();
				$res_category = $this->_getCategorySEF($s_Code);
				if ($res_category["XML_ID"]=="")$error=true;
				
				foreach($ar_Value as $key=>$value)
				{
					$val=$this->_getValueSEF($value, $res_category["XML_ID"]);
					if ($val!=="") 
					{
						$ar_Value_SEF[$key]= $val;
						unset ($ar_Value[$key]);
					}
				}
				if (count($ar_Value)>0)$ar_GETResult[] = $s_Code.':['.implode(',', $ar_Value).']';
				if (count($ar_Value_SEF)>0)$ar_Result[] = $res_category["NAME"].'-'.implode('-', $ar_Value_SEF);
			} 
			else
			{
				$ar_Result[] = $s_Code.':['.implode(',', $ar_Value).']';
			}
		}
		
		global $APPLICATION;
		if ($this->_USE_SEF)
			{
				return $this->_getURL($ar_Result,$ar_GETResult);
			}
			else
			{
				return $APPLICATION->GetCurPageParam("filter=".implode('|', $ar_Result), array('filter'));
			}
	}

	private function _delParam($s_PropertyCode, $s_Value)
	{
		$ar_Params = $this->ar_CurrentParams;
		if( in_array($s_Value, $ar_Params[$s_PropertyCode]) )
		{
			$ar_NewArrayValue = array();
			foreach ($ar_Params[$s_PropertyCode] as $s_Val)
			{
				if($s_Value != $s_Val)
					$ar_NewArrayValue[] = $s_Val;
			}
			
			if(count($ar_NewArrayValue) > 0)
				$ar_Params[$s_PropertyCode] = $ar_NewArrayValue;
			else
				 unset($ar_Params[$s_PropertyCode]);
		}
		
		$ar_Result = array();
		$ar_GETResult = array();
		foreach ($ar_Params as $s_Code => $ar_Value)
		{
			if ($this->_USE_SEF)
			{
				$ar_Value_SEF=array();
				$res_category = $this->_getCategorySEF($s_Code);
				if ($res_category["XML_ID"]=="")$error=true;
				foreach($ar_Value as $key=>$value)
				{
					$val=$this->_getValueSEF($value, $res_category["XML_ID"]);
					if ($val!=="") 
					{
						$ar_Value_SEF[$key]= $val;
						unset ($ar_Value[$key]);
					}
				}
				if (count($ar_Value)>0)$ar_GETResult[] = $s_Code.':['.implode(',', $ar_Value).']';
				if (count($ar_Value_SEF)>0)$ar_Result[] = $res_category["NAME"].'-'.implode('-', $ar_Value_SEF);
			} 
			else
			{
				$ar_Result[] = $s_Code.':['.implode(',', $ar_Value).']';
			}
		}
		
		
		global $APPLICATION;
		if ($this->_USE_SEF)
		{
			return $this->_getURL($ar_Result, $ar_GETResult);
		}
		else
		{
			if(count($ar_Result) > 0)	return $APPLICATION->GetCurPageParam("filter=".implode('|', $ar_Result), array('filter')); 
			else return $APPLICATION->GetCurPageParam("", array('filter'));
		}
		
		
		
	}
	
	public function clearProp($s_PropertyCode)
	{
		$ar_Params = $this->ar_CurrentParams;
		
		if(array_key_exists($s_PropertyCode, $ar_Params))
			unset($ar_Params[$s_PropertyCode]);

		
		$ar_Result = array();
		$ar_GETResult = array();
		foreach ($ar_Params as $s_Code => $ar_Value)
		{
			if ($this->_USE_SEF)
			{
				$ar_Value_SEF=array();
				$res_category = $this->_getCategorySEF($s_Code);
				if ($res_category["XML_ID"]=="")$error=true;
				foreach($ar_Value as $key=>$value)
				{
					$val=$this->_getValueSEF($value, $res_category["XML_ID"]);
					if ($val!=="") 
					{
						$ar_Value_SEF[$key]= $val;
						unset ($ar_Value[$key]);
					}
				}
				if (count($ar_Value)>0)$ar_GETResult[] = $s_Code.':['.implode(',', $ar_Value).']';
				if (count($ar_Value_SEF)>0)$ar_Result[] = $res_category["NAME"].'-'.implode('-', $ar_Value_SEF);
			}
			else
			{
				$ar_Result[] = $s_Code.':['.implode(',', $ar_Value).']';
			}
		}
		
		
		global $APPLICATION;
		if ($this->_USE_SEF)
		{
			return $this->_getURL($ar_Result, $ar_GETResult);
		}
		else
		{
			if(count($ar_Result) > 0) return $APPLICATION->GetCurPageParam("filter=".implode('|', $ar_Result), array('filter')); 
			else return $APPLICATION->GetCurPageParam("", array('filter'));
		}	
	}
	
	
	public function getFilterUrlAction($s_PropertyCode, $s_Value)
	{
		if( $this->isValue($s_PropertyCode, $s_Value) )
		{
			return $this->_delParam($s_PropertyCode, $s_Value);
		}	
		else
		{
			return $this->_addParam($s_PropertyCode, $s_Value);
		}
	}
	
	
	public function getFiltetForGetList()
	{
		$ar_Result = array();  
		if( count($this->ar_CurrentParams) > 0 )
		{
			foreach ($this->ar_CurrentParams as $s_PropertyCode => $ar_Value)
			{
				switch (true)
				{
					case ($s_PropertyCode == 'NALICHIE_BITOGO_PIKSELYA'):

						$ar = array();
						foreach ($ar_Value as $s_Val)
							$ar[] = ($s_Val == 'Y') ? 'Да' : 'Нет';
							
						
						$ar_Result['PROPERTY_NALICHIE_BITOGO_PIKSELYA'] = $ar;
						
						break;
					
					default:
						
						$ar_Result['PROPERTY_'.$s_PropertyCode] = $ar_Value;
						
						break;
				}
			}
		}
		
		# цена
		if( (isset($_GET['price-from']) && intval($_GET['price-from']) >= 0) && (isset($_GET['price-to']) && intval($_GET['price-to']) > 0) )
		{
			$ar_Result['>=CATALOG_PRICE_4'] = intval($_GET['price-from']);
			$ar_Result['<=CATALOG_PRICE_4'] = intval($_GET['price-to']);
		}
		
		return $ar_Result;
	}
}