<? require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");


$s_SessionID = $_POST['ses'];
$i_ProfileID = intval($_POST['id']);

if($s_SessionID == $_SESSION['fixed_session_id'] && $i_ProfileID > 0)
{
	CSaleOrderUserProps::Delete($i_ProfileID);
	echo CUtil::PhpToJSObject(array('st' => 'ok'));
}
else
	echo CUtil::PhpToJSObject(array('st' => 'error'));
