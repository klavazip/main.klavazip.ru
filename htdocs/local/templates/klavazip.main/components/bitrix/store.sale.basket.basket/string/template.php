<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

echo implode('|', array(
	'DISCOUNT_PRICE='.$arResult['DISCOUNT_PRICE'],
	'DISCOUNT_PERCENT='.$arResult['DISCOUNT_PERCENT'],
	'allSum='.$arResult['allSum']
));

