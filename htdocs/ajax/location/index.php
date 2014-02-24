<? 	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$s_Name = $_POST['name'];

if(strlen($s_Name) > 0)
{
	$rs_Location = CSaleLocation::GetList(array(), array("LID" => LANGUAGE_ID, 'CITY_NAME' => $s_Name));
	if ($ar_Location = $rs_Location->Fetch())
	{
		echo $ar_Location['ID'];
	}
}