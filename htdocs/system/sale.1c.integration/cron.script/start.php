<? 

# Переводим с запуска с крона в ручной запуск
if( ! isset($_SERVER['HTTP_USER_AGENT'])  ) die();

$_SERVER['DOCUMENT_ROOT'] = SITE_DOCUMENT_ROOT;

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/lib/class.KlavaIntegrationMain.php' );
require_once($_SERVER['DOCUMENT_ROOT'].'/system/lib/class.Klava1CExportUser.php' );


# Не забываем выключать, ибо забьется все логами!
$b_Debug = false;

if($b_Debug)
{
	$s_LogDir = $_SERVER['DOCUMENT_ROOT'].'/system/sale.1c.integration/cron.script/logs/';
	$s_DateTime = date("d.m.Y_H:i:s");
	mkdir($s_LogDir.$s_DateTime, 0777);
	$s_LogPatch = $s_LogDir.$s_DateTime.'/';
}
	
$ar_Action = KlavaIntegrationMain::getAction();

($b_Debug) ? arraytofile(array('action' => $ar_Action), $s_LogPatch.'очередь.txt') : '';

if(count($ar_Action) > 0)
{
	foreach ($ar_Action as $ar_Value)
	{
		KlavaIntegrationMain::setStartTimeAction($ar_Value['ID']);

		$ar_Params = unserialize($ar_Value['PREVIEW_TEXT']);
		
		 switch ($ar_Value['CODE'])
		 {
		 	
		 	case 'USER_ADD':
		 		
			 		if(is_array($ar_Params) && count($ar_Params) > 0)
			 		{
			 			Klava1CExportUser::add($ar_Params);
			 			($b_Debug) ? arraytofile(array('action' => 'USER_ADD'), $s_LogPatch.'USER_ADD.txt') : '';
			 		}
			 		else
			 			($b_Debug) ? arraytofile(array('action' => 'ADD_USER'), $s_LogPatch.'error.txt') : '';

			 		break;
			 		

		 	case 'USER_UPDATE':
		 		
			 		if(is_array($ar_Params) && count($ar_Params) > 0)
			 		{
			 			Klava1CExportUser::update($ar_Params);
			 			($b_Debug) ? arraytofile(array('action' => 'UPDATE_USER'), $s_LogPatch.'UPDATE_USER.txt') : '';
			 		}	
			 		else
			 			($b_Debug) ? arraytofile(array('action' => 'UPDATE_USER'), $s_LogPatch.'error.txt') : '';
		 		 
		 		break;

		 	
		 	case 'USER_DELETE':
	
			 		if(is_array($ar_Params) && count($ar_Params) > 0)
			 		{
			 			Klava1CExportUser::delete($ar_Params);
			 			($b_Debug) ? arraytofile(array('action' => 'DELETE_USER'), $s_LogPatch.'DELETE_USER.txt') : '';
			 		}
			 		else
				 		($b_Debug) ? arraytofile(array('action' => 'USER_DELETE'), $s_LogPatch.'error.txt') : '';
		 		 
		 		break;
		 		
		 	
		 	case 'USER_PROFILE_ADD':
	
		 			if(is_array($ar_Params) && count($ar_Params) > 0)
		 			{
		 				Klava1CExportUserProfile::add($ar_Params);
		 				($b_Debug) ? arraytofile(array('action' => 'USER_PROFILE_ADD'), $s_LogPatch.'USER_PROFILE_ADD.txt') : '';
		 			}
		 			else
		 				($b_Debug) ? arraytofile(array('action' => 'USER_PROFILE_ADD'), $s_LogPatch.'error.txt') : '';
		 			
		 		break;
		 		
		 		
		 	case 'USER_PROFILE_UPDATE':
		 			
		 			if(is_array($ar_Params) && count($ar_Params) > 0)
		 			{
		 				Klava1CExportUserProfile::update($ar_Params);
		 				($b_Debug) ? arraytofile(array('action' => 'USER_PROFILE_UPDATE'), $s_LogPatch.'USER_PROFILE_UPDATE.txt') : '';
		 			}
		 			else
		 				($b_Debug) ? arraytofile(array('action' => 'USER_PROFILE_UPDATE'), $s_LogPatch.'error.txt') : '';
		 			
		 		break;
		 		
		 	
		 	case 'ORDER_ADD':
	
		 			if(is_array($ar_Params) && count($ar_Params) > 0)
		 			{
		 				Klava1CExporOrder::add($ar_Params);
		 				($b_Debug) ? arraytofile(array('action' => 'ORDER_ADD'), $s_LogPatch.'ORDER_ADD.txt') : '';
		 			}
		 			else
		 				($b_Debug) ? arraytofile(array('action' => 'ORDER_ADD'), $s_LogPatch.'error.txt') : '';
		 			
		 		break;

		 	
		 	case 'ORDER_UPDATE':
	
		 			if(is_array($ar_Params) && count($ar_Params) > 0)
		 			{ 
		 				Klava1CExporOrder::update($ar_Params);
		 				($b_Debug) ? arraytofile(array('action' => 'ORDER_UPDATE'), $s_LogPatch.'ORDER_UPDATE.txt') : '';
		 			}
		 			else
		 				($b_Debug) ? arraytofile(array('action' => 'ORDER_UPDATE'), $s_LogPatch.'error.txt') : '';
		 			
		 		break;
		 }
		
		KlavaIntegrationMain::setEndTimeAction($ar_Value['ID']);
	}	
}	
else
{
	($b_Debug) ? arraytofile(array(), $s_LogPatch.'NO_ACTION.txt', "") : '';
	echo 'Очередь пуста';
}	


