<? 

# Переводим с запуска с крона в речной запуск
if( ! isset($_SERVER['HTTP_USER_AGENT'])  ) die();


$_SERVER['DOCUMENT_ROOT'] = '/srv/www/dev2.klavazip.ru/repo/htdocs/';

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/lib/class.KlavaIntegrationMain.php' );
require_once($_SERVER['DOCUMENT_ROOT'].'/system/lib/class.Klava1CExportUser.php' );



$b_Debug = true;

if($b_Debug)
{
	$s_LogDir = $_SERVER['DOCUMENT_ROOT'].'/system/sale.1c.integration/cron.script/logs/';
	$s_DateTime = date("d.m.Y_H:i:s");
	mkdir($s_LogDir.$s_DateTime, 0777);
	$s_LogPatch = $s_LogDir.$s_DateTime.'/';
}

$ar_Action = KlavaIntegrationMain::getAction();
if(count($ar_Action) > 0)
{
	foreach ($ar_Action as $ar_Value)
	{
		KlavaIntegrationMain::setStartTimeAction($ar_Value['ID']);

		$ar_Params = unserialize($ar_Value['PREVIEW_TEXT']);
		
		//AddMessage2Log(var_export($ar_Value, true), "my_module_id");
		//AddMessage2Log(var_export($ar_Params, true), "my_module_id");
		
		//($b_Debug) ? arraytofile($ar_Params, $s_LogPatch.'ar_Params.txt', "") : '';
		//($b_Debug) ? arraytofile(array('action' => $ar_Value['CODE']), $s_LogPatch.$ar_Value['CODE'].'.txt', "") : '';
		
		 switch ($ar_Value['CODE'])
		 {
		 	# Выгрузка нового пользователя в 1с
		 	case 'USER_ADD':
		 		
		 		 
			 		if(is_array($ar_Params) && count($ar_Params) > 0)
			 		{
			 			Klava1CExportUser::OnAfterUserAddHandler($ar_Params);
			 			($b_Debug) ? arraytofile(array('action' => 'USER_ADD'), $s_LogPatch.'USER_ADD.txt', "") : '';
			 		}
			 		else
			 			($b_Debug) ? arraytofile(array('action' => 'ADD_USER'), $s_LogPatch.'error.txt', "") : '';

			 		break;
			 		
			 		
		 	# Обновление данных пользователя 
		 	case 'USER_UPDATE':
		 		
			 		if(is_array($ar_Params) && count($ar_Params) > 0)
			 		{
			 			Klava1CExportUser::OnAfterUserUpdateHandler($ar_Params);
			 			($b_Debug) ? arraytofile(array('action' => 'UPDATE_USER'), $s_LogPatch.'UPDATE_USER.txt', "") : '';
			 		}	
			 		else
			 			($b_Debug) ? arraytofile(array('action' => 'ADD_USER'), $s_LogPatch.'error.txt', "") : '';
		 		 
		 		break;

		 		
		 	# Сообщаем 1с что пользователь удален с сайта
		 	case 'USER_DELETE':
	
			 		if(is_array($ar_Params) && count($ar_Params) > 0)
			 		{
			 			Klava1CExportUser::OnUserDeleteHandler($ar_Params);
			 			($b_Debug) ? arraytofile(array('action' => 'DELETE_USER'), $s_LogPatch.'DELETE_USER.txt', "") : '';
			 		}
			 		else
				 		($b_Debug) ? arraytofile(array('action' => 'ADD_USER'), $s_LogPatch.'error.txt', "") : '';
		 		 
		 		break;
		 }
		
		KlavaIntegrationMain::setEndTimeAction($ar_Value['ID']);
	}	
}	
else
{
	($b_Debug) ? arraytofile(array(), $s_LogPatch.'NO_ACTION.txt', "") : '';
	echo 'NO ACTION';
}	



