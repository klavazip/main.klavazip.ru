<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?


$ar_PropertyCode = array(
		"diagonal",
		"data_code",
		"emkost",
		"kollichesto_jacheek",
		"naprjazhenie",
		"resolution",
		"light",
		"connector",
		"surface",
		"location_connector",
		"manufactur",
		"type_bga",
		"state_bga",
		"color",
		"keyboard",
		"volume_video",
		"type_video",
		"frequency",
		"with_memory",
		"tip_chekhla",
		"dlya_chego",
		"material",
		"OBEM_OPERATIVNOY_PAMYATI",
		"OBEM_VSTROENNOY_PAMYATI",
		"PROTSESSOR",
		"FRONTALNAYA_KAMERA",
		"FOTOKAMERA",
		"TIP_SIM_KARTY",
		"STANDART_SVYAZI",
		"MODEL_VIDEOADAPTERA",
		"TIP_ZU",
		"SILA_TOKA",
		"MODEL_TELEFONA_ILI_PLANSHETA",
		"OPERATSIONNAYA_SISTEMA",
		"KOLICHESTVO_YADER",
		"PODDERZHKA_KARTY_PAMYATI",
		"RAZEM",
		"VID_NAUSHNIKOV",
		"PRODOLZHITELNOST_RABOTY",
		"DLINA_PROVODA",
		"RAZYEM_NAUSHNIKOV",
		"PITANIE",
		"TIP_NAUSHNIKOV",
		"KOLICHESTVO_IZLUCHATELEY",
		"SVETOVOY_POTOK_LYUMEN",
		"DIAPAZON_VOSPROIZVODIMYKH_CHASTOT",
		"IMPEDANS",
		"FORMA_RAZEMA_NAUSHNIKOV",
		"TIP_KREPLENIYA",
		"POZOLOCHENNYE_RAZEMY",
		"OSOBENNOSTI",
		"PODKLYUCHENIE",
		"CHUVSTVITELNOST",
		"ZVUK",
		"MOSHCHNOST_KOLONOK",
		"OTNOSHENIE_SIGNAL_SHUM",
		"VKHODY",
		"FM_TYUNER",
		"TIP_MAGNITOLY",
		"KOLICHESTVO_RADIOSTANTSIY",
		"LINEYNYY_VKHOD",
		"VYKHOD_NA_NAUSHNIKI",
		"INTERFEYS_USB",
		"CHASY",
		"TIP_NAVIGATORA",
		"OBLAST_PRIMENENIYA",
		"PODDERZHKA_GLONASS",
		"PODDERZHKA_WAAS",
		"TIP_ANTENNY",
		"KONSTRUKTSIYA_VIDEOREGISTRATORA",
		"KOLICHESTVO_KANALOV_ZAPISI_VIDEO_ZVUKA",
		"PODDERZHKA_HD",
		"ZAPIS_VIDEO",
		"REZHIM_ZAPISI",
		"FUNKTSII",
		"UGOL_OBZORA",
		"NOCHNOY_REZHIM",
		"REZHIM_FOTOSEMKI",
		"DLITELNOST_ROLIKA",
		"REZHIMY_ZAPISI_VIDEO",
		"VIDEOKODEK",
		"VYKHODY",
		"PODKLYUCHENIE_K_KOMPYUTERU_PO_USB",
		"DLINA",
		"MATERIAL_OPLETKI",
		"DIAPAZON_K",
		"DIAPAZON_KA",
		"DIAPAZON_KU",
		"DIAPAZON_X",
		"DETEKTOR_LAZERNOGO_IZLUCHENIYA",
		"PODDERZHKA_REZHIMOV",
		"PRIEMNIK_SIGNALA_RADIOKANAL",
		"REZHIM_GOROD",
		"REZHIM_TRASSA",
		"OBNARUZHENIE_RADARA_TIPA_STRELKA",
		"ZASHCHITA_OT_OBNARUZHENIYA",
		"PAMYAT_NASTROEK",
		"OTOBRAZHENIE_INFORMATSII",
		"REGULIROVKA_YARKOSTI",
		"REGULIROVKA_GROMKOSTI",
		"OTKLYUCHENIE_ZVUKA",
		"TIP_DISPLEYA",
		"PODSVETKA",
		"PODDERZHIVAEMYE_FORMATY_TEKSTOVYE",
		"PODDERZHIVAEMYE_FORMATY_GRAFICHESKIE",
		"PODDERZHIVAEMYE_FORMATY_ZVUKOVYE",
		"PODDERZHIVAEMYE_FORMATY_DRUGIE",
		"ZASHCHITA_V_KOMPLEKTE",
);



$i_OrderID = intval($_GET['id']);

if($i_OrderID == 0)
	$arResult['ERROR'] = 'Y';
