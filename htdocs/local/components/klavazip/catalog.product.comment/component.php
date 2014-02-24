<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


$rs_Element = CIBlockElement::GetList(
	array('DATE_CREATE' => 'desc'),
	array('IBLOCK_ID' => KlavaCatalogProductComment::IBLOCK_ID, 'PROPERTY_item' => intval($arParams['ID']) ),
	false,
	false,
	array('ID', 'NAME', 'PREVIEW_TEXT', 'PROPERTY_item', 'DATE_CREATE', 'PROPERTY_REATING_STAR')
);

while($ar_Element = $rs_Element->GetNext())
{
	$arResult['ITEM'][] = $ar_Element;
}


$this->IncludeComponentTemplate();