<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?



	if(intval($_GET['delprofilleid']) > 0)
	{
		CSaleOrderUserProps::Delete(intval($_GET['delprofilleid']));
		LocalRedirect($APPLICATION->GetCurPageParam('', array('delprofilleid')));
	}	




	if(strlen($_GET['XML_ID']) > 0)
		$ar_Filter['XML_ID'] = $_GET['XML_ID'];
	elseif(intval($_GET['ID']) > 0)
		$ar_Filter['ID'] = intval($_GET['ID']);
	
	if(count($ar_Filter) > 0)
	{
		$rs_User = CUser::GetList(($by=""), ($order=""), $ar_Filter, array("SELECT" => array("UF_*")) ); 
		if($ar_User = $rs_User->NavNext())
		{
			$arResult['USER'] = $ar_User;
			
			$rs_Profile = CSaleOrderUserProps::GetList(array('ID' => 'DESC'), array("USER_ID" => $ar_User['ID']));
			$ar_ProfileParams = array();
			while ($ar_Profile = $rs_Profile->Fetch())
			{
				$ar_ProfileParams = $ar_Profile;
				
				# Ищем XML_ID проифля
				
				$rs_Element = CIBlockElement::GetList(array(), array('IBLOCK_ID' => 40, '=CODE' => $ar_Profile['ID']), false, false, array('ID', 'NAME', 'CODE'));
				if($ar_Element = $rs_Element->GetNext(true, false))
				{
					$ar_ProfileParams['XML_ID'] = $ar_Element['NAME'];
				}
				
				
				# Выбираем все названия свойств
				$rs_OrderProp = CSaleOrderProps::GetList(array(), array("PERSON_TYPE_ID" => $ar_Profile['PERSON_TYPE_ID']), false, false, array('ID', 'NAME'));
				$ar_OrderProvParams = array();
				while ($ar_OrderProp = $rs_OrderProp->Fetch())
				{
					$ar_OrderProvParams[$ar_OrderProp['ID']] = $ar_OrderProp['NAME'];
				}
				
				$rs_ProfilePropVal = CSaleOrderUserPropsValue::GetList(($b="SORT"), ($o="ASC"), Array("USER_PROPS_ID"=>$ar_Profile['ID']));
				while ($ar_ProfilePropVal = $rs_ProfilePropVal->Fetch())
				{
					$ar_ProfilePropVal['PROP_NAME'] = $ar_OrderProvParams[$ar_ProfilePropVal['ORDER_PROPS_ID']];
					$ar_ProfileParams['PROPERTY'][] = $ar_ProfilePropVal;
				}
				
				$arResult['USER']['PROFILE'][] = $ar_ProfileParams;
			}
			
		}	
	}	
	
	
	
	
	
	




$this->IncludeComponentTemplate();


//echo '<pre>', print_r($arResult).'</pre>';