<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?

if (!CModule::IncludeModule("sale"))
{
	ShowError(GetMessage("SALE_MODULE_NOT_INSTALL"));
	return;
}

$rs_Order = CSaleOrder::GetList(array(), array('USER_ID' => $USER->GetID(), 'ID' => intval($_GET['ID'])));
if( $rs_Order->SelectedRowsCount() > 0 )
{
	if ($ar_Order = $rs_Order->GetNext(true, false))
	{
		$arResult["ORDER_ID"] = $ar_Order['ID'];
		$arResult["PRICE_DELIVERY"] = $ar_Order['PRICE_DELIVERY'];
		
		$dbPaySysAction = CSalePaySystemAction::GetList(array(), array('PAY_SYSTEM_ID' => $ar_Order['PAY_SYSTEM_ID'], 'PERSON_TYPE_ID' => $ar_Order['PERSON_TYPE_ID']));
		if ($arPaySysAction = $dbPaySysAction->Fetch())
		{
			$arPaySysAction['ID'] = $arPaySysAction['PAY_SYSTEM_ID'];
			$arPaySysAction["NAME"] = htmlspecialcharsEx($arPaySysAction["NAME"]);
			if (strlen($arPaySysAction["ACTION_FILE"]) > 0)
			{
				if ($arPaySysAction["NEW_WINDOW"] != "Y")
				{
					CSalePaySystemAction::InitParamArrays($arOrder, $ID, $arPaySysAction["PARAMS"]);
		
					$pathToAction = $_SERVER["DOCUMENT_ROOT"].$arPaySysAction["ACTION_FILE"];
		
					$pathToAction = str_replace("\\", "/", $pathToAction);
					while (substr($pathToAction, strlen($pathToAction) - 1, 1) == "/")
						$pathToAction = substr($pathToAction, 0, strlen($pathToAction) - 1);
		
					if (file_exists($pathToAction))
					{
						if (is_dir($pathToAction) && file_exists($pathToAction."/payment.php"))
							$pathToAction .= "/payment.php";
		
						$arPaySysAction["PATH_TO_ACTION"] = $pathToAction;
					}
		
					if(strlen($arPaySysAction["ENCODING"]) > 0)
					{
						define("BX_SALE_ENCODING", $arPaySysAction["ENCODING"]);
						AddEventHandler("main", "OnEndBufferContent", "ChangeEncoding");
						function ChangeEncoding($content)
						{
							global $APPLICATION;
							header("Content-Type: text/html; charset=".BX_SALE_ENCODING);
							$content = $APPLICATION->ConvertCharset($content, SITE_CHARSET, BX_SALE_ENCODING);
							$content = str_replace("charset=".SITE_CHARSET, "charset=".BX_SALE_ENCODING, $content);
						}
					}
				}
			}
			$arResult["PAY_SYSTEM"] = $arPaySysAction;
		}
		
		
		$rs_Basket = CSaleBasket::GetList(array("NAME" => "ASC", "ID" => "ASC"), array("FUSER_ID" => CSaleBasket::GetBasketUserID(), "LID" => SITE_ID, "ORDER_ID" => $arResult["ORDER_ID"]));
		$i_Summ = 0;
		while ($ar_Basket = $rs_Basket->Fetch())
		{
			$i_Summ += $ar_Basket["PRICE"] * $ar_Basket["QUANTITY"];
			$arResult['BASKET'][] = $ar_Basket;
		}
		
		$arResult['SUMM'] = $i_Summ;
	}
}
else
	$arResult['ERROR_OREDER'] = 'Y';	


$this->IncludeComponentTemplate();