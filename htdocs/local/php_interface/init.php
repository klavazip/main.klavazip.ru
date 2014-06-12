<?
CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");
CModule::IncludeModule("sale");
CModule::IncludeModule("highloadblock");
include_once($_SERVER['DOCUMENT_ROOT'].'/local/php_interface/lib.include.php');

# Обработчики и функционал работы по обмену данными между сайтом и 1с
include_once($_SERVER['DOCUMENT_ROOT'].'/system/sale.1c.integration/include.init.php');

 
# Этот код делает 301 редирект. который задается парами ссылок в инфоблоке
	use Bitrix\Highloadblock as HL;
	use Bitrix\Main\Entity;
	
	$entity = HL\HighloadBlockTable::compileEntity(HL\HighloadBlockTable::getById(1)->fetch());
	$entity_ConfigMenius = $entity->getDataClass();
	$rs_Data = $entity_ConfigMenius::getList(array("filter" => array('UF_OLD_LINK' => $APPLICATION->GetCurDir())));
	
	if($rs_Data->GetSelectedRowsCount() > 0)
	{
		$ar_Data = $rs_Data->Fetch();
		LocalRedirect($ar_Data['UF_NEW_LINK'], false, '301 Moved Permanently');
	}
# END 301 REDIRECT


function arraytofile($array, $filename = 0, $arrname, $file = 0)
{
	$level = 1;
	if($file == 0)
	{
		$level = 0;
		$file = fopen($filename, "a");
		if(!$file)
			return false;
		
		fwrite($file, date("d.m.Y / H:i:s")." <" . "?\n\$".$arrname." = ");
	}

	$cnt = count($array);
	$i = 0;
	fwrite($file, "\narray(\n");
	foreach($array as $key => $value)
	{
		if($i++ != 0)
		{
			fwrite($file, ",\n");
		}
		
		if(is_array($array[$key]))
		{
			fwrite($file, "'$key' => ");
			arraytofile($array[$key], 0,"_array", $file);
		}
		else
		{
			$value = addcslashes($value, "'"."\\\\");
			fwrite($file, str_repeat(' ', ($level + 1) * 2) . "'$key' => '$value'");
		}
	}
	
	fwrite($file, ")");

	if($level == 0)
	{
		fwrite($file, ";\n?".">");
		fclose($file);
		return true;
	}
}

// ==  НОВЫЙ ФОРМАТ ЗАПИСЕЙ ==
// необходимо пояснять каждую операцию, для возможности совместной работы
// нескольких разработчиков
// BXMaker 

/**
 * После обновления проверяется наличие у элемента свойства с перечислением аналогов,
 * если оно есть, то по аждому аналоу скидываем кэш, чтобы отображалось
 * актуальное количество товара - аналога
 * 8 - основной каталог товаров
 * */

 //Нет, нет и нет - это можно объединить с тем местом, где мы пишем товару аналоги. Бедросова
//@include_once 'include/bxmaker/catalog.analogi.php';

/**
 * После обновления каталога, пройдемся по всем товарам, хоят это
 * и ресурсоемкая процедура,надо запистаь максимальную и минимальную 
 * цены в свойства раздела, для того чтобы использовать 
 * фильтр по цене в виде ползунка с ограницениями по цене
 * 8 - основной каталог товаров 
 * _____________________________________________________________________
 * Переписала Не ресурсоемко. Бедросова.
 */
 // запишем какие свойства можно показывать в фильтре каталога 6*_
