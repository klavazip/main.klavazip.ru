<? require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if( strlen($_POST['phone']) > 0 )
{
	if( intval($_POST['id']) > 0 )
	{
		$rs_Element = CIBlockElement::GetList(
			array(), 
			array('IBLOCK_ID' => KlavaCatalog::IBLOCK_ID, 'ID' => intval($_POST['id']) ), 
			false, 
			false, 
			array(
				'ID', 
				'NAME', 
				'DETAIL_PAGE_URL', 
				'PROPERTY_CML2_ARTICLE', 
			)
		);
		if($ar_Element = $rs_Element->GetNext(true, false))
		{
			CEvent::Send('BUY_CLICK', 's1', array(
				'PHONE' 			=> $_POST['phone'],
				'URL'   			=> $_POST['url'],
				'DETAIL_PAGE_URL' 	=> 'http://klavazip.ru'.$ar_Element['DETAIL_PAGE_URL'],
				'ARTICUL'			=> $ar_Element['PROPERTY_CML2_ARTICLE_VALUE'] 	
			), false, 99);
			CEvent::CheckEvents();
		}
		
		$ob_Element = new CIBlockElement;
		$ar_Field = Array(
			"IBLOCK_ID"       => 25,
			"NAME"            => "Заказ в 1 клик / ".$_POST['phone'],
			"PREVIEW_TEXT"    => "Телефон: ".$_POST['phone']."\n Ссылка на сайте: ".$_POST['url'],
			'PROPERTY_VALUES' => array(
				'PHONE' 			=> $_POST['phone'], 
				'DETAIL_PAGE_URL' 	=> 'http://klavazip.ru'.$ar_Element['DETAIL_PAGE_URL'],
				'ARTICUL'			=> $ar_Element['PROPERTY_CML2_ARTICLE_VALUE'] 	
			)	
		);
		
		//arraytofile($ar_Field, $_SERVER['DOCUMENT_ROOT']."/___dev/1.txt", "ar_Field");
		
		$ob_Element->Add($ar_Field);
		
		
		echo CUtil::PhpToJSObject(array('st' => 'ok'));
	}	
}
else
{
	echo CUtil::PhpToJSObject(array('st' => 'error', 'mess' => 'Не введен номер телефона' ));
} 