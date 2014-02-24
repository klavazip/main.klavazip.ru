<? require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule("sale");

// Выведем актуальную корзину для текущего пользователя
$arBasketItems = array();
$dbBasketItems = CSaleBasket::GetList(
	array("NAME" => "ASC", "ID" => "ASC"),
	array("FUSER_ID" => CSaleBasket::GetBasketUserID(), "LID" => SITE_ID, "ORDER_ID" => "NULL"),
	false,
	false, 
	array("ID", "PRODUCT_ID", "QUANTITY", "DELAY", "CAN_BUY", "PRICE", "WEIGHT")
);
$ar_ElementID = array();
while ($arItems = $dbBasketItems->Fetch())
{
	$ar_BasketItems[$arItems['PRODUCT_ID']] = $arItems;
	$ar_ElementID[] = $arItems['PRODUCT_ID'];
}



if( count($ar_ElementID) > 0 && strlen($_POST['phone']) > 0)
{
	$rs_Catalog = CIBlockElement::GetList(
		array(), 
		array('IBLOCK_ID' => 8, 'ID' => $ar_ElementID), 
		false, 
		false, 
		array('ID', 'NAME', 'CATALOG_GROUP_4', 'DETAIL_PAGE_URL', 'PROPERTY_CML2_ARTICLE')
	);
	
	$s_AdminResultString = '';
	$ar_Articul = array();
	while($ar_Product = $rs_Catalog->GetNext(true, false))
	{
		$ar_Result[] = array(
			'ID'				=> $ar_Product['ID'],
			'NAME'  	 		=> $ar_Product['NAME'],
			'PRICE'		 		=> intval($ar_Product['CATALOG_PRICE_4']),
			'DETAIL_URL' 		=> $ar_Product['DETAIL_PAGE_URL'],
			'COUNT'				=> $ar_BasketItems[$ar_Product['ID']]['QUANTITY'],
			'ARTICLE'			=> $ar_Product['PROPERTY_CML2_ARTICLE_VALUE']		
		);
		$ar_Articul[] = $ar_Product['PROPERTY_CML2_ARTICLE_VALUE'];
		$s_AdminResultString .= $ar_Product['NAME'].' '.$ar_Product['PROPERTY_CML2_ARTICLE_VALUE'].' '.$ar_BasketItems[$ar_Product['ID']]['QUANTITY']." шт\n";
	}
	
	
	ob_start();
	
	?>
		<table style="width: 100%;">
			<tr>
				<td style="padding: 5px; border-bottom: 1px #000 solid; font-weight: bold;">ID</td>		
				<td style="padding: 5px; border-bottom: 1px #000 solid; font-weight: bold;">Название</td>		
				<td style="padding: 5px; border-bottom: 1px #000 solid; font-weight: bold;">Цена</td>		
				<td style="padding: 5px; border-bottom: 1px #000 solid; font-weight: bold;">Кол-во</td>		
				<td style="padding: 5px; border-bottom: 1px #000 solid; font-weight: bold;">Ссылка</td>		
				<td style="padding: 5px; border-bottom: 1px #000 solid; font-weight: bold;">Артикул</td>		
			</tr>
			<?
			foreach ($ar_Result as $ar_Value)
			{
				?>
				<tr>
					<td style="padding: 5px"><?=$ar_Value['ID']?></td>		
					<td style="padding: 5px"><?=$ar_Value['NAME']?></td>		
					<td style="padding: 5px"><?=$ar_Value['PRICE']?></td>		
					<td style="padding: 5px"><?=$ar_Value['COUNT']?></td>		
					<td style="padding: 5px"><a href="<?=$ar_Value['DETAIL_URL']?>">Ссылка</a></td>		
					<td style="padding: 5px"><?=$ar_Value['ARTICLE']?></td>		
				</tr>
				<?			
			}
			?>
		</table>
		<?	
		$html = ob_get_contents();
		
		ob_end_clean();
		
		
		CEvent::Send('BUY_CLICK', 's1', array(
			'BASKET_LIST' 	=> $html,
			'PHONE' 		=> $_POST['phone'],
		), false, 93);
		CEvent::CheckEvents();
		
		
		$ob_Element = new CIBlockElement;
		$ar_Field = Array(
			"IBLOCK_ID"      	=> 25,
			"NAME"           	=> "Заказ в 1 клик / ".$_POST['phone'],
			"CODE"			 	=> $_POST['phone'],
			"PREVIEW_TEXT"   	=> $html.'<br /> Телефон: '.$_POST['phone'],
			"PREVIEW_TEXT_TYPE" => 'html',
			'DETAIL_TEXT'		=> $s_AdminResultString,
			'PROPERTY_VALUES' => array(
				'PHONE'		 		=> ($_POST['phone'] !== '+7 ( _ _ _ ) _ _ _ - _ _ - _ _') ? $_POST['phone'] : '',
				'ARTICUL'	 		=> implode(', ', $ar_Articul),
				'DETAIL_PAGE_URL' 	=> SITE_SERVER_NAME.$ar_Element['DETAIL_PAGE_URL']
			),
		);
		
		$ob_Element->Add($ar_Field);

		echo CUtil::PhpToJSObject(array('st' => 'ok'));
	
}
else
{
	echo CUtil::PhpToJSObject(array('st' => 'error', 'mess' => 'Ошибка'));
}




