<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if (CModule::IncludeModule("iblock"));

class CIBlockCMLCustomImport extends CIBlockCMLImport
{

	
	
	function ImportSection($xml_tree_id, $IBLOCK_ID, $parent_section_id)
	{
		
		
		global $DB, $USER_FIELD_MANAGER;

		static $arUserFields;
		if($parent_section_id === false)
		{
			$arUserFields = array();
			foreach($USER_FIELD_MANAGER->GetUserFields("IBLOCK_".$IBLOCK_ID."_SECTION") as $FIELD_ID => $arField)
			{
				if(strlen($arField["XML_ID"]) <= 0)
					$arUserFields[$FIELD_ID] = $arField;
				else
					$arUserFields[$arField["XML_ID"]] = $arField;
			}
		}

		$this->next_step["section_sort"] += 10;
		$arSection = array(
			"IBLOCK_SECTION_ID" => $parent_section_id,
			"ACTIVE" => "Y",
		);
		$rsS = $this->_xml_file->GetList(
			array("ID" => "asc"),
			array("PARENT_ID" => $xml_tree_id)
		);
		
		$XML_SECTIONS_PARENT = false;
		$XML_PROPERTIES_PARENT = false;
		$XML_SECTION_PROPERTIES = false;
		while($arS = $rsS->Fetch())
		{
			//arraytofile($arS,$_SERVER['DOCUMENT_ROOT']."/upload/sections3.txt", "section");
			if(isset($arS["VALUE_CLOB"]))
				$arS["VALUE"] = $arS["VALUE_CLOB"];

			if($arS["NAME"]==GetMessage("IBLOCK_XML2_ID"))
				$arSection["XML_ID"] = $arS["VALUE"];
			elseif($arS["NAME"]==GetMessage("IBLOCK_XML2_NAME"))
				$arSection["NAME"] = $arS["VALUE"];
			elseif($arS["NAME"]==GetMessage("IBLOCK_XML2_DESCRIPTION"))
			{
				$arSection["DESCRIPTION"] = $arS["VALUE"];
				$arSection["DESCRIPTION_TYPE"] = "html";
			}
			elseif($arS["NAME"]==GetMessage("IBLOCK_XML2_GROUPS"))
				$XML_SECTIONS_PARENT = $arS["ID"];
			elseif($arS["NAME"]==GetMessage("IBLOCK_XML2_PROPERTIES_VALUES"))
				$XML_PROPERTIES_PARENT = $arS["ID"];
			elseif($arS["NAME"]==GetMessage("IBLOCK_XML2_BX_SORT"))
				$arSection["SORT"] = intval($arS["VALUE"]);
			elseif($arS["NAME"]==GetMessage("IBLOCK_XML2_BX_CODE"))
				$arSection["CODE"] = $arS["VALUE"];
			
			elseif($arS["NAME"] == GetMessage("IBLOCK_XML2_BX_PICTURE"))
			{
				if(strlen($arS["VALUE"]) > 0)
					$arSection["PICTURE"] = $this->MakeFileArray($arS["VALUE"]);
				else
					$arSection["PICTURE"] = $this->MakeFileArray($this->_xml_file->GetAllChildrenArray($arS["ID"]));
			}
			elseif($arS["NAME"] == GetMessage("IBLOCK_XML2_BX_DETAIL_PICTURE"))
			{
				if(strlen($arS["VALUE"]) > 0)
					$arSection["DETAIL_PICTURE"] = $this->MakeFileArray($arS["VALUE"]);
				else
					$arSection["DETAIL_PICTURE"] = $this->MakeFileArray($this->_xml_file->GetAllChildrenArray($arS["ID"]));
			}
			elseif($arS["NAME"] == GetMessage("IBLOCK_XML2_BX_ACTIVE"))
				$arSection["ACTIVE"] = ($arS["VALUE"]=="true") || intval($arS["VALUE"])? "Y": "N";
			elseif($arS["NAME"]=="Статус" && $arS["VALUE"]=="Удален"){
				$arSection["ACTIVE"] = "N";
				}
			elseif($arS["NAME"] == GetMessage("IBLOCK_XML2_SECTION_PROPERTIES"))
				$XML_SECTION_PROPERTIES = $arS["ID"];
		}

		if($XML_PROPERTIES_PARENT)
		{
			$rs = $this->_xml_file->GetList(
				array("ID" => "asc"),
				array("PARENT_ID" => $XML_PROPERTIES_PARENT),
				array("ID")
			);
			while($ar = $rs->Fetch())
			{
				$arXMLProp = $this->_xml_file->GetAllChildrenArray($ar["ID"]);
				if(
					array_key_exists(GetMessage("IBLOCK_XML2_ID"), $arXMLProp)
					&& array_key_exists($arXMLProp[GetMessage("IBLOCK_XML2_ID")], $arUserFields)
				)
				{
					$FIELD_NAME = $arUserFields[$arXMLProp[GetMessage("IBLOCK_XML2_ID")]]["FIELD_NAME"];
					$MULTIPLE = $arUserFields[$arXMLProp[GetMessage("IBLOCK_XML2_ID")]]["MULTIPLE"];
					$IS_FILE = $arUserFields[$arXMLProp[GetMessage("IBLOCK_XML2_ID")]]["USER_TYPE"]["BASE_TYPE"] === "file";

					unset($arXMLProp[GetMessage("IBLOCK_XML2_ID")]);
					$arProp = array();
					$i = 0;
					foreach($arXMLProp as $value)
					{
						if($IS_FILE)
							$arProp["n".($i++)] = $this->MakeFileArray($value);
						else
							$arProp["n".($i++)] = $value;
					}

					if($MULTIPLE == "N")
						$arSection[$FIELD_NAME] = array_pop($arProp);
					else
						$arSection[$FIELD_NAME] = $arProp;
				}
			}
		}
		
		

		$obSection = new CIBlockSection;
		$rsSection = $obSection->GetList(array(), array("IBLOCK_ID"=>$IBLOCK_ID, "XML_ID"=>$arSection["XML_ID"]), false);
		if($arDBSection = $rsSection->Fetch())
		{
			if(!array_key_exists("CODE", $arSection) && is_array($this->translit_on_update))
			{
				$arSection["CODE"] = CUtil::translit($arSection["NAME"], LANGUAGE_ID, $this->translit_on_update);
				//Check if name was not changed in a way to update CODE
				if(substr($arDBSection["CODE"], 0, strlen($arSection["CODE"])) === $arSection["CODE"])
					unset($arSection["CODE"]);
				else
					$arSection["CODE"] = $this->CheckSectionCode($IBLOCK_ID, $arSection["CODE"]);
			}

			$bChanged = false;
			foreach($arSection as $key=>$value)
			{
				if(is_array($arDBSection[$key]) || ($arDBSection[$key] != $value))
				{
					$bChanged = true;
					break;
				}
			}
			if($bChanged)
			{
				foreach($arUserFields as $arField)
				{
					if($arField["USER_TYPE"]["BASE_TYPE"] == "file")
					{
						$sectionUF = $USER_FIELD_MANAGER->GetUserFields("IBLOCK_".$IBLOCK_ID."_SECTION", $arDBSection["ID"]);
						foreach($sectionUF as $arField)
						{
							if(
								$arField["USER_TYPE"]["BASE_TYPE"] == "file"
								&& isset($arSection[$arField["FIELD_NAME"]])
							)
							{
								if($arField["MULTIPLE"] == "Y" && is_array($arField["VALUE"]))
									foreach($arField["VALUE"] as $i => $old_file_id)
										$arSection[$arField["FIELD_NAME"]][] = array("del"=>true,"old_id"=>$old_file_id);
								elseif($arField["MULTIPLE"] == "N" && $arField["VALUE"] > 0)
									$arSection[$arField["FIELD_NAME"]]["old_id"] = $arField["VALUE"];
							}
						}
						break;
					}
				}

				$res = $obSection->Update($arDBSection["ID"], $arSection);
				if(!$res)
				{
					$this->LAST_ERROR = $obSection->LAST_ERROR;
					return $this->LAST_ERROR;
				}
			}
			$arSection["ID"] = $arDBSection["ID"];
		}
		else
		{
			if(!array_key_exists("CODE", $arSection) && is_array($this->translit_on_add))
				$arSection["CODE"] = $this->CheckSectionCode($IBLOCK_ID, CUtil::translit($arSection["NAME"], LANGUAGE_ID, $this->translit_on_add));

			$arSection["IBLOCK_ID"] = $IBLOCK_ID;
			if(!isset($arSection["SORT"]))
				$arSection["SORT"] = $this->next_step["section_sort"];

			$arSection["ID"] = $obSection->Add($arSection);
			if(!$arSection["ID"])
			{
				$this->LAST_ERROR = $obSection->LAST_ERROR;
				return $this->LAST_ERROR;
			}
		}

		if($XML_SECTION_PROPERTIES)
		{
			$this->ImportSectionProperties($XML_SECTION_PROPERTIES, $IBLOCK_ID, $arSection["ID"]);
		}

		if($arSection["ID"])
			$this->_xml_file->Add(array("PARENT_ID" => 0, "LEFT_MARGIN" => $arSection["ID"]));

		if($XML_SECTIONS_PARENT)
		{
			$rs = $this->_xml_file->GetList(
				array("ID" => "asc"),
				array("PARENT_ID" => $XML_SECTIONS_PARENT),
				array("ID")
			);
			while($ar = $rs->Fetch())
			{
				$result = $this->ImportSection($ar["ID"], $IBLOCK_ID, $arSection["ID"]);
				if($result !== true)
					return $result;
			}
		}

		return true;
	}


