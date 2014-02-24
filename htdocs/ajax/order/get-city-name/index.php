<? 	
define('NO_KEEP_STATISTIC', true);
define('NO_AGENT_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
define('NO_AGENT_CHECK', true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");


$s_LetterSearchCity = $_POST['name'];


$rs_City = CSaleLocation::GetList(
	array(), 
	array("LID" => LANGUAGE_ID, "CITY_NAME" => $s_LetterSearchCity), 
	false, 
	array('nTopCount' => 8), 
	array()
);
if($rs_City->SelectedRowsCount() > 0)
{
	if ($ar_City = $rs_City->Fetch())
	{
		echo CUtil::PhpToJSObject(array('st' => 'ok', 'result' => array(
			'id' => $ar_City['ID']
		) ));
	}
}
else 
	echo CUtil::PhpToJSObject(array('st' => 'error'));
