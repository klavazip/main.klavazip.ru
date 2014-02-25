<? 
//require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

require_once( $_SERVER['DOCUMENT_ROOT'].'/system/lib/class.KlavaIntegrationMain.php' );
require_once( $_SERVER['DOCUMENT_ROOT'].'/system/lib/class.Klava1CExporOrder.php' );


//AddEventHandler('sale', 'OnOrderAdd', 	 array('Klava1CExporOrder', 'addActionAddOrder'));
//AddEventHandler('sale', 'OnOrderUpdate', array('Klava1CExporOrder', 'addActionUpdateOrder'));
//AddEventHandler('sale', 'OnOrderDelete', array('Klava1CExporOrder', 'addActionAddOrder'));