	function SetProductPrice($PRODUCT_ID, $arPrices, $arDiscounts = false)
	{


		$arDBPrices = array();
		$rsPrice = CPrice::GetList(array(), array("PRODUCT_ID" => $PRODUCT_ID));
		while($ar = $rsPrice->Fetch())
			$arDBPrices[$ar["CATALOG_GROUP_ID"].":".$ar["QUANTITY_FROM"].":".$ar["QUANTITY_TO"]] = $ar["ID"];

		if(!is_array($arPrices))
			$arPrices = array();

		foreach($arPrices as $key=>$price)
		{

			if(!isset($price[GetMessage("IBLOCK_XML2_CURRENCY")]))
				$price[GetMessage("IBLOCK_XML2_CURRENCY")] = $price["PRICE"]["CURRENCY"];

			$arPrice = Array(
				"PRODUCT_ID" => $PRODUCT_ID,
				"CATALOG_GROUP_ID" => $price["PRICE"]["ID"],
				"^PRICE" => $this->ToFloat($price[GetMessage("IBLOCK_XML2_PRICE_FOR_ONE")]),
				"CURRENCY" => $this->CheckCurrency($price[GetMessage("IBLOCK_XML2_CURRENCY")]),
			);

			if ($arPrice['CATALOG_GROUP_ID']=='4'){
					//Найдем группу в которую входит элемент для которого модифицируем цену
					$db_old_groups = CIBlockElement::GetElementGroups($arPrice['PRODUCT_ID'], true);
					while($ar_group = $db_old_groups->Fetch()){

						$_SESSION['prices'][''.$ar_group['ID'].''][]=$arPrice['^PRICE'];

					}
				}

			foreach($this->ConvertDiscounts($arDiscounts) as $arDiscount)
			{
				$arPrice["QUANTITY_FROM"] = $arDiscount["QUANTITY_FROM"];
				$arPrice["QUANTITY_TO"] = $arDiscount["QUANTITY_TO"];
				if($arDiscount["PERCENT"] > 0)
					$arPrice["PRICE"] = $arPrice["^PRICE"] - $arPrice["^PRICE"]/100*$arDiscount["PERCENT"];
				else
					$arPrice["PRICE"] = $arPrice["^PRICE"];

				$id = $arPrice["CATALOG_GROUP_ID"].":".$arPrice["QUANTITY_FROM"].":".$arPrice["QUANTITY_TO"];


				if(array_key_exists($id, $arDBPrices))
				{
					//arraytofile($arPrice,$_SERVER['DOCUMENT_ROOT']."/upload/arPrice".$arDBPrices[$id].".txt", "arPrice");
					CPrice::Update($arDBPrices[$id], $arPrice);
					unset($arDBPrices[$id]);
				}
				else
				{
					$pr_id=CPrice::Add($arPrice);

					//arraytofile($arPrice,$_SERVER['DOCUMENT_ROOT']."/upload/arPrice".$pr_id.".txt", "arPrice");
				}
			}
		}

		foreach($arDBPrices as $key=>$id)
			CPrice::Delete($id);
	}
}




class CIBlockCMLCustomImport1 extends CIBlockCMLImport
{

//	function ImportElement is called from function ImportElements($start_time, $interval)

