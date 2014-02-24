<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?



if( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit']) )
{
	$ob_User = new CUser;
	if($ob_User->Update($USER->GetID(), array('UF_PERSON_TYPE' => intval($_POST['USER_TYPE']))))
	{
		$arResult['STATUS'] = true;
	}
}

$rs_User = CUser::GetByID($USER->GetID());
$ar_User = $rs_User->Fetch();

$arResult['CURRENT_TYPE'] = (intval($ar_User['UF_PERSON_TYPE']) > 0) ? $ar_User['UF_PERSON_TYPE'] : 1;



$this->IncludeComponentTemplate();