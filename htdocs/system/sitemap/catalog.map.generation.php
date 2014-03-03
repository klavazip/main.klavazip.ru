<? 
//$_SERVER['DOCUMENT_ROOT'] = '/srv/www/dev2.klavazip.ru/repo/htdocs/';

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/lib/class.KlavaSitemap.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/system/lib/xml_to_array.php');

$ob_SiteMap = new KlavaSitemap();
$ob_SiteMap->run();