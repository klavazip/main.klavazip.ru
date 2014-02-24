<? 	
define('NO_KEEP_STATISTIC', true);
define('NO_AGENT_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
define('NO_AGENT_CHECK', true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$s_SearchCity = $_POST['s'];
if(strlen($s_SearchCity) > 0)
{
	$rs_City = CSaleLocation::GetList(array(), array("LID" => LANGUAGE_ID, "CITY_NAME" => $s_SearchCity));
	if($rs_City->SelectedRowsCount() > 0)
	{
		if ($ar_City = $rs_City->Fetch())
			echo CUtil::PhpToJSObject(array('st' => 'ok', 'id' => $ar_City['ID'] ));
	}
	else 
		echo CUtil::PhpToJSObject(array('st' => 'error'));
}	
else 
	echo CUtil::PhpToJSObject(array('st' => 'error')); 