<?
/*
 * При регистрации нового пользователя, отправляем его в 1с где создается новый партнер
* в ответ получаем xml и записываем xml_id пользователя, при удалении пользователя из базы 1с он не удаляется по помечается как не активный
*/


include( $_SERVER['DOCUMENT_ROOT'].'/system/lib/class.KlavaIntegrationMain.php' );
include( $_SERVER['DOCUMENT_ROOT'].'/system/lib/class.Klava1CExportUser.php' );


AddEventHandler('main', 'OnAfterUserAdd', 	  array('Klava1CExportUser', 'addActionAddUser'));
AddEventHandler('main', 'OnAfterUserUpdate',  array('Klava1CExportUser', 'addActionUpdateUser'));
AddEventHandler('main', 'OnBeforeUserDelete', array('Klava1CExportUser', 'addActionDeleteUser'));






// require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');


//AddEventHandler('main', 'OnAfterUserAdd',     array('Klava1CExportUser', 'OnAfterUserAddHandler'));
//AddEventHandler('main', 'OnAfterUserUpdate',  array('Klava1CExportUser', 'OnAfterUserUpdateHandler'));
//AddEventHandler('main', 'OnUserDelete', 	  array('Klava1CExportUser', 'OnUserDeleteHandler'));

/********************************************************************************************************************************************************************************************************/
/********************************************************************************************************************************************************************************************************/
/********************************************************************************************************************************************************************************************************/
/********************************************************************************************************************************************************************************************************/
/********************************************************************************************************************************************************************************************************/
/********************************************************************************************************************************************************************************************************/
/********************************************************************************************************************************************************************************************************/
/********************************************************************************************************************************************************************************************************/

//require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
//require_once( '../../lib/xml_to_array.php' );
//require_once( '../../lib/class.KlavaIntegrationMain.php' );

# /system/sale.1c.integration/export.user/handler.php



# !!!
//($b_Debug) ? arraytofile(array('data' => $data), $s_LogPatch.'data.xml', "data") : '';
# !!!


/*
 function getRandomString($length = 6)
 {
$validCharacters = "abcdefghijklmnopqrstuxyvwzABCDEFGHIJKLMNOPQRSTUXYVWZ+-*#&@!?";
$validCharNumber = strlen($validCharacters);
$result = "";
for ($i = 0; $i < $length; $i++)
{
$index = mt_rand(0, $validCharNumber - 1);
$result .= $validCharacters[$index];
}
return $result;
}

$client = new SoapClient('http://88.198.65.46:45454/TestBase/ws/Obmen?wsdl', array('login' => "Obmen", 'password' => "Obmen", 'exceptions' => 1));
//echo $client->Test(array('str' => getRandomString(50) ))->return;
echo $client->MainFunc(array('data' => getRandomString(50), 'type' => 'asdasdasdasd' ))->return;
*/

//die();

//$client = new SoapClient('http://88.198.65.46:45454/Main/ws/Obmen?wsdl', array('login' => "Obmen", 'password' => "Obmen", 'exceptions' => 1));
//echo $client->MainFunc(array('data' => 'getRandomString(1000)', 'type' => 'ADD_USER' ))->return;
