<?
/**
 *
 * Модуль платежного сервиса OnlineDengi для CMS 1С Битрикс.
 * @copyright Сервис OnlineDengi http://www.onlinedengi.ru/ (ООО "КомФинЦентр"), 2010
 *
 */

if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();?><?

if (!CModule::IncludeModule("sale"))
{
	ShowError(GetMessage("SOA_MODULE_NOT_INSTALL"));
	return;
}


if(!CModule::IncludeModule('rarusspb.onlinedengi')) {
	return;
}

if($_REQUEST['AJAX_OD'] == 'Y'){
	CSalePaySystemAction::InitParamArrays(array(),$_REQUEST['ORDER_ID']);
}

$arResult = array();
$arResult['ERRORS'] = array();
$arResult['sCurPage'] = $GLOBALS['APPLICATION']->GetCurPageParam();
$arResult['FIELDS']['project'] = CSalePaySystemAction::GetParamValue('ONLINEDENGI_PROJECT');
$arResult['FIELDS']['source'] = CSalePaySystemAction::GetParamValue('ONLINEDENGI_SOURCE');
$arResult['FIELDS']['paymentCurrency'] = 'RUB';
$arResult['FIELDS']['order_id'] = CSalePaySystemAction::GetParamValue('ONLINEDENGI_ORDER_ID');
$arResult['FIELDS']['nickname'] = CSalePaySystemAction::GetParamValue('ONLINEDENGI_NICKNAME');
$arResult['FIELDS']['nick_extra'] = CSalePaySystemAction::GetParamValue('ONLINEDENGI_NICK_EXTRA');
$arResult['FIELDS']['comment'] = CSalePaySystemAction::GetParamValue('ONLINEDENGI_COMMENT');
$arResult['PAYMENT'] = CSalePaySystem::GetByID($GLOBALS['SALE_INPUT_PARAMS']['ORDER']['PAY_SYSTEM_ID'], $GLOBALS['SALE_INPUT_PARAMS']['ORDER']['PERSON_TYPE_ID']);
$arResult['ORDER']['AMOUNT'] = CSalePaySystemAction::GetParamValue('ONLINEDENGI_AMOUNT');
$arResult['ORDER']['CURRENCY'] = $GLOBALS['SALE_INPUT_PARAMS']['ORDER']['CURRENCY'];
$arResult['ORDER']['ID'] = $GLOBALS['SALE_INPUT_PARAMS']['ORDER']['ID'];

// получим список доступных способов оплаты
$arResult['arOnlineDengiAvailablePaymentTypes'] = COnlineDengiPayment::GetPaymentTypesList();
$arResult['arModeTypeList'] = array();
// подготовим список для предоставления выбора покупателю
foreach($arResult['arOnlineDengiAvailablePaymentTypes'] as $arPaymentType) {
	// код валюты обязательное поле
	if(!empty($arPaymentType['currency'])) {
		$iTmpVal = intval(CSalePaySystemAction::GetParamValue('ONLINEDENGI_AVAILABLE_TYPE_'.$arPaymentType['id']));
		if($iTmpVal > 0) {
			$arResult['arModeTypeList'][] = array(
				'value' => $arPaymentType['id'],
				'description' => GetMessage($arPaymentType['lang']),
				'img' => $arPaymentType['img']
			);
		}
	}
}

$arResult['bAdminModeTypeDefined'] = (!empty($arResult['arModeTypeList']) && count($arResult['arModeTypeList']) == 1);

if(!$arResult['bAdminModeTypeDefined'] && $_SERVER['REQUEST_METHOD'] == 'POST' && intval($_REQUEST['ORDER_ID']) > 0) {
	// получим способ оплаты из формы
	$arResult['FIELDS']['mode_type'] = intval($_POST['mode_type']);
}
// Обработаем способы оплаты
if(empty($arResult['FIELDS']['mode_type'])) {
	// доступные способы оплаты не заданы в настройках платежной системы
	if(empty($arResult['arModeTypeList'])) {
		$arResult['ERRORS']['ERR_ONLINEDENGI_MODE_TYPES_EMPTY'] = GetMessage('ERR_ONLINEDENGI_MODE_TYPES_EMPTY');
	} else {
		// если всего один способ оплаты доступен, то установим его автоматом
		if($arResult['bAdminModeTypeDefined']) {
			reset($arResult['arModeTypeList']);
			$arResult['FIELDS']['mode_type'] = intval($arResult['arModeTypeList'][key($arResult['arModeTypeList'])]['value']);
		}
	}
} else {
	// проверим доступен ли выбранный способ оплаты
	if(empty($arResult['arOnlineDengiAvailablePaymentTypes'][$arResult['FIELDS']['mode_type']]) || empty($arResult['arOnlineDengiAvailablePaymentTypes'][$arResult['FIELDS']['mode_type']]['currency'])) {
		$arResult['FIELDS']['mode_type'] = false; 
		$arResult['ERRORS']['ERR_ONLINEDENGI_MODE_TYPE_WRONG'] = GetMessage('ERR_ONLINEDENGI_MODE_TYPE_WRONG');
	}
}

// подготовка поля amount для выбранного способа
if($arResult['FIELDS']['mode_type']) {

	if (!($arOrder1 = CSaleOrder::GetByID($_REQUEST['ORDER_ID'])))
	{
	   //echo "Заказ с кодом ".$ORDER_ID." не найден";
	   $arResult['FIELDS']['amount'] = $arResult['ORDER']['AMOUNT'];
	}
	else
	{
	   /*echo "<pre>";
	   print_r($arOrder);
	   echo "</pre>";*/
	   $arResult['FIELDS']['amount'] = $arOrder1['PRICE']-$arOrder1['SUM_PAID'];
	   $arResult['ORDER']['AMOUNT']=$arOrder1['PRICE']-$arOrder1['SUM_PAID'];
	}


	$arCurPaymentModeType =& $arResult['arOnlineDengiAvailablePaymentTypes'][$arResult['FIELDS']['mode_type']];

}

$this->IncludeComponentTemplate();