else
{
	
	$rs_Orders = CSaleOrder::GetList(
		array(), 
		array(
			'USER_ID' 	=> $USER->GetID(),
			'ID'		=> $i_OrderID		
		), 
		false, 
		false, 
		array('ID', 'DATE_INSERT', 'PRICE', 'DELIVERY_ID', 'STATUS_ID', 'PAY_SYSTEM_ID', 'PRICE_DELIVERY')
	);
	
	if( $rs_Orders->SelectedRowsCount() == 0 )
		$arResult['ERROR_TEXT'] == 'У вас нет заказа с таким номером';
	else
	{
		if ($ar_Orders = $rs_Orders->Fetch())
		{
			$ar_Orders['DELIVERY'] 	 	 = CSaleDelivery::GetByID($ar_Orders['DELIVERY_ID']);
			$ar_Orders['PAY_SYSTEM']     = CSalePaySystem::GetByID($ar_Orders['PAY_SYSTEM_ID']);
			$ar_Orders['PRODUCT_PRICE']  = intval($ar_Orders['PRICE']) - intval($ar_Orders['PRICE_DELIVERY']);
			
			$ar_PropOrderValue = array();
			$rs_OrderProperty = CSaleOrderPropsValue::GetList(array(), array('ORDER_ID' => $ar_Orders['ID'], 'PROP_CODE' => array('CITY', 'REGION', 'STREET', 'DOM', 'RAION', 'KORPUS', 'OFFICE', 'KVARTIRA')), false, false, array('PROP_CODE', 'VALUE'));
			while ($ar_OrderProperty = $rs_OrderProperty->Fetch())
			{
				$ar_PropOrderValue[$ar_OrderProperty['PROP_CODE']] = $ar_OrderProperty['VALUE'];
			}	

			$ar_Adrees = array();
			
			$ar_Adrees[] = $ar_PropOrderValue['CITY'];
			$ar_Adrees[] = $ar_PropOrderValue['STREET'];
			
			if(strlen($ar_PropOrderValue['DOM']) > 0)
				$ar_Adrees[] = 'д. '.$ar_PropOrderValue['DOM'];

			if(strlen($ar_PropOrderValue['KORPUS']) > 0)
				$ar_Adrees[] = 'кор. '.$ar_PropOrderValue['KORPUS'];

			if(strlen($ar_PropOrderValue['KVARTIRA']) > 0)
				$ar_Adrees[] = 'кв '.$ar_PropOrderValue['KVARTIRA'];

			if(strlen($ar_PropOrderValue['OFFICE']) > 0)
				$ar_Adrees[] = 'оф. '.$ar_PropOrderValue['OFFICE'];
			
			$ar_Orders['ADRESS'] = implode(', ', $ar_Adrees);
			
			$arResult['ORDER'] 	= $ar_Orders;
			
			
			# basket orders
			$ar_ElementID = array();
			$ar_BasketField = array();
			
			$rs_Basket = CSaleBasket::GetList(array(), array('ORDER_ID' => $ar_Orders['ID']), false, false, array());
			while ($ar_Basket = $rs_Basket->Fetch())
			{
				$ar_ElementID[] = $ar_Basket['PRODUCT_ID'];
				$ar_BasketField[$ar_Basket['PRODUCT_ID']] = array(
					'COUNT' => intval($ar_Basket['QUANTITY']),
					'NAME'	=> $ar_Basket['NAME'],
					'PRICE' => intval($ar_Basket['PRICE']),
				); 
			}
			
			if(count($ar_ElementID) > 0)
			{
				$rs_Element = CIBlockElement::GetList(array(), array('IBLOCK_ID' => KlavaCatalog::IBLOCK_ID, 'ID' => $ar_ElementID));
				while($ob_Element = $rs_Element->GetNextElement())
				{
					$ar_Fileds = $ob_Element->GetFields();
					$ar_Properties = $ob_Element->GetProperties();
					
					$ar_PropertyValue = array();
					foreach ($ar_Properties as $s_Code => $ar_Value)
					{
						if( in_array($s_Code, $ar_PropertyCode) && strlen($ar_Value['VALUE']) > 0)
							$ar_PropertyValue[] = $ar_Value['VALUE'];
					}
					
					$ar_File = CFile::ResizeImageGet($ar_Fileds['PREVIEW_PICTURE'], array('width' => 78, 'height' => 44), BX_RESIZE_IMAGE_PROPORTIONAL, true);
					
					$ar_BasketField[$ar_Fileds['ID']]['ID'] = $ar_Fileds['ID'];
					$ar_BasketField[$ar_Fileds['ID']]['IMG'] = (strlen($ar_File['src']) > 0) ? $ar_File['src'] : '/bitrix/templates/klavazip.main/img/no-pic-big.jpg';
					$ar_BasketField[$ar_Fileds['ID']]['PROPERTY'] = implode(' / ', $ar_PropertyValue);
					$ar_BasketField[$ar_Fileds['ID']]['ARTICUL'] = $ar_Properties['CML2_ARTICLE']['VALUE'];
					$ar_BasketField[$ar_Fileds['ID']]['DETAIL_PAGE_URL'] = $ar_Fileds['DETAIL_PAGE_URL'];
				}
			}

			$arResult['PRODUCT'] = $ar_BasketField;
		}    
	}
}



if( $_SERVER['REQUEST_METHOD'] == 'POST')
{
	$ar_Count = $_POST['COUNT'];
	
	if(count($_POST['SELECT_ELEMENT_ID']) > 0)
		$ar_ElementID = $_POST['SELECT_ELEMENT_ID'];
	else
		$ar_ElementID = $_POST['ELEMENT_ID'];
		
	
	if(count($ar_ElementID) > 0)
	{
		foreach ($ar_ElementID as $i_ElementID)
		{
			Add2BasketByProductID( $i_ElementID, $ar_Count[$i_ElementID] );
		}
		
		LocalRedirect('/personal/basket/');
	}
}

if(isset($_GET['status']))
{
	$s_StatusCode = strtoupper($_GET['status']);
	if(in_array($s_StatusCode, array('X')) && $i_OrderID > 0)
	{
		CSaleOrder::Update($i_OrderID, array('STATUS_ID' => $s_StatusCode, 'CANCELED' => 'Y'));
		LocalRedirect($APPLICATION->GetCurPageParam("", array('status')));
	}	
}	



$APPLICATION->AddChainItem("История заказов", "/cabinet/");
$APPLICATION->SetTitle("Заказ №".$i_OrderID);


$this->IncludeComponentTemplate();