<? 

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/*DEMO CODE for component inheritance
 CBitrixComponent::includeComponentClass("bitrix::news.base");
class CBitrixCatalogSmartFilter extends CBitrixNewsBase
*/
class KlavaFilter extends CBitrixComponent
{
	public function executeComponent()
	{
		
		return parent::executeComponent();
	}
	
	
	public function getArrayProeprtyCode()
	{
		return  array(
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
		
		
	}
	
	
}
