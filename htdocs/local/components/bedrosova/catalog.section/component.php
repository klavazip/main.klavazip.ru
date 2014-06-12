<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
CUtil::InitJSCore(array('popup'));

CPageOption::SetOptionString("main", "nav_page_in_session", "N");

/*************************************************************************
	Processing of received parameters
*************************************************************************/
if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;

$arParams["IBLOCK_TYPE"] = trim($arParams["IBLOCK_TYPE"]);
$arParams["IBLOCK_ID"] = intval($arParams["IBLOCK_ID"]);

$arParams["SECTION_ID"] = intval($arParams["~SECTION_ID"]);
if($arParams["SECTION_ID"] > 0 && $arParams["SECTION_ID"]."" != $arParams["~SECTION_ID"])
{
	ShowError(GetMessage("CATALOG_SECTION_NOT_FOUND"));
	@define("ERROR_404", "Y");
	if($arParams["SET_STATUS_404"]==="Y")
		CHTTP::SetStatus("404 Not Found");
	return;
}

if (!in_array($arParams["INCLUDE_SUBSECTIONS"], array('Y', 'A', 'N')))
	$arParams["INCLUDE_SUBSECTIONS"] = 'Y';
$arParams["SHOW_ALL_WO_SECTION"] = $arParams["SHOW_ALL_WO_SECTION"]==="Y";

if(strlen($arParams["ELEMENT_SORT_FIELD"])<=0)
	$arParams["ELEMENT_SORT_FIELD"]="sort";

if(!preg_match('/^(asc|desc|nulls)(,asc|,desc|,nulls){0,1}$/i', $arParams["ELEMENT_SORT_ORDER"]))
	$arParams["ELEMENT_SORT_ORDER"]="asc";

if(strlen($arParams["FILTER_NAME"])<=0 || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"]))
{
	$arrFilter = array();
}
else
{
	global $$arParams["FILTER_NAME"];
	$arrFilter = ${$arParams["FILTER_NAME"]};
	if(!is_array($arrFilter))
		$arrFilter = array();
}

$arParams["SECTION_URL"]=trim($arParams["SECTION_URL"]);
$arParams["DETAIL_URL"]=trim($arParams["DETAIL_URL"]);
$arParams["BASKET_URL"]=trim($arParams["BASKET_URL"]);
if(strlen($arParams["BASKET_URL"])<=0)
	$arParams["BASKET_URL"] = "/personal/basket.php";

$arParams["ACTION_VARIABLE"]=trim($arParams["ACTION_VARIABLE"]);
if(strlen($arParams["ACTION_VARIABLE"])<=0|| !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["ACTION_VARIABLE"]))
	$arParams["ACTION_VARIABLE"] = "action";

$arParams["PRODUCT_ID_VARIABLE"]=trim($arParams["PRODUCT_ID_VARIABLE"]);
if(strlen($arParams["PRODUCT_ID_VARIABLE"])<=0|| !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["PRODUCT_ID_VARIABLE"]))
	$arParams["PRODUCT_ID_VARIABLE"] = "id";

$arParams["PRODUCT_QUANTITY_VARIABLE"]=trim($arParams["PRODUCT_QUANTITY_VARIABLE"]);
if(strlen($arParams["PRODUCT_QUANTITY_VARIABLE"])<=0|| !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["PRODUCT_QUANTITY_VARIABLE"]))
	$arParams["PRODUCT_QUANTITY_VARIABLE"] = "quantity";

$arParams["PRODUCT_PROPS_VARIABLE"]=trim($arParams["PRODUCT_PROPS_VARIABLE"]);
if(strlen($arParams["PRODUCT_PROPS_VARIABLE"])<=0|| !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["PRODUCT_PROPS_VARIABLE"]))
	$arParams["PRODUCT_PROPS_VARIABLE"] = "prop";

$arParams["SECTION_ID_VARIABLE"]=trim($arParams["SECTION_ID_VARIABLE"]);
if(strlen($arParams["SECTION_ID_VARIABLE"])<=0|| !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["SECTION_ID_VARIABLE"]))
	$arParams["SECTION_ID_VARIABLE"] = "SECTION_ID";

$arParams["SET_TITLE"] = $arParams["SET_TITLE"]!="N";
$arParams["ADD_SECTIONS_CHAIN"] = $arParams["ADD_SECTIONS_CHAIN"]==="Y"; //Turn off by default
$arParams["DISPLAY_COMPARE"] = $arParams["DISPLAY_COMPARE"]=="Y";

$arParams["PAGE_ELEMENT_COUNT"] = intval($arParams["PAGE_ELEMENT_COUNT"]);
if($arParams["PAGE_ELEMENT_COUNT"]<=0)
	$arParams["PAGE_ELEMENT_COUNT"]=20;
$arParams["LINE_ELEMENT_COUNT"] = intval($arParams["LINE_ELEMENT_COUNT"]);
if($arParams["LINE_ELEMENT_COUNT"]<=0)
	$arParams["LINE_ELEMENT_COUNT"]=3;

if(!is_array($arParams["PROPERTY_CODE"]))
	$arParams["PROPERTY_CODE"] = array();
foreach($arParams["PROPERTY_CODE"] as $k=>$v)
	if($v==="")
		unset($arParams["PROPERTY_CODE"][$k]);

if(!is_array($arParams["PRICE_CODE"]))
	$arParams["PRICE_CODE"] = array();
	
	
$arParams["USE_PRICE_COUNT"] = $arParams["USE_PRICE_COUNT"]=="Y";
$arParams["SHOW_PRICE_COUNT"] = intval($arParams["SHOW_PRICE_COUNT"]);
if($arParams["SHOW_PRICE_COUNT"]<=0)
	$arParams["SHOW_PRICE_COUNT"]=1;