	function ImportElement($arXMLElement, &$counter, $bWF, $arParent)
	{
		

		global $USER;
		$USER_ID = is_object($USER)? intval($USER->GetID()): 0;

		$arElement = array(
			"ACTIVE" => "Y",
			"TMP_ID" => $this->GetElementCRC($arXMLElement),
			"PROPERTY_VALUES" => array(),
		);
		if(isset($arXMLElement[GetMessage("IBLOCK_XML2_ID")]))
			$arElement["XML_ID"] = $arXMLElement[GetMessage("IBLOCK_XML2_ID")];

		$obElement = new CIBlockElement;
		$obElement->CancelWFSetMove();
		$rsElement = $obElement->GetList(
			Array("ID"=>"asc"),
			Array("=XML_ID" => $arElement["XML_ID"], "IBLOCK_ID" => $this->next_step["IBLOCK_ID"]),
			false, false,
			Array("ID", "TMP_ID", "ACTIVE", "CODE")
		);

		$bMatch = false;
		if($arDBElement = $rsElement->Fetch())
			$bMatch = ($arElement["TMP_ID"] == $arDBElement["TMP_ID"]);

		if($bMatch && $this->use_crc)
		{
			//Check Active flag in XML is not set to false
			if($this->CheckIfElementIsActive($arXMLElement))
			{
				//In case element is not active in database we have to activate it and its offers
				if($arDBElement["ACTIVE"] != "Y")
				{
					$obElement->Update($arDBElement["ID"], array("ACTIVE"=>"Y"), $bWF);
					$this->ChangeOffersStatus($arDBElement["ID"], "Y", $bWF);
					$counter["UPD"]++;
				}
			}
			$arElement["ID"] = $arDBElement["ID"];
		}
		else
		{
			if($arDBElement)
			{
				$rsProperties = $obElement->GetProperty($this->next_step["IBLOCK_ID"], $arDBElement["ID"], "sort", "asc");
				while($arProperty = $rsProperties->Fetch())
				{
					if(!array_key_exists($arProperty["ID"], $arElement["PROPERTY_VALUES"]))
						$arElement["PROPERTY_VALUES"][$arProperty["ID"]] = array(
							"bOld" => true,
						);

					$arElement["PROPERTY_VALUES"][$arProperty["ID"]][$arProperty['PROPERTY_VALUE_ID']] = array(
						"VALUE"=>$arProperty['VALUE'],
						"DESCRIPTION"=>$arProperty["DESCRIPTION"]
					);
				}
			}

			if($this->bCatalog && $this->next_step["bOffer"])
			{
				$p = strpos($arXMLElement[GetMessage("IBLOCK_XML2_ID")], "#");
				if($p !== false)
					$link_xml_id = substr($arXMLElement[GetMessage("IBLOCK_XML2_ID")], 0, $p);
				else
					$link_xml_id = $arXMLElement[GetMessage("IBLOCK_XML2_ID")];
				$arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_LINK"]] = $this->GetElementByXML_ID($this->arProperties[$this->PROPERTY_MAP["CML2_LINK"]]["LINK_IBLOCK_ID"], $link_xml_id);
			}

			if(isset($arXMLElement[GetMessage("IBLOCK_XML2_NAME")]))
				$arElement["NAME"] = $arXMLElement[GetMessage("IBLOCK_XML2_NAME")];
			if(array_key_exists(GetMessage("IBLOCK_XML2_BX_TAGS"), $arXMLElement))
				$arElement["TAGS"] = $arXMLElement[GetMessage("IBLOCK_XML2_BX_TAGS")];
			if(array_key_exists(GetMessage("IBLOCK_XML2_DESCRIPTION"), $arXMLElement))
			{
				if(strlen($arXMLElement[GetMessage("IBLOCK_XML2_DESCRIPTION")]) > 0)
					$arElement["DETAIL_TEXT"] = $arXMLElement[GetMessage("IBLOCK_XML2_DESCRIPTION")];
				else
					$arElement["DETAIL_TEXT"] = "";

				if(preg_match('/<[a-zA-Z0-9]+.*?>/', $arElement["DETAIL_TEXT"]))
					$arElement["DETAIL_TEXT_TYPE"] = "html";
				else
					$arElement["DETAIL_TEXT_TYPE"] = "text";
			}
			if(array_key_exists(GetMessage("IBLOCK_XML2_FULL_TITLE"), $arXMLElement))
			{
				if(strlen($arXMLElement[GetMessage("IBLOCK_XML2_FULL_TITLE")]) > 0)
					$arElement["PREVIEW_TEXT"] = $arXMLElement[GetMessage("IBLOCK_XML2_FULL_TITLE")];
				else
					$arElement["PREVIEW_TEXT"] = "";

				if(preg_match('/<[a-zA-Z0-9]+.*?>/', $arElement["PREVIEW_TEXT"]))
					$arElement["PREVIEW_TEXT_TYPE"] = "html";
				else
					$arElement["PREVIEW_TEXT_TYPE"] = "text";
			}
			if(array_key_exists(GetMessage("IBLOCK_XML2_BAR_CODE"), $arXMLElement))
				$arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_BAR_CODE"]] = $arXMLElement[GetMessage("IBLOCK_XML2_BAR_CODE")];
			if(array_key_exists(GetMessage("IBLOCK_XML2_BAR_CODE2"), $arXMLElement))
				$arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_BAR_CODE"]] = $arXMLElement[GetMessage("IBLOCK_XML2_BAR_CODE2")];
			if(array_key_exists(GetMessage("IBLOCK_XML2_ARTICLE"), $arXMLElement))
				$arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_ARTICLE"]] = $arXMLElement[GetMessage("IBLOCK_XML2_ARTICLE")];

			if(array_key_exists(GetMessage("IBLOCK_XML2_PICTURE"), $arXMLElement))
			{
				$rsFiles = $this->_xml_file->GetList(
					array("ID" => "asc"),
					array("PARENT_ID" => $arParent["ID"], "NAME" => GetMessage("IBLOCK_XML2_PICTURE"))
				);
				$arFile = $rsFiles->Fetch();
				if($arFile)
				{
					$description = false;
					if(strlen($arFile["ATTRIBUTES"]))
					{
						$arAttributes = unserialize($arFile["ATTRIBUTES"]);
						if(is_array($arAttributes) && array_key_exists(GetMessage("IBLOCK_XML2_DESCRIPTION"), $arAttributes))
							$description = $arAttributes[GetMessage("IBLOCK_XML2_DESCRIPTION")];
					}

					if(strlen($arFile["VALUE"]) > 0)
					{
						$file = $this->MakeFileArray($arFile["VALUE"]);
						$arElement["DETAIL_PICTURE"] = $this->ResizePicture($arFile["VALUE"], $this->detail);

						if($description !== false && is_array($arElement["DETAIL_PICTURE"]))
							$arElement["DETAIL_PICTURE"]["description"] = $description;

						if(is_array($this->preview))
						{
							$arElement["PREVIEW_PICTURE"] = $this->ResizePicture($arFile["VALUE"], $this->preview);
							if($description !== false && is_array($arElement["PREVIEW_PICTURE"]))
								$arElement["PREVIEW_PICTURE"]["description"] = $description;
						}
					}
					else
					{
						$arElement["DETAIL_PICTURE"] = $this->MakeFileArray($this->_xml_file->GetAllChildrenArray($arFile["ID"]));

						if($description !== false && is_array($arElement["DETAIL_PICTURE"]))
							$arElement["DETAIL_PICTURE"]["description"] = $description;
					}

					$prop_id = $this->PROPERTY_MAP["CML2_PICTURES"];
					if($prop_id > 0)
					{
						$i = 1;
						while($arFile = $rsFiles->Fetch())
						{
							$description = false;
							if(strlen($arFile["ATTRIBUTES"]))
							{
								$arAttributes = unserialize($arFile["ATTRIBUTES"]);
								if(is_array($arAttributes) && array_key_exists(GetMessage("IBLOCK_XML2_DESCRIPTION"), $arAttributes))
									$description = $arAttributes[GetMessage("IBLOCK_XML2_DESCRIPTION")];
							}

							if(strlen($arFile["VALUE"]) > 0)
								$arFile = $this->ResizePicture($arFile["VALUE"], $this->detail);
							else
								$arFile = $this->MakeFileArray($this->_xml_file->GetAllChildrenArray($arFile["ID"]));

							if($description !== false && is_array($arFile))
								$arFile = array(
									"VALUE" => $arFile,
									"DESCRIPTION" => $description,
								);
							$arElement["PROPERTY_VALUES"][$prop_id]["n".$i] = $arFile;
							$i++;
						}

						if(is_array($arElement["PROPERTY_VALUES"][$prop_id]))
						{
							foreach($arElement["PROPERTY_VALUES"][$prop_id] as $PROPERTY_VALUE_ID => $PROPERTY_VALUE)
							{
								if(!$PROPERTY_VALUE_ID)
									unset($arElement["PROPERTY_VALUES"][$prop_id][$PROPERTY_VALUE_ID]);
								elseif(substr($PROPERTY_VALUE_ID, 0, 1)!=="n")
									$arElement["PROPERTY_VALUES"][$prop_id][$PROPERTY_VALUE_ID] = array(
										"tmp_name" => "",
										"del" => "Y",
									);
							}
							unset($arElement["PROPERTY_VALUES"][$prop_id]["bOld"]);
						}
					}
				}
			}
//************************** begin item 1 ******************************** 02.07.2013

			if($arXMLElement["delpic"] === "Y")
			{
				$arFilter = Array(
					"XML_ID" => $arXMLElement[GetMessage("IBLOCK_XML2_ID")], 
					);
				$res = CIBlockElement::GetList(Array(), $arFilter, false, false, Array());
				if($arTmp = $res->GetNext())
				{
					if($arTmp["PREVIEW_PICTURE"])
					{
						CFile::Delete($arTmp["PREVIEW_PICTURE"]);
						$arElement["PREVIEW_PICTURE"] = array("del" => "Y");
					}
					if($arTmp["DETAIL_PICTURE"])
					{
						CFile::Delete($arTmp["DETAIL_PICTURE"]);
						$arElement["DETAIL_PICTURE"] = array("del" => "Y");
					}
				}
				
				$prop_id = $this->PROPERTY_MAP["CML2_PICTURES"];
				if($prop_id > 0)
				{
					if(is_array($arElement["PROPERTY_VALUES"][$prop_id]))
					{
						foreach($arElement["PROPERTY_VALUES"][$prop_id] as $PROPERTY_VALUE_ID => $PROPERTY_VALUE)
						{
							if(!$PROPERTY_VALUE_ID)
								unset($arElement["PROPERTY_VALUES"][$prop_id][$PROPERTY_VALUE_ID]);
							elseif(substr($PROPERTY_VALUE_ID, 0, 1)!=="n")
								$arElement["PROPERTY_VALUES"][$prop_id][$PROPERTY_VALUE_ID] = array(
									"tmp_name" => "",
									"del" => "Y",
								);
						}
						unset($arElement["PROPERTY_VALUES"][$prop_id]["bOld"]);
					}
				}
			}

//**************************** end item 1 ********************************

			if(
				array_key_exists(GetMessage("IBLOCK_XML2_FILE"), $arXMLElement)
				&& strlen($this->PROPERTY_MAP["CML2_FILES"]) > 0
			)
			{
				$prop_id = $this->PROPERTY_MAP["CML2_FILES"];
				$rsFiles = $this->_xml_file->GetList(
					array("ID" => "asc"),
					array("PARENT_ID" => $arParent["ID"], "NAME" => GetMessage("IBLOCK_XML2_FILE"))
				);
				$i = 1;
				while($arFile = $rsFiles->Fetch())
				{

					if(strlen($arFile["VALUE"]) > 0)
						$file = $this->MakeFileArray($arFile["VALUE"]);
					else
						$file = $this->MakeFileArray($this->_xml_file->GetAllChildrenArray($arFile["ID"]));

					$arElement["PROPERTY_VALUES"][$prop_id]["n".$i] = array(
						"VALUE" => $file,
						"DESCRIPTION" => $file["description"],
					);
					if(strlen($arFile["ATTRIBUTES"]))
					{
						$desc = unserialize($arFile["ATTRIBUTES"]);
						if(is_array($desc) && array_key_exists(GetMessage("IBLOCK_XML2_DESCRIPTION"), $desc))
							$arElement["PROPERTY_VALUES"][$prop_id]["n".$i]["DESCRIPTION"] = $desc[GetMessage("IBLOCK_XML2_DESCRIPTION")];
					}
					$i++;
				}

				if(is_array($arElement["PROPERTY_VALUES"][$prop_id]))
				{
					foreach($arElement["PROPERTY_VALUES"][$prop_id] as $PROPERTY_VALUE_ID => $PROPERTY_VALUE)
					{
						if(!$PROPERTY_VALUE_ID)
							unset($arElement["PROPERTY_VALUES"][$prop_id][$PROPERTY_VALUE_ID]);
						elseif(substr($PROPERTY_VALUE_ID, 0, 1)!=="n")
							$arElement["PROPERTY_VALUES"][$prop_id][$PROPERTY_VALUE_ID] = array(
								"tmp_name" => "",
								"del" => "Y",
							);
					}
					unset($arElement["PROPERTY_VALUES"][$prop_id]["bOld"]);
				}
			}

			if(isset($arXMLElement[GetMessage("IBLOCK_XML2_GROUPS")]))
			{
				$arElement["IBLOCK_SECTION"] = array();
				foreach($arXMLElement[GetMessage("IBLOCK_XML2_GROUPS")] as $key=>$value)
				{
					if(array_key_exists($value, $this->SECTION_MAP))
						$arElement["IBLOCK_SECTION"][] = $this->SECTION_MAP[$value];
				}
			}
			if(isset($arXMLElement[GetMessage("IBLOCK_XML2_PRICES")]))
			{//Collect price information for future use
				$arElement["PRICES"] = array();
				foreach($arXMLElement[GetMessage("IBLOCK_XML2_PRICES")] as $key=>$price)
				{
					if(isset($price[GetMessage("IBLOCK_XML2_PRICE_TYPE_ID")]) && array_key_exists($price[GetMessage("IBLOCK_XML2_PRICE_TYPE_ID")], $this->PRICES_MAP))
					{
						$price["PRICE"] = $this->PRICES_MAP[$price[GetMessage("IBLOCK_XML2_PRICE_TYPE_ID")]];
						$arElement["PRICES"][] = $price;
					}
				}

				$arElement["DISCOUNTS"] = array();
				if(isset($arXMLElement[GetMessage("IBLOCK_XML2_DISCOUNTS")]))
				{
					foreach($arXMLElement[GetMessage("IBLOCK_XML2_DISCOUNTS")] as $key=>$discount)
					{
						if(
							isset($discount[GetMessage("IBLOCK_XML2_DISCOUNT_CONDITION")])
							&& $discount[GetMessage("IBLOCK_XML2_DISCOUNT_CONDITION")]===GetMessage("IBLOCK_XML2_DISCOUNT_COND_VOLUME")
						)
						{
							$discount_value = $this->ToInt($discount[GetMessage("IBLOCK_XML2_DISCOUNT_COND_VALUE")]);
							$discount_percent = $this->ToFloat($discount[GetMessage("IBLOCK_XML2_DISCOUNT_COND_PERCENT")]);
							if($discount_value > 0 && $discount_percent > 0)
								$arElement["DISCOUNTS"][$discount_value] = $discount_percent;
						}
					}
				}
			}

			$arElement["CAN_BUY_ZERO"]="D";

			if(isset($arXMLElement[GetMessage("IBLOCK_XML2_AMOUNT")]))
				$arElement["QUANTITY"] = $this->ToFloat($arXMLElement[GetMessage("IBLOCK_XML2_AMOUNT")]);
			else
				$arElement["QUANTITY"] = 0;

			if(isset($arXMLElement[GetMessage("IBLOCK_XML2_ITEM_ATTRIBUTES")]))
			{
				$arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_ATTRIBUTES"]] = array();
				$i = 0;
				foreach($arXMLElement[GetMessage("IBLOCK_XML2_ITEM_ATTRIBUTES")] as $key => $value)
				{
					$arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_ATTRIBUTES"]]["n".$i] = array(
						"VALUE" => $value[GetMessage("IBLOCK_XML2_VALUE")],
						"DESCRIPTION" => $value[GetMessage("IBLOCK_XML2_NAME")],
					);
					$i++;
				}
			}
			if(isset($arXMLElement[GetMessage("IBLOCK_XML2_TRAITS_VALUES")]))
			{
				$arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_TRAITS"]] = array();
				$i = 0;
				foreach($arXMLElement[GetMessage("IBLOCK_XML2_TRAITS_VALUES")] as $key => $value)
				{
					if(
						!array_key_exists("PREVIEW_TEXT", $arElement)
						&& $value[GetMessage("IBLOCK_XML2_NAME")] == GetMessage("IBLOCK_XML2_FULL_TITLE2")
					)
					{
						$arElement["PREVIEW_TEXT"] = $value[GetMessage("IBLOCK_XML2_VALUE")];
						if(strpos($arElement["PREVIEW_TEXT"], "<")!==false)
							$arElement["PREVIEW_TEXT_TYPE"] = "html";
						else
							$arElement["PREVIEW_TEXT_TYPE"] = "text";
					}
					elseif(
						$value[GetMessage("IBLOCK_XML2_NAME")] == GetMessage("IBLOCK_XML2_HTML_DESCRIPTION")
					)
					{
						if(strlen($value[GetMessage("IBLOCK_XML2_VALUE")]) > 0)
						{
							$arElement["DETAIL_TEXT"] = $value[GetMessage("IBLOCK_XML2_VALUE")];
							$arElement["DETAIL_TEXT_TYPE"] = "html";
						}
					}
					else
					{
						if($value[GetMessage("IBLOCK_XML2_NAME")] == GetMessage("IBLOCK_XML2_WEIGHT"))
						{
							$arElement["BASE_WEIGHT"] = $this->ToFloat($value[GetMessage("IBLOCK_XML2_VALUE")])*1000;
						}

						$arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_TRAITS"]]["n".$i] = array(
							"VALUE" => $value[GetMessage("IBLOCK_XML2_VALUE")],
							"DESCRIPTION" => $value[GetMessage("IBLOCK_XML2_NAME")],
						);
						$i++;
					}
				}
			}
			if(isset($arXMLElement[GetMessage("IBLOCK_XML2_TAXES_VALUES")]))
			{
				$arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_TAXES"]] = array();
				$i = 0;
				foreach($arXMLElement[GetMessage("IBLOCK_XML2_TAXES_VALUES")] as $key => $value)
				{
					$arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_TAXES"]]["n".$i] = array(
						"VALUE" => $value[GetMessage("IBLOCK_XML2_TAX_VALUE")],
						"DESCRIPTION" => $value[GetMessage("IBLOCK_XML2_NAME")],
					);
					$i++;
				}
			}

			if(isset($arXMLElement[GetMessage("IBLOCK_XML2_BASE_UNIT")]))
			{
				$arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_BASE_UNIT"]] = $arXMLElement[GetMessage("IBLOCK_XML2_BASE_UNIT")];
			}

			if(isset($arXMLElement[GetMessage("IBLOCK_XML2_PROPERTIES_VALUES")]))
			{
				foreach($arXMLElement[GetMessage("IBLOCK_XML2_PROPERTIES_VALUES")] as $key=>$value)
				{
					if(!array_key_exists(GetMessage("IBLOCK_XML2_ID"), $value))
						continue;

					$prop_id = $value[GetMessage("IBLOCK_XML2_ID")];
					unset($value[GetMessage("IBLOCK_XML2_ID")]);

					//Handle properties which is actually element fields
					if(!array_key_exists($prop_id, $this->PROPERTY_MAP))
					{
						if($prop_id == "CML2_CODE")
							$arElement["CODE"] = isset($value[GetMessage("IBLOCK_XML2_VALUE")])? $value[GetMessage("IBLOCK_XML2_VALUE")]: "";
						elseif($prop_id == "CML2_ACTIVE")
						{
							$value = array_pop($value);
							$arElement["ACTIVE"] = ($value=="true") || intval($value)? "Y": "N";
						}
						elseif($prop_id == "CML2_SORT")
							$arElement["SORT"] = array_pop($value);
						elseif($prop_id == "CML2_ACTIVE_FROM")
							$arElement["ACTIVE_FROM"] = CDatabase::FormatDate(array_pop($value), "YYYY-MM-DD HH:MI:SS", CLang::GetDateFormat("FULL"));
						elseif($prop_id == "CML2_ACTIVE_TO")
							$arElement["ACTIVE_TO"] = CDatabase::FormatDate(array_pop($value), "YYYY-MM-DD HH:MI:SS", CLang::GetDateFormat("FULL"));
						elseif($prop_id == "CML2_PREVIEW_TEXT")
						{
							if(array_key_exists(GetMessage("IBLOCK_XML2_VALUE"), $value))
							{
								if(isset($value[GetMessage("IBLOCK_XML2_VALUE")]))
									$arElement["PREVIEW_TEXT"] = $value[GetMessage("IBLOCK_XML2_VALUE")];
								else
									$arElement["PREVIEW_TEXT"] = "";

								if(isset($value[GetMessage("IBLOCK_XML2_TYPE")]))
									$arElement["PREVIEW_TEXT_TYPE"] = $value[GetMessage("IBLOCK_XML2_TYPE")];
								else
									$arElement["PREVIEW_TEXT_TYPE"] = "html";
							}
						}
						elseif($prop_id == "CML2_DETAIL_TEXT")
						{
							if(array_key_exists(GetMessage("IBLOCK_XML2_VALUE"), $value))
							{
								if(isset($value[GetMessage("IBLOCK_XML2_VALUE")]))
									$arElement["DETAIL_TEXT"] = $value[GetMessage("IBLOCK_XML2_VALUE")];
								else
									$arElement["DETAIL_TEXT"] = "";

								if(isset($value[GetMessage("IBLOCK_XML2_TYPE")]))
									$arElement["DETAIL_TEXT_TYPE"] = $value[GetMessage("IBLOCK_XML2_TYPE")];
								else
									$arElement["DETAIL_TEXT_TYPE"] = "html";
							}
						}
						elseif($prop_id == "CML2_PREVIEW_PICTURE")
						{
							if(!is_array($this->preview) || !$arElement["PREVIEW_PICTURE"])
							{
								$arElement["PREVIEW_PICTURE"] = $this->MakeFileArray($value[GetMessage("IBLOCK_XML2_VALUE")]);
								$arElement["PREVIEW_PICTURE"]["COPY_FILE"] = "Y";
							}
						}

						continue;
					}

					$prop_id = $this->PROPERTY_MAP[$prop_id];
					$prop_type = $this->arProperties[$prop_id]["PROPERTY_TYPE"];

					if(!array_key_exists($prop_id, $arElement["PROPERTY_VALUES"]))
						$arElement["PROPERTY_VALUES"][$prop_id] = array();

					//check for bitrix extended format
					if(array_key_exists(GetMessage("IBLOCK_XML2_PROPERTY_VALUE"), $value))
					{
						$i = 1;
						$strPV = GetMessage("IBLOCK_XML2_PROPERTY_VALUE");
						$lPV = strlen($strPV);
						foreach($value as $k=>$prop_value)
						{
							if(substr($k, 0, $lPV) === $strPV)
							{
								if(array_key_exists(GetMessage("IBLOCK_XML2_SERIALIZED"), $prop_value))
									$prop_value[GetMessage("IBLOCK_XML2_VALUE")] = $this->Unserialize($prop_value[GetMessage("IBLOCK_XML2_VALUE")]);
								if($prop_type=="F")
								{
									$prop_value[GetMessage("IBLOCK_XML2_VALUE")] = $this->MakeFileArray($prop_value[GetMessage("IBLOCK_XML2_VALUE")]);
								}
								elseif($prop_type=="G")
									$prop_value[GetMessage("IBLOCK_XML2_VALUE")] = $this->GetSectionByXML_ID($this->arProperties[$prop_id]["LINK_IBLOCK_ID"], $prop_value[GetMessage("IBLOCK_XML2_VALUE")]);
								elseif($prop_type=="E")
									$prop_value[GetMessage("IBLOCK_XML2_VALUE")] = $this->GetElementByXML_ID($this->arProperties[$prop_id]["LINK_IBLOCK_ID"], $prop_value[GetMessage("IBLOCK_XML2_VALUE")]);
								elseif($prop_type=="L")
									$prop_value[GetMessage("IBLOCK_XML2_VALUE")] = $this->GetEnumByXML_ID($this->arProperties[$prop_id]["ID"], $prop_value[GetMessage("IBLOCK_XML2_VALUE")]);

								if(array_key_exists("bOld", $arElement["PROPERTY_VALUES"][$prop_id]))
								{
									if($prop_type=="F")
									{
										foreach($arElement["PROPERTY_VALUES"][$prop_id] as $PROPERTY_VALUE_ID => $PROPERTY_VALUE)
											$arElement["PROPERTY_VALUES"][$prop_id][$PROPERTY_VALUE_ID] = array(
												"tmp_name" => "",
												"del" => "Y",
											);
										unset($arElement["PROPERTY_VALUES"][$prop_id]["bOld"]);
									}
									else
										$arElement["PROPERTY_VALUES"][$prop_id] = array();
								}

								$arElement["PROPERTY_VALUES"][$prop_id]["n".$i] = array(
									"VALUE" => $prop_value[GetMessage("IBLOCK_XML2_VALUE")],
									"DESCRIPTION" => $prop_value[GetMessage("IBLOCK_XML2_DESCRIPTION")],
								);
							}
							$i++;
						}
					}
					else
					{
						if($prop_type == "L" && !array_key_exists(GetMessage("IBLOCK_XML2_VALUE_ID"), $value))
							$l_key = GetMessage("IBLOCK_XML2_VALUE");
						else
							$l_key = GetMessage("IBLOCK_XML2_VALUE_ID");

						foreach($value as $k=>$prop_value)
						{
							if(array_key_exists("bOld", $arElement["PROPERTY_VALUES"][$prop_id]))
							{
								if($prop_type=="F")
								{
									foreach($arElement["PROPERTY_VALUES"][$prop_id] as $PROPERTY_VALUE_ID => $PROPERTY_VALUE)
										$arElement["PROPERTY_VALUES"][$prop_id][$PROPERTY_VALUE_ID] = array(
											"tmp_name" => "",
											"del" => "Y",
										);
									unset($arElement["PROPERTY_VALUES"][$prop_id]["bOld"]);
								}
								else
								{
									$arElement["PROPERTY_VALUES"][$prop_id] = array();
								}
							}

							if($prop_type == "L" && $k == $l_key)
							{
								$prop_value = $this->GetEnumByXML_ID($this->arProperties[$prop_id]["ID"], $prop_value);
							}
							elseif($prop_type == "N" && isset($this->next_step["sdp"]))
							{
								$prop_value = $this->ToFloat($prop_value);
							}

							$arElement["PROPERTY_VALUES"][$prop_id][] = $prop_value;
						}
					}
				}
			}

			//If there is no BaseUnit specified check prices for it
			if(
				(
					!array_key_exists($this->PROPERTY_MAP["CML2_BASE_UNIT"], $arElement["PROPERTY_VALUES"])
					|| (
						is_array($arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_BASE_UNIT"]])
						&& array_key_exists("bOld", $arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_BASE_UNIT"]])
					)
				)
				&& isset($arXMLElement[GetMessage("IBLOCK_XML2_PRICES")])
			)
			{
				foreach($arXMLElement[GetMessage("IBLOCK_XML2_PRICES")] as $key => $price)
				{
					if(
						isset($price[GetMessage("IBLOCK_XML2_PRICE_TYPE_ID")])
						&& array_key_exists($price[GetMessage("IBLOCK_XML2_PRICE_TYPE_ID")], $this->PRICES_MAP)
						&& array_key_exists(GetMessage("IBLOCK_XML2_MEASURE"), $price)
					)
					{
						$arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_BASE_UNIT"]] = $price[GetMessage("IBLOCK_XML2_MEASURE")];
						break;
					}
				}
			}

			if($arDBElement)
			{
				foreach($arElement["PROPERTY_VALUES"] as $prop_id=>$prop)
				{
					if(is_array($arElement["PROPERTY_VALUES"][$prop_id]) && array_key_exists("bOld", $arElement["PROPERTY_VALUES"][$prop_id]))
					{
						if($this->arProperties[$prop_id]["PROPERTY_TYPE"]=="F")
							unset($arElement["PROPERTY_VALUES"][$prop_id]);
						else
							unset($arElement["PROPERTY_VALUES"][$prop_id]["bOld"]);
					}
				}

				if(intval($arElement["MODIFIED_BY"]) <= 0 && $USER_ID > 0)
					$arElement["MODIFIED_BY"] = $USER_ID;

				if(!array_key_exists("CODE", $arElement) && is_array($this->translit_on_update))
				{
					$arElement["CODE"] = CUtil::translit($arElement["NAME"], LANGUAGE_ID, $this->translit_on_update);
					//Check if name was not changed in a way to update CODE
					if(substr($arDBElement["CODE"], 0, strlen($arElement["CODE"])) === $arElement["CODE"])
						unset($arElement["CODE"]);
					else
						$arElement["CODE"] = $this->CheckElementCode($this->next_step["IBLOCK_ID"], $arElement["CODE"]);
				}
				
				//arraytofile($arElement, $_SERVER['DOCUMENT_ROOT']."/___dev/logs/arElement.txt", "arElement");
				//arraytofile($arXMLElement, $_SERVER['DOCUMENT_ROOT']."/___dev/logs/arXMLElement.txt", "arXMLElement");
				
				
				$obElement->Update($arDBElement["ID"], $arElement, $bWF, true, $this->iblock_resize);
				//In case element was not active in database we have to activate its offers
				if($arDBElement["ACTIVE"] != "Y")
				{
					$this->ChangeOffersStatus($arDBElement["ID"], "Y", $bWF);
				}
				$arElement["ID"] = $arDBElement["ID"];
				if($arElement["ID"])
				{
					$counter["UPD"]++;
				}
				else
				{
					$this->LAST_ERROR = $obElement->LAST_ERROR;
					$counter["ERR"]++;
				}
			}
			else
			{
				if(!array_key_exists("CODE", $arElement) && is_array($this->translit_on_add))
					$arElement["CODE"] = $this->CheckElementCode($this->next_step["IBLOCK_ID"], CUtil::translit($arElement["NAME"], LANGUAGE_ID, $this->translit_on_add));

				$arElement["IBLOCK_ID"] = $this->next_step["IBLOCK_ID"];
				$arElement["ID"] = $obElement->Add($arElement, $bWF, true, $this->iblock_resize);
				if($arElement["ID"])
				{
					$counter["ADD"]++;
				}
				else
				{
					$this->LAST_ERROR = $obElement->LAST_ERROR;
					$counter["ERR"]++;
				}
			}

			if($arElement["ID"] && $this->bCatalog && $this->next_step["bOffer"])
			{
				$CML_LINK = $this->PROPERTY_MAP["CML2_LINK"];

				$arProduct = array(
					"ID" => $arElement["ID"],
				);
				if(isset($arElement["QUANTITY"]))
					$arProduct["QUANTITY"] = $arElement["QUANTITY"];

				if(isset($arElement["BASE_WEIGHT"]))
				{
					$arProduct["WEIGHT"] = $arElement["BASE_WEIGHT"];
				}
				else
				{
					$rsWeight = CIBlockElement::GetProperty($this->arProperties[$CML_LINK]["LINK_IBLOCK_ID"], $arElement["PROPERTY_VALUES"][$CML_LINK], array(), array("CODE" => "CML2_TRAITS"));
					while($arWheight = $rsWeight->Fetch())
					{
						if($arWheight["DESCRIPTION"] == GetMessage("IBLOCK_XML2_WEIGHT"))
							$arProduct["WEIGHT"] = $this->ToFloat($arWheight["VALUE"])*1000;
					}
				}

				if(isset($arElement["PRICES"]))
				{
					//Here start VAT handling

					//Check if all the taxes exists in BSM catalog
					$arTaxMap = array();
					$rsTaxProperty = CIBlockElement::GetProperty($this->arProperties[$CML_LINK]["LINK_IBLOCK_ID"], $arElement["PROPERTY_VALUES"][$CML_LINK], "sort", "asc", array("CODE" => "CML2_TAXES"));
					while($arTaxProperty = $rsTaxProperty->Fetch())
					{
						if(
							strlen($arTaxProperty["VALUE"]) > 0
							&& strlen($arTaxProperty["DESCRIPTION"]) > 0
							&& !array_key_exists($arTaxProperty["DESCRIPTION"], $arTaxMap)
						)
						{
							$arTaxMap[$arTaxProperty["DESCRIPTION"]] = array(
								"RATE" => $this->ToFloat($arTaxProperty["VALUE"]),
								"ID" => $this->CheckTax($arTaxProperty["DESCRIPTION"], $this->ToFloat($arTaxProperty["VALUE"])),
							);
						}
					}

					//First find out if all the prices have TAX_IN_SUM true
					$TAX_IN_SUM = "Y";
					foreach($arElement["PRICES"] as $key=>$price)
					{
						if($price["PRICE"]["TAX_IN_SUM"] !== "true")
						{
							$TAX_IN_SUM = "N";
							break;
						}
					}
					//If there was found not insum tax we'll make shure
					//that all prices has the same flag
					if($TAX_IN_SUM === "N")
					{
						foreach($arElement["PRICES"] as $key=>$price)
						{
							if($price["PRICE"]["TAX_IN_SUM"] !== "false")
							{
								$TAX_IN_SUM = "Y";
								break;
							}
						}
						//Check if there is a mix of tax in sum
						//and correct it by recalculating all the prices
						if($TAX_IN_SUM === "Y")
						{
							foreach($arElement["PRICES"] as $key=>$price)
							{
								if($price["PRICE"]["TAX_IN_SUM"] !== "true")
								{
									$TAX_NAME = $price["PRICE"]["TAX_NAME"];
									if(array_key_exists($TAX_NAME, $arTaxMap))
									{
										$PRICE_WO_TAX = $this->ToFloat($price[GetMessage("IBLOCK_XML2_PRICE_FOR_ONE")]);
										$PRICE = $PRICE_WO_TAX + ($PRICE_WO_TAX / 100.0 * $arTaxMap[$TAX_NAME]["RATE"]);
										$arElement["PRICES"][$key][GetMessage("IBLOCK_XML2_PRICE_FOR_ONE")] = $PRICE;
									}
								}
							}
						}
					}
					foreach($arElement["PRICES"] as $key=>$price)
					{
						$TAX_NAME = $price["PRICE"]["TAX_NAME"];
						if(array_key_exists($TAX_NAME, $arTaxMap))
						{
							$arProduct["VAT_ID"] = $arTaxMap[$TAX_NAME]["ID"];
							break;
						}
					}
					$arProduct["VAT_INCLUDED"] = $TAX_IN_SUM;
				}

				CCatalogProduct::Add($arProduct);

				if(isset($arElement["PRICES"]))
					$this->SetProductPrice($arElement["ID"], $arElement["PRICES"], $arElement["DISCOUNTS"]);

			}
		}

		if(isset($arXMLElement[GetMessage("IBLOCK_XML2_STORE_AMOUNT_LIST")]))
		{
			$arElement["STORE_AMOUNT"]=array();
			foreach($arXMLElement[GetMessage("IBLOCK_XML2_STORE_AMOUNT_LIST")] as $key=>$storeAmount)
			{
				if(isset($storeAmount[GetMessage("IBLOCK_XML2_STORE_ID")]))
				{
					$storeXMLID = $storeAmount[GetMessage("IBLOCK_XML2_STORE_ID")];
					$amount = $storeAmount[GetMessage("IBLOCK_XML2_AMOUNT")];
					$arElement["STORE_AMOUNT"][]=array($storeXMLID => $amount);
				}
			}
		}
		if(isset($arElement["STORE_AMOUNT"]))
			$this->ImportStoresAmount($arElement["STORE_AMOUNT"], $arElement["ID"], $counter);

		return $arElement["ID"];
	}

} 


