<? 	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$i_ElementID = intval($_POST['id']);
if( $i_ElementID > 0 )
{
	
	$rs_Element = CIBlockElement::GetList(
			array(),
			array('IBLOCK_ID' => KlavaCatalog::IBLOCK_ID, 'ID' => $i_ElementID ),
			false,
			false,
			array(
				'ID',
				'NAME',
				'DETAIL_PAGE_URL',
				'PROPERTY_CML2_ARTICLE'
			)
	);
	if($ar_Element = $rs_Element->GetNext(true, false))
	{
		$ob_Element = new CIBlockElement;
		$ar_Field = array(
			'IBLOCK_ID'       => KlavaCatalog::NOTIFY_ADD_PRODUCT_IBLOCK_ID,
			'PROPERTY_VALUES' => array(
				'ELEMENT_ID' 		=> $i_ElementID,
				'EMAIL'		 		=> $_POST['mail'],
				'PHONE'		 		=> ($_POST['phone'] !== '+7 ( _ _ _ ) _ _ _ - _ _ - _ _') ? $_POST['phone'] : '',
				'USER_ID'	 		=> $USER->GetID(),
				'ARTICLE'	 		=> $ar_Element['PROPERTY_CML2_ARTICLE_VALUE'],
				'DETAIL_PAGE_URL' 	=> SITE_SERVER_NAME.$ar_Element['DETAIL_PAGE_URL']
			),
			'NAME' => "Элемент ".$i_ElementID
		);
		
		if($ob_Element->Add($ar_Field))
		{
			CEvent::Send("NOTYFY_ADD_PRODUCT", 's1', array(
					'PRODUCT_LINK' 	=> SITE_SERVER_NAME.'/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=8&type=catalog&ID='.$i_ElementID.'&lang=ru&find_section_section=-1&WF=Y',
					'PRODUCT_ID' 	=> $i_ElementID,
					'EMAIL' 		=> $_POST['mail'],
					'PHONE' 		=> $_POST['phone']
			));
			CEvent::CheckEvents();
		
			echo 'ok';
		}
	}
}