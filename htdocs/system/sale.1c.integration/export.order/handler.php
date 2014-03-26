<? 
# Обработчики для выгрузки заказов в 1с  
//AddEventHandler('sale', 'OnBeforeOrderAdd', array('Klava1CExporOrder', 'addActionAddOrder'));
//AddEventHandler('sale', 'OnOrderUpdate', array('Klava1CExporOrder', 'addActionUpdateOrder'));
//AddEventHandler('sale', 'OnOrderDelete', array('Klava1CExporOrder', 'addActionAddOrder')); 


# Скрипт используется только для текстирования


require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');



die();
//$arFields

$ar_Action = KlavaIntegrationMain::getAction();

//echo '<pre>', print_r($ar_Action).'</pre>';

$ar_Params = unserialize($ar_Action[1]['PREVIEW_TEXT']);

//echo '<pre>', print_r($ar_Params).'</pre>';

Klava1CExporOrder::add($ar_Params);


