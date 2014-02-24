<? 
class KlavaCatalogProductReating
{
	const IBLOCK_ID = 24;
	
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
}