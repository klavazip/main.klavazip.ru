<?
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

require_once( '../../lib/xml_to_array.php' );
require_once( '../../lib/class.KlavaIntegrationMain.php' );
require_once( '../../lib/class.KlavaSale.php' );
require_once( '../../lib/class.KlavaUserProfile.php' );
require_once( '../../lib/class.KlavaPaymentSystem.php' );

# /system/sale.1c.integration/import.sale/handler1.php

$s_LogDir = $_SERVER['DOCUMENT_ROOT'].'/system/sale.1c.integration/import.sale/logs/';

$b_Debug = true;

if($b_Debug)
{
	$s_DateTime = date("d.m.Y_H:i:s");
	mkdir($s_LogDir.$s_DateTime, 0777);
	$s_LogPatch = $s_LogDir.$s_DateTime.'/';
}


# !!!
($b_Debug) ? arraytofile($_POST,  $s_LogPatch.'POST.txt',  "post") : '';
($b_Debug) ? arraytofile($_FILES, $s_LogPatch.'FILES.txt', "file") : '';
# !!!



/**
 * to-do
 * если статус заказа согласован (уточнить у манагеров) то данные контрагента не меняем
 */


//'test.xml/xml1.xml'
if( $data = KlavaIntegrationMain::xml() )
{
	$ar_XML = XML2Array::createArray($data);
	
	($b_Debug) ? arraytofile(array('data' => $data), $s_LogPatch.'data.xml', "data") : '';
	
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
	
	($b_Debug) ? arraytofile($ar_Result, $s_LogPatch.'ar_ResultXML.txt', "ar_ResultXML") : '';
	
	# Сначала валидация, потом все остальное

	
	
	
	
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
		
		# Работаем с платежной системой
			$i_PaymentID = KlavaPaymentSystem::getPaymentSystemID($ar_ResultValue['ДополнительныеРеквизиты']['МетодОплат']);
			if(intval($i_PaymentID) <= 0)
			{
				$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_ResultValue['СтандартныеРеквизиты']['Ссылка'], false, 'не найдена система оплаты');
				continue;
			}
		
			//($b_Debug) ? arraytofile($ar_UserProfile, $s_LogPatch.'ar_UserProfile.txt', "ar_UserProfile") : '';
			//($b_Debug) ? arraytofile(array($i_ProfileID), $s_LogPatch.'i_ProfileID.txt', "i_ProfileID") : '';

		# Проверяем тип польователя	
			$ar_UserProfile = CSaleOrderUserProps::GetByID($i_ProfileID);
			if( intval($ar_UserProfile['PERSON_TYPE_ID']) <= 0 )
			{
				$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_ResultValue['СтандартныеРеквизиты']['Ссылка'], false, 'не найден PERSON_TYPE_ID');
				continue;
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
			);
			
			($b_Debug) ? arraytofile($ar_OrderFields, $s_LogPatch.'ar_OrderFields.txt', "ar_OrderFields") : '';
		
		
		$i_OrderID = KlavaSale::getOrderID($ar_ResultValue['СтандартныеРеквизиты']['Ссылка']);
			
		/**************************************************************************************************************************************************************/
		if(intval($i_OrderID) > 0)
		{
			# Если документ не проведен то деактивируем и не изменяем дальше данные
			if($ar_ResultValue['СтандартныеРеквизиты']['Проведен'] == 'false')
				continue;
				
		
			$i_OrderID = CSaleOrder::Update($i_OrderID, $ar_SaleFields);
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
					
				CSaleBasket::OrderBasket($i_NewOrderID, $ar_BasketItem['FUSER_ID'], SITE_ID);
									
				$rs_PropertyProfileValue = CSaleOrderUserPropsValue::GetList(($b=""), ($o=""), array('USER_PROPS_ID' => $i_ProfileID));
				while ($ar_PropertyProfileValue = $rs_PropertyProfileValue->Fetch())
				{
					$ar_PropertyName = CSaleOrderProps::GetByID($ar_PropertyProfileValue['ORDER_PROPS_ID']);
			
					$ar_FieldsOrderProperty = array(
						"ORDER_ID" 			=> $i_NewOrderID,
						"ORDER_PROPS_ID" 	=> $ar_PropertyProfileValue['ORDER_PROPS_ID'],
						"NAME" 				=> $ar_PropertyName['NAME'],
						"CODE" 				=> $ar_PropertyProfileValue['CODE'],
						"VALUE" 			=> $ar_PropertyProfileValue['VALUE'],
					);
							
					CSaleOrderPropsValue::Add($ar_FieldsOrderProperty);
							
					($b_Debug) ? arraytofile($ar_FieldsOrderProperty, $s_LogPatch.'proeprtyValue.txt', 'ar_FieldsOrderProperty') : '';
				}
				
				
				
				# Добавим в заказ информацию о доставке из 1С
				$rs_PropertyOrederDekivery = CSaleOrderProps::GetList(array(), array("PERSON_TYPE_ID" => $ar_UserProfile['PERSON_TYPE_ID'], 'CODE'=> array('DELIVERY_NAME', 'DELIVERY_PRICE', 'DELIVERY_XML_ID')));
				while($ar_PropertyOrederDekivery = $rs_PropertyOrederDekivery->Fetch())
				{
					$s_ValueDeliveryProp = '';
						
					switch ($ar_PropertyOrederDekivery['CODE'])
					{
						case 'DELIVERY_NAME':
				
							$s_ValueDeliveryProp = KlavaIntegrationMain::getDeliveryName($ar_ResultValue['Реквизиты']['kz_ТоварДоставлен']);
					
						break;
				
						case 'DELIVERY_PRICE':
					
							foreach ($ar_ResultValue['Товары'] as $ar_Val)
							{
								if($ar_Val['Номенклатура'] == $ar_ResultValue['Реквизиты']['kz_ТоварДоставлен'])
								{
									$s_ValueDeliveryProp = $ar_Val['Сумма'];
									break;
								}
							}
					
						break;
				
						case 'DELIVERY_XML_ID':
					
							$s_ValueDeliveryProp = $ar_ResultValue['Реквизиты']['kz_ТоварДоставлен'];
					
						break;
					}
					
					
					$ar_FieldsOrderProperty = array(
						'ORDER_ID' 			=> $i_NewOrderID,
						'ORDER_PROPS_ID' 	=> $ar_PropertyOrederDekivery['ID'],
						'NAME' 				=> $ar_PropertyOrederDekivery['NAME'],
						'CODE' 				=> $ar_PropertyOrederDekivery['CODE'],
						'VALUE' 			=> $s_ValueDeliveryProp,
					);
				
					CSaleOrderPropsValue::Add($ar_FieldsOrderProperty);
										
					($b_Debug) ? arraytofile($ar_FieldsOrderProperty, $s_LogPatch.'proeprtyValue.txt', 'ar_FieldsOrderProperty') : '';
				}
			}
		
			$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_ResultValue['СтандартныеРеквизиты']['Ссылка'], true);
			
		}
		else
		{
			# Создаем заказ
				$i_NewOrderID = CSaleOrder::Add($ar_SaleFields);
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
					$ar_PropertyName = CSaleOrderProps::GetByID($ar_PropertyProfileValue['ORDER_PROPS_ID']);
				
					$ar_FieldsOrderProperty = array(
						"ORDER_ID" 			=> $i_NewOrderID,
						"ORDER_PROPS_ID" 	=> $ar_PropertyProfileValue['ORDER_PROPS_ID'],
						"NAME" 				=> $ar_PropertyName['NAME'],
						"CODE" 				=> $ar_PropertyProfileValue['CODE'],
						"VALUE" 			=> $ar_PropertyProfileValue['VALUE'],
					);
				
					CSaleOrderPropsValue::Add($ar_FieldsOrderProperty);
				
					# !!!
					($b_Debug) ? arraytofile($ar_FieldsOrderProperty, $s_LogPatch.'proeprtyValue.txt', "ar_FieldsOrderProperty") : '';
					# !!!
				}
				
				# Добавим в заказ информацию о доставке из 1С
				$rs_PropertyOrederDekivery = CSaleOrderProps::GetList(array(), array("PERSON_TYPE_ID" => $ar_UserProfile['PERSON_TYPE_ID'], 'CODE'=> array('DELIVERY_NAME', 'DELIVERY_PRICE', 'DELIVERY_XML_ID')));
				while($ar_PropertyOrederDekivery = $rs_PropertyOrederDekivery->Fetch())
				{
					$s_ValueDeliveryProp = '';

					switch ($ar_PropertyOrederDekivery['CODE'])
					{
						case 'DELIVERY_NAME':

							$s_ValueDeliveryProp = KlavaIntegrationMain::getDeliveryName($ar_ResultValue['Реквизиты']['kz_ТоварДоставлен']);

						break;

						case 'DELIVERY_PRICE':

							foreach ($ar_ResultValue['Товары'] as $ar_Val)
							{
								if($ar_Val['Номенклатура'] == $ar_ResultValue['Реквизиты']['kz_ТоварДоставлен'])
								{
									$s_ValueDeliveryProp = $ar_Val['Сумма'];
									break;
								}
							}

							break;

						case 'DELIVERY_XML_ID':

							$s_ValueDeliveryProp = $ar_ResultValue['Реквизиты']['kz_ТоварДоставлен'];

						break;
					}

					$ar_FieldsOrderProperty = array(
						"ORDER_ID" 			=> $i_NewOrderID,
						"ORDER_PROPS_ID" 	=> $ar_PropertyOrederDekivery['ID'],
						"NAME" 				=> $ar_PropertyOrederDekivery['NAME'],
						"CODE" 				=> $ar_PropertyOrederDekivery['CODE'],
						"VALUE" 			=> $s_ValueDeliveryProp,
					);
					
					CSaleOrderPropsValue::Add($ar_FieldsOrderProperty);

					# !!!
					($b_Debug) ? arraytofile($ar_FieldsOrderProperty, $s_LogPatch.'proeprtyValue.txt', "ar_FieldsOrderProperty") : '';
					# !!!
				}
					
				# Создаем элемент для сопоствления ID заказа с XML_ID из 1с
				KlavaSale::addOrderElement($ar_ResultValue['СтандартныеРеквизиты']['Ссылка'], $i_NewOrderID);
					
				$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_ResultValue['СтандартныеРеквизиты']['Ссылка'], true);
		}
	}


	($b_Debug) ? arraytofile($ar_Status, $s_LogPatch.'ar_Status.txt', "ar_Status") : '';
	
	header('Content-Type: text/xml');
	echo KlavaIntegrationMain::getReturnXML($ar_Status);

}


