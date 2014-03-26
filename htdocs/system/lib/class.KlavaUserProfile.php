<? 
class KlavaUserProfile
{
	
	const IBLOCK_ID = 40;
	
	
	public static function getProfileID($s_XMlID)
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
	
	public static function getProfileXMLID($i_ID)
	{
		if(intval($i_ID) == 0)
			return false;
		
		
		$rs_Profile = CIBlockElement::GetList(array(), array('IBLOCK_ID' => self::IBLOCK_ID, 'CODE' => $i_ID), false, false, array('CODE', 'NAME'));
		if( $rs_Profile->SelectedRowsCount() > 0 )
		{
			$ar_Profile = $rs_Profile->Fetch();
			return $ar_Profile['NAME'];
		}
		else 
			return false;
	}

	public static function addProfile($s_XmlID, $i_ProfileID)
	{
		if(strlen($s_XmlID) == 0 || intval($i_ProfileID) == 0)
			return;
		
		$ob_Element = new CIBlockElement;
		$ob_Element->Add(array(
			'IBLOCK_ID'  => self::IBLOCK_ID,
			'NAME'       => $s_XmlID,    # Сюда пишем XML_ID
			'CODE'       => $i_ProfileID # Сюда ID профиля на сайте 
		));
	}
}