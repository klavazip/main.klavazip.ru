<? 

require_once('const.php');


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
//echo '<pre>', print_r($ar_Action).'</pre>';

($b_Debug) ? arraytofile(array('action' => $ar_Action), $s_LogPatch.'очередь.txt') : '';

if(count($ar_Action) > 0)
{
	foreach ($ar_Action as $ar_Value)
	{
		$ar_Params = unserialize($ar_Value['PREVIEW_TEXT']);
		
		switch ($ar_Value['CODE'])
		{

			case 'USER_ADD':
		 		
			 		if(is_array($ar_Params) && count($ar_Params) > 0)
			 			$ar_Report = Klava1CExportUser::add($ar_Params);

			 		break;
			 		

		 	case 'USER_UPDATE':
		 		
			 		if(is_array($ar_Params) && count($ar_Params) > 0)
			 			$ar_Report = Klava1CExportUser::update($ar_Params);
		 		 
		 		break;
		 		
		 	
		 	case 'USER_PROFILE_ADD':
	
		 			if(is_array($ar_Params) && count($ar_Params) > 0)
		 				$ar_Report = Klava1CExportUserProfile::add($ar_Params);
		 			
		 		break;
		 		
		 		
		 	case 'USER_PROFILE_UPDATE':
		 			
		 			if(is_array($ar_Params) && count($ar_Params) > 0)
		 				$ar_Report = Klava1CExportUserProfile::update($ar_Params);
		 			
		 		break;
		 		
		 	
		 	case 'ORDER_ADD':
	
		 			if(is_array($ar_Params) && count($ar_Params) > 0)
		 				$ar_Report = Klava1CExporOrder::add($ar_Params);
		 			
		 		break;

		 	
		 	case 'ORDER_UPDATE':
	
		 			if(is_array($ar_Params) && count($ar_Params) > 0)
		 				$ar_Report = Klava1CExporOrder::update($ar_Params);
		 			
		 		break;
		 }
		 
		 $r = KlavaIntegrationMain::exportReport($ar_Value['ID'], $ar_Report);
		
		 echo '<pre>', print_r($r).'</pre>';
	}	
}	
else
{
	($b_Debug) ? arraytofile(array(), $s_LogPatch.'NO_ACTION.txt', "") : '';
	echo 'Очередь пуста';
}	


