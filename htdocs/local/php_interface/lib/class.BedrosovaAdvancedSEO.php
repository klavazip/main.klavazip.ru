<? 
class BedrosovaAdvancedSEO
{
	
	public $URL_GET_PART="";
	public $URL_WITHOUT_GET_PART="";
	public $URL_WITHOUT_FILTER="";
	public $URL_FILTER="";
	public $URL="";
	
	private $_SEOArray=array();
	private $_CurrentRow="";
	private $_filterSEOKeywords=array();
	
	public function __construct()  								
	{
		global $APPLICATION;
		$url=$APPLICATION->GetCurPageParam();
		$this->URL = $url;
		
		if ($p = strpos($url,'?'))
		{
			$this->URL_GET_PART = substr($url,$p);
			$url=substr($url,0,$p);
		}
		$this->URL_WITHOUT_GET_PART = $url;

		if ($p = strpos($url,'/filter/'))
		{
			$this->URL_FILTER=substr($url,$p+8);			
			$url = substr($url,0,$p+1);
			
		}
		$this->URL_WITHOUT_FILTER = $url;

		$this->_SEOArray = $this->GetSEOArray("bedrosova_advanced_seo");
		
		foreach ($this->_SEOArray as $el)
		{
			if ($el["UF_URL"]==$this->URL_WITHOUT_GET_PART)	$this->_CurrentRow = $el;
		}
		$filter = new KlavaCatalogFilter(true);
		$this->_filterSEOKeywords = $filter->SEOArray;
		$this->_filterSEOKeywords = $this->_SortSEOKeywords();
		$this->_filterSEOKeywords = $this->_RenameSEOKeywords();
		
		//print_r ($this->_filterSEOKeywords);
	}
	
	public function SetAll()
	{
		$this->SetTitle();
		$this->SetKeywords();
		$this->SetDescription();
		$this->SetPageHeader();
		$this->SetPageDesc();
	}
	
	private $_SEOKeywordsSorting=array
	(
		"Для чего" => 100,
		"Производитель" => 200,
		"Серия"=>300,
		"Диагональ экрана" => 400,
		"Раскладка клавиатуры"=>410,
		"Цвет"=>420,
		"Поверхность"=>500,
		"Варианты подсветок экрана"=>600,
		"Емкость, mAh"=>610,
		"Напряжение, V"=>620,
		"Разрешение экрана"=>700,
		"Количество ячеек"=>710,
		"Расположение коннектора"=>900,
		"Коннектор на шлейфе"=>1000,
	);
	
	private $_SEOKeywordsRenaming=array
	(
		"Для чего" => "Для",
	);
	
		private function _RenameSEOKeywords()
	{
		$arr=array();
		$temp = $this->_filterSEOKeywords;

		foreach($this->_SEOKeywordsRenaming as $rename_key=>$rename_val)
		{
			foreach ($temp as $key=>$val)
			{
				if ($rename_key==$key) 
				{
					$arr[$rename_val] = $val;
					unset($temp[$key]);
				}
			}
		}
		foreach ($temp as $key=>$val)
		{
			$arr[$key] = $val;
		}
		return $arr;
	}
	
	private function _SortSEOKeywords()
	{
		$arr=array();
		
		asort($this->_SEOKeywordsSorting);
		$temp = $this->_filterSEOKeywords;
		
		//from our dictionary...
		foreach($this->_SEOKeywordsSorting as $sort_key=>$sort_val)
		{
			foreach ($temp as $key=>$val)
			{
				if ($sort_key==$key) 
				{
					$arr[$key] = $val;
					unset($temp[$key]);
				}
			}
		}
		
		//other...
		foreach ($temp as $key=>$val)
		{
			$arr[$key] = $val;
		}
		return $arr;
	}
	
	private function _getTitleKeyword()
	{
		$generator="";
		if (count($this->_filterSEOKeywords)>0)
		{
			foreach($this->_filterSEOKeywords as $param_key=>$param)
			{
				if (count($param)==1)
				{
					$generator = $generator." ".strtolower($param_key);
					foreach($param as $value)
					{
						$generator = $generator." ".strtolower($value);
					}
				}
			} 
		}
		if ($generator!="")$generator=" ".$generator;
		return $generator;
	}
	
	public function SetTitle()
	{
		global $APPLICATION;
		if (is_array($this->_CurrentRow))
		{
			
			$val = $this->_CurrentRow["UF_TITLE"];
			if ($val!="") $APPLICATION->SetTitle($val);
		}
		else
		{
			$APPLICATION->SetTitle($APPLICATION->GetTitle().$this->_getTitleKeyword());
		}
	}
	
	public function SetKeywords()
	{
		global $APPLICATION;
		if (is_array($this->_CurrentRow))
		{
			$val = $this->_CurrentRow["UF_KEYWORDS"];
			if ($val!="") $APPLICATION->SetPageProperty("keywords", $val);
		}
		else
		{
			$APPLICATION->SetPageProperty("keywords", $APPLICATION->GetPageProperty("keywords").$this->_getTitleKeyword());
		}
	}
	
	public function SetDescription()
	{
		global $APPLICATION;
		if (is_array($this->_CurrentRow))
		{
			$val = $this->_CurrentRow["UF_DESCRIPTION"];
			if ($val!="") $APPLICATION->SetPageProperty("description", $val);
		}
		else
		{
			$APPLICATION->SetPageProperty("description", $APPLICATION->GetPageProperty("description").$this->_getTitleKeyword());
		}
	}
	
	public function SetPageHeader()
	{
		global $APPLICATION;
		if (is_array($this->_CurrentRow))
		{
			$val = $this->_CurrentRow["UF_HEADER"];
			if ($val!="") $APPLICATION->SetPageProperty("page_title", $val);
		}
		else
		{
			$APPLICATION->SetPageProperty("page_title", $APPLICATION->GetPageProperty("page_title").$this->_getTitleKeyword());
		}
	}
	
	public function SetPageDesc()
	{
		global $APPLICATION;
		if (is_array($this->_CurrentRow))
		{
			$val = $this->_CurrentRow["UF_DESC"];
			if ($val!="") $APPLICATION->SetPageProperty("page_description", $val);
		}
	}
		
	private function GetSEOArray($tableName)
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
				$arr[$row["UF_URL"]]=$row;
			}	
		} 
		
		return $arr;
	}
	
}
?>