<? 
class KlavaCatalogFilter
{
	private $s_UrlLinkParam;
	
	private $ar_CurrentParams; 

	public function __construct()
	{
		if(isset($_GET['filter']) && strlen($_GET['filter']) > 0)
			$this->s_UrlLinkParam = $_GET['filter'];
		
		$this->ar_CurrentParams = $this->_parseUrlLinkParam();
	}

	
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
		foreach ($ar_Params as $s_Code => $ar_Value)
		{
			$ar_Result[] = $s_Code.':['.implode(',', $ar_Value).']';
		}
		
		//return '?filter='.implode('|', $ar_Result);
		global $APPLICATION;
		return $APPLICATION->GetCurPageParam("filter=".implode('|', $ar_Result), array('filter'));
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
		foreach ($ar_Params as $s_Code => $ar_Value)
		{
			$ar_Result[] = $s_Code.':['.implode(',', $ar_Value).']';
		}
		
		
		global $APPLICATION;
		if(count($ar_Result) > 0)
			return $APPLICATION->GetCurPageParam("filter=".implode('|', $ar_Result), array('filter')); 
		else
		{
			return $APPLICATION->GetCurPageParam("", array('filter'));
		}
	}
	
	public function clearProp($s_PropertyCode)
	{
		$ar_Params = $this->ar_CurrentParams;
		
		if(array_key_exists($s_PropertyCode, $ar_Params))
			unset($ar_Params[$s_PropertyCode]);

		
		$ar_Result = array();
		foreach ($ar_Params as $s_Code => $ar_Value)
		{
			$ar_Result[] = $s_Code.':['.implode(',', $ar_Value).']';
		}
		
		
		global $APPLICATION;
		if(count($ar_Result) > 0)
			return $APPLICATION->GetCurPageParam("filter=".implode('|', $ar_Result), array('filter')); 
		else
		{
			return $APPLICATION->GetCurPageParam("", array('filter'));
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