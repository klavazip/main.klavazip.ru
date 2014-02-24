<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?


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


/*
foreach ( $arResult['SHOW_PROPERTIES'] as $s_Code => $ar_Value )
{
	if( in_array($s_Code, $ar_PropertyCode) )
	{
		$arResult['_SHOW_PROPERTIES'][] = $ar_Value;
	}
}
*/

//echo count($arResult['_SHOW_PROPERTIES']);
//echo count($arResult['SHOW_PROPERTIES']);



//echo '<pre>', print_r($arParams).'</pre>';
//echo '<pre>', print_r($arResult["SHOW_PROPERTIES"]).'</pre>';


foreach ($arResult['ITEMS'] as $key => $arElement) 
{
    
    
    if($arElement['CATALOG_QUANTITY'] <= 0)
    {
        // помимо наименований 
        $c = '';
        ob_start();
        $APPLICATION->IncludeComponent("bxmaker:bxmaker.catalog.analogi", ".default", array (
       	    "CACHE_TYPE" => "A",
           	"CACHE_TIME" => "36000000",
           	"IBLOCK_TYPE" => $arElement["IBLOCK_TYPE"],
           	"IBLOCK_ID" => $arElement["IBLOCK_ID"],
           	"ELEMENT_ID" => $arElement['ID'],
            "ELEMENT_COUNT" => 100,
            "SHOW_QUANTITY" => 'Y',
            'QUANTITY_MORE' => 'Y'
           	),
           	false
        );
        $c = ob_get_contents();
        ob_end_clean();
        
        $arResult["ANALOGI"][$arElement['ID']] = $c;
    }
    
}

/*
 * Названия в каталоге для SEO
 */
/*
$arSectionIDs = array();
$arSectionNames = array();
foreach ($arResult['ITEMS'] as $arItem) {
    $arSectionIDs[$arItem['ID']] = $arItem['IBLOCK_SECTION_ID'];
}

$arParentBySection = array();
$rsList = CIBlockSection::GetList(array(), array('ID' => array_unique(array_values($arSectionIDs)), 'IBLOCK_ID' => $arResult['IBLOCK_ID']));
while ($arItem = $rsList->GetNext()) {
    $arParentBySection[$arItem['ID']] = $arItem['IBLOCK_SECTION_ID'];
    $arSectionNames[$arItem['ID']] = $arItem['NAME'];
}

$arParents = array();
$rsList = CIBlockSection::GetList(array(), array('ID' => array_unique(array_values($arParentBySection)), 'IBLOCK_ID' => $arResult['ITEMS'][0]['IBLOCK_ID']), false, array('UF_NEW_TITLE'));
while ($arItem = $rsList->GetNext()) {
    $arParents[$arItem['ID']] = $arItem['UF_NEW_TITLE'];
}
foreach ($arResult['ITEMS'] as $k => $arItem) {
    if (!empty($arParents[$arParentBySection[$arItem['IBLOCK_SECTION_ID']]])) {
	$arResult['ITEMS'][$k]['NAME'] = str_replace(array('#NAME#', '#SECTION_NAME#'), array($arItem['NAME'], $arSectionNames[$arItem['IBLOCK_SECTION_ID']]), $arParents[$arParentBySection[$arItem['IBLOCK_SECTION_ID']]]);
    }
}



$arResult["ROWS"] = array();

foreach ($arResult['ITEMS'] as $key => $arElement) {
    $arRow = array_splice($arResult["ITEMS"], 0, $arParams["LINE_ELEMENT_COUNT"]);
    while (count($arRow) < $arParams["LINE_ELEMENT_COUNT"])
	$arRow[] = false;
    if (!empty($arRow[0]))
	$arResult["ROWS"][] = $arRow;
}
*/