/*
die();


$ar_Status = array();
		
//'test.xml/xml1.xml'
if( $data = KlavaIntegrationMain::xml() )
{
	$ar_XML = XML2Array::createArray($data);
	
	# !!!
	($b_Debug) ? arraytofile(array('data' => $data), $s_LogPatch.'data.xml', "data") : '';
	# !!!
	
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
		

	# !!!
	($b_Debug) ? arraytofile($ar_Result, $s_LogPatch.'ar_Result.txt', "ar_Result") : '';
	# !!!	
	
	//echo '<pre>', print_r($ar_Result).'</pre>';
	
	foreach ($ar_Result as $ar_ResultValue )
	{
		if( $i_OrderID = KlavaSale::getOrderID($ar_ResultValue['СтандартныеРеквизиты']['Ссылка']) )
		{
			# !!!
			($b_Debug) ? arraytofile(array('action' => 'update'), $s_LogPatch.'orderAction.txt', "orderAction") : '';
			# !!!
			
			# Обновляем 
			# ========================================================================================================================
			
			# ищем пользователя
			$rs_Users = CUser::GetList(($by=""), ($order=""), array('XML_ID' => $ar_ResultValue['Реквизиты']['Партнер']));
			if($ar_User = $rs_Users->NavNext(true, false))
			{
				if( $i_ProfileID = KlavaUserProfile::getProfileID($ar_ResultValue['Реквизиты']['Контрагент']) )
				{
					# Работаем со статусом
					if($s_Status = KlavaSale::getStatusLetter($ar_ResultValue))
					{
						# Работаем с доставкой
						if($ar_DeliveryParams = KlavaSale::getDeliveryParams($ar_ResultValue))
						{
							# Работаем с платежной системой
							if( $i_PaymentID = KlavaPaymentSystem::getPaymentSystemID($ar_ResultValue['ДополнительныеРеквизиты']['МетодОплат']) )
							{
								$ar_UserProfile = CSaleOrderUserProps::GetByID($i_ProfileID);
								
								if( intval($ar_UserProfile['PERSON_TYPE_ID']) > 0 )
								{	
									$ar_SaleFields = array(
										'ACTIVE'				 => ($ar_ResultValue['СтандартныеРеквизиты']['Проведен'] == 'false') ? 'N' : 'Y', 	
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
									);
						
									# !!!
									($b_Debug) ? arraytofile($ar_SaleFields, $s_LogPatch.'orderParams.txt', "orderParams") : '';
									# !!!
								
									if($i_NewOrderID = CSaleOrder::Update($i_OrderID, $ar_SaleFields))
									{
										# Если документ не проведен то деактивируем и не изменяем дальше данные
										if($ar_ResultValue['СтандартныеРеквизиты']['Проведен'] == 'false')
											continue;										
										
										
										# Создаем корзину и ложим в нее товары
										# Получаем ID товаров в каталоге
						
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
												$ar_ProductID = array();
												if($ar_Element = $rs_Element->GetNext(true, false))
													$ar_ProductParams[ $ar_Element['XML_ID'] ] = $ar_Element;
											}
				
											if(count($ar_ProductParams) > 0)
											{
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
											}
				
											CSaleBasket::OrderBasket($i_NewOrderID, $ar_BasketItem['FUSER_ID'], SITE_ID);
											
											
											$rs_PropertyProfileValue = CSaleOrderUserPropsValue::GetList(($b=""), ($o=""), array('USER_PROPS_ID' => $i_ProfileID));
											while ($ar_PropertyProfileValue = $rs_PropertyProfileValue->Fetch())
											{
												$ar_PropertyName = CSaleOrderProps::GetByID($ar_PropertyProfileValue['ORDER_PROPS_ID']);
		
												$ar_FieldsOrderProperty = array(
													"ORDER_ID" 			=> $i_NewOrderID,
													"ORDER_PROPS_ID" 	=> $ar_PropertyProfileValue['ORDER_PROPS_ID'],
													"NAME" 				=> $ar_PropertyName['NAME'],
													"CODE" 				=> $ar_PropertyProfileValue['CODE'],
													"VALUE" 			=> $ar_PropertyProfileValue['VALUE'],
												);
															
												CSaleOrderPropsValue::Add($ar_FieldsOrderProperty);
															
												# !!!
												($b_Debug) ? arraytofile($ar_FieldsOrderProperty, $s_LogPatch.'proeprtyValue.txt', "ar_FieldsOrderProperty") : '';
												# !!!
											}
											
											
											
											# Добавим в заказ информацию о доставке из 1С
											$rs_PropertyOrederDekivery = CSaleOrderProps::GetList(array(), array("PERSON_TYPE_ID" => $ar_UserProfile['PERSON_TYPE_ID'], 'CODE'=> array('DELIVERY_NAME', 'DELIVERY_PRICE', 'DELIVERY_XML_ID')));
											while($ar_PropertyOrederDekivery = $rs_PropertyOrederDekivery->Fetch())
											{
												$s_ValueDeliveryProp = '';
															
												switch ($ar_PropertyOrederDekivery['CODE'])
												{
													case 'DELIVERY_NAME':
				
														$s_ValueDeliveryProp = KlavaIntegrationMain::getDeliveryName($ar_ResultValue['Реквизиты']['kz_ТоварДоставлен']);
															
														break;
				
													case 'DELIVERY_PRICE':
															
														foreach ($ar_ResultValue['Товары'] as $ar_Val)
														{
															if($ar_Val['Номенклатура'] == $ar_ResultValue['Реквизиты']['kz_ТоварДоставлен'])
															{
																$s_ValueDeliveryProp = $ar_Val['Сумма'];
																break;
															}
														}
															
														break;
				
													case 'DELIVERY_XML_ID':
															
														$s_ValueDeliveryProp = $ar_ResultValue['Реквизиты']['kz_ТоварДоставлен'];
															
													break;
												}
															
															
												$ar_FieldsOrderProperty = array(
													"ORDER_ID" 			=> $i_NewOrderID,
													"ORDER_PROPS_ID" 	=> $ar_PropertyOrederDekivery['ID'],
													"NAME" 				=> $ar_PropertyOrederDekivery['NAME'],
													"CODE" 				=> $ar_PropertyOrederDekivery['CODE'],
													"VALUE" 			=> $s_ValueDeliveryProp,
												);
				
												CSaleOrderPropsValue::Add($ar_FieldsOrderProperty);
															
												# !!!
												($b_Debug) ? arraytofile($ar_FieldsOrderProperty, $s_LogPatch.'proeprtyValue.txt', "ar_FieldsOrderProperty") : '';
												# !!!
												
											}
				
											# Создаем элемент для сопоствления ID заказа с XML_ID из 1с
											//KlavaSale::addOrderElement($ar_ResultValue['СтандартныеРеквизиты']['Ссылка'], $i_NewOrderID);
				
											$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_ResultValue['СтандартныеРеквизиты']['Ссылка'], true);
										}
										else
										{
											$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_ResultValue['СтандартныеРеквизиты']['Ссылка'], false, 'не выбрано ни одного товара');
										}
									}
									else
									{
										$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_ResultValue['СтандартныеРеквизиты']['Ссылка'], false, 'ошибка в обновлении заказа');
									}
								}
								else 
								{
									$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_ResultValue['СтандартныеРеквизиты']['Ссылка'], false, 'не найден PERSON_TYPE_ID');
								}
							}
							else
							{
								$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_ResultValue['СтандартныеРеквизиты']['Ссылка'], false, 'не найдена система оплаты');
							}
						}
						else
						{
							$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_ResultValue['СтандартныеРеквизиты']['Ссылка'], false, 'не установлена доставка');
						}
					}
					else
					{
						$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_ResultValue['СтандартныеРеквизиты']['Ссылка'], false, 'не установлен статус заказа');
					}
				}
				else
				{
					$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_ResultValue['СтандартныеРеквизиты']['Ссылка'], false, 'не найден контрагент '.$ar_ResultValue['СтандартныеРеквизиты']['Контрагент']);
				}
			}
			else
			{
				$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_ResultValue['СтандартныеРеквизиты']['Ссылка'], false, 'не найден пользователь на сайте');
			}	
		}
		else
		{
			# !!!
			($b_Debug) ? arraytofile(array('action' => 'add'), $s_LogPatch.'orderAction.txt', "orderAction") : '';
			# !!!
					
			
			# Добавляем
			# ========================================================================================================================
		
			# ищем пользователя
			$rs_Users = CUser::GetList(($by=""), ($order=""), array('XML_ID' => $ar_ResultValue['Реквизиты']['Партнер']));
			if($ar_User = $rs_Users->NavNext(true, false))
			{
				($b_Debug) ? arraytofile(array($ar_ResultValue['Реквизиты']['Контрагент']), $s_LogPatch.'Контрагент.txt', "Контрагент") : '';
				
				if( $i_ProfileID = KlavaUserProfile::getProfileID($ar_ResultValue['Реквизиты']['Контрагент']) )
				{
					# Работаем со статусом
					if($s_Status = KlavaSale::getStatusLetter($ar_ResultValue))
					{
						# Работаем с доставкой
						if($ar_DeliveryParams = KlavaSale::getDeliveryParams($ar_ResultValue))
						{
							# Работаем с платежной системой
							if( $i_PaymentID = KlavaPaymentSystem::getPaymentSystemID($ar_ResultValue['ДополнительныеРеквизиты']['МетодОплат']) )
							{
								$ar_UserProfile = CSaleOrderUserProps::GetByID($i_ProfileID);
								
								# !!!
								($b_Debug) ? arraytofile($ar_UserProfile, $s_LogPatch.'ar_UserProfile.txt', "ar_UserProfile") : '';
								($b_Debug) ? arraytofile(array($i_ProfileID), $s_LogPatch.'i_ProfileID.txt', "i_ProfileID") : '';
								# !!!
								
								if( intval($ar_UserProfile['PERSON_TYPE_ID']) > 0 )
								{
									$ar_SaleFields = array(
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
									);
								
									# !!!
									($b_Debug) ? arraytofile($ar_SaleFields, $s_LogPatch.'orderParams.txt', "orderParams") : '';
									# !!!
									
									if($i_NewOrderID = CSaleOrder::Add($ar_SaleFields))
									{
										# Создаем корзину и ложим в нее товары
										# Получаем ID товаров в каталоге
						
										$ar_ProductXMLID = array();
										foreach ($ar_ResultValue['Товары'] as $ar_Product)
										{
											if($ar_Product['Номенклатура'] !== $ar_ResultValue['Реквизиты']['kz_ТоварДоставлен'])
											$ar_ProductXMLID[] = $ar_Product['Номенклатура'];
										}
						
										if(count($ar_ProductXMLID) > 0)
										{
											foreach ($ar_ProductXMLID as $s_XML_ID)
											{
												$rs_Element = CIBlockElement::GetList(array(), array('IBLOCK_ID' => KlavaCatalog::IBLOCK_ID, 'XML_ID' => $s_XML_ID), false, false, array('ID', 'XML_ID', 'NAME'));
												$ar_ProductID = array();
												if($ar_Element = $rs_Element->GetNext(true, false))
													$ar_ProductParams[ $ar_Element['XML_ID'] ] = $ar_Element;
											}
									
											if(count($ar_ProductParams) > 0)
											{
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
											}
									
											CSaleBasket::OrderBasket($i_NewOrderID, $ar_BasketItem['FUSER_ID'], SITE_ID);
									
											$rs_PropertyProfileValue = CSaleOrderUserPropsValue::GetList(($b=""), ($o=""), array('USER_PROPS_ID' => $i_ProfileID));
											while ($ar_PropertyProfileValue = $rs_PropertyProfileValue->Fetch())
											{
												$ar_PropertyName = CSaleOrderProps::GetByID($ar_PropertyProfileValue['ORDER_PROPS_ID']);
															
												$ar_FieldsOrderProperty = array(
													"ORDER_ID" 			=> $i_NewOrderID,
													"ORDER_PROPS_ID" 	=> $ar_PropertyProfileValue['ORDER_PROPS_ID'],
													"NAME" 				=> $ar_PropertyName['NAME'],
													"CODE" 				=> $ar_PropertyProfileValue['CODE'],
													"VALUE" 			=> $ar_PropertyProfileValue['VALUE'],
												);
																		
												CSaleOrderPropsValue::Add($ar_FieldsOrderProperty);
												
												# !!!
												($b_Debug) ? arraytofile($ar_FieldsOrderProperty, $s_LogPatch.'proeprtyValue.txt', "ar_FieldsOrderProperty") : '';
												# !!!
											}
											
											# Добавим в заказ информацию о доставке из 1С 			
											$rs_PropertyOrederDekivery = CSaleOrderProps::GetList(array(), array("PERSON_TYPE_ID" => $ar_UserProfile['PERSON_TYPE_ID'], 'CODE'=> array('DELIVERY_NAME', 'DELIVERY_PRICE', 'DELIVERY_XML_ID')));
											while($ar_PropertyOrederDekivery = $rs_PropertyOrederDekivery->Fetch())
											{
												$s_ValueDeliveryProp = '';
												
												switch ($ar_PropertyOrederDekivery['CODE'])
												{
													case 'DELIVERY_NAME':
	
															$s_ValueDeliveryProp = KlavaIntegrationMain::getDeliveryName($ar_ResultValue['Реквизиты']['kz_ТоварДоставлен']);
														
														break;
	
													case 'DELIVERY_PRICE':
														
														foreach ($ar_ResultValue['Товары'] as $ar_Val)
														{
															if($ar_Val['Номенклатура'] == $ar_ResultValue['Реквизиты']['kz_ТоварДоставлен'])
															{
																$s_ValueDeliveryProp = $ar_Val['Сумма'];
																break;
															}	
														}	
														
														break;
	
													case 'DELIVERY_XML_ID':
														
														$s_ValueDeliveryProp = $ar_ResultValue['Реквизиты']['kz_ТоварДоставлен'];
														
														break;
												}
												
												
												$ar_FieldsOrderProperty = array(
													"ORDER_ID" 			=> $i_NewOrderID,
													"ORDER_PROPS_ID" 	=> $ar_PropertyOrederDekivery['ID'],
													"NAME" 				=> $ar_PropertyOrederDekivery['NAME'],
													"CODE" 				=> $ar_PropertyOrederDekivery['CODE'],
													"VALUE" 			=> $s_ValueDeliveryProp,
												);
													
												CSaleOrderPropsValue::Add($ar_FieldsOrderProperty);
												
												# !!!
												($b_Debug) ? arraytofile($ar_FieldsOrderProperty, $s_LogPatch.'proeprtyValue.txt', "ar_FieldsOrderProperty") : '';
												# !!!
											}
									
											# Создаем элемент для сопоствления ID заказа с XML_ID из 1с
											KlavaSale::addOrderElement($ar_ResultValue['СтандартныеРеквизиты']['Ссылка'], $i_NewOrderID);
									
											$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_ResultValue['СтандартныеРеквизиты']['Ссылка'], true);
										}
										else
										{
											$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_ResultValue['СтандартныеРеквизиты']['Ссылка'], false, 'не выбрано ни одного товара');
										}
									}
									else
									{
										$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_ResultValue['СтандартныеРеквизиты']['Ссылка'], false, 'ошибка в создании заказа');
									}
									
								}
								else
								{
									$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_ResultValue['СтандартныеРеквизиты']['Ссылка'], false, 'не найден PERSON_TYPE_ID');
								}		
							}
							else
							{
								$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_ResultValue['СтандартныеРеквизиты']['Ссылка'], false, 'не найдена система оплаты');
							}
						}
						else
						{
							$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_ResultValue['СтандартныеРеквизиты']['Ссылка'], false, 'не установлена доставка');
						}
					}
					else
					{
						$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_ResultValue['СтандартныеРеквизиты']['Ссылка'], false, 'не установлен статус заказа');
					}
				}
				else
				{
					$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_ResultValue['СтандартныеРеквизиты']['Ссылка'], false, 'не найден контрагент '.$ar_ResultValue['СтандартныеРеквизиты']['Контрагент']);
				}
			}
			else
			{
				$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_ResultValue['СтандартныеРеквизиты']['Ссылка'], false, 'не найден пользователь на сайте');
			}
		}
	}
		
	
	# !!!
	($b_Debug) ? arraytofile($ar_Status, $s_LogPatch.'ar_Status.txt', "ar_Status") : '';
	# !!!
	
	header('Content-Type: text/xml');
	echo KlavaIntegrationMain::getReturnXML($ar_Status);
	
	
	//echo '<pre>', print_r($ar_Status).'</pre>';
}	
	*/	
		


