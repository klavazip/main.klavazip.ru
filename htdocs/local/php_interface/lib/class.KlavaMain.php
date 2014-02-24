<? 
class KlavaMain
{
	const BANK_INFO_IBLOCK_ID = 31; 

	const RUB = 'руб.';
	
	
	/**
	 * Конвертирует формат даты из 04.11.2008 17:45:27 в 04 Декабря, 2008
	 * @param string $s_Date - дата в формате 04.11.2008 17:45:27
	 * @param bool $b_ShowTime - если выставить в true то после месяца будет показано время в формате HH:MM
	 * @return string
	 */
	public static function formadDateMes($s_Date, $b_ShowTime = false)
	{
		$MES 		= array("01" => "января", "02" => "февраля", "03" => "марта", "04" => "апреля", "05" => "мая", "06" => "июня",	"07" => "июля", "08" => "августа", "09" => "сентября", "10" => "октября", "11" => "ноября", "12" => "декабря");
		$arALLData 	= explode(" ", $s_Date);
		$arData 	= explode(".", $arALLData[0]);
		$arDataTime = explode(":", $arALLData[1]);
		 
		$d = ($arData[0] < 10) ? substr($arData[0], 1) : $arData[0];
		 
		$s_Month = ( $b_ShowTime ) ? $MES[$arData[1]].' '.$arDataTime[0].':'.$arDataTime[1] : $MES[$arData[1]];
		 
		return $d." ".$s_Month.", ".$arData[2];
	}
	
	
	public static function isBasketEmpty()
	{
		$rs_Basket = CSaleBasket::GetList(
			array(), 
			array(
				'FUSER_ID' 	=> CSaleBasket::GetBasketUserID(), 
				'LID' 		=> SITE_ID, 
				'ORDER_ID' 	=> 'NULL', 
				'MODULE' 	=> 'catalog'
			), 
			false, 
			false, 
			array("ID")
		);
		
		return ($rs_Basket->SelectedRowsCount() > 0);
	}


	/**
	 * Возвращает все данные текущего пользователя, можно выбрать 1 параметр по ключу, можно указать ID другого пользователя
	 * @param string $s_ParamKey - Ключ поля CUser
	 * @param intval $i_UserID - ID пользователя данные которогонужно выбрать
	 * @return mixed
	 */
	public static function getUserParams($s_ParamKey = false, $i_UserID = false)
	{
		global $USER;
		if( ! $i_UserID && ! $USER->IsAuthorized() )
			return; 	
	
		$ar_User = CUser::GetByID( ( intval($i_UserID) > 0 ) ? intval($i_UserID) : $USER->GetID() )->Fetch();
		return (!$s_ParamKey) ? $ar_User : $ar_User[$s_ParamKey]; 
	}
}