$arParams["USE_PRODUCT_QUANTITY"] = $arParams["USE_PRODUCT_QUANTITY"]==="Y";

if(!is_array($arParams["PRODUCT_PROPERTIES"]))
	$arParams["PRODUCT_PROPERTIES"] = array();
foreach($arParams["PRODUCT_PROPERTIES"] as $k=>$v)
	if($v==="")
		unset($arParams["PRODUCT_PROPERTIES"][$k]);

$arParams["DISPLAY_TOP_PAGER"] = $arParams["DISPLAY_TOP_PAGER"]=="Y";
$arParams["DISPLAY_BOTTOM_PAGER"] = $arParams["DISPLAY_BOTTOM_PAGER"]!="N";
$arParams["PAGER_TITLE"] = trim($arParams["PAGER_TITLE"]);
$arParams["PAGER_SHOW_ALWAYS"] = $arParams["PAGER_SHOW_ALWAYS"]!="N";
$arParams["PAGER_TEMPLATE"] = trim($arParams["PAGER_TEMPLATE"]);
$arParams["PAGER_DESC_NUMBERING"] = $arParams["PAGER_DESC_NUMBERING"]=="Y";
$arParams["PAGER_DESC_NUMBERING_CACHE_TIME"] = intval($arParams["PAGER_DESC_NUMBERING_CACHE_TIME"]);
$arParams["PAGER_SHOW_ALL"] = $arParams["PAGER_SHOW_ALL"]!=="N";