/*************************************************************************
	Processing of received parameters
*************************************************************************/

$arParams["IBLOCK_TYPE"] = trim($arParams["IBLOCK_TYPE"]);
$arParams["INTERVAL"] = intval($arParams["INTERVAL"]);

if(!is_array($arParams["GROUP_PERMISSIONS"]))
	$arParams["GROUP_PERMISSIONS"] = array(1);

if(!is_array($arParams["SITE_LIST"]))
	$arParams["SITE_LIST"] = array();

if($arParams["ELEMENT_ACTION"]!="N" && $arParams["ELEMENT_ACTION"]!="A")
	$arParams["ELEMENT_ACTION"] = "D";
if($arParams["SECTION_ACTION"]!="N" && $arParams["SECTION_ACTION"]!="A")
	$arParams["SECTION_ACTION"] = "D";

$arParams["FILE_SIZE_LIMIT"] = intval($arParams["FILE_SIZE_LIMIT"]);
if($arParams["FILE_SIZE_LIMIT"] < 1)
	$arParams["FILE_SIZE_LIMIT"] = 200*1024; //200KB

$arParams["USE_CRC"] = $arParams["USE_CRC"]!="N";
$arParams["USE_ZIP"] = $arParams["USE_ZIP"]!="N";
$arParams["USE_OFFERS"] = $arParams["USE_OFFERS"]=="Y";
$arParams["USE_IBLOCK_TYPE_ID"] = $arParams["USE_IBLOCK_TYPE_ID"]=="Y";
$arParams["USE_IBLOCK_PICTURE_SETTINGS"] = $arParams["USE_IBLOCK_PICTURE_SETTINGS"]=="Y";
$arParams["SKIP_ROOT_SECTION"] = $arParams["SKIP_ROOT_SECTION"]=="Y";

