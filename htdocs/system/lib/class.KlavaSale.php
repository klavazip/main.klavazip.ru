<? 
class KlavaSale
{
	
	const IBLOCK_ID = 41;
	
	
	
	public static function getOrderID($s_XMlID)
	{
		$rs_Profile = CIBlockElement::GetList(array(), array('IBLOCK_ID' => self::IBLOCK_ID, 'NAME' => $s_XMlID), false, false, array('CODE', 'NAME'));
		if( $rs_Profile->SelectedRowsCount() > 0 )
		{
			$ar_Profile = $rs_Profile->Fetch();
			return $ar_Profile['CODE'];
		}
		else 
			return false;
	}
	

	/**
	 * Добавляем значение сопоставления
	 * @param string $s_XmlID - XML_ID ищ 1с
	 * @param string $i_OrderID - ID заказа
	 */
	public static function addOrderElement($s_XmlID, $i_OrderID)
	{
		if(strlen($s_XmlID) == 0 || intval($i_OrderID) == 0)
			return;
		
		$ob_Element = new CIBlockElement;
		$ob_Element->Add(array(
			'IBLOCK_ID'  => self::IBLOCK_ID,
			'NAME'       => $s_XmlID,    # Сюда пишем XML_ID
			'CODE'       => $i_OrderID # Сюда ID профиля на сайте 
		));
	}


	public static function getStatusLetter($ar_Params)
	{
		$s_Status = $ar_Params['Реквизиты']['Статус'];
		$s_Condition = $ar_Params['Реквизиты']['Состояние'];
		
		$s_Result = false;
		
		switch (true)
		{
			case ($s_Status == 'НеСогласован' && $s_Condition == 'Ожидается согласование'):
				$s_Result = 'N';
				break;
				
			case ($s_Status == 'Согласован' && $s_Condition == 'ГотовКОбеспечению'):
				$s_Result = 'P';
				break;
				
			case ($s_Status == 'КОбеспечению' && $s_Condition == 'ОжидаетсяПредоплатаДоОтгрузки'):
				$s_Result = 'S';
				break;
				
			case ($s_Status == 'КОбеспечению' && $s_Condition == 'ГотовКОтгрузке'):
				$s_Result = 'P';
				break;
				
			case ($s_Status == 'КОтгрузке' && $s_Condition == 'ОжидаетсяОтгрузка'):
				$s_Result = 'L';
				break;
				
			case ($s_Status == 'КОтгрузке' && $s_Condition == 'ОжидаетсяПредоплатаДоОтгрузки'):
				$s_Result = 'D';
				break;
				
			case ($s_Status == 'КОтгрузке' && $s_Condition == 'ГотовКЗакрытию'):
				$s_Result = 'D';
				break;
				
			case ($s_Status == 'Закрыт' && $s_Condition == 'Закрыт'):
				$s_Result = 'D';
				break;

				
			default:

				# Если у всех товаров в поле отменено стоит true то статус заказа считаем "Отменен", если хотя бы у одного товара стоит false то считаем что заказ "Доставлен"   
				$ar_ProductStatus = array();
				foreach ($ar_Params['Товары'] as $ar_Value)
				{
					if($ar_Value['Отменено'] == 'false')
						$s_Result = 'D';
				}	
				
				if(!$s_Result)
					$s_Result = 'F';
				
				break;
		}
		
		return $s_Result;
		
		/*
		Название статусов на сайте            || Соответвие статусов в 1с
		=============================================================================================
		N 1. Принят, ожидает подтверждения    || НеСогласован  Ожидается согласование
		P 2. Подтвержден, ожидает оплаты      || КОбеспечению  ОжидаетсяПредоплатаДоОтгрузки
		O 3. Оплачен, ожидает отправки        || КОбеспечению  ГотовКОтгрузке
		L 4. Отправлен, ожидается доставка    || КОтгрузке     ОжидаетсяОтгрузка
		D 5. Доставлен 					      || Закрыт (как то идентифицировать что заказ не отменен)
		F 6. Отменен 						  || Закрыт (как то идентифицировать что заказ отменен)
		*/
		
		
		
		
		
		
		/*
		 case ($s_Status == 'НеСогласован' && $s_Condition == 'Ожидается согласование'):
		$s_Result = 'N';
		break;
			
		case ($s_Status == 'КОбеспечению' && $s_Condition == 'ОжидаетсяПредоплатаДоОтгрузки'):
		$s_Result = 'P';
		break;
		
		case ($s_Status == 'КОбеспечению' && $s_Condition == 'ГотовКОтгрузке'):
		$s_Result = 'O';
		break;
		
		case ($s_Status == 'КОтгрузке' && $s_Condition == 'ОжидаетсяОтгрузка'):
		$s_Result = 'L';
		break;
		
		case ($s_Status == 'КОтгрузке' && $s_Condition == 'ОжидаетсяОтгрузка'):
		$s_Result = 'S';
		break;
		*/
		
	}


	public static function getDeliveryParams($ar_Params)
	{
		$rs_Element = CIBlockElement::GetList(
			array(), 
			array('IBLOCK_ID' => 38, 'PROPERTY_LINK' => $ar_Params['Реквизиты']['kz_ТоварДоставлен']), 
			false, 
			false, 
			array(
				'ID', 
				'NAME', 
				'PROPERTY_FULL_NAME', 
				'PROPERTY_LINK_SITE_TNN', 
				'PROPERTY_DESCRIPTION', 
				'PROPERTY_SITE_NAME',
				'PROPERTY_LINK'	 
			)
		);
		if($ar_Element = $rs_Element->GetNext(true, false))
		{
			foreach ($ar_Params['Товары'] as $ar_Value)
			{
				if($ar_Value['Номенклатура'] == $ar_Params['Реквизиты']['kz_ТоварДоставлен'])
				{
					$ar_Element['DELIVERY_PRODUCT'] = $ar_Value;
				}	
			}	

			return $ar_Element;
		}		
		else
			return false;
	}
	
	
	

	
	
	
	
	
	
	
}