/*
 * Array
(
		[ID] => 219193
		[NAME] => Самовывоз "СДЭК" с наложенным платежом
		[PROPERTY_FULL_NAME_VALUE] => Самовывоз "СДЭК" с наложенным платежом
		[PROPERTY_FULL_NAME_DESCRIPTION] =>
		[PROPERTY_FULL_NAME_VALUE_ID] => 219193:649
		[PROPERTY_LINK_SITE_TNN_VALUE] => С наложенным платежом
		[PROPERTY_LINK_SITE_TNN_DESCRIPTION] =>
		[PROPERTY_LINK_SITE_TNN_VALUE_ID] => 219193:655
		[PROPERTY_DESCRIPTION_VALUE] =>
		[PROPERTY_DESCRIPTION_DESCRIPTION] =>
		[PROPERTY_DESCRIPTION_VALUE_ID] => 219193:651
		[PROPERTY_SITE_NAME_VALUE] => ТК "СДЭК" (Без предоплаты)
		[PROPERTY_SITE_NAME_DESCRIPTION] =>
		[PROPERTY_SITE_NAME_VALUE_ID] => 219193:656
		[PROPERTY_LINK_VALUE] => 468720c5-35c9-11e3-942a-d43d7ed6c74b
		[PROPERTY_LINK_DESCRIPTION] =>
		[PROPERTY_LINK_VALUE_ID] => 219193:653
		[DELIVERY_PRODUCT] => Array
		(
				[Номенклатура] => 468720c5-35c9-11e3-942a-d43d7ed6c74b
				[Количество] => 1
				[Цена] => 180
				[Сумма] => 180
				[СтавкаНДС] => НДС18
				[СуммаНДС] => 27.46
				[ПроцентРучнойСкидки] => 0
				[СуммаРучнойСкидки] => 0
				[ПроцентАвтоматическойСкидки] => 0
				[СуммаАвтоматическойСкидки] => 0
				[Отменено] => false
				[ПричинаОтмены] => 00000000-0000-0000-0000-000000000000
		)
			
)
1
*/