if($arParams["USE_IBLOCK_PICTURE_SETTINGS"])
{
	$preview = true;
	$detail = true;
}
else
{
	$arParams["GENERATE_PREVIEW"] = $arParams["GENERATE_PREVIEW"]!="N";
	if($arParams["GENERATE_PREVIEW"])
	{
		$preview = array(
			intval($arParams["PREVIEW_WIDTH"]) > 1? intval($arParams["PREVIEW_WIDTH"]): 100,
			intval($arParams["PREVIEW_HEIGHT"]) > 1? intval($arParams["PREVIEW_HEIGHT"]): 100,
		);
	}
	else
	{
		$preview = false;
	}

	$arParams["DETAIL_RESIZE"] = $arParams["DETAIL_RESIZE"]!="N";
	if($arParams["DETAIL_RESIZE"])
	{
		$detail = array(
			intval($arParams["DETAIL_WIDTH"]) > 1? intval($arParams["DETAIL_WIDTH"]): 300,
			intval($arParams["DETAIL_HEIGHT"]) > 1? intval($arParams["DETAIL_HEIGHT"]): 300,
		);
	}
	else
	{
		$detail = false;
	}
}

$arParams["TRANSLIT_MAX_LEN"] = intval($arParams["TRANSLIT_MAX_LEN"]);
if($arParams["TRANSLIT_MAX_LEN"] <= 0)
	$arParams["TRANSLIT_MAX_LEN"] = 100;
