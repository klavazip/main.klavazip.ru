<? 	
define('NO_KEEP_STATISTIC', true);
define('NO_AGENT_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
define('NO_AGENT_CHECK', true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");


$s_LetterSearchCity = $_POST['s'];


$rs_City = CSaleLocation::GetList(
	array(), 
	array("LID" => LANGUAGE_ID, "%CITY_NAME" => $s_LetterSearchCity), 
	false, 
	array('nTopCount' => 8), 
	array()
);
if($rs_City->SelectedRowsCount() > 0)
{
	ob_start();

		while ($ar_City = $rs_City->Fetch())
		{
			?>
			<a 
				href="#" 
				data-id="<?=$ar_City['ID']?>"
				data-val="<?=$ar_City['CITY_NAME']?>"
				onclick="klava.order.setLocation(this); return false;"
				>
					<?=$ar_City['CITY_NAME']?>  
			</a>
			<?
		}
		
	$html = ob_get_contents();
	ob_end_clean();
	
	echo CUtil::PhpToJSObject(array('st' => 'ok', 'html' => $html ));
}
else 
	echo CUtil::PhpToJSObject(array('st' => 'error'));
