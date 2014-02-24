<? require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if( strlen($_POST['phone']) > 0 )
{
	$ob_Element = new CIBlockElement;
	$ar_Field = Array(
		"IBLOCK_ID"       => 33,
		"NAME"            => "Заказ обратного звонка  / ".$_POST['phone'],
		'PROPERTY_VALUES' => array(
			'PHONE' 			=> $_POST['phone'],
		)
	);
	
	$ob_Element->Add($ar_Field);
	
	CEvent::Send('CALLBASK', 's1', array('PHONE' => $_POST['phone']));
	CEvent::CheckEvents();
	
	echo CUtil::PhpToJSObject(array('st' => 'ok'));
}
else
{
	echo CUtil::PhpToJSObject(array('st' => 'error', 'mess' => 'Не введен номер телефона' ));
} 