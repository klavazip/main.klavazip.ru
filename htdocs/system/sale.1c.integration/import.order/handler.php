<?
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

require_once( '../../lib/xml_to_array.php' );
require_once( '../../lib/class.KlavaSale.php' );
//require_once( '../../lib/class.KlavaUserProfile.php' );
//require_once( '../../lib/class.KlavaPaymentSystem.php' );


$s_LogDir = $_SERVER['DOCUMENT_ROOT'].'/system/sale.1c.integration/import.order/logs/';

$b_Debug = INTEGRATION_DEBUG_LOG;

if($b_Debug)
{
	$s_DateTime = date("d.m.Y_H:i:s");
	mkdir($s_LogDir.$s_DateTime, 0777);
	$s_LogPatch = $s_LogDir.$s_DateTime.'/';
}


# !!!
//($b_Debug) ? arraytofile($_POST,  $s_LogPatch.'массив_POST.txt',  "") : '';
//($b_Debug) ? arraytofile($_FILES, $s_LogPatch.'массив_FILES.txt', "") : '';
# !!!



/**
 * to-do
 * если статус заказа согласован (уточнить у манагеров) то данные контрагента не меняем
 */


//'test.xml/xml1.xml'
if( $data = KlavaIntegrationMain::xml(/*'test.xml/test.xml'*/) ) 
{
	$ar_XML = XML2Array::createArray($data);
	
	($b_Debug) ? arraytofile(array('data' => $data), $s_LogPatch.'полученный_xml_файл_из_1с.xml') : '';
	
	# Синхронизуем массив при 2 митуациях, когда в xml 1 элемент и несколько
	if(isset($ar_XML['Array']['Value']['Property']))
	{
		$ar_XML['Array']['Value'][] = array('@attributes' =>  $ar_XML['Array']['Value']['@attributes'], 'Property' => $ar_XML['Array']['Value']['Property']);
		unset($ar_XML['Array']['Value']['Property']);
		unset($ar_XML['Array']['Value']['@attributes']);
	}
	
	foreach ($ar_XML['Array']['Value'] as $ar_Value)
	{
		$ar_Params = array();
		foreach ($ar_Value['Property'] as $ar_Val)
		{
			$s_Type = $ar_Val['Value']['@attributes']['type'];
	
			if( $s_Type == 'xs:string' || $s_Type == 'd4p1:CatalogRef.Партнеры' || $s_Type == 'd4p1:EnumRef.ЮрФизЛицо' || $s_Type == 'd4p1:CatalogRef.Контрагенты')
				$ar_Params[$ar_Val['@attributes']['name']] = $ar_Val['Value']['@value'];
	
			if($s_Type == 'Structure')
			{
				$ar_Params1 = array();
				foreach ($ar_Val['Value']['Property'] as $ar_Val1)
					$ar_Params1[$ar_Val1['@attributes']['name']] = $ar_Val1['Value']['@value'];
	
				$ar_Params[$ar_Val['@attributes']['name']] = $ar_Params1;
			}
	
			if($s_Type == 'Array')
			{
				if($ar_Val['@attributes']['name'] == 'ДополнительныеРеквизиты')
				{
					if(isset($ar_Val['Value']['Value']['Property']))
					{
						$ar_Val['Value']['Value'][] = array('@attributes' =>  $ar_Val['Value']['Value']['@attributes'], 'Property' => $ar_Val['Value']['Value']['Property']);
						unset($ar_Val['Value']['Value']['Property']);
						unset($ar_Val['Value']['Value']['@attributes']);
					}
						
					$ar_RecValue = array();
					foreach ($ar_Val['Value']['Value'] as $ar_RecVal)
						$ar_RecValue[$ar_RecVal['Property']['@attributes']['name']] = $ar_RecVal['Property']['Value']['@value'];
						
					$ar_Params[$ar_Val['@attributes']['name']] = $ar_RecValue;
				}
				else
				{
					$ar_RecValue = array();
					foreach ($ar_Val['Value']['Value'] as $ar_RecVal)
					{
						$ar_Ar = array();
						foreach ($ar_RecVal['Property'] as $ar_ArVal)
							$ar_Ar[$ar_ArVal['@attributes']['name']] = $ar_ArVal['Value']['@value'];
	
						$ar_RecValue[] = $ar_Ar;
					}
	
					$ar_Params[$ar_Val['@attributes']['name']] = $ar_RecValue;
				}
			}
		}
	
		$ar_Result[] = $ar_Params;
	}
	
	($b_Debug) ? arraytofile($ar_Result, $s_LogPatch.'массив_поле_разбора_xml.dmp') : '';
	
	
	foreach ($ar_Result as $ar_ResultValue )
	{
		# ищем пользователя
			$rs_Users = CUser::GetList(($by=""), ($order=""), array('XML_ID' => $ar_ResultValue['Реквизиты']['Партнер']));
			if($rs_Users->SelectedRowsCount() == 0)
			{
				$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_ResultValue['СтандартныеРеквизиты']['Ссылка'], false, 'не найден пользователь на сайте');
				continue;
			}	
			else
				$ar_User = $rs_Users->NavNext(true, false);

		
		# ищем контрагент/профиль
			$i_ProfileID = KlavaUserProfile::getProfileID($ar_ResultValue['Реквизиты']['Контрагент']);
			if(intval($i_ProfileID) <= 0)
			{
				$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_ResultValue['СтандартныеРеквизиты']['Ссылка'], false, 'не найден контрагент '.$ar_ResultValue['СтандартныеРеквизиты']['Контрагент']);
				continue;
			}
			
		
		# Работаем со статусом
			$s_Status = KlavaSale::getStatusLetter($ar_ResultValue);
			
			$ar_DebugLogSattus = array('STATUS' => $ar_ResultValue['Реквизиты']['Статус'], 'STATE' => $ar_ResultValue['Реквизиты']['Состояние'],  $s_Status);
			($b_Debug) ? arraytofile($ar_DebugLogSattus, $s_LogPatch.'стутас_состояние_стутуссайт.dmp', "") : '';
			
			if(strlen($s_Status) <= 0)
			{
				$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_ResultValue['СтандартныеРеквизиты']['Ссылка'], false, 'не установлен статус заказа');
				continue;
			}
		
		
		# Работаем с доставкой
			$ar_DeliveryParams = KlavaSale::getDeliveryParams($ar_ResultValue);
			if(!$ar_DeliveryParams)
			{
				$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_ResultValue['СтандартныеРеквизиты']['Ссылка'], false, 'не установлена доставка');
				continue;
			}

			
		# Проверяем тип польователя	
			$ar_UserProfile = CSaleOrderUserProps::GetByID($i_ProfileID);
			if( intval($ar_UserProfile['PERSON_TYPE_ID']) <= 0 )
			{
				$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_ResultValue['СтандартныеРеквизиты']['Ссылка'], false, 'не найден PERSON_TYPE_ID');
				continue;
			}

			
		# Работаем с платежной системой
			$i_PaymentID = KlavaPaymentSystem::getPaymentSystemID($ar_ResultValue['ДополнительныеРеквизиты']['МетодОплат']);
			if(intval($i_PaymentID) <= 0)
			{
				$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_ResultValue['СтандартныеРеквизиты']['Ссылка'], false, 'не найдена система оплаты');
				continue;
			}
			else
			{
				$rs_Payment = CSalePaySystem::GetList(array(), array('ID' => $i_PaymentID, 'PERSON_TYPE_ID' => $ar_UserProfile['PERSON_TYPE_ID']));
				if( $rs_Payment->SelectedRowsCount() == 0 )
				{
					$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_ResultValue['СтандартныеРеквизиты']['Ссылка'], false, 'платежная система не применима к данному типу плательщика');
					continue;
				}
			}
			
			$ar_OrderFields = array(
				'LID' 			         => SITE_ID,
				'PERSON_TYPE_ID'         => $ar_UserProfile['PERSON_TYPE_ID'],
				'USER_ID' 		         => $ar_User['ID'],
				'COMMENTS'		         => $ar_ResultValue['Реквизиты']['Комментарий'],
				'PAYED'			 		 => (in_array($s_Status, array('O', 'D')) ) ? 'Y' : 'N',
				'CANCELED'		 		 => ($s_Status == 'F') ? 'Y' : 'N', // флаг (Y/N) отменён ли заказ;
				'STATUS_ID' 	 		 => $s_Status,
				'PRICE' 		 		 => $ar_ResultValue['Реквизиты']['СуммаДокумента'],
				'CURRENCY' 		 		 => 'RUB',
				'PAY_SYSTEM_ID'  		 => $i_PaymentID,
				'PRICE_DELIVERY' 		 => $ar_DeliveryParams['DELIVERY_PRODUCT']['Сумма'],
				'DELIVERY_ID' 	 		 => false,
				'EMP_PAYED_ID'	 		 => 7567, //1c_import
				'EMP_STATUS_ID'	 		 => 7567, //1c_import
				'EMP_ALLOW_DELIVERY_ID'	 => 7567, //1c_import
				'XML_ID'				 => $ar_ResultValue['СтандартныеРеквизиты']['Ссылка']
			);
			
			($b_Debug) ? arraytofile($ar_OrderFields, $s_LogPatch.'массив_параметров_заказа.dmp') : '';
		
		
		$i_OrderID = KlavaSale::getOrderID($ar_ResultValue['СтандартныеРеквизиты']['Ссылка']);
		if(intval($i_OrderID) > 0)
		{
			($b_Debug) ? arraytofile(array(), $s_LogPatch.'ID_изменяемого_заказа_'.$i_OrderID) : '';
			
			# Если документ не проведен то деактивируем и не изменяем дальше данные
			if($ar_ResultValue['СтандартныеРеквизиты']['Проведен'] == 'false')
			{
				$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_ResultValue['СтандартныеРеквизиты']['Ссылка'], true, 'Заказ не проведен');
				continue;
			}
				
			$i_OrderID = CSaleOrder::Update($i_OrderID, $ar_OrderFields);
			if(!$i_OrderID)
			{
				$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_ResultValue['СтандартныеРеквизиты']['Ссылка'], false, 'ошибка при обновлении заказа');
				continue;
			}	
		
			$ar_ProductXMLID = array();
			foreach ($ar_ResultValue['Товары'] as $ar_Product)
			{
				if($ar_Product['Номенклатура'] !== $ar_ResultValue['Реквизиты']['kz_ТоварДоставлен'])
					$ar_ProductXMLID[] = $ar_Product['Номенклатура'];
			}
			
			
			if(count($ar_ProductXMLID) > 0)
			{
				# У текущего заказа удаляем корзину
				$rs_BasketItems = CSaleBasket::GetList(array(), array('LID' => SITE_ID, 'ORDER_ID' => $i_OrderID));
				while ($ar_BasketItems = $rs_BasketItems->Fetch())
				{
					CSaleBasket::Delete($ar_BasketItems['ID']);
				}
				
				foreach ($ar_ProductXMLID as $s_XML_ID)
				{
					$rs_Element = CIBlockElement::GetList(array(), array('IBLOCK_ID' => KlavaCatalog::IBLOCK_ID, 'XML_ID' => $s_XML_ID), false, false, array('ID', 'XML_ID', 'NAME'));
					if($ar_Element = $rs_Element->GetNext(true, false))
						$ar_ProductParams[ $ar_Element['XML_ID'] ] = $ar_Element;
				}
				
				if(count($ar_ProductParams) == 0)
				{
					$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_ResultValue['СтандартныеРеквизиты']['Ссылка'], false, 'ошибка при обновлении заказа, ошибка добавления товаров');
					continue;
				}	
				
			
				# Добавляем товары в корзину
				$i_BasketItem = false;
			
				foreach ($ar_ResultValue['Товары'] as $ar_Product)
				{
					if($ar_Product['Номенклатура'] === $ar_ResultValue['Реквизиты']['kz_ТоварДоставлен'])
						continue; # Доставка лежит товар в XML по этому убираем ее
			
					$ar_BasketFields = array(
						'PRODUCT_ID' => $ar_ProductParams[$ar_Product['Номенклатура']]['ID'],
						'PRICE' 	 => $ar_Product['Цена'],
						'CURRENCY'   => 'RUB',
						'QUANTITY'   => $ar_Product['Количество'],
						'LID'		 =>	LANG,
						'DELAY'		 => 'N',
						'CAN_BUY'	 => 'Y',
						'NAME'		 => $ar_ProductParams[$ar_Product['Номенклатура']]['NAME'],
					);
					
					($b_Debug) ? arraytofile($ar_BasketFields, $s_LogPatch.'товары.dmp') : '';
					
					$i_BasketItem = CSaleBasket::Add($ar_BasketFields);
				}
							
				$ar_BasketItem = CSaleBasket::GetByID($i_BasketItem);
					
				CSaleBasket::OrderBasket($i_OrderID, $ar_BasketItem['FUSER_ID'], SITE_ID);

				$rs_ProfileValue = CSaleOrderUserPropsValue::GetList(($b=""), ($o=""), array('USER_PROPS_ID' => $i_ProfileID));
				$ar_ProfilePropValue = array();
				while ($ar_ProfileValue = $rs_ProfileValue->Fetch())
				{
					$ar_ProfilePropValue[$ar_ProfileValue['CODE']] = $ar_ProfileValue;   
				}
				

				# Если заказ падает из 1с у него заполняются поля DELIVERY_NAME, DELIVERY_PRICE, DELIVERY_XML_ID так-как мы не можем работать с доставками нормально
				# В ситуации когда заказ делается на сайте -> выгружается в 1с -> изменятся там и опять выгружается на сайт то мы теряем ID доставки но имеем ее ценц и название, по этому добавлением
				# Эти данныве в свойства
				# Из за черезжопия с доставками, оно тянется дальше :(
				$ar_PropCode = array();
				$rs_OrderPropValue = CSaleOrderPropsValue::GetList(array(), array('ORDER_ID' => $i_OrderID));
				while ($ar_OrderPropValue = $rs_OrderPropValue->Fetch())
				{
					$ar_PropCode[] = $ar_OrderPropValue['CODE'];
					
					$s_ValueDevValue = '';
					switch ($ar_OrderPropValue['CODE'])
					{
						case 'DELIVERY_NAME'  : $s_ValueDevValue = $ar_DeliveryParams['NAME']; break;
						case 'DELIVERY_PRICE' : $s_ValueDevValue = $ar_DeliveryParams['DELIVERY_PRODUCT']['Сумма']; break;
						case 'DELIVERY_XML_ID': $s_ValueDevValue = $ar_DeliveryParams['PROPERTY_LINK_VALUE']; break;
						case 'DELIVERY': $s_ValueDevValue = $ar_DeliveryParams['NAME']; break;
						default: $s_ValueDevValue = $ar_ProfilePropValue[$ar_OrderPropValue['CODE']]['VALUE']; break;
					}
					
					CSaleOrderPropsValue::Update(
						$ar_OrderPropValue['ID'], 
						array(
							'ORDER_ID' 		 => $ar_OrderPropValue['ORDER_ID'],
							'ORDER_PROPS_ID' => $ar_OrderPropValue['ORDER_PROPS_ID'],
							'NAME'			 => $ar_OrderPropValue['NAME'],
							'CODE'			 => $ar_OrderPropValue['CODE'],
							'VALUE'			 => $s_ValueDevValue
						)
					);
				}
				
				if( ! in_array('DELIVERY_XML_ID', $ar_PropCode))
				{
					$rs_OrderProp = CSaleOrderProps::GetList(($b=""), ($o=""), array('PERSON_TYPE_ID' => $ar_UserProfile['PERSON_TYPE_ID']));
					while ($ar_OrderProp = $rs_OrderProp->Fetch())
					{
						$s_OrderPropValue = false;
						 
						switch ($ar_OrderProp['CODE'])
						{
							case 'DELIVERY_NAME'  : $s_OrderPropValue = $ar_DeliveryParams['NAME']; break;
							case 'DELIVERY_PRICE' : $s_OrderPropValue = $ar_DeliveryParams['DELIVERY_PRODUCT']['Сумма']; break;
							case 'DELIVERY_XML_ID': $s_OrderPropValue = $ar_DeliveryParams['PROPERTY_LINK_VALUE']; break;
						}
						
						if($s_OrderPropValue !== false)
							CSaleOrderPropsValue::Add(array("ORDER_ID" => $i_OrderID, "ORDER_PROPS_ID" => $ar_OrderProp['ID'], "NAME" => $ar_OrderProp['NAME'], "CODE" => $ar_OrderProp['CODE'], "VALUE" => $s_OrderPropValue));
					}
				}	
			}
		
			$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_ResultValue['СтандартныеРеквизиты']['Ссылка'], true);
		}
		else
		{
			# Создаем заказ
				$i_NewOrderID = CSaleOrder::Add($ar_OrderFields);
				($b_Debug) ? arraytofile(array(), $s_LogPatch.'ID_нового_заказа_'.$i_NewOrderID) : '';
				if(intval($i_NewOrderID) <= 0)
				{
					$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_ResultValue['СтандартныеРеквизиты']['Ссылка'], false, 'ошибка в создании заказа');
					continue; 
				}
			
			
			# Получаем ID товаров в каталоге
				$ar_ProductXMLID = array();
				foreach ($ar_ResultValue['Товары'] as $ar_Product)
				{
					if($ar_Product['Номенклатура'] !== $ar_ResultValue['Реквизиты']['kz_ТоварДоставлен'])
						$ar_ProductXMLID[] = $ar_Product['Номенклатура'];
				}
				
				foreach ($ar_ProductXMLID as $s_XML_ID)
				{
					$rs_Element = CIBlockElement::GetList(array(), array('IBLOCK_ID' => KlavaCatalog::IBLOCK_ID, 'XML_ID' => $s_XML_ID), false, false, array('ID', 'XML_ID', 'NAME'));
					if($ar_Element = $rs_Element->GetNext(true, false))
						$ar_ProductParams[ $ar_Element['XML_ID'] ] = $ar_Element;
				}
				
				if(count($ar_ProductParams) == 0)
				{
					$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_ResultValue['СтандартныеРеквизиты']['Ссылка'], false, 'ошибка добавление товаров в заказ');
					continue;
				}	
				
			# Добавляем товары в корзину
				$i_BasketItem = false;
				foreach ($ar_ResultValue['Товары'] as $ar_Product)
				{
					if($ar_Product['Номенклатура'] == $ar_ResultValue['Реквизиты']['kz_ТоварДоставлен'])
					continue; # Доставка лежит товар в XML по этому убираем ее
						
					$ar_BasketFields = array(
						'PRODUCT_ID' => $ar_ProductParams[$ar_Product['Номенклатура']]['ID'],
						'PRICE' 	 => $ar_Product['Цена'],
						'CURRENCY'   => 'RUB',
						'QUANTITY'   => $ar_Product['Количество'],
						'LID'		 =>	LANG,
						'DELAY'		 => 'N',
						'CAN_BUY'	 => 'Y',
						'NAME'		 => $ar_ProductParams[$ar_Product['Номенклатура']]['NAME'],
					);
					$i_BasketItem = CSaleBasket::Add($ar_BasketFields);
				}
			
				$ar_BasketItem = CSaleBasket::GetByID($i_BasketItem);
				
			# Привязываем корзину к заказу	
				CSaleBasket::OrderBasket($i_NewOrderID, $ar_BasketItem['FUSER_ID'], SITE_ID);

			# Добавляем свойтсва	
				$rs_PropertyProfileValue = CSaleOrderUserPropsValue::GetList(($b=""), ($o=""), array('USER_PROPS_ID' => $i_ProfileID));
				while ($ar_PropertyProfileValue = $rs_PropertyProfileValue->Fetch())
				{
					$s_ValueDevValue = '';
					switch ($ar_PropertyProfileValue['CODE'])
					{
						case 'DELIVERY_NAME'  : $s_ValueDevValue = $ar_DeliveryParams['NAME']; break;
						case 'DELIVERY_PRICE' : $s_ValueDevValue = $ar_DeliveryParams['DELIVERY_PRODUCT']['Сумма']; break;
						case 'DELIVERY_XML_ID': $s_ValueDevValue = $ar_DeliveryParams['PROPERTY_LINK_VALUE']; break;
						default: $s_ValueDevValue = $ar_PropertyProfileValue['VALUE']; break;
					}
					
					$ar_PropertyName = CSaleOrderProps::GetByID($ar_PropertyProfileValue['ORDER_PROPS_ID']);
				
					$ar_FieldsOrderProperty = array(
						'ORDER_ID' 			=> $i_NewOrderID,
						'ORDER_PROPS_ID' 	=> $ar_PropertyProfileValue['ORDER_PROPS_ID'],
						'NAME' 				=> $ar_PropertyName['NAME'],
						'CODE' 				=> $ar_PropertyProfileValue['CODE'],
						'VALUE' 			=> $s_ValueDevValue,
					);
				
					CSaleOrderPropsValue::Add($ar_FieldsOrderProperty);
				
					# !!!
					($b_Debug) ? arraytofile($ar_FieldsOrderProperty, $s_LogPatch.'массив_значений_свойств.dmp') : '';
					# !!!
				}

				$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_ResultValue['СтандартныеРеквизиты']['Ссылка'], true);
		}
	}

	# !!!
	($b_Debug) ? arraytofile($ar_Status, $s_LogPatch.'массив_статусов_по_каждому_элементу.dmp') : '';
	# !!!
	 
	header('Content-Type: text/xml');
	echo KlavaIntegrationMain::getReturnXML($ar_Status);

}
else
{
	# !!!
	($b_Debug) ? arraytofile(array('data' => 'error'), $s_LogPatch.'КРИТИЧЕСКАЯ_ОШИБКА') : '';
	# !!!
}


