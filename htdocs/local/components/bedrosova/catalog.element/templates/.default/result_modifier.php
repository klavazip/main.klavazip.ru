<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();



	//echo '<pre>', print_r($arResult).'</pre>';

$TITLE_PREFIX = '';
$dbr = CIBlockSection::GetList(array(),array(
		'ID' => $arResult['IBLOCK_SECTION_ID'],
		'IBLOCK_ID' => $arResult['IBLOCK_ID']
),
		false,
		array(
				'UF_TITLE', // Title no?aieou
				'UF_TITLE2', // Caaieiaie no?aieou
				'UF_NEW_TITLE', // Iiaiene e yeaiaioai
				'UF_DESCRIPTION_UM'
		)
);
if($ar = $dbr->Fetch())
{
	$TITLE_PREFIX = str_replace(array('#SECTION_NAME#'),array($ar['NAME']),$ar['UF_NEW_TITLE']);
	$TITLE_PREFIX_TITLE = str_replace(array('#SECTION_NAME#'),array($ar['NAME']),$ar['UF_NEW_TITLE']);
}
