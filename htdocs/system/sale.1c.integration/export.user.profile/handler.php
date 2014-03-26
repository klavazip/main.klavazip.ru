<?
# При регистрации нового пользователя, отправляем его в 1с где создается новый партнер в ответ получаем xml 
# и записываем xml_id пользователя, при удалении пользователя из базы 1с он не удаляется но помечается как не активный
//AddEventHandler('main', 'OnAfterUserAdd', 	  array('Klava1CExportUser', 'addActionAddUser'));
//AddEventHandler('main', 'OnAfterUserUpdate',  array('Klava1CExportUser', 'addActionUpdateUser'));
//AddEventHandler('main', 'OnBeforeUserDelete', array('Klava1CExportUser', 'addActionDeleteUser'));



//require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');


//echo '<pre>', print_r( CUser::GetByID(16230)->Fetch() ).'</pre>';


//die();
//$arFields

//$ar_Action = KlavaIntegrationMain::getAction();

//echo '<pre>', print_r($ar_Action).'</pre>';

//$ar_Params = unserialize($ar_Action[0]['PREVIEW_TEXT']);

//echo '<pre>', print_r($ar_Params).'</pre>';

//Klava1CExportUserProfile::add($ar_Params);