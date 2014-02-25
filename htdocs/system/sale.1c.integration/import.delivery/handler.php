<?
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
require_once( '../../lib/xml_to_array.php' );
require_once( '../../lib/class.KlavaIntegrationMain.php' );

//arraytofile($_POST, $_SERVER['DOCUMENT_ROOT']."/system/rest.delivery/POST.txt", "");
//arraytofile($_FILES, $_SERVER['DOCUMENT_ROOT']."/system/rest.delivery/FILE.txt", "");
//arraytofile($_SERVER, $_SERVER['DOCUMENT_ROOT']."/system/rest.delivery/SERVER.txt", "");

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
			
			$ob_Element = new CIBlockElement;
			
			foreach ($ar_XML['Array'] as $ar_Value)
			{
				$ar_Proeprty = array(
					'FULL_NAME' 	=> $ar_Value['Property'][0]['Value']['Property'][0]['Value']['@value'],
					'ARTICUL'		=> $ar_Value['Property'][0]['Value']['Property'][1]['Value']['@value'],
					'DESCRIPTION' 	=> $ar_Value['Property'][0]['Value']['Property'][2]['Value']['@value'],
					'COD'			=> $ar_Value['Property'][0]['Value']['Property'][4]['Value']['@value'],
					'LINK' 			=> $ar_Value['Property'][0]['Value']['Property'][5]['Value']['@value'],
					'PAYMENT_TYPE' 	=> $ar_Value['Property'][1]['Value']['Property'][0]['Value']['@value'],
					'LINK_SITE_TNN'	=> $ar_Value['Property'][1]['Value']['Property'][1]['Value']['@value'],
				);
			
				$ar_Delivery = array(
					'IBLOCK_ID'       => 38,
					'PROPERTY_VALUES' => $ar_Proeprty,
					'NAME'            => $ar_Value['Property'][0]['Value']['Property'][3]['Value']['@value'],
				);
				
				if($i_DeliveryID = $ob_Element->Add($ar_Delivery))
				{
					header("Content-Type: text/xml");
					echo KlavaIntegrationMain::getReturnXML(array(array(
						'1C_ID'  => $ar_Value['Property'][0]['Value']['Property'][5]['Value']['@value'],
						'STATUS' => true,
						'REPORT' => 'Успешно добавленно ID'.$i_DeliveryID		
					)));
				}	
				else
				{
					header("Content-Type: text/xml");
					echo KlavaIntegrationMain::getReturnXML(array(array(
						'1C_ID'  => $ar_Value['Property'][0]['Value']['Property'][5]['Value']['@value'],
						'STATUS' => false,
						'REPORT' => 'Ошибка добавления '.$ob_Element->LAST_ERROR
					)));
				}	
			}
		}	
		else
		{
			die('file_get_contents error');
		}	
	}
}