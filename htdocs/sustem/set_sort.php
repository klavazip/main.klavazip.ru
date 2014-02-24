<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

/**
 * Обновдление сортировки у товаров, выгрузка из 1с файлом методом POST
 * @author Ramil' Yunaliev
 * @version 0.1
 */

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
		@header('HTTP/1.0 403 Forbidden');
		@header('Content-type: text/html; charset=windows-1251'); 
		die('Ошибка при загрузке файла, потому что нет файла');
	}
	else
	{
		if($data = file_get_contents($s_TmpName))
		//if($data = file_get_contents( $_SERVER['DOCUMENT_ROOT'].'/sustem/test.xml' ))
		{
			$xml = simplexml_load_string( $data );
			if( ! $xml )
			{
				arraytofile(array($data), $_SERVER['DOCUMENT_ROOT']."/sustem/set_sort.txt", "ERROR");
				
				@header('HTTP/1.0 403 Forbidden');
				@header('Content-type: text/html; charset=windows-1251');
				die('Ошибка при загрузке файла, проблемы с валидацией XML');
			}	
			
			$ar_XML = json_decode(json_encode($xml), true);
			
			$ar_Result = array();
			$ar_XML_ID = array();
			foreach ($ar_XML['row'] as $ar_Value)
			{
				if( intval($ar_Value['Vl'][1]) > 0 )
				{
					$ar_Result[] = array('XML_ID' => $ar_Value['Vl'][0], 'SORT' => $ar_Value['Vl'][1]);
					$ar_XML_ID[] = $ar_Value['Vl'][0];
				}
			}
			
			if(count($ar_XML_ID) > 0)
			{ 
				# очищаем все
				$rs_ElementClear = CIBlockElement::GetList(array(), array('IBLOCK_ID' => KlavaCatalog::IBLOCK_ID, '!SORT' => 0), false, false, array('ID'));
				while($ar_ElementClear = $rs_ElementClear->Fetch())
				{
					$ar_ElID[] = $ar_ElementClear['ID'];
				}
				
				$DB->Update("b_iblock_element", array("SORT" => 0), "WHERE ID in (".implode(',', $ar_ElID).")", $err_mess.__LINE__);
				
				
				$rs_Element = CIBlockElement::GetList(array(), array('IBLOCK_ID' => KlavaCatalog::IBLOCK_ID, 'XML_ID' => $ar_XML_ID), false, false, array('ID', 'XML_ID'));
				$ar_ElementID = array();
				while($ar_Element = $rs_Element->Fetch())
				{
					$ar_ElementID[$ar_Element['XML_ID']] = $ar_Element['ID'];
				}
			
				foreach ($ar_Result as $i => $ar_Value )
				{
					$i_ElementID = $ar_ElementID[$ar_Value['XML_ID']];
					
					if($i_ElementID > 0)
						$DB->Update("b_iblock_element", array("SORT" => $ar_Value['SORT']), "WHERE ID=".$i_ElementID, $err_mess.__LINE__);
				}
			}			
			
			@header('HTTP/1.1 200 Ok');
			echo 'OK';
		}	
		else
		{
			@header('HTTP/1.0 403 Forbidden');
			@header('Content-type: text/html; charset=windows-1251');
			die('file_get_contents error');
		}	
	}
}	














//arraytofile($ob_RemDebug->Output(), $_SERVER['DOCUMENT_ROOT']."/sustem/set_sort.txt", "DEBUG");

//echo '<pre>', print_r( $ob_RemDebug->Output(false) ).'</pre>';


//arraytofile($_POST, $_SERVER['DOCUMENT_ROOT']."/sustem/set_sort.txt", "");
//arraytofile($_FILES, $_SERVER['DOCUMENT_ROOT']."/sustem/set_sort.txt", "");


/*

//Читаем текстовые данные POST-запроса
$submit = ( isset($_POST['submit']) ) ? intval($_POST['submit']) : false;
$decode = ( isset($_POST['decode']) ) ? intval($_POST['decode']) : false;
$message = ( isset($_POST['message']) ) ? htmlspecialchars($_POST['message']) : '';

//Проверим user-agent, хотя большого толку от такой проверки нет. См. статью.
if ( $_SERVER['HTTP_USER_AGENT'] != '1C+Enterprise/8.3' )
{
	@header('HTTP/1.0 403 Forbidden');
	die('Hacking attempt');
}

if ( $submit )
{
	//Здесь работаем с содержимым переданного файла.
	$uploadFile = $_FILES['datafile'];
	$tmp_name = $uploadFile['tmp_name'];
	$data_filename = $uploadFile['name'];
	if ( !is_uploaded_file($tmp_name) )
	{
		die('Ошибка при загрузке файла ' . $data_filename);
	}
	else
	{
		//Считываем файл в строку
		$data = file_get_contents($tmp_name);

		if ($decode)
		{
			//При необходимости декодируем данные
			$data = base64_decode($data);
		}
		//Теперь нормальный файл можно сохранить на диске
		if ( !empty($data) && ($fp = @fopen($data_filename, 'wb')) )
		{
			@fwrite($fp, $data);
			@fclose($fp);
		}
		else
		{
			die('Ошибка при записи файла ' . $data_filename);
		}
		@header('HTTP/1.1 200 Ok');
		@header('Content-type: text/html; charset=windows-1251');
		$answer = "\n" . 'Файл ' . $data_filename . ' успешно загружен. ' . "\n" . 'Переданное сообщение: ' . $message;
		print ($answer);
	}
}

echo 'OK';


include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/php_interface/lib/class.RemDebug.php');
$ob_RemDebug = new RemDebug();
$ob_RemDebug->Start();


	
	$rs_ElementClear = CIBlockElement::GetList(array(), array('IBLOCK_ID' => KlavaCatalog::IBLOCK_ID), false, false, array('ID'));
	while($ar_ElementClear = $rs_ElementClear->Fetch())
	{
		$ar_ID[] = $ar_ElementClear['ID'];
	}

	$t = $DB->Update("b_iblock_element", array("SORT" => 999), "WHERE ID in (".implode(',', $ar_ID).")", $err_mess.__LINE__);
 		
	var_dump($t);
	
	echo '<pre>', print_r( $ob_RemDebug->Output() ).'</pre>';
	
	die();	
 		
 	
        $arFields = array("SORT" => 0);
		$DB->StartTransaction();
        if ($ID>0) 
        {
            $DB->Update("b_form", array("SORT" => 0), "WHERE ID='".$ID."'", $err_mess.__LINE__);
        }
        else 
        {
            $ID = $DB->Insert("b_form", $arFields, $err_mess.__LINE__);
            $new="Y";
        }
        $ID = intval($ID);
        if (strlen($strError)<=0) 
        {
            $DB->Commit();
            if (strlen($save)>0) LocalRedirect("form_list.php?lang=".LANGUAGE_ID);
            elseif ($new=="Y") LocalRedirect("form_edit.php?lang=".LANGUAGE_ID."&ID=".$ID);
        }
        else $DB->Rollback();
		*/

//echo '<pre>', print_r( $ob_RemDebug->Output() ).'</pre>';
