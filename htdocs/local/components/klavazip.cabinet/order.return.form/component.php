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



$i_OrderID = intval($_GET['order_id']);
$ar_SelectProductID = $_GET['id'];


if($i_OrderID == 0 && ! isset($_GET['result_id']))
	LocalRedirect('/cabinet/');
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
		array('ID', 'DATE_INSERT', /* 'PRICE', 'DELIVERY_ID', 'STATUS_ID', 'PAY_SYSTEM_ID', 'PRICE_DELIVERY'*/)
	);
	
	if( $rs_Orders->SelectedRowsCount() == 0 )
		$arResult['ERROR_TEXT'] == 'У вас нет заказа с таким номером';
	else
	{
		if ($ar_Orders = $rs_Orders->Fetch())
		{
			$arResult['ORDER'] 	= $ar_Orders;
			
			
			# basket orders
			$ar_ElementID = array();
			$ar_BasketField = array();
			$rs_Basket = CSaleBasket::GetList(array(), array('ORDER_ID' => $ar_Orders['ID']), false, false, array());
			while ($ar_Basket = $rs_Basket->Fetch())
			{
				if( count($ar_SelectProductID) > 0 && in_array($ar_Basket['PRODUCT_ID'], $ar_SelectProductID) )
				{
					$ar_ElementID[] = $ar_Basket['PRODUCT_ID'];
					$ar_BasketField[$ar_Basket['PRODUCT_ID']] = array(
						'COUNT' => intval($ar_Basket['QUANTITY']),
						'NAME'	=> $ar_Basket['NAME'],
						'PRICE' => intval($ar_Basket['PRICE']),
					);
				}
				else
				{
					$ar_ElementID[] = $ar_Basket['PRODUCT_ID'];
					$ar_BasketField[$ar_Basket['PRODUCT_ID']] = array(
						'COUNT' => intval($ar_Basket['QUANTITY']),
						'NAME'	=> $ar_Basket['NAME'],
						'PRICE' => intval($ar_Basket['PRICE']),
					); 
				}
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
			
			
			$arResult['PRICE_PRODUCT_SUMM'] = 0;
			foreach ($ar_BasketField as $ar_ValueFiled)
			{
				$arResult['PRICE_PRODUCT_SUMM'] += $ar_ValueFiled['PRICE'] * $ar_ValueFiled['COUNT']; 
			}	
		}    
	}
}




if( $_SERVER['REQUEST_METHOD'] == 'POST')
{
	$s_TextReturn = trim($_POST['TEXT_RETURN']);
	if(strlen($s_TextReturn) == 0)
		$arResult['ERROR'][] = 'Пожалуйста напишите причину возврата товара';
	else
	{
		$i_CountFile = count($_FILES['FILE']);
		if($i_CountFile > 0 )
		{
			$ar_FileResult = array();
			for( $i = 0; $i < $i_CountFile; $i++ )
			{
				$ar_FileResult[] = array(
					'name' 		=> $_FILES['FILE']['name'][$i],
					'type' 		=> $_FILES['FILE']['type'][$i],
					'tmp_name' 	=> $_FILES['FILE']['tmp_name'][$i],
					'error' 	=> $_FILES['FILE']['error'][$i],
					'size' 		=> $_FILES['FILE']['size'][$i],
				);
			}
			
			$ar_FileError = array();
			foreach ($ar_FileResult as $ar_File)
			{
				$rs = CFile::CheckImageFile($ar_File, 15728640, 0, 0, 'IMAGE');
				if(strlen($rs) > 0)
					$ar_Error[] = 'Ошибка добавления файла '.$ar_File['name'];
			}
			
			
			if($ar_Error == 0)
			{
				foreach ($_FILES['FILE'] as $ar)
				{
					if(strlen($ar['name']) > 0)
						$ar_foto[] = $ar;
				}
			
				$ar_FotoResult = array();
				for ($i = 0; $i < 3; $i++)
				{
					$_ar_foto = array();
					
					$_ar_foto['name'] 		= $_FILES['FILE']['name'][$i];
					$_ar_foto['type'] 		= $_FILES['FILE']['type'][$i];
					$_ar_foto['tmp_name'] 	= $_FILES['FILE']['tmp_name'][$i];
					$_ar_foto['error'] 		= $_FILES['FILE']['error'][$i];
					$_ar_foto['size'] 		= $_FILES['FILE']['size'][$i];
					$_ar_foto['MODULE_ID'] 	= 'iblock';
					$ar_FotoResult['n'.$i] 		= $_ar_foto;
				}
			}
			else
			{
				$arResult['ERROR'] = $ar_Error;
			}
		}
		
		if( count($arResult['ERROR']) == 0 )
		{
			$ob_Element = new CIBlockElement;
			$ar_Field = array(
				'IBLOCK_ID'      => KlavaCabinet::RETURN_ORDER_IBLOCK_ID,
				'PROPERTY_VALUES'=> array(
					'USER_ID' 		=> $USER->GetID(),
					'ELEMENT_ID' 	=> $ar_ElementID,
					'ORDER_ID'		=> $i_OrderID,
					'FOTO'			=> $ar_FotoResult					
				),
				'NAME'           => $USER->GetLogin(),
				'PREVIEW_TEXT'   => htmlspecialchars($s_TextReturn)
			);
			
			if($i_NewElementID = $ob_Element->Add($ar_Field))
			{
				LocalRedirect('/cabinet/order-return/form/?result_id='.$i_NewElementID);
			}
		}
	}
}

$APPLICATION->AddChainItem("История заказов", "/cabinet/");
$APPLICATION->SetTitle("Заявка на возврат товаров из заказа № ".$i_OrderID);

$this->IncludeComponentTemplate();