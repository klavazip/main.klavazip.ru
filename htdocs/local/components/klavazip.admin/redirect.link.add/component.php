<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$requiredModules = array('highloadblock');

foreach ($requiredModules as $requiredModule)
{
	if (!CModule::IncludeModule($requiredModule))
	{
		ShowError(GetMessage("F_NO_MODULE"));
		return 0;
	}
}

use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

if( $_SERVER['REQUEST_METHOD'] == 'POST')
{
	$s_NewLink = $_POST['NEW_LINK'];
	$s_OldLink = $_POST['OLD_LINK'];
	
	if(strlen($s_NewLink) == 0)
		$ar_Error[] = 'Не заполненно поле Новая ссылка';
	
	if(strlen($s_OldLink) == 0)
		$ar_Error[] = 'Не заполненно поле Старая ссылка';
	
	if( count($ar_Error) == 0 )
	{
		$hlblock = HL\HighloadBlockTable::getById(1)->fetch();
		
		$entity = HL\HighloadBlockTable::compileEntity($hlblock);
		$entity_ConfigMenius = $entity->getDataClass();
		
		$entity_ConfigMenius::add( array( 'UF_NEW_LINK' => $s_NewLink, 'UF_OLD_LINK' => $s_OldLink ) );
		
		LocalRedirect('/klavazip/tools/add-redirect-links/?add=Y');
	}
	else
		$arResult['ERROR'] = $ar_Error;
}	










/*

// pagination
$limit = array(
	'nPageSize' => $arParams['ROWS_PER_PAGE'],
	'iNumPage' => is_set($_GET['PAGEN_1']) ? $_GET['PAGEN_1'] : 1,
	'bShowAll' => true
);

// sort
$sort_id = 'ID';
$sort_type = 'DESC';

if (!empty($_GET['sort_id']) && (isset($fields[$_GET['sort_id']])))
{
	$sort_id = $_GET['sort_id'];
}

if (!empty($_GET['sort_type']) && in_array($_GET['sort_type'], array('ASC', 'DESC'), true))
{
	$sort_type = $_GET['sort_type'];
}

// limit
$limit = array(
	'nPageSize' => $arParams['ROWS_PER_PAGE'],
	'iNumPage' => is_set($_GET['PAGEN_1']) ? $_GET['PAGEN_1'] : 1,
	'bShowAll' => true
);



// execute query

$main_query = new Entity\Query($entity);
$main_query->setSelect(array('*'));
$main_query->setOrder(array($sort_id => $sort_type));
//$main_query->setSelect($select)
//	->setFilter($filter)
//	->setGroup($group)
//	->setOrder($order)
//	->setOptions($options);


if (isset($limit['nPageTop']))
{
	$main_query->setLimit($limit['nPageTop']);
}
else
{
	$main_query->setLimit($limit['nPageSize']);
	$main_query->setOffset(($limit['iNumPage']-1) * $limit['nPageSize']);
}

//$main_query->setLimit($limit['nPageSize']);
//$main_query->setOffset(($limit['iNumPage']-1) * $limit['nPageSize']);

$result = $main_query->exec();
$result = new CDBResult($result);

// build results
$rows = array();

$tableColumns = array();

while ($row = $result->Fetch())
{
	foreach ($row as $k => $v)
	{
		if ($k == 'ID')
		{
			$tableColumns['ID'] = true;
			continue;
		}

		$arUserField = $fields[$k];

		if ($arUserField["SHOW_IN_LIST"]!="Y")
		{
			continue;
		}

		$html = call_user_func_array(
			array($arUserField["USER_TYPE"]["CLASS_NAME"], "getadminlistviewhtml"),
			array(
				$arUserField,
				array(
					"NAME" => "FIELDS[".$row['ID']."][".$arUserField["FIELD_NAME"]."]",
					"VALUE" => htmlspecialcharsbx($v)
				)
			)
		);

		if($html == '')
		{
			$html = '&nbsp;';
		}

		$tableColumns[$k] = true;

		$row[$k] = $html;
	}


	$rows[] = $row;
}


$arResult["NAV_STRING"] = $result->GetPageNavString('', (is_set($arParams['NAV_TEMPLATE'])) ? $arParams['NAV_TEMPLATE'] : 'arrows');
$arResult["NAV_PARAMS"] = $result->GetNavParams();
$arResult["NAV_NUM"] = $result->NavNum;


$arResult['ITEMS'] = $rows;
$arResult['fields'] = $fields;
$arResult['tableColumns'] = $tableColumns;

$arResult['sort_id'] = $sort_id;
$arResult['sort_type'] = $sort_type;
*/

$this->IncludeComponentTemplate();