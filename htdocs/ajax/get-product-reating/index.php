<? 	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$i_ProductID = intval($_POST['id']);

if( $i_ProductID > 0 )
{
	$i_ReatingNum = KlavaCatalogProductReating::getReatingProduct($i_ProductID);
	
	for( $i = 1; $i <= 5; $i++ )
	{
		if( $i <= intval($i_ReatingNum) )
		{
			?><a href="#" class="active"></a><?
		}
		else
		{
			?><a href="#"></a><?
		}
	}
	?><div class="clear"></div><?
}