$arNavParams = array(
	"nPageSize" => $arParams["PAGE_ELEMENT_COUNT"],
	"bDescPageNumbering" => $arParams["PAGER_DESC_NUMBERING"],
	"bShowAll" => $arParams["PAGER_SHOW_ALL"],
);
$arNavigation = CDBResult::GetNavParams($arNavParams);
if($arNavigation["PAGEN"]==0 && $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"]>0)
	$arParams["CACHE_TIME"] = $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"];

$arParams["CACHE_FILTER"]=$arParams["CACHE_FILTER"]=="Y";
if(!$arParams["CACHE_FILTER"] && count($arrFilter)>0)
	$arParams["CACHE_TIME"] = 0;

$arParams["PRICE_VAT_INCLUDE"] = $arParams["PRICE_VAT_INCLUDE"] !== "N";

$arParams['CONVERT_CURRENCY'] = (isset($arParams['CONVERT_CURRENCY']) && 'Y' == $arParams['CONVERT_CURRENCY'] ? 'Y' : 'N');
$arParams['CURRENCY_ID'] = trim(strval($arParams['CURRENCY_ID']));
if ('' == $arParams['CURRENCY_ID'])
{
	$arParams['CONVERT_CURRENCY'] = 'N';
}
elseif ('N' == $arParams['CONVERT_CURRENCY'])
{
	$arParams['CURRENCY_ID'] = '';
}

$arParams["OFFERS_LIMIT"] = intval($arParams["OFFERS_LIMIT"]);
if (0 > $arParams["OFFERS_LIMIT"])
	$arParams["OFFERS_LIMIT"] = 0;

/*************************************************************************
			Processing of the Buy link
*************************************************************************/
$strError = "";
if (array_key_exists($arParams["ACTION_VARIABLE"], $_REQUEST) && array_key_exists($arParams["PRODUCT_ID_VARIABLE"], $_REQUEST))
{
	if(array_key_exists($arParams["ACTION_VARIABLE"]."BUY", $_REQUEST))
		$action = "BUY";
	elseif(array_key_exists($arParams["ACTION_VARIABLE"]."ADD2BASKET", $_REQUEST))
		$action = "ADD2BASKET";
	else
		$action = strtoupper($_REQUEST[$arParams["ACTION_VARIABLE"]]);

	$productID = intval($_REQUEST[$arParams["PRODUCT_ID_VARIABLE"]]);
	if(($action == "ADD2BASKET" || $action == "BUY" || $action == "SUBSCRIBE_PRODUCT") && $productID > 0)
	{
		if(CModule::IncludeModule("iblock") && CModule::IncludeModule("sale") && CModule::IncludeModule("catalog"))
		{
			if($arParams["USE_PRODUCT_QUANTITY"])
				$QUANTITY = intval($_REQUEST[$arParams["PRODUCT_QUANTITY_VARIABLE"]]);
			if($QUANTITY <= 1)
				$QUANTITY = 1;

			$product_properties = array();
			if(count($arParams["PRODUCT_PROPERTIES"]))
			{
				if(is_array($_REQUEST[$arParams["PRODUCT_PROPS_VARIABLE"]]))
				{
					$product_properties = CIBlockPriceTools::CheckProductProperties(
						$arParams["IBLOCK_ID"],
						$productID,
						$arParams["PRODUCT_PROPERTIES"],
						$_REQUEST[$arParams["PRODUCT_PROPS_VARIABLE"]]
					);
					if(!is_array($product_properties))
						$strError = GetMessage("CATALOG_ERROR2BASKET").".";
				}
				else
				{
					$strError = GetMessage("CATALOG_ERROR2BASKET").".";
				}
			}

			if(is_array($arParams["OFFERS_CART_PROPERTIES"]))
			{
				foreach($arParams["OFFERS_CART_PROPERTIES"] as $i => $pid)
					if($pid === "")
						unset($arParams["OFFERS_CART_PROPERTIES"][$i]);

				if(!empty($arParams["OFFERS_CART_PROPERTIES"]))
				{
					$product_properties = CIBlockPriceTools::GetOfferProperties(
						$productID,
						$arParams["IBLOCK_ID"],
						$arParams["OFFERS_CART_PROPERTIES"]
					);
				}
			}

			$notifyOption = COption::GetOptionString("sale", "subscribe_prod", "");
			$arNotify = unserialize($notifyOption);
			
			if ($action == "SUBSCRIBE_PRODUCT" && $arNotify[SITE_ID]['use'] == 'Y')
			{
				$arRewriteFields["SUBSCRIBE"] = "Y";
				$arRewriteFields["CAN_BUY"] = "N";
			}

			if(!$strError && Add2BasketByProductID($productID, $QUANTITY, $arRewriteFields, $product_properties))
			{
				if($action == "BUY")
					LocalRedirect($arParams["BASKET_URL"]);
				else
					LocalRedirect($APPLICATION->GetCurPageParam("", array($arParams["PRODUCT_ID_VARIABLE"], $arParams["ACTION_VARIABLE"])));
			}
			else
			{
				if($ex = $GLOBALS["APPLICATION"]->GetException())
					$strError = $ex->GetString();
				else
					$strError = GetMessage("CATALOG_ERROR2BASKET").".";
			}
		}
	}
}
if(strlen($strError)>0)
{
	ShowError($strError);
	return;
}

if(isset($_REQUEST['price-from']) || isset( $_REQUEST['price-to']) || isset($_REQUEST['filter']))
{
	$s_FilterCacheID = $_REQUEST['price-from'].'_'.$_REQUEST['price-to'].'_'.$_REQUEST['filter']; 	
}	


$ob_KlavaFilter = new KlavaCatalogFilter(true);

/*************************************************************************
			Work with cache
*************************************************************************/
global $APPLICATION;

if($this->StartResultCache(false, array($arrFilter, $APPLICATION->GetCurPageParam(), ($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups()), $arNavigation, $s_FilterCacheID)))
{
	

if(!CModule::IncludeModule("iblock"))
	{
		$this->AbortResultCache();
		ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
		return;
	}

	global $CACHE_MANAGER;
	$arConvertParams = array();
	if ('Y' == $arParams['CONVERT_CURRENCY'])
	{
		if (!CModule::IncludeModule('currency'))
		{
			$arParams['CONVERT_CURRENCY'] = 'N';
			$arParams['CURRENCY_ID'] = '';
		}
		else
		{
			$arCurrencyInfo = CCurrency::GetByID($arParams['CURRENCY_ID']);
			if (!(is_array($arCurrencyInfo) && !empty($arCurrencyInfo)))
			{
				$arParams['CONVERT_CURRENCY'] = 'N';
				$arParams['CURRENCY_ID'] = '';
			}
			else
			{
				$arParams['CURRENCY_ID'] = $arCurrencyInfo['CURRENCY'];
				$arConvertParams['CURRENCY_ID'] = $arCurrencyInfo['CURRENCY'];
			}
		}
	}

	$arSelect = array();
	if(isset($arParams["SECTION_USER_FIELDS"]) && is_array($arParams["SECTION_USER_FIELDS"]))
	{
		foreach($arParams["SECTION_USER_FIELDS"] as $field)
			if(is_string($field) && preg_match("/^UF_/", $field))
				$arSelect[] = $field;
	}
	if(preg_match("/^UF_/", $arParams["META_KEYWORDS"])) $arSelect[] = $arParams["META_KEYWORDS"];
	if(preg_match("/^UF_/", $arParams["META_DESCRIPTION"])) $arSelect[] = $arParams["META_DESCRIPTION"];
	if(preg_match("/^UF_/", $arParams["BROWSER_TITLE"])) $arSelect[] = $arParams["BROWSER_TITLE"];
	
	$arSelect[]='UF_TITLE'; 
    $arSelect[]='UF_TITLE2'; 
    $arSelect[]='UF_NEW_TITLE'; 
    $arSelect[]='UF_PRICE_MAX';
    $arSelect[]='UF_PRICE_MIN';
    $arSelect[]='UF_PRICE_INCSEC_MAX';
    $arSelect[]='UF_PRICE_INCSEC_MIN';
    $arSelect[]='UF_PROPERTY_SHOW';            

	$arFilter = array(
		"IBLOCK_ID"=>$arParams["IBLOCK_ID"],
		"IBLOCK_ACTIVE"=>"Y",
		"ACTIVE"=>"Y",
		"GLOBAL_ACTIVE"=>"Y",
	);

	$bSectionFound = false;
	//Hidden triky parameter USED to display linked
	//by default it is not set
	if($arParams["BY_LINK"]==="Y")
	{
		$arResult = array(
			"ID" => 0,
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		);
		$bSectionFound = true;
	}
	elseif(strlen($arParams["SECTION_CODE"]) > 0)
	{
		$arFilter["CODE"]=$arParams["SECTION_CODE"];
		$rsSection = CIBlockSection::GetList(Array(), $arFilter, false, $arSelect);
		$rsSection->SetUrlTemplates("", $arParams["SECTION_URL"]);
		$arResult = $rsSection->GetNext();
		if($arResult)
			$bSectionFound = true;
	}
	elseif($arParams["SECTION_ID"])
	{
		$arFilter["ID"]=$arParams["SECTION_ID"];
		$rsSection = CIBlockSection::GetList(Array(), $arFilter, false, $arSelect);
		$rsSection->SetUrlTemplates("", $arParams["SECTION_URL"]);
		$arResult = $rsSection->GetNext();
		if($arResult)
			$bSectionFound = true;
	}
	else
	{
		//Root section (no section filter)
		$arResult = array(
			"ID" => 0,
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		);
		$bSectionFound = true;
	}

	if(!$bSectionFound)
	{
		$this->AbortResultCache();
		ShowError(GetMessage("CATALOG_SECTION_NOT_FOUND"));
		@define("ERROR_404", "Y");
		if($arParams["SET_STATUS_404"]==="Y")
			CHTTP::SetStatus("404 Not Found");
		return;
	}
	elseif($arResult["ID"] > 0 && $arParams["ADD_SECTIONS_CHAIN"])
	{
		$arResult["PATH"] = array();
		$rsPath = GetIBlockSectionPath($arResult["IBLOCK_ID"], $arResult["ID"]);
		$rsPath->SetUrlTemplates("", $arParams["SECTION_URL"]);
		while($arPath = $rsPath->GetNext())
		{
			$arResult["PATH"][]=$arPath;
		}
	}
	
	

	//This function returns array with prices description and access rights
	//in case catalog module n/a prices get values from element properties
	
	$arResult["PRICES"] = CIBlockPriceTools::GetCatalogPrices($arParams["IBLOCK_ID"], $arParams["PRICE_CODE"]);

	$arResult['CONVERT_CURRENCY'] = $arConvertParams;

//	$arResult["PICTURE"] = CFile::GetFileArray($arResult["PICTURE"]);
//	$arResult["DETAIL_PICTURE"] = CFile::GetFileArray($arResult["DETAIL_PICTURE"]);
	$arResult["DETAIL_PICTURE"] = CFile::ResizeImageGet($arResult["DETAIL_PICTURE"],array("width"=>"157","height"=>"87"),BX_RESIZE_IMAGE_PROPORTIONAL_ALT,false);

	// list of the element fields that will be used in selection
	$arSelect = array(
		"ID",
		"NAME",
		"CODE",
		"DATE_CREATE",
		"ACTIVE_FROM",
		"ACTIVE_TO",
		"CREATED_BY",
		"IBLOCK_ID",
		"IBLOCK_SECTION_ID",
		"DETAIL_PAGE_URL",
		"DETAIL_TEXT",
		"DETAIL_TEXT_TYPE",
		"DETAIL_PICTURE",
		"PREVIEW_TEXT",
		"PREVIEW_TEXT_TYPE",
		"PREVIEW_PICTURE",
		"TAGS",
		"PROPERTY_*",
	);
	$arFilter = array(
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"IBLOCK_LID" => SITE_ID,
		"IBLOCK_ACTIVE" => "Y",
		"ACTIVE_DATE" => "Y",
		"ACTIVE" => "Y",
		"CHECK_PERMISSIONS" => "Y",
		"MIN_PERMISSION" => "R",
		"INCLUDE_SUBSECTIONS" => ($arParams["INCLUDE_SUBSECTIONS"] == 'N' ? 'N' : 'Y'),
	);
	if ($arParams["INCLUDE_SUBSECTIONS"] == 'A')
		$arFilter["SECTION_GLOBAL_ACTIVE"] = "Y";

	if($arParams["BY_LINK"]!=="Y")
	{
		if($arResult["ID"])
			$arFilter["SECTION_ID"] = $arResult["ID"];
		elseif(!$arParams["SHOW_ALL_WO_SECTION"])
			$arFilter["SECTION_ID"] = 0;
		else
		{
			if (is_set($arFilter, 'INCLUDE_SUBSECTIONS'))
				unset($arFilter["INCLUDE_SUBSECTIONS"]);
			if (is_set($arFilter, 'SECTION_GLOBAL_ACTIVE'))
				unset($arFilter["SECTION_GLOBAL_ACTIVE"]);
		}
	}

	if(is_array($arrFilter["OFFERS"]))
	{
		$arOffersIBlock = CIBlockPriceTools::GetOffersIBlock($arParams["IBLOCK_ID"]);
		if(is_array($arOffersIBlock))
		{
			if(!empty($arrFilter["OFFERS"]))
			{
				$arSubFilter = $arrFilter["OFFERS"];
				$arSubFilter["IBLOCK_ID"] = $arOffersIBlock["OFFERS_IBLOCK_ID"];
				$arSubFilter["ACTIVE_DATE"] = "Y";
				$arSubFilter["ACTIVE"] = "Y";
				$arFilter["=ID"] = CIBlockElement::SubQuery("PROPERTY_".$arOffersIBlock["OFFERS_PROPERTY_ID"], $arSubFilter);
			}

			$arPriceFilter = array();
			foreach($arrFilter as $key => $value)
			{
				if(preg_match('/^(>=|<=)CATALOG_PRICE_/', $key))
				{
					$arPriceFilter[$key] = $value;
					unset($arrFilter[$key]);
				}
			}

			if(!empty($arPriceFilter))
			{
				$arSubFilter = $arPriceFilter;
				$arSubFilter["IBLOCK_ID"] = $arOffersIBlock["OFFERS_IBLOCK_ID"];
				$arSubFilter["ACTIVE_DATE"] = "Y";
				$arSubFilter["ACTIVE"] = "Y";
				$arFilter[] = array(
					"LOGIC" => "OR",
					array($arPriceFilter),
					"=ID" => CIBlockElement::SubQuery("PROPERTY_".$arOffersIBlock["OFFERS_PROPERTY_ID"], $arSubFilter),
				);
			}
		}
	}

	//PRICES

	
	$arPriceTypeID = array();
	if(!$arParams["USE_PRICE_COUNT"])
	{
		foreach($arResult["PRICES"] as &$value)
		{
			$arSelect[] = $value["SELECT"];
			$arFilter["CATALOG_SHOP_QUANTITY_".$value["ID"]] = $arParams["SHOW_PRICE_COUNT"];
		}
		if (isset($value))
			unset($value);
	}
	else
	{
		foreach ($arResult["PRICES"] as &$value)
		{
			$arPriceTypeID[] = $value["ID"];
		}
		if (isset($value))
			unset($value);
	}

	$arSort = array(
		$arParams["ELEMENT_SORT_FIELD"] => $arParams["ELEMENT_SORT_ORDER"],
	);
	

	$arCurrencyList = array();

	
	//EXECUTE

	$rsElements = CIBlockElement::GetList($arSort, array_merge($arrFilter, $arFilter, $ob_KlavaFilter->getFiltetForGetList()), false, $arNavParams, $arSelect);
	$rsElements->SetUrlTemplates($arParams["DETAIL_URL"]);
	if($arParams["BY_LINK"]!=="Y" && !$arParams["SHOW_ALL_WO_SECTION"])
		$rsElements->SetSectionContext($arResult);
	$arResult["ITEMS"] = array();
	while($obElement = $rsElements->GetNextElement())
	{

		
		$arItem = $obElement->GetFields();

		if($arResult["ID"])
			$arItem["IBLOCK_SECTION_ID"] = $arResult["ID"];

		$arButtons = CIBlock::GetPanelButtons(
			$arItem["IBLOCK_ID"],
			$arItem["ID"],
			$arResult["ID"],
			array("SECTION_BUTTONS"=>false, "SESSID"=>false, "CATALOG"=>true)
		);
		$arItem["EDIT_LINK"] = $arButtons["edit"]["edit_element"]["ACTION_URL"];
		$arItem["DELETE_LINK"] = $arButtons["edit"]["delete_element"]["ACTION_URL"];

		$arItem["PREVIEW_PICTURE"] = CFile::GetFileArray($arItem["PREVIEW_PICTURE"]);
		$arItem["DETAIL_PICTURE"] = CFile::GetFileArray($arItem["DETAIL_PICTURE"]);

		if(count($arParams["PROPERTY_CODE"]))
			$arItem["PROPERTIES"] = $obElement->GetProperties();
		elseif(count($arParams["PRODUCT_PROPERTIES"]))
			$arItem["PROPERTIES"] = $obElement->GetProperties();

		$arItem["DISPLAY_PROPERTIES"] = array();
		foreach($arParams["PROPERTY_CODE"] as $pid)
		{
			$prop = &$arItem["PROPERTIES"][$pid];
			if(
				(is_array($prop["VALUE"]) && count($prop["VALUE"]) > 0)
				|| (!is_array($prop["VALUE"]) && strlen($prop["VALUE"]) > 0)
			)
			{
				$arItem["DISPLAY_PROPERTIES"][$pid] = CIBlockFormatProperties::GetDisplayValue($arItem, $prop, "catalog_out");
			}
		}

		$arItem["PRODUCT_PROPERTIES"] = CIBlockPriceTools::GetProductProperties(
			$arParams["IBLOCK_ID"],
			$arItem["ID"],
			$arParams["PRODUCT_PROPERTIES"],
			$arItem["PROPERTIES"]
		);

		if($arParams["USE_PRICE_COUNT"])
		{
			if(CModule::IncludeModule("catalog"))
			{
				$arItem["PRICE_MATRIX"] = CatalogGetPriceTableEx($arItem["ID"], 0, $arPriceTypeID, 'Y', $arConvertParams);
				foreach($arItem["PRICE_MATRIX"]["COLS"] as $keyColumn=>$arColumn)
					$arItem["PRICE_MATRIX"]["COLS"][$keyColumn]["NAME_LANG"] = htmlspecialcharsex($arColumn["NAME_LANG"]);
			}
			else
			{
				$arItem["PRICE_MATRIX"] = false;
			}
			$arItem["PRICES"] = array();
		}
		else
		{
			$arItem["PRICE_MATRIX"] = false;
			$arItem["PRICES"] = CIBlockPriceTools::GetItemPrices($arParams["IBLOCK_ID"], $arResult["PRICES"], $arItem, $arParams['PRICE_VAT_INCLUDE'], $arConvertParams);
		}
		$arItem["CAN_BUY"] = CIBlockPriceTools::CanBuy($arParams["IBLOCK_ID"], $arResult["PRICES"], $arItem);

		$arItem["BUY_URL"] = htmlspecialcharsbx($APPLICATION->GetCurPageParam($arParams["ACTION_VARIABLE"]."=BUY&".$arParams["PRODUCT_ID_VARIABLE"]."=".$arItem["ID"], array($arParams["PRODUCT_ID_VARIABLE"], $arParams["ACTION_VARIABLE"])));
		$arItem["ADD_URL"] = htmlspecialcharsbx($APPLICATION->GetCurPageParam($arParams["ACTION_VARIABLE"]."=ADD2BASKET&".$arParams["PRODUCT_ID_VARIABLE"]."=".$arItem["ID"], array($arParams["PRODUCT_ID_VARIABLE"], $arParams["ACTION_VARIABLE"])));
		$arItem["COMPARE_URL"] = htmlspecialcharsbx($APPLICATION->GetCurPageParam("action=ADD_TO_COMPARE_LIST&id=".$arItem["ID"], array("action", "id")));
		$arItem["SUBSCRIBE_URL"] = htmlspecialcharsbx($APPLICATION->GetCurPageParam($arParams["ACTION_VARIABLE"]."=SUBSCRIBE_PRODUCT&id=".$arItem["ID"], array($arParams["PRODUCT_ID_VARIABLE"], $arParams["ACTION_VARIABLE"])));

		$arItem["SECTION"]["PATH"] = array();
		if($arParams["BY_LINK"]==="Y")
		{
			$rsPath = GetIBlockSectionPath($arItem["IBLOCK_ID"], $arItem["IBLOCK_SECTION_ID"]);
			$rsPath->SetUrlTemplates("", $arParams["SECTION_URL"]);
			while($arPath = $rsPath->GetNext())
			{
				$arItem["SECTION"]["PATH"][]=$arPath;
			}
		}

		if ('Y' == $arParams['CONVERT_CURRENCY'])
		{
			if ($arParams["USE_PRICE_COUNT"])
			{
				if (is_array($arItem["PRICE_MATRIX"]) && !empty($arItem["PRICE_MATRIX"]))
				{
					if (isset($arItem["PRICE_MATRIX"]['CURRENCY_LIST']) && is_array($arItem["PRICE_MATRIX"]['CURRENCY_LIST']))
						$arCurrencyList = array_merge($arCurrencyList, $arItem["PRICE_MATRIX"]['CURRENCY_LIST']);
				}
			}
			else
			{
				if (!empty($arItem["PRICES"]))
				{
					foreach ($arItem["PRICES"] as &$arOnePrices)
					{
						if (isset($arOnePrices['ORIG_CURRENCY']))
							$arCurrencyList[] = $arOnePrices['ORIG_CURRENCY'];
					}
					if (isset($arOnePrices))
						unset($arOnePrices);
				}
			}
		}

		$arResult["ITEMS"][]=$arItem;
		$arResult["ELEMENTS"][] = $arItem["ID"];
	}

	$arResult["NAV_STRING"] = $rsElements->GetPageNavStringEx($navComponentObject, $arParams["PAGER_TITLE"], $arParams["PAGER_TEMPLATE"], $arParams["PAGER_SHOW_ALWAYS"]);
	$arResult["NAV_CACHED_DATA"] = $navComponentObject->GetTemplateCachedData();
	$arResult["NAV_RESULT"] = $rsElements;

	if(!isset($arParams["OFFERS_FIELD_CODE"]))
		$arParams["OFFERS_FIELD_CODE"] = array();
	elseif (!is_array($arParams["OFFERS_FIELD_CODE"]))
		$arParams["OFFERS_FIELD_CODE"] = array($arParams["OFFERS_FIELD_CODE"]);
	foreach($arParams["OFFERS_FIELD_CODE"] as $key => $value)
		if($value === "")
			unset($arParams["OFFERS_FIELD_CODE"][$key]);

	if(!isset($arParams["OFFERS_PROPERTY_CODE"]))
		$arParams["OFFERS_PROPERTY_CODE"] = array();
	elseif (!is_array($arParams["OFFERS_PROPERTY_CODE"]))
		$arParams["OFFERS_PROPERTY_CODE"] = array($arParams["OFFERS_PROPERTY_CODE"]);
	foreach($arParams["OFFERS_PROPERTY_CODE"] as $key => $value)
		if($value === "")
			unset($arParams["OFFERS_PROPERTY_CODE"][$key]);

	if(
		!empty($arResult["ELEMENTS"])
		&& (
			!empty($arParams["OFFERS_FIELD_CODE"])
			|| !empty($arParams["OFFERS_PROPERTY_CODE"])
		)
	)
	{
		$arOffers = CIBlockPriceTools::GetOffersArray(
			$arParams["IBLOCK_ID"]
			,$arResult["ELEMENTS"]
			,array(
				$arParams["OFFERS_SORT_FIELD"] => $arParams["OFFERS_SORT_ORDER"],
				"ID" => "DESC",
			)
			,$arParams["OFFERS_FIELD_CODE"]
			,$arParams["OFFERS_PROPERTY_CODE"]
			,$arParams["OFFERS_LIMIT"]
			,$arResult["PRICES"]
			,$arParams['PRICE_VAT_INCLUDE']
			,$arConvertParams
		);
		if(!empty($arOffers))
		{
			$arElementOffer = array();
			foreach($arResult["ELEMENTS"] as $i => $id)
			{
				$arResult["ITEMS"][$i]["OFFERS"] = array();
				$arElementOffer[$id] = &$arResult["ITEMS"][$i]["OFFERS"];
			}

			foreach($arOffers as $arOffer)
			{
				if(array_key_exists($arOffer["LINK_ELEMENT_ID"], $arElementOffer))
				{
					$arOffer["BUY_URL"] = htmlspecialcharsbx($APPLICATION->GetCurPageParam($arParams["ACTION_VARIABLE"]."=BUY&".$arParams["PRODUCT_ID_VARIABLE"]."=".$arOffer["ID"], array($arParams["PRODUCT_ID_VARIABLE"], $arParams["ACTION_VARIABLE"])));
					$arOffer["ADD_URL"] = htmlspecialcharsbx($APPLICATION->GetCurPageParam($arParams["ACTION_VARIABLE"]."=ADD2BASKET&".$arParams["PRODUCT_ID_VARIABLE"]."=".$arOffer["ID"], array($arParams["PRODUCT_ID_VARIABLE"], $arParams["ACTION_VARIABLE"])));
					$arOffer["COMPARE_URL"] = htmlspecialcharsbx($APPLICATION->GetCurPageParam("action=ADD_TO_COMPARE_LIST&id=".$arOffer["ID"], array($arParams["PRODUCT_ID_VARIABLE"], $arParams["ACTION_VARIABLE"])));
					$arOffer["SUBSCRIBE_URL"] = htmlspecialcharsbx($APPLICATION->GetCurPageParam($arParams["ACTION_VARIABLE"]."=SUBSCRIBE_PRODUCT&id=".$arOffer["ID"], array($arParams["PRODUCT_ID_VARIABLE"], $arParams["ACTION_VARIABLE"])));

					$arElementOffer[$arOffer["LINK_ELEMENT_ID"]][] = $arOffer;

					if ('Y' == $arParams['CONVERT_CURRENCY'])
					{
						if (!empty($arOffer['PRICES']))
						{
							foreach ($arOffer['PRICES'] as &$arOnePrices)
							{
								if (isset($arOnePrices['ORIG_CURRENCY']))
									$arCurrencyList[] = $arOnePrices['ORIG_CURRENCY'];
							}
							if (isset($arOnePrices))
								unset($arOnePrices);
						}
					}
				}
			}
		}
	}

	if ('Y' == $arParams['CONVERT_CURRENCY'])
	{
		if (!empty($arCurrencyList))
		{
			if (defined("BX_COMP_MANAGED_CACHE"))
			{
				$arCurrencyList[] = $arConvertParams['CURRENCY_ID'];
				$arCurrencyList = array_unique($arCurrencyList);
				$CACHE_MANAGER->StartTagCache($this->GetCachePath());
				foreach ($arCurrencyList as &$strOneCurrency)
				{
					$CACHE_MANAGER->RegisterTag("currency_id_".$strOneCurrency);
				}
				if (isset($strOneCurrency))
					unset($strOneCurrency);
				$CACHE_MANAGER->EndTagCache();
			}
		}
	}
	
	
	//global $USER;	
	//if ($USER->GetID()==7429){
		$analogs_by_item=array();
		$analogs_by_item2=array();
		foreach($arResult['ITEMS'] as $val){
		
			$analogs_by_item[''.$val['ID']]['VALUE']=$val['PROPERTIES']['CML2_TRAITS']['VALUE'];
			$analogs_by_item[''.$val['ID']]['DESCRIPTION']=$val['PROPERTIES']['CML2_TRAITS']['DESCRIPTION'];
			
			$key_analog=-1;
			foreach ($analogs_by_item[''.$val['ID']]['DESCRIPTION'] as $key2=>$val2){
						if (strpos($val2,'налог')){
							$key_analog=$key2;
						}
					}
				
				
			if ($key_analog>=0){
				$analogs_by_item2[''.$val['ID']]=explode(',',$analogs_by_item[''.$val['ID']]['VALUE'][$key_analog]);
			
			}
		
		}
		
		
		$xml_filter=array();
		foreach($analogs_by_item2 as $val){
			foreach($val as $val2){
				if (!in_array($val2,$xml_filter)){
					$xml_filter[]=$val2;
				}
			}
		}
		
		if (count($xml_filter)>0){
			$arSelect = array('NAME','ID','DETAIL_PAGE_URL','CATALOG_QUANTITY','XML_ID');
			$arFilterAnalog = array('IBLOCK_ID'=>8,'XML_ID' => $xml_filter);
														
			$arFilterAnalog['>=CATALOG_QUANTITY'] = '1';
									
			$dbre = CIBlockElement::GetList(array(),$arFilterAnalog,false,false,$arSelect);
			while($are = $dbre->GetNext())
			{
				$are['PRICES'] = CPrice::GetBasePrice($are['ID']);
				$arResult['ANALOGS'][''.$are['XML_ID']] = $are;
				//print "ok";
			}
			
			$analogs_by_item3=array();
			
			foreach($analogs_by_item2 as $key=>$val){
				foreach($val as $val2){
					if ($arResult['ANALOGS'][''.$val2]){
						$analogs_by_item3[''.$key][]=$arResult['ANALOGS'][''.$val2];
					}
				}
			}
			
			$arResult['ITEMS_ANALOGS']=$analogs_by_item3;
		
		}
		
		
		$IBLOCK_ID=8;
        $SECTION_ID = $arResult['ID'];


            $ibs = new CIBlockSection();
            $ibe = new CIBlockElement();

            if(is_array($GLOBALS['______BXMAKER_GLOBAL_PARAMS_CATALOG_FILTER_SHOW_PROPERTY'][$IBLOCK_ID]))
            {
                $arrFilterG = $GLOBALS['______BXMAKER_GLOBAL_PARAMS_CATALOG_FILTER_SHOW_PROPERTY'][$IBLOCK_ID];
                for($i=0;$i<count($arrFilterG);$i++)
                {
                    $globSelectionProp[$arrFilterG[$i]] = 'PROPERTY_'.strtoupper($arrFilterG[$i]).'_VALUE';
                }
            }


			$arSaveProperty = array(); 
					foreach($globSelectionProp as $k=>$val){
						$arFilterFilter = array('IBLOCK_ID' =>$IBLOCK_ID,'ACTIVE' => 'Y','SECTION_ID' =>  $SECTION_ID,'INCLUDE_SUBSECTIONS' => 'Y');
						$arSelectionFilter = array('ID','IBLOCK_ID','IBLOCK_SECTION_ID',$val);
						$arSelectionFilter = array_merge($arSelection);
						$dbre = $ibe->GetList(array(),$arFilterFilter,array($val), false,$arSelectionFilter);
						while($obElementFilter = $dbre->GetNextElement())
						{
							$arFieldsFilter = $obElementFilter->GetFields();
							if ($arFieldsFilter[$val.'_ENUM_ID']) { $arSaveProperty[$k][]=$arFieldsFilter[$val.'_ENUM_ID'];}
							
						}
					}
			$arResult['FILTER_PROPERTY_SHOW']=$arSaveProperty;

	//}

	$this->SetResultCacheKeys(array(
		"ID",
		"NAV_CACHED_DATA",
		$arParams["META_KEYWORDS"],
		$arParams["META_DESCRIPTION"],
		$arParams["BROWSER_TITLE"],
		"NAME",
		"PATH",
		"IBLOCK_SECTION_ID",
		"ITEMS_ANALOGS",
		"ANALOGS"
	));
	
	
	$title="";
	foreach($arResult["PATH"] as $arPath)
	{
		//if ()
		//$APPLICATION->AddChainItem($arPath["NAME"], $arPath["~SECTION_PAGE_URL"]);
		$title=$title.$arPath["NAME"]." ";
	}
	//$APPLICATION->SetTitle($title, $arTitleOptions);
	//$APPLICATION->SetPageProperty("title", $title, $arTitleOptions); 
	
	$arResult["ADDITIONAL_TITLE"]=$title;

	$this->IncludeComponentTemplate();
}

$arTitleOptions = null;
if($USER->IsAuthorized())
{
	if(
		$APPLICATION->GetShowIncludeAreas()
		|| $arParams["SET_TITLE"]
		|| isset($arResult[$arParams["BROWSER_TITLE"]])
	)
	{
		if(CModule::IncludeModule("iblock"))
		{
			$UrlDeleteSectionButton = "";
			if($arResult["IBLOCK_SECTION_ID"] > 0)
			{
				$rsSection = CIBlockSection::GetList(
					array(),
					array("=ID" => $arResult["IBLOCK_SECTION_ID"]),
					false,
					array("SECTION_PAGE_URL")
				);
				$rsSection->SetUrlTemplates("", $arParams["SECTION_URL"]);
				$arSection = $rsSection->GetNext();
				$UrlDeleteSectionButton = $arSection["SECTION_PAGE_URL"];
			}

			if(empty($UrlDeleteSectionButton))
			{
				$url_template = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "LIST_PAGE_URL");
				$arIBlock = CIBlock::GetArrayByID($arParams["IBLOCK_ID"]);
				$arIBlock["IBLOCK_CODE"] = $arIBlock["CODE"];
				$UrlDeleteSectionButton = CIBlock::ReplaceDetailURL($url_template, $arIBlock, true, false);
			}

			$arReturnUrl = array(
				"add_section" => (
					strlen($arParams["SECTION_URL"])?
					$arParams["SECTION_URL"]:
					CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_PAGE_URL")
				),
				"delete_section" => $UrlDeleteSectionButton,
			);
			$arButtons = CIBlock::GetPanelButtons(
				$arParams["IBLOCK_ID"],
				0,
				$arResult["ID"],
				array("RETURN_URL" =>  $arReturnUrl, "CATALOG"=>true)
			);

			if($APPLICATION->GetShowIncludeAreas())
				$this->AddIncludeAreaIcons(CIBlock::GetComponentMenu($APPLICATION->GetPublicShowMode(), $arButtons));

			if($arParams["SET_TITLE"] || isset($arResult[$arParams["BROWSER_TITLE"]]))
			{
				$arTitleOptions = array(
					'ADMIN_EDIT_LINK' => $arButtons["submenu"]["edit_section"]["ACTION"],
					'PUBLIC_EDIT_LINK' => $arButtons["edit"]["edit_section"]["ACTION"],
					'COMPONENT_NAME' => $this->GetName(),
				);
			}
		}
	}
}

