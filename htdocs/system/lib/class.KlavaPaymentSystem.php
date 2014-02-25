<? 
class KlavaPaymentSystem
{
	
	const IBLOCK_ID = 42;
	
	
	public static function getPaymentSystemID($s_XMlID)
	{
		if(strlen($s_XMlID) == 0)
			return false;
		
		
		$rs_Profile = CIBlockElement::GetList(array(), array('IBLOCK_ID' => self::IBLOCK_ID, 'NAME' => $s_XMlID), false, false, array('CODE', 'NAME'));
		if( $rs_Profile->SelectedRowsCount() > 0 )
		{
			$ar_Profile = $rs_Profile->Fetch();
			return $ar_Profile['CODE'];
		}
		else 
			return false;
	}

	public static function addMatching($s_XmlID, $i_ElementID)
	{
		if(strlen($s_XmlID) == 0 || intval($i_ElementID) == 0)
			return;
		
		$ob_Element = new CIBlockElement;
		$ob_Element->Add(array(
			'IBLOCK_ID'  => self::IBLOCK_ID,
			'NAME'       => $s_XmlID,    # Сюда пишем XML_ID
			'CODE'       => $i_ElementID # Сюда ID профиля на сайте 
		));
	}
}