if(!array_key_exists("TRANSLIT_CHANGE_CASE", $arParams))
	$arParams["TRANSLIT_CHANGE_CASE"] = 'L'; // 'L' - toLower, 'U' - toUpper, false - do not change
if(!array_key_exists("TRANSLIT_REPLACE_SPACE", $arParams))
	$arParams["TRANSLIT_REPLACE_SPACE"] = '_';
if(!array_key_exists("TRANSLIT_REPLACE_OTHER", $arParams))
	$arParams["TRANSLIT_REPLACE_OTHER"] = '_';
$arParams["TRANSLIT_DELETE_REPEAT_REPLACE"] = $arParams["TRANSLIT_DELETE_REPEAT_REPLACE"] !== "N";

$arTranslitParams = array(
	"max_len" => $arParams["TRANSLIT_MAX_LEN"],
	"change_case" => $arParams["TRANSLIT_CHANGE_CASE"],
	"replace_space" => $arParams["TRANSLIT_REPLACE_SPACE"],
	"replace_other" => $arParams["TRANSLIT_REPLACE_OTHER"],
	"delete_repeat_replace" => $arParams["TRANSLIT_DELETE_REPEAT_REPLACE"],
);

$arParams["TRANSLIT_ON_ADD"] = $arParams["TRANSLIT_ON_ADD"] === "Y";
$arParams["TRANSLIT_ON_UPDATE"] = $arParams["TRANSLIT_ON_UPDATE"] === "Y";

if($arParams["INTERVAL"] <= 0)
	@set_time_limit(0);

$start_time = time();

$bUSER_HAVE_ACCESS = false;
if(isset($GLOBALS["USER"]) && is_object($GLOBALS["USER"]))
{
	$bUSER_HAVE_ACCESS = $GLOBALS["USER"]->IsAdmin();
	if(!$bUSER_HAVE_ACCESS)
	{
		$arUserGroupArray = $GLOBALS["USER"]->GetUserGroupArray();
		foreach($arParams["GROUP_PERMISSIONS"] as $PERM)
		{
			if(in_array($PERM, $arUserGroupArray))
			{
				$bUSER_HAVE_ACCESS = true;
				break;
			}
		}
	}
}

$bDesignMode = $GLOBALS["APPLICATION"]->GetShowIncludeAreas()
		&& !isset($_GET["mode"])
		&& is_object($GLOBALS["USER"])
		&& $GLOBALS["USER"]->IsAdmin();

if(!$bDesignMode)
{
	if(!isset($_GET["mode"]))
		return;
	$APPLICATION->RestartBuffer();
	header("Pragma: no-cache");
}

$DIR_NAME = "";

ob_start();

