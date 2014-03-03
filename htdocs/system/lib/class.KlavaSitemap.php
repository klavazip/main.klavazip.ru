<? 
class KlavaSitemap 
{
	private $i_CatalogIblockID;
	
	public function __construct()
	{
		$this->i_CatalogIblockID = KlavaCatalog::IBLOCK_ID;
	}
	
	
	private function _getSection()
	{
		$rs_Section = CIBlockSection::GetList(array(), array('IBLOCK_ID' => $this->i_CatalogIblockID, 'ACTIVE' => 'Y'), false);
		while($ar_Section = $rs_Section->GetNext(true, false))
		{
			$s_DetailUrl = implode('/', preg_split("/%2f+/", strtolower($ar_Section['SECTION_PAGE_URL'])));
			$ar_Result[] = array('URL' => 'http://klavazip.ru'.$s_DetailUrl, 'DATE' => date("Y-m-d\Th:i:s+04:00", strtotime($ar_Section['TIMESTAMP_X'])));  
		}
		
		return $ar_Result;
	}
	
	
	private function _getProduct()
	{
		$rs_Element = CIBlockElement::GetList(array(), array('IBLOCK_ID' => $this->i_CatalogIblockID, 'ACTIVE' => 'Y'), false, false, array('TIMESTAMP_X', 'DETAIL_PAGE_URL'));
		while($ar_Element = $rs_Element->GetNext())
		{
			$s_DetailUrl = implode('/', preg_split("/%2f+/", strtolower($ar_Element['DETAIL_PAGE_URL'])));
			$ar_Result[] = array('URL' => 'http://klavazip.ru'.$s_DetailUrl, 'DATE' => date("Y-m-d\Th:i:s+04:00", strtotime($ar_Element['TIMESTAMP_X'])));
		}
		
		return $ar_Result; 
	}
	
	
	private function _getXML()
	{
		$s_XML = '<?xml version="1.0" encoding="UTF-8"?>';
		$s_XML .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

		foreach ($this->_getSection() as $ar_Item)
		{
			$s_XML .= '<url>';
				$s_XML .= '<loc>'.$ar_Item['URL'].'</loc>';
				$s_XML .= '<lastmod>'.$ar_Item['DATE'].'</lastmod>';
			$s_XML .= '</url>';
		}	

		foreach ($this->_getProduct() as $ar_Item)
		{
			$s_XML .= '<url>';
				$s_XML .= '<loc>'.$ar_Item['URL'].'</loc>';
				$s_XML .= '<lastmod>'.$ar_Item['DATE'].'</lastmod>';
			$s_XML .= '</url>';
		}	
		
		$s_XML .= '</urlset>';
		
		return $s_XML;
	}
	
	
	public function run()
	{
		$fp = fopen($_SERVER['DOCUMENT_ROOT']."/sitemap_iblock_8.xml", "w+");
		fwrite($fp, $this->_getXML());
		fclose($fp);
		
		
		$ar_XML = XML2Array::createArray(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/sitemap.xml'));
		
		$b_IncludXMLCatalog = false;
		foreach ($ar_XML['sitemapindex']['sitemap'] as $ar_Val)
		{
			if($ar_Val['loc'] == 'http://klavazip.ru/sitemap_iblock_8.xml')
			{
				$ar_Val['lastmod'] = date("Y-m-d\Th:i:s+04:00", strtotime(date("d.m.Y H:i:s")));
				$b_IncludXMLCatalog = true;
			}
			
			$ar_Result[] = $ar_Val;
		}

		if(!$b_IncludXMLCatalog)
			$ar_Result[] = array('loc' => 'http://klavazip.ru/sitemap_iblock_8.xml', 'lastmod' => date("Y-m-d\Th:i:s+04:00", strtotime(date("d.m.Y H:i:s"))) );

		
		$s_XML = '<?xml version="1.0" encoding="UTF-8"?>';
		$s_XML .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		
			foreach ($ar_Result as $ar_Val)
			{
				$s_XML .= '<sitemap>';
					$s_XML .= '<loc>'.$ar_Val['loc'].'</loc>';
					$s_XML .= '<lastmod>'.$ar_Val['lastmod'].'</lastmod>';
				$s_XML .= '</sitemap>';
			}	
		
		$s_XML .= '</sitemapindex>';
		
		$fp = fopen($_SERVER['DOCUMENT_ROOT']."/sitemap.xml", "w+");
		fwrite($fp, $s_XML);
		fclose($fp);
	}
}