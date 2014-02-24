<? 	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $USER;

if( $USER->IsAuthorized() )
{
	$i_ProductID 	= intval($_POST['id']);
	$i_Value 		= intval($_POST['v']);

	if($i_ProductID > 0 && $i_Value > 0 && ($i_Value >= 1 && $i_Value <= 5))
	{
		KlavaCatalogProductReating::setReating($i_ProductID, $i_Value);
		echo CUtil::PhpToJSObject(array('st' => 'ok'));
	}	
	else
	{
		echo CUtil::PhpToJSObject(array('st' => 'error', 'mess' => 'Ошибка' ));
	}
}
else
{
	echo CUtil::PhpToJSObject(array('st' => 'error', 'mess' => 'Вы не авторизаваны на сайте' ));
}