if($_GET["mode"] == "checkauth" && $USER->IsAuthorized())
{
	if(
		(COption::GetOptionString("main", "use_session_id_ttl", "N") == "Y")
		&& (COption::GetOptionInt("main", "session_id_ttl", 0) > 0)
		&& !defined("BX_SESSION_ID_CHANGE")
	)
	{
		echo "failure\n",GetMessage("CC_BSC1_ERROR_SESSION_ID_CHANGE");
	}
	else
	{
		echo "success\n";
		echo session_name()."\n";
		echo session_id() ."\n";
	}
}
elseif(!$USER->IsAuthorized())
{
	echo "failure\n",GetMessage("CC_BSC1_ERROR_AUTHORIZE");
}
elseif(!$bUSER_HAVE_ACCESS)
{
	echo "failure\n",GetMessage("CC_BSC1_PERMISSION_DENIED");
}
elseif(!CModule::IncludeModule('iblock'))
{
	echo "failure\n",GetMessage("CC_BSC1_ERROR_MODULE");
}
else
{
	//We have to strongly check all about file names at server side
	$DIR_NAME = "/".COption::GetOptionString("main", "upload_dir", "upload")."/1c_catalog";
	$ABS_FILE_NAME = false;
	$WORK_DIR_NAME = false;
	if(isset($_GET["filename"]) && (strlen($_GET["filename"])>0))
	{
		//This check for 1c server on linux
		$filename = preg_replace("#^(/tmp/|upload/1c/webdata)#", "", $_GET["filename"]);
		$filename = trim(str_replace("\\", "/", trim($filename)), "/");

		$io = CBXVirtualIo::GetInstance();
		$bBadFile = HasScriptExtension($filename)
			|| IsFileUnsafe($filename)
			|| !$io->ValidatePathString("/".$filename)
		;

		if(!$bBadFile)
		{
			$FILE_NAME = rel2abs($_SERVER["DOCUMENT_ROOT"].$DIR_NAME, "/".$filename);
			if((strlen($FILE_NAME) > 1) && ($FILE_NAME === "/".$filename))
			{
				$ABS_FILE_NAME = $_SERVER["DOCUMENT_ROOT"].$DIR_NAME.$FILE_NAME;
				$WORK_DIR_NAME = substr($ABS_FILE_NAME, 0, strrpos($ABS_FILE_NAME, "/")+1);
			}
		}
	}

	if(($_GET["mode"] == "file") && $ABS_FILE_NAME)
	{
		//Read http data
		if(function_exists("file_get_contents"))
			$DATA = file_get_contents("php://input");
		elseif(isset($GLOBALS["HTTP_RAW_POST_DATA"]))
			$DATA = &$GLOBALS["HTTP_RAW_POST_DATA"];
		else
			$DATA = false;

		$DATA_LEN = defined("BX_UTF")? mb_strlen($DATA, 'latin1'): strlen($DATA);
		//And save it the file
		if($DATA_LEN > 0 || $_GET["method"]='ftp')
		{
			CheckDirPath($ABS_FILE_NAME);
			if($fp = fopen($ABS_FILE_NAME, "ab"))
			{
				$result = fwrite($fp, $DATA);
				if($result === $DATA_LEN)
				{
					echo "success\n";
					if($_SESSION["BX_CML2_IMPORT"]["zip"])
						$_SESSION["BX_CML2_IMPORT"]["zip"] = $ABS_FILE_NAME;
				}
				else
				{
					echo "failure\n",GetMessage("CC_BSC1_ERROR_FILE_WRITE", array("#FILE_NAME#"=>$FILE_NAME));
				}
			}
			else
			{
				echo "failure\n",GetMessage("CC_BSC1_ERROR_FILE_OPEN", array("#FILE_NAME#"=>$FILE_NAME));
			}
		}
		else
		{
			echo "failure\n",GetMessage("CC_BSC1_ERROR_HTTP_READ");
		}
	}
	elseif(($_GET["mode"] == "import") && $_SESSION["BX_CML2_IMPORT"]["zip"])
	{
		if(!array_key_exists("last_zip_entry", $_SESSION["BX_CML2_IMPORT"]))
			$_SESSION["BX_CML2_IMPORT"]["last_zip_entry"] = "";

		$result = CIBlockXMLFile::UnZip($_SESSION["BX_CML2_IMPORT"]["zip"], $_SESSION["BX_CML2_IMPORT"]["last_zip_entry"]);
		if($result===false)
		{
			echo "failure\n",GetMessage("CC_BSC1_ZIP_ERROR");
		}
		elseif($result===true)
		{
			$_SESSION["BX_CML2_IMPORT"]["zip"] = false;
			echo "progress\n".GetMessage("CC_BSC1_ZIP_DONE");
		}
		else
		{
			$_SESSION["BX_CML2_IMPORT"]["last_zip_entry"] = $result;
			echo "progress\n".GetMessage("CC_BSC1_ZIP_PROGRESS");
		}
	}
	elseif(($_GET["mode"] == "import") && $ABS_FILE_NAME)
	{
		$NS = &$_SESSION["BX_CML2_IMPORT"]["NS"];
		$strError = "";
		$strMessage = "";

		if($NS["STEP"] < 1)
		{
			CIBlockXMLFile::DropTemporaryTables();
			$strMessage = GetMessage("CC_BSC1_TABLES_DROPPED");
			$NS["STEP"] = 1;
		}
		elseif($NS["STEP"] == 1)
		{
			if(CIBlockXMLFile::CreateTemporaryTables())
			{
				$strMessage = GetMessage("CC_BSC1_TABLES_CREATED");
				$NS["STEP"] = 2;
			}
			else
			{
				$strError = GetMessage("CC_BSC1_TABLE_CREATE_ERROR");
			}
		}
		elseif($NS["STEP"] == 2)
		{
			$fp = fopen($ABS_FILE_NAME, "rb");
			$total = filesize($ABS_FILE_NAME);
			
			//arraytofile( array($fp) , $_SERVER['DOCUMENT_ROOT']."/___dev/logs/xml.xml", "xml");

			if(($total > 0) && is_resource($fp))
			{
				$obXMLFile = new CIBlockXMLFile;
				if($obXMLFile->ReadXMLToDatabase($fp, $NS, $arParams["INTERVAL"]))
				{
					$NS["STEP"] = 3;
					$strMessage = GetMessage("CC_BSC1_FILE_READ");
				}
				else
				{
					$strMessage = GetMessage("CC_BSC1_FILE_PROGRESS", array("#PERCENT#"=>$total > 0? round($obXMLFile->GetFilePosition()/$total*100, 2): 0));
				}
				fclose($fp);
			}
			else
			{
				$strError = GetMessage("CC_BSC1_FILE_ERROR");
			}
		}
		elseif($NS["STEP"] == 3)
		{
			if(CIBlockXMLFile::IndexTemporaryTables())
			{

				if (CModule::IncludeModule("iblock")){
		$arrSections=array();
		$arFilter=array("IBLOCK_ID"=>8,"ACTIVE"=>"Y");
		$db_list = CIBlockSection::GetList(Array(), $arFilter, false, array("UF_PRICE_MIN","UF_PRICE_MAX","UF_PRICE_INCSEC_MIN","UF_PRICE_INCSEC_MAX"));

		while($ar_result = $db_list->GetNext())
		  {
			$arrSections[''.$ar_result['ID'].'']=array("min"=>$ar_result["UF_PRICE_MIN"],"max"=>$ar_result["UF_PRICE_MAX"],"min_vs"=>$ar_result["UF_PRICE_INCSEC_MIN"],"max_vs"=>$ar_result["UF_PRICE_INCSEC_MAX"],"parent"=>$ar_result["IBLOCK_SECTION_ID"]);
		  }

		$_SESSION['arrSections']=$arrSections;
		//$_SESSION['prices']=array();
		//arraytofile($_SESSION,$_SERVER['DOCUMENT_ROOT']."/upload/SESSION.txt", "SESSION");


		}


				$strMessage = GetMessage("CC_BSC1_INDEX_CREATED");
				$NS["STEP"] = 4;
			}
			else
				$strError = GetMessage("CC_BSC1_INDEX_CREATE_ERROR");
		}
		elseif($NS["STEP"] == 4)
		{
			$obCatalog = new CIBlockCMLImport;
			$obCatalog->InitEx($NS, array(
				"files_dir" => $WORK_DIR_NAME,
				"use_crc" => $arParams["USE_CRC"],
				"preview" => $preview,
				"detail" => $detail,
				"use_offers" => $arParams["USE_OFFERS"],
				"use_iblock_type_id" => $arParams["USE_IBLOCK_TYPE_ID"],
				"translit_on_add" => $arParams["TRANSLIT_ON_ADD"],
				"translit_on_update" => $arParams["TRANSLIT_ON_UPDATE"],
				"translit_params" => $arTranslitParams,
				"skip_root_section" => $arParams["SKIP_ROOT_SECTION"],
			));
			$result = $obCatalog->ImportMetaData(1, $arParams["IBLOCK_TYPE"], $arParams["SITE_LIST"]);
			if($result === true)
			{
				$strMessage = GetMessage("CC_BSC1_METADATA_IMPORTED");
				$NS["STEP"] = 5;
			}
			else
			{
				$strError = GetMessage("CC_BSC1_METADATA_ERROR").implode("\n", $result);
			}
		}
		elseif($NS["STEP"] == 5)
		{
			$obCatalog = new CIBlockCMLCustomImport;
			$obCatalog->InitEx($NS, array(
				"files_dir" => $WORK_DIR_NAME,
				"use_crc" => $arParams["USE_CRC"],
				"preview" => $preview,
				"detail" => $detail,
				"use_offers" => $arParams["USE_OFFERS"],
				"use_iblock_type_id" => $arParams["USE_IBLOCK_TYPE_ID"],
				"translit_on_add" => $arParams["TRANSLIT_ON_ADD"],
				"translit_on_update" => $arParams["TRANSLIT_ON_UPDATE"],
				"translit_params" => $arTranslitParams,
				"skip_root_section" => $arParams["SKIP_ROOT_SECTION"],
			));
			$result = $obCatalog->ImportSections();
			$strMessage = GetMessage("CC_BSC1_SECTIONS_IMPORTED");
			$NS["STEP"] = 6;
		}
		elseif($NS["STEP"] == 6)
		{
			$obCatalog = new CIBlockCMLCustomImport;
			$obCatalog->InitEx($NS, array(
				"files_dir" => $WORK_DIR_NAME,
				"use_crc" => $arParams["USE_CRC"],
				"preview" => $preview,
				"detail" => $detail,
				"use_offers" => $arParams["USE_OFFERS"],
				"use_iblock_type_id" => $arParams["USE_IBLOCK_TYPE_ID"],
				"translit_on_add" => $arParams["TRANSLIT_ON_ADD"],
				"translit_on_update" => $arParams["TRANSLIT_ON_UPDATE"],
				"translit_params" => $arTranslitParams,
				"skip_root_section" => $arParams["SKIP_ROOT_SECTION"],
			));
			$result = $obCatalog->DeactivateSections($arParams["SECTION_ACTION"]);
			$obCatalog->SectionsResort();
			$strMessage = GetMessage("CC_BSC1_SECTION_DEA_DONE");
			$NS["STEP"] = 7;
		}
		elseif($NS["STEP"] == 7)
		{
			if(($NS["DONE"]["ALL"] <= 0) && $NS["XML_ELEMENTS_PARENT"])
			{
				$rs = $DB->Query("select count(*) C from b_xml_tree where PARENT_ID = ".intval($NS["XML_ELEMENTS_PARENT"]));
				$ar = $rs->Fetch();
				$NS["DONE"]["ALL"] = $ar["C"];
			}

			//$obCatalog = new CIBlockCMLImport;
			$obCatalog = new CIBlockCMLCustomImport1;
			$obCatalog->InitEx($NS, array(
				"files_dir" => $WORK_DIR_NAME,
				"use_crc" => $arParams["USE_CRC"],
				"preview" => $preview,
				"detail" => $detail,
				"use_offers" => $arParams["USE_OFFERS"],
				"use_iblock_type_id" => $arParams["USE_IBLOCK_TYPE_ID"],
				"translit_on_add" => $arParams["TRANSLIT_ON_ADD"],
				"translit_on_update" => $arParams["TRANSLIT_ON_UPDATE"],
				"translit_params" => $arTranslitParams,
				"skip_root_section" => $arParams["SKIP_ROOT_SECTION"],
			));
			$obCatalog->ReadCatalogData($_SESSION["BX_CML2_IMPORT"]["SECTION_MAP"], $_SESSION["BX_CML2_IMPORT"]["PRICES_MAP"]);
			$result = $obCatalog->ImportElements($start_time, $arParams["INTERVAL"]);

			$counter = 0;
			foreach($result as $key=>$value)
			{
				$NS["DONE"][$key] += $value;
				$counter+=$value;
			}

			if(!$counter)
			{
				$strMessage = GetMessage("CC_BSC1_DONE");
				$NS["STEP"] = 8;
			}
			elseif(strlen($obCatalog->LAST_ERROR))
			{
				$strError = $obCatalog->LAST_ERROR;
			}
			else
			{
				$strMessage = GetMessage("CC_BSC1_PROGRESS", array("#TOTAL#"=>$NS["DONE"]["ALL"],"#DONE#"=>intval($NS["DONE"]["CRC"])));
			}
		}
		elseif($NS["STEP"] == 8)
		{
			$obCatalog = new CIBlockCMLImport;
			$obCatalog->Init($NS);
			$result = $obCatalog->DeactivateElement($arParams["ELEMENT_ACTION"], $start_time, $arParams["INTERVAL"]);

			$counter = 0;
			foreach($result as $key=>$value)
			{
				$NS["DONE"][$key] += $value;
				$counter+=$value;
			}

			if(!$counter)
			{
				$strMessage = GetMessage("CC_BSC1_DEA_DONE");
				$NS["STEP"] = 9;
			}
			else
			{
				$strMessage = GetMessage("CC_BSC1_PROGRESS", array("#TOTAL#"=>$NS["DONE"]["ALL"],"#DONE#"=>$NS["DONE"]["NON"]));
			}
		}
		else
		{
			$NS["STEP"]++;
		}

		if($strError)
		{
			echo "failure\n";
			echo str_replace("<br>", "", $strError);
		}
		elseif($NS["STEP"] < 10)
		{
			echo "progress\n",$strMessage;
		}
		else
		{
			//foreach(GetModuleEvents("catalog", "OnSuccessCatalogImport1C", true) as $arEvent)
			//ExecuteModuleEventEx($arEvent);
			
			$rs_ec = CIBlockElement::GetList(
			array(), 
			array("IBLOCK_ID"=>8, '!%CODE' => '/'), 
			false, 
			false, 
			array('ID', 'NAME', 'CODE', 'PROPERTY_CML2_ARTICLE', 'PROPERTY_CML2_BAR_CODE', 'DATE_CREATE', 'TIMESTAMP_X')
			);
			if($rs_ec->SelectedRowsCount() > 0)
			{
				while($ar_ec = $rs_ec->GetNext(true, false))
				{
					$oElement_ec = new CIBlockElement();
					$oElement_ec->Update($ar_ec["ID"], array("CODE" => "recalculate"));
				}
			}

$potomki=array();
				$arrSections=$_SESSION['arrSections'];
				foreach($arrSections as $key=>$val){
				//Найдем предков раздела

						if($val['parent']){
							$potomki[''.$val['parent'].''][]=$key;
						}

				}

				$predki=array();

					foreach($potomki as $key=>$val){
						foreach($val as $key2=>$val2){
							$predki[''.$val2.''][]=$key;
						}

					}


				$arrSections=$_SESSION['arrSections'];
				$exchange_min_max=array();
				foreach($_SESSION['prices'] as $section=>$prices){
					foreach($prices as $key=>$price){
						if ($price<$arrSections[''.$section.'']['min'] || !$arrSections[''.$section.'']['min'] ){
							$exchange_min_max[''.$section.'']['min']=$price;
							$arrSections[''.$section.'']['min']=$price;
						}
						
						if ($price>$arrSections[''.$section.'']['max'] || !$arrSections[''.$section.'']['max'] ){
							$exchange_min_max[''.$section.'']['max']=$price;
							$arrSections[''.$section.'']['max']=$price;
						}
						
						if ($price<$arrSections[''.$section.'']['min_vs'] || !$arrSections[''.$section.'']['min_vs'] ){
							$exchange_min_max[''.$section.'']['min_vs']=$price;
							$arrSections[''.$section.'']['min_vs']=$price;
						}
						
						if ($price>$arrSections[''.$section.'']['max_vs'] || !$arrSections[''.$section.'']['max_vs'] ){
							$exchange_min_max[''.$section.'']['max_vs']=$price;
							$arrSections[''.$section.'']['max_vs']=$price;
						}
						
						
						$section_parent=$arrSections[''.$section.'']['parent'];
						if ($section_parent>0){
							if ($price<$arrSections[''.$section_parent.'']['min_vs'] || !$arrSections[''.$section_parent.'']['min_vs'] ){
								$exchange_min_max[''.$section_parent.'']['min_vs']=$price;
								$arrSections[''.$section_parent.'']['min_vs']=$price;
							}
							
							if ($price>$arrSections[''.$section_parent.'']['max_vs'] || !$arrSections[''.$section_parent.'']['max_vs'] ){
								$exchange_min_max[''.$section_parent.'']['max_vs']=$price;
								$arrSections[''.$section_parent.'']['max_vs']=$price;
							} 
						}
					}
				
				}
				
				//arraytofile($exchange_min_max,$_SERVER['DOCUMENT_ROOT']."/upload/exchange_min_max.txt", "exchange_min_max");
				
			foreach($exchange_min_max as $key=>$val){
				
					
					if ($val['max']) $GLOBALS["USER_FIELD_MANAGER"]->Update( "IBLOCK_8_SECTION",  $key, array("UF_PRICE_MAX"=>$val['max']));
					if ($val['min']) $GLOBALS["USER_FIELD_MANAGER"]->Update("IBLOCK_8_SECTION", $key, array("UF_PRICE_MIN"=>$val['min']));
					if ($val['max_vs']) $GLOBALS["USER_FIELD_MANAGER"]->Update("IBLOCK_8_SECTION", $key, array("UF_PRICE_INCSEC_MAX"=>$val['max_vs']));
					if ($val['min_vs']) $GLOBALS["USER_FIELD_MANAGER"]->Update("IBLOCK_8_SECTION", $key, array("UF_PRICE_INCSEC_MIN"=>$val['min_vs']));
				
}
				
				
				
				
				//arraytofile($potomki,$_SERVER['DOCUMENT_ROOT']."/upload/potomki.txt", "potomki");
				//arraytofile($predki,$_SERVER['DOCUMENT_ROOT']."/upload/predki.txt", "predki");
				
				
				unset($_SESSION['prices']);


			echo "success\n",GetMessage("CC_BSC1_IMPORT_SUCCESS");
			$_SESSION["BX_CML2_IMPORT"] = array(
				"zip" => $_SESSION["BX_CML2_IMPORT"]["zip"], //save from prev load
				"NS" => array(
					"STEP" => 0,
				),
				"SECTION_MAP" => false,
				"PRICES_MAP" => false,
			);
		}
	}
	elseif($_GET["mode"]=="init")
	{
		DeleteDirFilesEx($DIR_NAME);
		CheckDirPath($_SERVER["DOCUMENT_ROOT"].$DIR_NAME."/");
		if(!is_dir($_SERVER["DOCUMENT_ROOT"].$DIR_NAME))
		{
			echo "failure\n",GetMessage("CC_BSC1_ERROR_INIT");
		}
		else
		{
			$_SESSION["BX_CML2_IMPORT"] = array(
				"zip" => $arParams["USE_ZIP"] && function_exists("zip_open"),
				"NS" => array(
					"STEP" => 0,
				),
				"SECTION_MAP" => false,
				"PRICES_MAP" => false,
			);
			echo "zip=".($_SESSION["BX_CML2_IMPORT"]["zip"]? "yes": "no")."\n";
			echo "file_limit=".$arParams["FILE_SIZE_LIMIT"]."\n";
		}
	}
	else
	{
		echo "failure\n",GetMessage("CC_BSC1_ERROR_UNKNOWN_COMMAND");
	}
}