$GLOBALS['______BXMAKER_GLOBAL_PARAMS_CATALOG_FILTER_SHOW_PROPERTY'][8] = array(
     "data_code","diagonal", "type_video","color", "with_memory", "naprjazhenie",
    "kollichesto_jacheek", "emkost", "keyboard", "volume_video", "NALICHIE_BITOGO_PIKSELYA",
    "type_bga", "state_bga", "connector", "surface", "light", "resolution", "manufactur", 
    "location_connector","tip_chekhla","dlya_chego","material","OBEM_OPERATIVNOY_PAMYATI","OBEM_VSTROENNOY_PAMYATI",
"PROTSESSOR","FRONTALNAYA_KAMERA","FOTOKAMERA","TIP_SIM_KARTY","STANDART_SVYAZI","MODEL_VIDEOADAPTERA","TIP_ZU",
"SILA_TOKA","MODEL_TELEFONA_ILI_PLANSHETA","OPERATSIONNAYA_SISTEMA",

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
//@require_once 'include/bxmaker/catalog.set_diapasone_price.php'; Переписано на корню 


// ведем логи  работы с платежной системой деньги онлайн модуля BXmaker
define('BXMAKER_DENGIONLINE_LOG',TRUE);


// ############################################## 

// извещение о поступлении товара в каталог
include_once 'include/handlers/notice_available_good.php';
//подписка
include_once 'include/handlers/subscribe.php';
include_once 'include/handlers/main.php';
//автоподписка gпосле регистрации
include_once 'include/handlers/user_subscribe.php';
// антиспам в формах
include_once 'include/handlers/web_form_untispam.php';
// отзывы - извещение по почте для менеджеров
require_once 'include/handlers/customer_reviews.php';


/// правим все url адреса
AddEventHandler("main", "OnEndBufferContent", "RestoreUrlChars", 10000);
function RestoreUrlChars($html)
{
    $html = str_ireplace("%"."2f", "/", $html);
}




function ec($str, $die=false, $skip_check=false) {
	if ($GLOBALS['USER']->IsAdmin() || $skip_check) {
		if (is_array($str)) {
			echo '<pre>';print_r($str);echo '</pre>';
		} else {
			echo $str;
		}
		if ($die !== false) {
			echo $die;
			die();
		}
	}
}

/*
Товарные предложения мы пока не используем - смысла в этом бреде нет, а если начнем использовать - этот бред надо переписать по-другому. Бедросова
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", "BXIBlockAfterSave");
AddEventHandler("iblock", "OnAfterIBlockElementAdd", "BXIBlockAfterSave");
AddEventHandler("catalog", "OnPriceAdd", "BXIBlockAfterSave");
AddEventHandler("catalog", "OnPriceUpdate", "BXIBlockAfterSave");
function BXIBlockAfterSave($arg1, $arg2 = false)
{
    $ELEMENT_ID = false;
	$IBLOCK_ID = false;
	$OFFERS_IBLOCK_ID = false;
	$OFFERS_PROPERTY_ID = false;

	//Check for catalog event
	if(is_array($arg2) && $arg2["PRODUCT_ID"] > 0)
	{
		//Get iblock element
		$rsPriceElement = CIBlockElement::GetList(
			array(),
			array(
				"ID" => $arg2["PRODUCT_ID"],
			),
			false,
			false,
			array("ID", "IBLOCK_ID")
		);
		if($arPriceElement = $rsPriceElement->Fetch())
		{
			$arCatalog = CCatalog::GetByID($arPriceElement["IBLOCK_ID"]);
			if(is_array($arCatalog))
			{
				//Check if it is offers iblock
				if($arCatalog["OFFERS"] == "Y")
				{
					//Find product element
					$rsElement = CIBlockElement::GetProperty(
						$arPriceElement["IBLOCK_ID"],
						$arPriceElement["ID"],
						"sort",
						"asc",
						array("ID" => $arCatalog["SKU_PROPERTY_ID"])
					);
					$arElement = $rsElement->Fetch();
					if($arElement && $arElement["VALUE"] > 0)
					{
						$ELEMENT_ID = $arElement["VALUE"];
						$IBLOCK_ID = $arCatalog["PRODUCT_IBLOCK_ID"];
						$OFFERS_IBLOCK_ID = $arCatalog["IBLOCK_ID"];
						$OFFERS_PROPERTY_ID = $arCatalog["SKU_PROPERTY_ID"];
					}
				}
				//or iblock wich has offers
				elseif($arCatalog["OFFERS_IBLOCK_ID"] > 0)
				{
					$ELEMENT_ID = $arPriceElement["ID"];
					$IBLOCK_ID = $arPriceElement["IBLOCK_ID"];
					$OFFERS_IBLOCK_ID = $arCatalog["OFFERS_IBLOCK_ID"];
					$OFFERS_PROPERTY_ID = $arCatalog["OFFERS_PROPERTY_ID"];
				}
				//or it's regular catalog
				else
				{
					$ELEMENT_ID = $arPriceElement["ID"];
					$IBLOCK_ID = $arPriceElement["IBLOCK_ID"];
					$OFFERS_IBLOCK_ID = false;
					$OFFERS_PROPERTY_ID = false;
				}
			}
		}
	}
	//Check for iblock event
	elseif(is_array($arg1) && $arg1["ID"] > 0 && $arg1["IBLOCK_ID"] > 0)
	{
		//Check if iblock has offers
		$arOffers = CIBlockPriceTools::GetOffersIBlock($arg1["IBLOCK_ID"]);
		if(is_array($arOffers))
		{
			$ELEMENT_ID = $arg1["ID"];
			$IBLOCK_ID = $arg1["IBLOCK_ID"];
			$OFFERS_IBLOCK_ID = $arOffers["OFFERS_IBLOCK_ID"];
			$OFFERS_PROPERTY_ID = $arOffers["OFFERS_PROPERTY_ID"];
		}
	}

	if($ELEMENT_ID)
	{
		static $arPropCache = array();
		if(!array_key_exists($IBLOCK_ID, $arPropCache))
		{
			//Check for MINIMAL_PRICE property
			$rsProperty = CIBlockProperty::GetByID("MINIMUM_PRICE", $IBLOCK_ID);
			$arProperty = $rsProperty->Fetch();
			if($arProperty)
				$arPropCache[$IBLOCK_ID] = $arProperty["ID"];
			else
				$arPropCache[$IBLOCK_ID] = false;
		}

		if($arPropCache[$IBLOCK_ID])
		{
			//Compose elements filter
			$arProductID = array($ELEMENT_ID);
			if($OFFERS_IBLOCK_ID)
			{
				$rsOffers = CIBlockElement::GetList(
					array(),
					array(
						"IBLOCK_ID" => $OFFERS_IBLOCK_ID,
						"PROPERTY_".$OFFERS_PROPERTY_ID => $ELEMENT_ID,
					),
					false,
					false,
					array("ID")
				);
				while($arOffer = $rsOffers->Fetch())
					$arProductID[] = $arOffer["ID"];
			}

			$minPrice = false;
			$maxPrice = false;
			//Get prices
			$rsPrices = CPrice::GetList(
				array(),
				array(
					"BASE" => "Y",
					"PRODUCT_ID" => $arProductID,
				)
			);
			while($arPrice = $rsPrices->Fetch())
			{
				$PRICE = $arPrice["PRICE"];

				if($minPrice === false || $minPrice > $PRICE)
					$minPrice = $PRICE;

				if($maxPrice === false || $maxPrice < $PRICE)
					$maxPrice = $PRICE;
			}

			//Save found minimal price into property
			if($minPrice !== false)
			{
				CIBlockElement::SetPropertyValuesEx(
					$ELEMENT_ID,
					$IBLOCK_ID,
					array(
						"MINIMUM_PRICE" => $minPrice,
						"MAXIMUM_PRICE" => $maxPrice,
					)
				);
			}
		}
	}
}*/


////////////////////////////////////////////////////////////////////
class CSubsections
{
   function GetCode($iblock_id, $section_id, $element_name="")
   {
      $arPath = array();

      if(CModule::IncludeModule("iblock"))
      {
         if($element_name)
         {
            $element_name = CUtil::translit($element_name, LANGUAGE_ID, array("replace_space"=>"-","replace_other"=>"-"));
            $element_name = $element_name?  "/".$element_name:  "";
         }

         if($rs = CIBlockSection::GetNavChain($iblock_id, $section_id))
         {
            while($ar = $rs->Fetch())
            {
               $arPath[] = CUtil::translit($ar["NAME"], LANGUAGE_ID, array("replace_space"=>"-","replace_other"=>"-"));
            }
         }
      }

      return trim(implode("/", $arPath).$element_name, "/");
   }

 function ElementAddHandler($arFields)
   {
      if($arFields["IBLOCK_ID"] == 8)
      {
         

		 
		 $oElement = new CIBlockElement();
		 $arFields["CODE"]="recalculate";
         $oElement->Update($arFields["ID"], /*array("CODE" => "recalculate")*/ $arFields);
		

      }
   }

function ElementUpdateHandler($arFields)//Эта готова
   {
      
	 arraytofile($arFields,$_SERVER['DOCUMENT_ROOT']."/upload/logupdate.txt", "arFields"); 
	  
	  
	  if($arFields["IBLOCK_ID"] == '8' || $arFields["IBLOCK_ID"] == '23')
      {

		 if($rs = CIBlockElement::GetByID($arFields["ID"]))
         {
            if($ar = $rs->Fetch())
            {

				$arFields["CODE"] = CSubsections::GetCode(
                  $arFields["IBLOCK_ID"],
                 // $ar["IBLOCK_SECTION_ID"],
				 $arFields['IBLOCK_SECTION'][0],
                  $ar["NAME"]
               );

			    //Вот тут и проверим только что полученный код на уникальность. Бедросова	
				// проверим наличие товаров с таким же кодом, в случае если найдены товары  одни и теже но разных цветов например
				// и допишем  к коду теущего, его  артикул, для уникальности
				$dbResClone = CIBlockElement::GetList(array(), array( '!ID' => $arFields['ID'] , 'CODE' => $arFields["CODE"] ), false, false, array( 'ID','IBLOCK_ID', 'CODE'));
				if($arResClone  = $dbResClone->Fetch())
				{

					 $db_props_articul = CIBlockElement::GetProperty(8, $arFields['ID'], array(), Array("CODE"=>"CML2_ARTICLE"));
							if($ar_props_articul = $db_props_articul->Fetch())
								$CML2_ARTICLE = $ar_props_articul["VALUE"];
							else $CML2_ARTICLE="";
					$arFields["CODE"]=$arFields["CODE"].$CML2_ARTICLE;

				}

            }


			 //Начало аналоги
			 /*$arP = $arFields['PROPERTY_VALUES'][92];  




		$v = array();
		foreach($arP as $key=>$val){
			if (stripos($val['DESCRIPTION'], 'Аналог')!==false){

				$v = explode(',',$val['VALUE']);
			}
		}

        // Запишем все наименования аналогов в свойство, для последующего использования при
        if(!empty($v))
        {

			//arraytofile($v,$_SERVER['DOCUMENT_ROOT']."/upload/v".$arFields['ID'].".txt", "v");

			$ibe = new CIBlockElement();
			$strNames = '';
            $i=0;
            $dbr_xml = $ibe->GetList(ARRAY('ID'=>'ASC'),array('IBLOCK_ID'=>8,'XML_ID'=>$v),false,false,array('NAME','ID'));
            while($ar = $dbr_xml->Fetch())
            {
                $strNames .= (($i++) < 1 ? ' ' : '  /  ').$ar['NAME'];

				//arraytofile($ar,$_SERVER['DOCUMENT_ROOT']."/upload/ar".$ar['ID'].".txt", "v");
				//Вот тут и сбросим кеш у аналога. Бедросова
				global $CACHE_MANAGER;
				$BXMCP_NAME = 'bxmaker.catalog.analogi';
				$CACHE_MANAGER->ClearByTag($BXMCP_NAME.'|ib_8|el_'.$ar['ID']);
            }

            // запишем в свойство товара наименования всех аналогов
            if(strlen($strNames) > 0)
            {
                    $ibe->SetPropertyValuesEx($arFields['ID'],8,array(
                    'ANALOGS_NAMES' =>$strNames
                    )
                );
            }

}*/

$dbr = CIBlockElement::GetList(ARRAY(),array('ID'=>$arFields['ID']));
    while($oe = $dbr->GetNextElement())
    {
        $arE = $oe->GetFields();
        $arP = $oe->getProperty('CML2_TRAITS');
        $k = -1;
        $v = array();

        for($i=0;$i<count($arP['DESCRIPTION']);$i++)
        {
            if (stripos($arP['DESCRIPTION'][$i], 'Аналог') !== false) {
                $k = $i;
                break;
            };
        }
        if($k > -1)
        {
            $v = explode(',',$arP['VALUE'][$k]);
        }

        // Запишем наименования // ANALOGS_NAMES
        if(!empty($v))
        {
            $strNames = '';
            $i=0;
            $dbr_xml = CIBlockElement::GetList(ARRAY('ID'=>'ASC'),array('XML_ID'=>$v),false,false,array('NAME','ID'));
            while($ar = $dbr_xml->Fetch())
            {
                $strNames .= (($i++) < 1 ? ' ' : '  /  ').$ar['NAME'];
            }

            // запишем в свойство товара наименования всех аналогов
            if(strlen($strNames) > 0)
            {
                CIBlockElement::SetPropertyValuesEx($arE['ID'],$arE['IBLOCK_ID'],array(
                    'ANALOGS_NAMES' =>$strNames
                    )
                );
            }

        }
	}

			 ///Конец аналоги



         }  



	}

   }


function SectionAddHandler($arFields)
   {
      if($arFields["IBLOCK_ID"] == 8)
      {
         $oSection = new CIBlockSection();
         $oSection->Update($arFields["ID"], array("CODE" => "recalculate"));
      }
   }


   function SectionUpdateHandler($arFields)
   {
      if($arFields["IBLOCK_ID"] == '8' || $arFields["IBLOCK_ID"] == '23')
      {



		$arFields["CODE"] = CSubsections::GetCode(
            $arFields["IBLOCK_ID"],
            $arFields["ID"]
         );



      }
   }

}


AddEventHandler("iblock", "OnAfterIBlockElementAdd", array("CSubsections", "ElementAddHandler"));
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", array("CSubsections", "ElementUpdateHandler"));
AddEventHandler("iblock", "OnAfterIBlockSectionAdd", array("CSubsections", "SectionAddHandler"));
AddEventHandler("iblock", "OnBeforeIBlockSectionUpdate", array("CSubsections", "SectionUpdateHandler"));




////////////////////////////////////////////////////////////////////

//регистрация
//AddEventHandler("main", "OnAfterUserRegister", "OnBeforeUserRegisterHandler");
/*
    function OnBeforeUserRegisterHandler(&$arFields)
    {

      if($_POST['user-type']=='fizlico') {
      	$userGroup = array(3,9);
      	$userFields = Array(
			"UF_PERSON_TYPE" => 1 ,
			"GROUP_ID"          => $userGroup
			);
      }
      else {
      	$userGroup = array(3,10);
      	$userFields = Array(
      	  "GROUP_ID"          => $userGroup,
		  "UF_PERSON_TYPE" =>2,


			);
      }


		$user = new CUser;
		$user->Update($arFields['USER_ID'], $userFields);
		if (CModule::IncludeModule("iblock")){
					$managerObj  = CIBlockElement::GetList(array(),array("BLOCK_ID"=>5),false, false,array("ID","PROPERTY_MANAGER","DETAIL_PICTURE"));
					$min_count=array();
					while($manager = $managerObj->GetNext())
					{
						$propOb= CIBlockElement::GetProperty(5,$manager["ID"],array(),array("NAME"=>"Заказчики"));
						$i=0;
						while($prop = $propOb->GetNext()){
							$i++;
						}
						$min_coun[$manager["ID"]] =$i;
					}
					asort($min_coun);
					$manid = null;
					foreach ($min_coun as $key => $value) {
						$manid = $key;
						break;
					}

					$arProp = array();
					$propOb= CIBlockElement::GetProperty(5,$manid,array(),array("NAME"=>"Заказчики"));
					while ($prop=$propOb->getNext())
					{
						array_push($arProp,$prop["VALUE"]);
					}
					array_push($arProp,$arFields['USER_ID']);
					CIBlockElement::SetPropertyValuesEx($manid, false, array("customer" => $arProp));

				}
    }
    */

function getPricesByItemId($id)
{
    CModule::IncludeModule("catalog");

    $prices=array();

	$db_res = CPrice::GetList(array(), array( "PRODUCT_ID" => $id));

    while($arPrice = $db_res->Fetch())
		$prices[$arPrice["CATALOG_GROUP_NAME"]]=number_format($arPrice["PRICE"],0,"","");

    $prices[0]=isset($prices["Розничная цена"]) ? $prices["Розничная цена"]:0;



	$prices[1]=isset($prices["Цена при покупке свыше 10 тыс руб"]) ? $prices["Цена при покупке свыше 10 тыс руб"]:0;
	$prices[2]=isset($prices["Цена при покупке свыше 50 тыс руб"]) ? $prices["Цена при покупке свыше 50 тыс руб"]:0;
	return $prices;
}





error_reporting(0);

global $mailAppendix;
$mailAppendix='<div><div><p>&nbsp;</p><p>&nbsp;</p><p><img src="http://klavazip.ru/image001.png" alt="Описание:" width="103px" height="123px" align="left" hspace="12"><span style="color:#1f497d;">Тел: +7&nbsp;499&nbsp;<span><span class="wmi-callto">346 37 01</span></span> (офис)</span></p><p><span style="color:#1f497d;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; +7 917 493 54 44 (моб.)</span></p><p><span style="color:#1f497d;" lang="EN-US">ICQ</span><span style="color:#1f497d;">: <span><span class="wmi-callto">643286189</span></span></span></p><p><span style="color:#1f497d;" lang="EN-US">Email</span><span style="color:#1f497d;">: </span><span style="color:#1f497d;" lang="EN-US"><a href="mailto:alik@klavazip.ru" class="daria-action" data-action="common.go" data-params="new_window&amp;url=#compose/mailto=alik@klavazip.ru"><span style="color:blue;">alik</span><span style="color:blue;" lang="RU">@</span><span style="color:blue;">klavazip</span><span style="color:blue;" lang="RU">.</span><span style="color:blue;">ru</span></a></span></p><p><span style="color:#1f497d;" lang="EN-US">Skype: klavazip</span></p><p><span style="color:#1f497d;">Сайт</span><span style="color:#1f497d;" lang="EN-US">: <a href="http://www.klavazip.ru/" class=" daria-goto-anchor" target="_blank"><span style="color:blue;">www.klavazip.ru</span></a></span></p><p><span style="color:#1f497d;">Все детали в одном месте </span></p><p><span>&nbsp;</span></p><p><span>&nbsp;</span></p><p><span>Здравствуйте! В продолжении разговора отраправляю прайс лист. </span></p><p><span>Наш интернет-магазин поставляет запасные части для ноутбуков. Головной офис и основной склад находятся в Москве. </span></p><p><span>&nbsp;</span></p><p><span>Доставка осуществляется с Московского склада, следующими способами:</span></p><p><span> 1.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;ТК «Байкал-Сервис», срок доставки: на следущие сутки, стоимость: 400 руб.; </span></p><p><span>&nbsp;2.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Почта России, срок доставки: 5-10 дней, стоимость: 250 руб. (без наложенного платежа), 400 руб. (с наложенным платежом); </span></p><p><span>&nbsp;3.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span><span lang="EN-US">EMS </span><span>срок доставки: от 2-4 дней стоимость: 1000р</span></p><p><span>&nbsp;</span></p><p><span>Оплата возможна</span></p><ol style="margin-top:0cm;" type="1" start="1"><li><span>На сайте, любым способом <a href="http://www.w1.ru/map/input/" class=" daria-goto-anchor" target="_blank"><span style="color:blue;">http://www.w1.ru/map/input/</span></a></span></li><li><span>Переводом на счет физ лица.</span></li><li><span>На расчетный счет организации.</span></li><li><span>Наложенным платежем.</span></li></ol><p style="margin-left:36pt;"><span>&nbsp;</span></p><p style="margin-left:36pt;"><span>Если у вас возникли вопросы, пишите , звоните, все контакты в подписи, буду рад вам помочь.</span></p><p><span><img alt="Описание:" width="643" height="4" border="0"></span></p><p><span>С уважением,</span></p><p><span>Галин Алик</span></p><p><span>Специалист группы продаж </span></p><p>&nbsp;</p></div></div>';


if(function_exists('i')===false) { function i($v) { ?><pre><?;var_dump($v);?></pre><?; } } // отладочная функция
if(function_exists('ir')===false&&function_exists('i')!==false) { function ir($v) { ob_start(); i($v); $r = ob_get_contents(); ob_end_clean(); return $r; } }


AddEventHandler('main', 'OnBeforeEventSend', "ChangeMailByPersonalManager");
function ChangeMailByPersonalManager(&$arFields, &$arFieldsMail)
{
	//подмена письма после заказа
	if($arFieldsMail['ID']=='33')
	{
		$url='http://'.$_SERVER['HTTP_HOST'].'/bitrix/templates/klavazip/ajax/order.php?oid='.$arFields["ORDER_ID"];
		$basketi = file_get_contents($url);
		$arFieldsMail["MESSAGE"] = str_replace(' #SITE_NAME#',(' #SITE_NAME#'.'<br/><br/>'.$basketi.'<br/>'),$arFieldsMail["MESSAGE"]);
	}
	elseif($arFieldsMail['ID']=='25')
	{
		$order=CSaleOrder::GetByID($arFields["ORDER_ID"]);
		$arFields["PRICE"]=number_format(($order["PRICE"]),0,""," ").' руб.';
		$delAr=CSaleDelivery::GetByID($order["DELIVERY_ID"]);
		//$arFields["PRICE_DELIVERY"]=$delAr["PRICE"];
		$arFieldsMail["MESSAGE"] = str_replace('#PRICE_DELIVERY#',(number_format($delAr["PRICE"],0,""," ").' руб.'),$arFieldsMail["MESSAGE"]);
		//mail('zden12@mail.ru','OnBeforeEventSend arFields 25',ir($arFieldsMail["MESSAGE"]));
		//#PRICE_DELIVERY#
	}

}

//basket clean
function basketVerify() {
    return true;
	error_reporting(1);
	CModule::IncludeModule('iblock');
	CModule::IncludeModule('catalog');
	CModule::IncludeModule('sale');
	$arSelect=array("ID","PRODUCT_ID",'NAME');
	$dbi = CSaleBasket::GetList( array(), array( "FUSER_ID" => CSaleBasket::GetBasketUserID(), "LID" => SITE_ID, "DELAY" => "N" ), false, false,$arSelect);
	while($bi=$dbi->Fetch())
	{
	  $rs = CIBlockElement::GetList(array(),array('ID'=>$bi['PRODUCT_ID']),false,false,array('NAME'));
	  $count=$rs->SelectedRowsCount();
	  if($count==0)
		CSaleBasket::Delete($bi['ID']);
	}
}

?>
<?////////////////////////////////////////////////////////////////////
//Раз в три дня строим карту сайта
//проверим наличие файла


/* отключаем нах
if(!file_exists($_SERVER['DOCUMENT_ROOT']."/data_time.php"))
{
    $f = fopen($_SERVER['DOCUMENT_ROOT']."/data_time.php", "a");
    fclose($f);
}
else
{

    $handle = fopen($_SERVER['DOCUMENT_ROOT']."/data_time.php", "r");
    while (!feof($handle)) 
        $buffer = fgets($handle);
    fclose($handle);

    $day_in_mounth=intval(date("t"));
    $day=intval(date("j"));

    if (($day+3)<=$day_in_mounth)
    {
    	//этот месяц
    	$new_day=$day+3;
    	if (($new_day-$buffer>3) || ($new_day-$buffer<0))
    		$buffer=$day;
    }
    else
    {
    	//новый месяц
    	$new_day=3-($day_in_mounth-$day);
    	if (($new_day-$buffer<0) && ($day_in_mounth-$buffer+$new_day)>3)
    		$buffer=$day;
    }

    if (intval($buffer)==$day)
    {
    	$handle = fopen($_SERVER['DOCUMENT_ROOT']."/data_time.php", "w");
    	$test = fwrite($handle, $new_day);
    	fclose($handle);

    	//подключение модуля поиска
    	if(CModule::IncludeModule('search'))
    	{
    		//В этом массиве будут передаваться данные "прогресса". Он же послужит индикатором окончания исполнения.
    		$NS=Array();
    		//Задаем максимальную длительность одной итерации равной "бесконечности".
    		$sm_max_execution_time = 0;
    		//Это максимальное количество ссылок обрабатываемых за один шаг.
    		//Установка слишком большого значения приведет к значительным потерям производительности.
    		$sm_record_limit = 5000;
    		do
    		{
    			$cSiteMap = new CSiteMap;
    			//Выполняем итерацию создания,
    			$NS = $cSiteMap->Create("s1", array($sm_max_execution_time, $sm_record_limit), $NS);
    			//Пока карта сайта не будет создана.
    		} 
    		while(is_array($NS));
    	}
    	//BXClearCache(true, "");
    	$handle=file_get_contents($_SERVER['DOCUMENT_ROOT']."/sitemap_000.xml"); 
    	$handle=str_replace('/', '/', $handle);
    	$fp = fopen($_SERVER['DOCUMENT_ROOT']."/map_klavazip_index.xml", "w+");
    	fwrite($fp, $handle);
    	fclose($fp);	
    }
}
*/
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

/*// ___ BXMAKER  
// Операции после добавления или обновления товара
// Заполнение свойства АНАЛОГИ у товаров, для добавления описания в YML  
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", "BXMUpdateElement_FIELDS");
AddEventHandler("iblock", "OnAfterIBlockElementAdd", "BXMUpdateElement_FIELDS");
// Используется для заполнения свойства с наименованиями товаров аналогов,
// для добавления  в описание в каталог экспорта для Яндекс.Маркет
function BXMUpdateElement_FIELDS(&$arFields)
{

}*/


/// __ BXMAKER изменение наименований картинок у товаров
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", "BXMTestElementUpdateHandler");
//AddEventHandler("iblock", "OnBeforeIBlockElementAdd", "BXMTestElementUpdateHandler");
function BXMTestElementUpdateHandler(&$arFields)
{
    if($arFields['IBLOCK_ID'] == '8')
    {
        // картинки
        if(is_array($arFields['DETAIL_PICTURE']) && isset($arFields['DETAIL_PICTURE']['name']) && isset($arFields['CODE']))
        {
            $ext = '';
            if($arFields['DETAIL_PICTURE']['type'] == 'image/jpeg')
                $ext = '.jpg';

            $arFields['DETAIL_PICTURE']['name'] = CUtil::translit(trim($arFields['CODE']), LANGUAGE_ID, array(
						"max_len" => 100,
						"change_case" => false, // 'L' - toLower, 'U' - toUpper, false - do not change
						"replace_space" => '-',
						"replace_other" => '-',
						"delete_repeat_replace" => true,
					)).$ext;
        }
    }
    return true;
}


///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// ___ MAIL MSG EPILOG HTML -- подпись в сообщениях о фирме 
// в шаблоне письма производится поиск и замена #MSG_EPILOG#
AddEventHandler("main", "OnBeforeEventSend", "FBXMakerAddAllMailPostfix");

function FBXMakerAddAllMailPostfix(&$arFields)
{
    $mpPATH = $_SERVER['DOCUMENT_ROOT'].'/bxmaker/mail/.mail.msg.epilog.html';
    if(file_exists($mpPATH))
    {
        $mpHTML = file_get_contents($mpPATH);
        $arFields['MSG_EPILOG'] .= $mpHTML;
    }
    return true;
}



//_____________________________________________________________________________________________
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////
// ТОЛЬКО ДЛЯ ТЕСТИРОВАНИЯ, возможно удаление
// __ BXMAKER Сохраняет любые данные в файл, для анализа
function FBXMSaveDataToFile($data,$overwrite = true,$filename = 'data.export.tmp')
{
    $d = $_SERVER['DOCUMENT_ROOT'].'/logs';
    if(CheckDirPath($d))
    {
        $m = ($overwrite == true ? 'w' : 'a');
        if($f = fopen($d.'/'.$filename,$m))
        {
            $t = "\n==========================================================================================================\n";
            $t .= "==========================================================================================================\n";
            $t .= "==========================================================================================================\n";
            $t .= "\t\t\t\t ".date('H:m:i d.m.Y')."\n";
            $t .= "==========================================================================================================\n";
            $t .= "==========================================================================================================\n";

            $t .= var_export($data,TRUE);

            fwrite($f,$t);
            fclose($f);
        }
    }
}


function BXMDebug($ar,$bReturn = false)
{
    global $USER;
    if($USER->GetLogin() == 'bxadmin')
    {
        if($bReturn)
        {
            return var_export($ar,TRUE);
        }
        else
        {
            echo '<pre>';
            print_r($ar);
            echo '</pre>';
        }
    }
}

//Функция склонения в падеж кого что
function inflect4 ( $name ) { 
      
        // Building Request URL 
        $url = 'http://export.yandex.ru/inflect.xml?name='.urlencode($name); 
      
		$result=file_get_contents($url);
      
        // Preparing Inflections 
        $cases = array (); 
        preg_match_all( '#\<inflection\s+case\=\"([0-9]+)\"\>(.*?)\<\/inflection\>#si', $result, $m ); 
      
        // Creating Inflection List 
        if ( count($m[0]) ) { 
		  foreach ( $m[1] as $i => &$id ) { 
			$cases[ (int) $id ] = $m[2][$i]; 
		  } unset ( $id ); 
        } else return $name; 
      
        // Sending Request Back to User 
        if ( count( $cases ) > 1 ) { 
			return $cases[4]; 
        } else return $name; 
      
    }



?>