/*
 LID 					- код сайта, на котором сделан заказ;
PERSON_TYPE_ID  		- тип плательщика, к которому принадлежит посетитель, сделавший заказ (заказчик);
PAYED 					- флаг (Y/N) оплачен ли заказ;
DATE_PAYED 				- дата оплаты заказа;
EMP_PAYED_ID 			- код пользователя (сотрудника магазина), который установил флаг оплаченности;
CANCELED 				- флаг (Y/N) отменён ли заказ;
DATE_CANCELED 			- дата отмены заказа;
EMP_CANCELED_ID 		- код пользователя, который установил флаг отмены заказа;
REASON_CANCELED 		- текстовое описание причины отмены заказа;
STATUS_ID 				- код статуса заказа;
EMP_STATUS_ID 			- код пользователя (сотрудника магазина), который установил текущий статус заказа;
PRICE_DELIVERY 			- стоимость доставки заказа;
ALLOW_DELIVERY 			- флаг (Y/N) разрешена ли доставка (отгрузка) заказа;
DATE_ALLOW_DELIVERY 	- дата, когда была разрешена доставка заказа;
EMP_ALLOW_DELIVERY_ID 	- код пользователя (сотрудника магазина), который разрешил доставку заказа;
PRICE 					- общая стоимость заказа;
CURRENCY 				- валюта стоимости заказа;
DISCOUNT_VALUE 			- общая величина скидки;
USER_ID 				- код пользователя заказчика;
PAY_SYSTEM_ID 			- платежная система, которой (будет) оплачен заказа;
DELIVERY_ID 			- способ (служба) доставки заказа;
USER_DESCRIPTION 		- описание заказа заказчиком;
ADDITIONAL_INFO 		- дополнительная информация по заказу;
COMMENTS 				- произвольные комментарии;
TAX_VALUE 				- общая сумма налогов;
AFFILIATE_ID 			- код аффилиата, через которого пришел посетитель;
STAT_GID 				- параметр события в статистике;
PS_STATUS 				- флаг (Y/N) статуса платежной системы - успешно ли оплачен заказ (для платежных систем, которые позволяют автоматически получать данные по проведенным через них заказам);
PS_STATUS_CODE 			- код статуса платежной системы (значение зависит от системы);
PS_STATUS_DESCRIPTION 	- описание результата работы платежной системы;
PS_STATUS_MESSAGE 		- сообщение от платежной системы;
PS_SUM 					- сумма, которая была реально оплачена через платежную систему;
PS_CURRENCY 			- валюта суммы;
PS_RESPONSE_DATE 		- дата получения статуса платежной системы;
SUM_PAID 				- сумма, которая уже была оплачена покупателем по данному счету (например, с внутреннего счета);
PAY_VOUCHER_NUM 		- номер платежного поручения;
PAY_VOUCHER_DATE 		- дата платежного поручения.
*/

//arraytofile($_POST, $s_LogPatch.'POST.txt', "_POST");
//arraytofile($_FILES, $s_LogPatch.'FILES.txt', "_FILES");
//arraytofile($_SERVER, $s_LogPatch, "FILES");

//$ar_XML = XML2Array::createArray( file_get_contents('test.xml/xml.multy.xml') );
//$ar_XML = XML2Array::createArray( file_get_contents('test.xml/xml.multy.xml') );
//$ar_XML = XML2Array::createArray( file_get_contents('test.xml/xml1.xml') );

