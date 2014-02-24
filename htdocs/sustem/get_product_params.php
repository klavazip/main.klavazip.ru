<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

//Скрипт для 1С отдает URL элемента по запросу артикла
CModule::IncludeModule('iblock');
$res = CIBlockElement::GetList(
 array("SORT"=>"ASC"), // массив для задания сортировки
 array("IBLOCK_ID"=>8,"PROPERTY_CML2_ARTICLE"=>$_GET['art']), // массив фильтра
 false, // массив групировки
 false, // параметры навигации
 array("ID","NAME","DETAIL_PAGE_URL","CODE") // массив нужных полей
);
if($ar_res = $res->Fetch())
{
	if (intval($_GET['art'])!== 0) {
	echo "http://www.klavazip.ru/catalog/" .$ar_res['CODE'];
	}else 
	{
		echo 'Not found';
	}
	
}
else
{
	echo 'Not found';
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>