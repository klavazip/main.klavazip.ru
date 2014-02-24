<? require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

	
	$s_Email = trim($_POST['email']);
	$s_Text  = trim($_POST['text']);
	$s_Name  = trim($_POST['name']);
	$i_ElementID  = intval($_POST['id']);
	$i_Reating    = intval($_POST['reating']);

		
	if( strlen($s_Text) > 0 && $i_ElementID > 0)
	{ 
		$i_CommentID = KlavaCatalogProductComment::addComment(
			array(
				'PRODUCT_ID'   => $i_ElementID,
		 		'USER_EMAIL'   => $s_Email,
		 		'USER_TEXT'    => $s_Text,
	 			'USER_NAME'    => $s_Name,
				'USER_REATING' => $i_Reating	
			)
		);
		
		if($i_CommentID > 0)
		{
			CEvent::Send('ADD_COMMENT', 's1', array(
				'PRODUCT_ID'   => $i_ElementID,
		 		'USER_EMAIL'   => $s_Email,
		 		'USER_TEXT'    => $s_Text,
	 			'USER_NAME'    => $s_Name,
				'USER_REATING' => $i_Reating,
				'COMMNET_URL'  => "http://klavazip.ru/bitrix/admin/iblock_element_edit.php?WF=Y&ID=".$i_CommentID."&type=services&lang=ru&IBLOCK_ID=10&find_section_section=0"			
			));
			CEvent::CheckEvents();
			
			echo CUtil::PhpToJSObject(array('st' => 'ok'));
		}
		else
		{
			echo CUtil::PhpToJSObject(array('st' => 'error', 'mess' => $b_AddComment));
		}
	}
	else
	{
		echo CUtil::PhpToJSObject(array('st' => 'error', 'mess' => 'Не указан текст комментария'));
	}
