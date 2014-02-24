<? 
class KlavaCatalogProductComment
{
	const IBLOCK_ID = 10;

	public static function addComment($ar_Params)
	{
		if( intval($ar_Params['PRODUCT_ID']) == 0 || strlen($ar_Params['USER_TEXT']) == 0 )
			return;

		$s_UserName = (strlen($ar_Params['USER_NAME']) > 0) ? $ar_Params['USER_NAME'] : 'Гость';
		
		global $USER;
		$ob_Element = new CIBlockElement;
		$i_NewElement = $ob_Element->Add(array(
			'IBLOCK_ID' 		=> self::IBLOCK_ID,
			'NAME'      		=> $s_UserName,
			'PREVIEW_TEXT'		=> $ar_Params['USER_TEXT'],
			'PROPERTY_VALUES' 	=> array(
				'USER_ID' 	    => $USER->GetID(),
				'name'		    => $s_UserName,
				'item'		    => $ar_Params['PRODUCT_ID'],
				'email'		    => $ar_Params['USER_EMAIL'],
				'uid'		    => $USER->GetID(),
				'REATING_STAR'  => intval($ar_Params['USER_REATING'])		
			)
		));
		
		return $i_NewElement;
	}
	
	
	static public function getCommentCount($i_PropductID)
	{
		if( intval($i_PropductID) == 0 )
			return; 
		
		
		$rs_Element = CIBlockElement::GetList(
			array('DATE_CREATE' => 'asc'),
			array('IBLOCK_ID' => KlavaCatalogProductComment::IBLOCK_ID, 'PROPERTY_item' => intval($i_PropductID) ),
			false,
			false,
			array('ID')
		);

		return intval($rs_Element->SelectedRowsCount());
	} 
	
	
	
	/*
	public static function setReating( $i_ProductID, $i_Value )
	{
		if( intval($i_ProductID) == 0 || intval($i_Value) == 0 )
			return;
		
		global $USER;
		$ob_Element = new CIBlockElement;
		
		$rs_Element = CIBlockElement::GetList(
			array(), 
			array('IBLOCK_ID' => self::IBLOCK_ID, 'CODE' => $i_ProductID, '=PROPERTY_USER_ID' => $USER->GetID() ), 
			false, 
			false, 
			array('ID', 'CODE')
		);
		if($ar_Element = $rs_Element->GetNext(true, false))
		{
			$ob_Element->Update($ar_Element['ID'], array('NAME' => intval($i_Value)));
		}
		else
		{
			$ob_Element->Add(array(
				'IBLOCK_ID' 		=> self::IBLOCK_ID,
				'NAME'      		=> intval($i_Value),
				'CODE'				=> $i_ProductID,
				'PROPERTY_VALUES' 	=> array('USER_ID' => $USER->GetID())
			));
		}
		
		CIBlockElement::SetPropertyValueCode($i_ProductID, 'REATING_STAR', self::getReatingProduct($i_ProductID));
	}
	
	
	public static function getReatingProduct( $i_ProductID )
	{
		if(intval($i_ProductID) == 0)
			return;
		
		$rs_Element = CIBlockElement::GetList(
			array(),
			array('IBLOCK_ID' => self::IBLOCK_ID, 'CODE' => intval($i_ProductID)),
			false,
			false,
			array('ID', 'NAME', 'CODE')
		);
		$ar_Reating = array();
		while($ar_Fields = $rs_Element->GetNext())
			$ar_Reating[] = intval($ar_Fields['NAME']);
		
		return ceil( array_sum($ar_Reating) / count($ar_Reating) );
	} 
	*/
}