<?

/*
// необходимо сделать так перед запуском
------------------------------------------
COption::SetOptionString("main", "agents_use_crontab", "Y"); 
echo COption::GetOptionString("main", "agents_use_crontab", "N");
COption::SetOptionString("main", "check_agents", "N"); 
echo COption::GetOptionString("main", "check_agents", "Y");


// cron
------------------------------------------
crontab.conf

// далее испольнительный механизм
*/

$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__)."/../../..");
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS",true);

define("BX_CRONTAB", in_array ("BX_CRONTAB=1", $_SERVER["argv"]));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if(in_array ("BX_SUBSCRIBE=1", $_SERVER["argv"]))
{
    if (CModule::IncludeModule("subscribe"))
    {
        $cPosting = new CPosting;
        $cPosting->AutoSend();
    }
}
else
{
    
    CAgent::CheckAgents();
    
    @set_time_limit(0);
    @ignore_user_abort(true);
    
    CEvent::CheckEvents();
    
}


echo 'OK | '.date('H:i:s d.m.Y')."\n".var_export($_SERVER["argv"],TRUE);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>