<? 
/*
 * Класс для экспорта заказов в 1с 
 */
class Klava1CExporOrder
{ 
	/**
	 * Добавляем в очередь на экспорт в 1с новы заказ
	 * @param array $arFields параметры зказа
	 */
	public static function addActionAddOrder(&$arFields)
	{
		KlavaIntegrationMain::addAction('Добавление нового заказа', 'ORDER_ADD', $arFields);
	}

	
	/**
	 * Добавляем в очередь на экспорт в 1с обновления по заказу
	 * @param array $arFields параметры зказа
	 */
	public static function addActionUpdateOrder($arFields)
	{
		KlavaIntegrationMain::addAction('Обновление данных заказа', 'ORDER_UPDATE', $arFields);
	}


	/**
	 * Основной обработчик и генеротар xml файла для экспорта в 1с
	 * @param array $arFields - массив параметров
	 * @param string $s_Action - метка действия add/update как есть особенности 
	 * @return string xml
	 */
	private static function _handler($arFields, $s_Action = 'add')
	{
		$s_LogDir = $_SERVER['DOCUMENT_ROOT'].'/system/sale.1c.integration/export.order/logs/';
		
		$b_Debug = true;
		if($b_Debug)
		{
			$s_DateTime = date("d.m.Y_H:i:s");
			mkdir($s_LogDir.$s_DateTime, 0777);
			$s_LogPatch = $s_LogDir.$s_DateTime.'/';
		}

		
		# Пользователь 
		$ar_User = CUser::GetByID($arFields['USER_ID'])->Fetch();

		
		# Профиль
		$rs_Proeprty = CSaleOrderPropsValue::GetOrderProps($arFields['ID']);
		while ($ar_Proeprty = $rs_Proeprty->Fetch())
			$ar_PropertyValue[$ar_Proeprty['CODE']] = $ar_Proeprty['VALUE'];  
		
		$s_ProfileXML_ID = KlavaUserProfile::getProfileXMLID($ar_PropertyValue['PROFILE_ID']);
		
		
		/*
		 * Статусы
		 * 
		 Название статусов на сайте            || Соответвие статусов в 1с
		=============================================================================================
		N 1. Принят, ожидает подтверждения    || НеСогласован  Ожидается согласование
		P 2. Подтвержден, ожидает оплаты      || КОбеспечению  ОжидаетсяПредоплатаДоОтгрузки
		O 3. Оплачен, ожидает отправки        || КОбеспечению  ГотовКОтгрузке
		L 4. Отправлен, ожидается доставка    || КОтгрузке     ОжидаетсяОтгрузка
		D 5. Доставлен 					      || Закрыт (как то идентифицировать что заказ не отменен)
		F 6. Отменен 						  || Закрыт (как то идентифицировать что заказ отменен)
		*/
		
		switch ($arFields['STATUS_ID'])
		{
			case 'N': $s_Status = 'НеСогласован'; break;	
			case 'P': $s_Status = 'ОжидаетсяПредоплатаДоОтгрузки'; break;	
			case 'O': $s_Status = 'ГотовКОтгрузке'; break;	
			case 'L': $s_Status = 'ОжидаетсяОтгрузка'; break;	
			case 'D': $s_Status = 'Закрыт'; break;	
			case 'F': $s_Status = 'Закрыт'; break;	
		}
		
		# Доставка
		if(intval($arFields['DELIVERY_ID']) > 0)
		{
			$ar_Delivery = CSaleDelivery::GetByID(intval($arFields['DELIVERY_ID']));
			$rs_DeliveryElement = CIBlockElement::GetList(array(), array('IBLOCK_ID' => 38), false, false, array('PROPERTY_SITE_NAME', 'PROPERTY_LINK'));
			while ( $ar_DeliveryElement = $rs_DeliveryElement->Fetch())
			{
				if( $ar_DeliveryElement['PROPERTY_SITE_NAME_VALUE'] == $ar_Delivery['NAME'] || strpos($ar_Delivery['NAME'], $ar_DeliveryElement['PROPERTY_SITE_NAME_VALUE']) !== false )
				{
					$s_DeliceryXML_ID = $ar_DeliveryElement['PROPERTY_LINK_VALUE'];
					break;
				}
			}
		}	
		
		if(strlen($s_DeliceryXML_ID) == 0)
			$s_DeliceryXML_ID = '00000000-0000-0000-0000-000000000000';
		
		# Товары
		$rs_Basket = CSaleBasket::GetList(array(), array('ORDER_ID' => $arFields['ID']), false, false, array("ID", "PRODUCT_ID", "QUANTITY", "PRICE"));
		while ($ar_Basket = $rs_Basket->Fetch())
		{
			$rs_Element = CIBlockElement::GetList(array(), array('IBLOCK_ID' => KlavaCatalog::IBLOCK_ID, 'ID' => $ar_Basket['PRODUCT_ID']), false, false, array('ID', 'XML_ID'));
			if($ar_Element = $rs_Element->GetNext(true, false))
			{
				$ar_Basket['XML_ID'] = $ar_Element['XML_ID'];
			}
			
			$ar_Product[] = $ar_Basket; 
		}
		
		
		# Система оплаты
		$s_PatmentSystemXML_ID = KlavaPaymentSystem::getPaymentSystemXML_ID($arFields['PAY_SYSTEM_ID']);
		
		
		$s_XML = '<Array xmlns="http://v8.1c.ru/8.1/data/core" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
			$s_XML .= '<Value xsi:type="Structure">';
		
				$s_XML .= '<Property name="ДополнительныеРеквизиты">';
					$s_XML .= '<Value xsi:type="Structure">';
						$s_XML .= '<Property name="IDСайта"><Value xsi:type="xs:string">'.$arFields['ID'].'</Value></Property>';
						$s_XML .= '<Property name="МетодОплат">';
							$s_XML .= '<Value xmlns:d7p1="http://v8.1c.ru/8.1/data/enterprise/current-config" xsi:type="d7p1:CatalogRef.МетодыОплаты">'.$s_PatmentSystemXML_ID.'</Value>';
						$s_XML .= '</Property>';
					$s_XML .= '</Value>';
				$s_XML .= '</Property>';
			
				
				$s_XML .= '<Property name="СтандартныеРеквизиты">';
					$s_XML .= '<Value xsi:type="Structure">';
						$s_OrderXMLID = ($s_Action == 'add') ? '00000000-0000-0000-0000-000000000000' : $arFields['XML_ID'];
						$s_XML .= '<Property name="Ссылка"><Value xmlns:d6p1="http://v8.1c.ru/8.1/data/enterprise/current-config" xsi:type="d6p1:DocumentRef.ЗаказКлиента">'.$s_OrderXMLID.'</Value></Property>';
					$s_XML .= '</Value>';
				$s_XML .= '</Property>';
				
		
				$s_XML .= '<Property name="Реквизиты">';				
					$s_XML .= '<Value xsi:type="Structure">';	
						$s_XML .= '<Property name="Дата"><Value xsi:type="xs:dateTime">'.date("Y-m-d\TH:i:s+04:00", strtotime($arFields['DATE_INSERT'])).'</Value></Property>';
						$s_XML .= '<Property name="Партнер"><Value xmlns:d6p1="http://v8.1c.ru/8.1/data/enterprise/current-config" xsi:type="d6p1:CatalogRef.Партнеры">'.$ar_User['XML_ID'].'</Value></Property>';				
						$s_XML .= '<Property name="СуммаДокумента"><Value xsi:type="xs:decimal">'.$arFields['PRICE'].'</Value></Property>';				
						$s_XML .= '<Property name="Комментарий"><Value xsi:type="xs:string">'.$arFields['USER_DESCRIPTION'].'</Value></Property>';				
						$s_XML .= '<Property name="Контрагент"><Value xmlns:d6p1="http://v8.1c.ru/8.1/data/enterprise/current-config" xsi:type="d6p1:CatalogRef.Контрагенты">'.$s_ProfileXML_ID.'</Value></Property>';	
						$s_XML .= '<Property name="Статус"><Value xmlns:d6p1="http://v8.1c.ru/8.1/data/enterprise/current-config" xsi:type="d6p1:EnumRef.СтатусыЗаказовКлиентов">'.$s_Status.'</Value></Property>';				
						$s_XML .= '<Property name="аВремяДоставкиС"><Value xsi:type="xs:dateTime">0001-01-01T00:00:00</Value></Property>';
						$s_XML .= '<Property name="аВремяДоставкиПо"><Value xsi:type="xs:dateTime">0001-01-01T00:00:00</Value></Property>';

						$i_CountDay = (intval($ar_Delivery['PERIOD_TO']) > 0) ? $ar_Delivery['PERIOD_TO'] : $ar_Delivery['PERIOD_FROM'];
						$stmp = AddToTimeStamp(array("DD" => $i_CountDay), MakeTimeStamp(date('d.m.Y'), "DD.MM.YYYY HH:MI:SS"));
						$s_XML .= '<Property name="аПредполагаемаяДатаДоставки"><Value xsi:type="xs:dateTime">'.date("Y-m-d\TH:i:s+04:00", strtotime(date("d.m.Y H:i:s", $stmp))).'</Value></Property>';				
						$s_XML .= '<Property name="kz_ТоварДоставлен"><Value xmlns:d6p1="http://v8.1c.ru/8.1/data/enterprise/current-config" xsi:type="d6p1:CatalogRef.Номенклатура">'.$s_DeliceryXML_ID.'</Value></Property>';				
						
						
					$s_XML .= '</Value>';				
				$s_XML .= '</Property>';				
				
				$s_XML .= '<Property name="Товары">';
					$s_XML .= '<Value xsi:type="Array">';
						
						foreach ($ar_Product as $ar_ProdValue)
						{
							$i_Summ = $ar_ProdValue['PRICE'] * $ar_ProdValue['QUANTITY'];
							$i_NDS = ($i_Summ * 18) / 100;
							
							$s_XML .= '<Value xsi:type="Structure">';
								$s_XML .= '<Property name="Номенклатура"><Value xmlns:d7p1="http://v8.1c.ru/8.1/data/enterprise/current-config" xsi:type="d7p1:CatalogRef.Номенклатура">'.$ar_ProdValue['XML_ID'].'</Value></Property>';
								$s_XML .= '<Property name="Количество"><Value xsi:type="xs:decimal">'.$ar_ProdValue['QUANTITY'].'</Value></Property>';
								$s_XML .= '<Property name="Цена"><Value xsi:type="xs:decimal">'.$ar_ProdValue['PRICE'].'</Value></Property>';
								$s_XML .= '<Property name="Сумма"><Value xsi:type="xs:decimal">'.$i_Summ.'</Value></Property>';
								$s_XML .= '<Property name="СтавкаНДС"><Value xmlns:d7p1="http://v8.1c.ru/8.1/data/enterprise/current-config" xsi:type="d7p1:EnumRef.СтавкиНДС">НДС18</Value></Property>';
								$s_XML .= '<Property name="СуммаНДС"><Value xsi:type="xs:decimal">'.$i_NDS.'</Value></Property>';
								$s_XML .= '<Property name="ПроцентРучнойСкидки"><Value xsi:type="xs:decimal">0</Value></Property>';
								$s_XML .= '<Property name="СуммаРучнойСкидки"><Value xsi:type="xs:decimal">0</Value></Property>';
								$s_XML .= '<Property name="ПроцентАвтоматическойСкидки"><Value xsi:type="xs:decimal">0</Value></Property>';
								$s_XML .= '<Property name="СуммаАвтоматическойСкидки"><Value xsi:type="xs:decimal">0</Value></Property>';
								$s_Canceled = ($arFields['CANCELED'] == 'Y') ? "true" : "false";
								$s_XML .= '<Property name="Отменено"><Value xsi:type="xs:boolean">'.$s_Canceled.'</Value></Property>';
								$s_XML .= '<Property name="ПричинаОтмены"><Value xmlns:d7p1="http://v8.1c.ru/8.1/data/enterprise/current-config" xsi:type="d7p1:CatalogRef.ПричиныОтменыЗаказовКлиентов">00000000-0000-0000-0000-000000000000</Value></Property>';
							$s_XML .= '</Value>';
						}	
						
						# Доставка как товар O_o
						if(is_array($ar_Delivery))
						{
							$i_NDSDelivery = ($ar_Delivery['PRICE'] * 18) / 100;
							
							$s_XML .= '<Value xsi:type="Structure">';
								$s_XML .= '<Property name="Номенклатура"><Value xmlns:d7p1="http://v8.1c.ru/8.1/data/enterprise/current-config" xsi:type="d7p1:CatalogRef.Номенклатура">'.$s_DeliceryXML_ID.'</Value></Property>';
								$s_XML .= '<Property name="Количество"><Value xsi:type="xs:decimal">1</Value></Property>';
								$s_XML .= '<Property name="Цена"><Value xsi:type="xs:decimal">'.$ar_Delivery['PRICE'].'</Value></Property>';
								$s_XML .= '<Property name="Сумма"><Value xsi:type="xs:decimal">'.$ar_Delivery['PRICE'].'</Value></Property>';
								$s_XML .= '<Property name="СтавкаНДС"><Value xmlns:d7p1="http://v8.1c.ru/8.1/data/enterprise/current-config" xsi:type="d7p1:EnumRef.СтавкиНДС">НДС18</Value></Property>';
								$s_XML .= '<Property name="СуммаНДС"><Value xsi:type="xs:decimal">'.$i_NDSDelivery.'</Value></Property>';
								$s_XML .= '<Property name="ПроцентРучнойСкидки"><Value xsi:type="xs:decimal">0</Value></Property>';
								$s_XML .= '<Property name="СуммаРучнойСкидки"><Value xsi:type="xs:decimal">0</Value></Property>';
								$s_XML .= '<Property name="ПроцентАвтоматическойСкидки"><Value xsi:type="xs:decimal">0</Value></Property>';
								$s_XML .= '<Property name="СуммаАвтоматическойСкидки"><Value xsi:type="xs:decimal">0</Value></Property>';
								$s_Canceled = "false";
								$s_XML .= '<Property name="Отменено"><Value xsi:type="xs:boolean">'.$s_Canceled.'</Value></Property>';
								$s_XML .= '<Property name="ПричинаОтмены"><Value xmlns:d7p1="http://v8.1c.ru/8.1/data/enterprise/current-config" xsi:type="d7p1:CatalogRef.ПричиныОтменыЗаказовКлиентов">00000000-0000-0000-0000-000000000000</Value></Property>';
							$s_XML .= '</Value>';
						}	
						
						
					$s_XML .= '</Value>';
				$s_XML .= '</Property>';	
				
			$s_XML .= '</Value>';
		$s_XML .= '</Array>';
		
 		
		$client = new SoapClient('http://88.198.65.46:45454/TestBase/ws/Obmen?wsdl', array('login' => "Obmen", 'password' => "Obmen", 'exceptions' => 1));
		$data = $client->MainFunc(array('data' => $s_XML, 'type' => 'ДокументСсылка.ЗаказКлиента' ))->return;

		($b_Debug) ? arraytofile(array('data' => $s_XML), $s_LogPatch.'выгрузка.xml') : '';
		($b_Debug) ? arraytofile(array('data' => $data), $s_LogPatch.'ответ.xml') : '';
		
		
		return $data; 
	}
	
	
	public static function add($arFields)
	{
		$data = self::_handler($arFields);
		
		if(strlen($data) > 0 && $data !== 'Ошибка десериализации')
		{
			require_once( $_SERVER['DOCUMENT_ROOT'].'/system/lib/xml_to_array.php' );
			$ar_XML = XML2Array::createArray($data);
			$s_XML_ID = $ar_XML['ValueTable']['row']['Value'][0]['@value'];
	
			if(strlen($s_XML_ID) > 0)
				CSaleOrder::Update($arFields['ID'], array('XML_ID' => $s_XML_ID));
			
			
			return $ar_XML;
		}
	}

	
	public static function update($arFields)
	{
		$data = self::_handler($arFields, 'update');
		
		if(strlen($data) > 0 && $data !== 'Ошибка десериализации')
		{
			require_once( $_SERVER['DOCUMENT_ROOT'].'/system/lib/xml_to_array.php' );
			return XML2Array::createArray($data);
		}
	}

}