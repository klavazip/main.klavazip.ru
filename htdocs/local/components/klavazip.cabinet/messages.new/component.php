<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?


$rs_Users = CUser::GetList(($by=""), ($order=""), array('GROUPS_ID' => array(KlavaCabinetMessages::$i_UserAdminGroupID))); 
while($ar_Users = $rs_Users->NavNext())
{
	$arResult['ITEM_USER'][] = $ar_Users; 
}


if( $_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['smb'])
{
	$s_Text = htmlspecialchars(trim($_POST['USER_TEXT']));
	if( strlen($s_Text) > 0 )
	{
		$i_Count = count($_FILES['FILE']['name']);
		if( $i_Count > 0 )
		{
			$ar_Foto = array();
			for ($i = 0; $i < $i_Count; $i++)
			{
				$_ar_foto = array();
					
				$_ar_foto['name'] 		= $_FILES['FILE']['name'][$i];
				$_ar_foto['type'] 		= $_FILES['FILE']['type'][$i];
				$_ar_foto['tmp_name'] 	= $_FILES['FILE']['tmp_name'][$i];
				$_ar_foto['error'] 		= $_FILES['FILE']['error'][$i];
				$_ar_foto['size'] 		= $_FILES['FILE']['size'][$i];
				$_ar_foto['MODULE_ID'] 	= 'iblock';
				$ar_Foto['n'.$i] 		= $_ar_foto;
			}
		}
		
		$ob_Element = new CIBlockElement;
		$ar_Field = array(
			'IBLOCK_ID'       => KlavaCabinetMessages::$i_IBlockID,
			'PROPERTY_VALUES' => array(
				'USER_FROM_ID'  => $USER->GetID(),
				'USER_TO_ID'	=> intval($_POST['USER_MANAGER']),
				'BASKET'		=> '',
				'FILE'			=> $ar_Foto				
			),
			'NAME'            => 'Сообщение от пользователя '.$USER->GetFullName(),
			'PREVIEW_TEXT'    => $s_Text
		);
		if($i_NewElement = $ob_Element->Add($ar_Field))
		{
			LocalRedirect('/cabinet/messages/new/?result=ok');
		}
	}
	else
	{
		$arResult['ERROR'] = 'Введите текст сообщение';
	}
}



$this->IncludeComponentTemplate();