<?
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

require_once( '../../lib/xml_to_array.php' );
require_once( '../../lib/class.KlavaIntegrationMain.php' );
require_once( '../../lib/class.KlavaPaymentSystem.php' );
//require_once( '../../lib/class.KlavaUserProfile.php' );

$s_LogPatch = $_SERVER['DOCUMENT_ROOT']."/system/sale.1c.integration/import.payment.system/logs/";

//arraytofile($_POST, $s_LogPatch.'test.text', "_POST");
//arraytofile($_FILES, $s_LogPatch, "");
//arraytofile($_SERVER, $s_LogPatch, ""); 

set_time_limit(9999999);

if ( $_SERVER['HTTP_USER_AGENT'] != '1C+Enterprise/8.3')
{
	@header('HTTP/1.0 403 Forbidden');
	die('Hacking attempt');
}
else
{
	$ar_UploadFile = $_FILES['datafile'];
	$s_TmpName = $ar_UploadFile['tmp_name'];
	if ( ! is_uploaded_file($s_TmpName) )
	{
		die('Error loading file');
	}
	else
	{
		if($data = file_get_contents($s_TmpName))
		{
			$ar_XML = XML2Array::createArray( iconv("cp1251", "UTF-8", $data) );
			
			# Синхронизуем массив при 2 митуациях, когда в xml 1 элемент и несколько
			if(isset($ar_XML['Array']['Value']['Property']))
			{
				$ar_XML['Array']['Value'][] = array('@attributes' =>  $ar_XML['Array']['Value']['@attributes'], 'Property' => $ar_XML['Array']['Value']['Property']);
				unset($ar_XML['Array']['Value']['Property']);
				unset($ar_XML['Array']['Value']['@attributes']);
			}
			
			
			foreach ($ar_XML['Array']['Value'] as $ar_Value)
			{
				$ar_Params = array();
				foreach ($ar_Value['Property'] as $ar_Val)
				{
					$s_Type = $ar_Val['Value']['@attributes']['type'];
					$ar_Params[$ar_Val['@attributes']['name']] = $ar_Val['Value']['@value'];
				}
			
				$ar_Result[] = $ar_Params;
			}
			
			//arraytofile($ar_Result, $s_LogPatch.'ar_Result', "ar_Result");
			
			$ar_Status = array();
			foreach ($ar_Result as $ar_ResultValue)
			{
				$rs_Payment = CSalePaySystem::GetList(array(), array('NAME' => $ar_ResultValue['Наименование']));
				if ($ar_Payment = $rs_Payment->Fetch())
				{
					KlavaPaymentSystem::addMatching($ar_ResultValue['Ссылка'], $ar_Payment['ID']);
					$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_ResultValue['Ссылка'], true);
				}
				else
				{
					$ar_Status[] = KlavaIntegrationMain::arrayItemStatus($ar_ResultValue['Ссылка'], false, 'не найдена системы оплаты на сайте');
				}
			}
			
			header('Content-Type: text/xml');
			echo KlavaIntegrationMain::getReturnXML($ar_Status);
			
		}
		else
		{
			die('file_get_contents error');
		}
	}
}
	