$this->SetTemplateCachedData($arResult["NAV_CACHED_DATA"]);





if(isset($arResult[$arParams["META_KEYWORDS"]]))
{
	$val = $arResult[$arParams["META_KEYWORDS"]];
	if(is_array($val))
		$val = implode(" ", $val);
	$APPLICATION->SetPageProperty("keywords", $val);
}

if(isset($arResult[$arParams["META_DESCRIPTION"]]))
{
	$val = $arResult[$arParams["META_DESCRIPTION"]];
	if(is_array($val))
		$val = implode(" ", $val);
	$APPLICATION->SetPageProperty("description", $val);
}

if ($arParams["SET_TITLE"] && isset($arResult["NAME"]))
	$APPLICATION->SetTitle($arResult["NAME"], $arTitleOptions);

if(isset($arResult[$arParams["BROWSER_TITLE"]]))
{
	$val = $arResult[$arParams["BROWSER_TITLE"]];
	if(is_array($val))
		$val = implode(" ", $val);
	
	$APPLICATION->SetPageProperty("title", $val, $arTitleOptions); 
}

if($arParams["ADD_SECTIONS_CHAIN"] && isset($arResult["PATH"]) && is_array($arResult["PATH"]))
{
	$title="";
	foreach($arResult["PATH"] as $arPath)
	{
		# Внимание этот грандиозный костыль был согласован с Никитой,
		switch ($arPath["NAME"])
		{
			case 'Аккумуляторы по моделям': $arPath["NAME"] = 'Аккумуляторы для ноутбуков'; break;
			case 'Запчасти': $arPath["NAME"] = 'Запчасти для ноутбуков'; break;
			case 'Микросхемы': $arPath["NAME"] = 'Микросхемы для ноутбуков'; break;
			case 'Блоки питания': $arPath["NAME"] = 'Блоки питания для ноутбуков'; break;
			case 'Клавиатуры': $arPath["NAME"] = 'Клавиатуры для ноутбуков'; break;
			case 'Матрицы': $arPath["NAME"] = 'Матрицы для ноутбуков'; break;
		}
		
		$title=$title.$arPath["NAME"]." ";
		$APPLICATION->AddChainItem($arPath["NAME"], $arPath["~SECTION_PAGE_URL"]);
	}
	
	
	//echo '<pre>', print_r($title).'</pre>';
	
	//$dla="";
	//if ($title=='Клавиатуры' || $title=='Матрицы' || $title=='Аккумуляторы' || $title=='Системы охлаждения' || $title=='Корпусные детали')
	
	$title=str_replace( 'Клавиатуры' , 'Клавиатуры для ноутбука' , $title);
	$title=str_replace( 'Матрицы' , 'Матрицы для ноутбука' , $title);
	$title=str_replace( 'Аккумуляторы по моделям' , 'Аккумуляторы для ноутбука' , $title);
	$title=str_replace( 'Аккумуляторы для ноутбука Для' , 'Аккумуляторы для ноутбука' , $title);
	$title=str_replace( 'Системы охлаждения' , 'Системы охлаждения для ноутбука' , $title);
	$title=str_replace( 'Корпусные детали' , 'Корпусные детали для ноутбука' , $title);
	
	$inf_title=inflect4($title);
	
	$title2="Купить ".$inf_title." в Москве по низким ценам. Подобрать ".$inf_title." по модели в интернет-магазине: характеристики, совместимость, отзывы";

	
	$APPLICATION->SetTitle($title2, $arTitleOptions);
	
	$APPLICATION->SetPageProperty("title", $title2, $arTitleOptions); 
	
}

?>