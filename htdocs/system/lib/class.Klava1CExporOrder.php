<? 
class Klava1CExporOrder
{ 
	
	function addActionAddOrder($arFields)
	{
		KlavaIntegrationMain::addAction('Добавление нового заказа', 'ORDER_ADD', $arFields);
	}

	function addActionUpdateOrder($arFields)
	{
		KlavaIntegrationMain::addAction('Обновление данных заказа', 'ORDER_UPDATE', $arFields);
	}

	function addActionDeleteOrder($i_OrderID)
	{
		KlavaIntegrationMain::addAction('Удаление заказа', 'ORDER_DELETE', array('ID' => $i_OrderID));
	}

}