$contents = ob_get_contents();
ob_end_clean();

if($DIR_NAME != "")
{
	$ht_name = $_SERVER["DOCUMENT_ROOT"].$DIR_NAME."/.htaccess";
	file_put_contents($ht_name, "Deny from All");
	@chmod($ht_name, BX_FILE_PERMISSIONS);
}

if(!$bDesignMode)
{
	if(toUpper(LANG_CHARSET) != "WINDOWS-1251")
		$contents = $APPLICATION->ConvertCharset($contents, LANG_CHARSET, "windows-1251");
	header("Content-Type: text/html; charset=windows-1251");

	echo $contents;
	die();
}
else
{
	$this->IncludeComponentLang(".parameters.php");
	$arAction = array(
		"N" => GetMessage("CC_BCI1_NONE"),
		"A" => GetMessage("CC_BCI1_DEACTIVATE"),
		"D" => GetMessage("CC_BCI1_DELETE"),
	);

	if(
		(COption::GetOptionString("main", "use_session_id_ttl", "N") == "Y")
		&& (COption::GetOptionInt("main", "session_id_ttl", 0) > 0)
		&& !defined("BX_SESSION_ID_CHANGE")
	)
		ShowError(GetMessage("CC_BSC1_ERROR_SESSION_ID_CHANGE"));
	?><table class="data-table">
	<tr><td><?echo GetMessage("CC_BCI1_IBLOCK_TYPE")?></td><td><?echo $arParams["IBLOCK_TYPE"]?></td></tr>
	<tr><td><?echo GetMessage("CC_BCI1_SITE_LIST")?></td><td><?echo implode(", ", $arParams["SITE_LIST"])?></td></tr>
	<tr><td><?echo GetMessage("CC_BCI1_INTERVAL")?></td><td><?echo $arParams["INTERVAL"]?></td></tr>
	<tr><td><?echo GetMessage("CC_BCI1_ELEMENT_ACTION")?></td><td><?echo $arAction[$arParams["ELEMENT_ACTION"]]?></td></tr>
	<tr><td><?echo GetMessage("CC_BCI1_SECTION_ACTION")?></td><td><?echo $arAction[$arParams["SECTION_ACTION"]]?></td></tr>
	<tr><td><?echo GetMessage("CC_BCI1_FILE_SIZE_LIMIT")?></td><td><?echo $arParams["FILE_SIZE_LIMIT"]?></td></tr>
	<tr><td><?echo GetMessage("CC_BCI1_USE_CRC")?></td><td><?echo $arParams["USE_CRC"]? GetMessage("MAIN_YES"): GetMessage("MAIN_NO")?></td></tr>
	<tr><td><?echo GetMessage("CC_BCI1_USE_ZIP")?></td><td><?echo $arParams["USE_ZIP"]? GetMessage("MAIN_YES"): GetMessage("MAIN_NO")?></td></tr>
	<tr><td><?echo GetMessage("CC_BCI1_USE_IBLOCK_PICTURE_SETTINGS")?></td><td><?echo $arParams["USE_IBLOCK_PICTURE_SETTINGS"]? GetMessage("MAIN_YES"): GetMessage("MAIN_NO")?></td></tr>
	<?if(!$arParams["USE_IBLOCK_PICTURE_SETTINGS"]):?>
		<tr><td><?echo GetMessage("CC_BCI1_GENERATE_PREVIEW")?></td><td><?echo $arParams["GENERATE_PREVIEW"]? GetMessage("MAIN_YES")." ".$arParams["PREVIEW_WIDTH"]."x".$arParams["PREVIEW_HEIGHT"]: GetMessage("MAIN_NO")?></td></tr>
		<tr><td><?echo GetMessage("CC_BCI1_DETAIL_RESIZE")?></td><td><?echo $arParams["DETAIL_RESIZE"]? GetMessage("MAIN_YES")." ".$arParams["DETAIL_WIDTH"]."x".$arParams["DETAIL_HEIGHT"]: GetMessage("MAIN_NO")?></td></tr>
	<?endif?>
	<tr><td><?echo GetMessage("CC_BCI1_TRANSLIT_ON_ADD")?></td><td><?echo $arParams["TRANSLIT_ON_ADD"]? GetMessage("MAIN_YES"): GetMessage("MAIN_NO")?></td></tr>
	<tr><td><?echo GetMessage("CC_BCI1_TRANSLIT_ON_UPDATE")?></td><td><?echo $arParams["TRANSLIT_ON_UPDATE"]? GetMessage("MAIN_YES"): GetMessage("MAIN_NO")?></td></tr>
	<?if($arParams["TRANSLIT_ON_ADD"] || $arParams["TRANSLIT_ON_UPDATE"]):?>
		<tr><td><?echo GetMessage("CC_BCI1_TRANSLIT_MAX_LEN")?></td><td><?echo $arParams["TRANSLIT_MAX_LEN"]?></td></tr>
		<tr><td><?echo GetMessage("CC_BCI1_TRANSLIT_CHANGE_CASE")?></td><td><?
			if($arParams["TRANSLIT_CHANGE_CASE"] == "L" || $arParams["TRANSLIT_CHANGE_CASE"] == "l")
				echo GetMessage("CC_BCI1_TRANSLIT_CHANGE_CASE_LOWER");
			elseif($arParams["TRANSLIT_CHANGE_CASE"] == "U" || $arParams["TRANSLIT_CHANGE_CASE"] == "u")
				echo GetMessage("CC_BCI1_TRANSLIT_CHANGE_CASE_UPPER");
			else
				echo GetMessage("CC_BCI1_TRANSLIT_CHANGE_CASE_PRESERVE");
		?></td></tr>
		<tr><td><?echo GetMessage("CC_BCI1_TRANSLIT_REPLACE_SPACE")?></td><td><?echo $arParams["TRANSLIT_REPLACE_SPACE"]?></td></tr>
		<tr><td><?echo GetMessage("CC_BCI1_TRANSLIT_REPLACE_OTHER")?></td><td><?echo $arParams["TRANSLIT_REPLACE_OTHER"]?></td></tr>
		<tr><td><?echo GetMessage("CC_BCI1_TRANSLIT_DELETE_REPEAT_REPLACE")?></td><td><?echo $arParams["TRANSLIT_DELETE_REPEAT_REPLACE"]? GetMessage("MAIN_YES"): GetMessage("MAIN_NO")?></td></tr>
	<?endif?>
	<tr><td><?echo GetMessage("CC_BCI1_USE_OFFERS")?></td><td><?echo $arParams["USE_OFFERS"]? GetMessage("MAIN_YES"): GetMessage("MAIN_NO")?></td></tr>
	</table>
	<?
}
?>