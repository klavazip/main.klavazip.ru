<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock")) die();



$aMenuItem = $APPLICATION->IncludeComponent("klavazip:menu.sections", "", array(
				"IS_SEF" => "N",
				"SEF_BASE_URL" => "",
				"IBLOCK_TYPE" => "catalog",
				"IBLOCK_ID" => 51,
				"DEPTH_LEVEL" => "4",
				"CACHE_TYPE" => "A",
			), false, Array('HIDE_ICONS' => 'Y'));
			
			
			
			$props_by_section=array(); //Карта - раздел - свойство - возможные значения свойсв
			$props_array=array(); //Свойство и его свойства
			$props_xml=array(); //XML_ID значений свойств 
			$sef_array=array();//Все содержимое хайлоад блока свойств
			$sef_category_array=array();
			$props_root_xml=array();//xml коды свойств по их коду
			$props_root_sef=array();//сеф коды свойств
			
			
		
			
			
			//Выберем содержимое хайлод инфоблока sef в массив
			CModule::IncludeModule('highloadblock');
			
		//	UF_VALUE_XML_ID
			
			$HLData = \Bitrix\Highloadblock\HighloadBlockTable::getList(array('filter'=>array('TABLE_NAME'=>"bedrosova_filter_sef")));
			if ($HLBlock = $HLData->fetch())
				{
				  
					   
				   $HLBlock_entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($HLBlock);
				   
				   
				
					

						   //Зададим параметры запроса, любой параметр можно опустить
						   

							$main_query = new \Bitrix\Main\Entity\Query($HLBlock_entity);
							$main_query->setSelect(array('*'));
						//	$main_query->setFilter(array('UF_XML_ID'=> $parent_xml_id,"UF_SEF"=>$code));

							//Выполним запрос
							$res_query = $main_query->exec();

							//Получаем результат по привычной схеме
							$res_query = new CDBResult($res_query);   
							while($row = $res_query->Fetch())
							{
								
								if ($row['UF_VALUE_XML_ID']=='root'){
								
									$sef_category_array[$row['UF_CATEGORY_XML_ID']]=$row['UF_SEF'];
								}
								else{
									$sef_array[ $row['UF_VALUE_XML_ID']]=$row['UF_SEF'];
								
								}
								
							}
				
				}
				
				/*print "<pre>";
				print_r($sef_category_array);
				print "</pre>";
				
				print "<pre>";
				print_r($sef_array);
				print "</pre>";*/
				
				
					//Получим XML_ID свойств
			
				
							$IBLOCK_ID = 8;
							$properties = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$IBLOCK_ID));
							while ($prop_fields = $properties->GetNext())
							{
							    
								$props_root_xml[$prop_fields['CODE']]=$prop_fields['XML_ID'];
								if ($sef_category_array[$prop_fields['XML_ID']]){
									$props_root_sef[$prop_fields['CODE']]=$sef_category_array[$prop_fields['XML_ID']];
								}
								
							}
							
							
				/*print "<pre>";
				print_r($props_root_sef);
				print "</pre>";*/
			
			
			foreach($aMenuItem as $item){
			
	
			
				
				$item1['LINK']=$item[2][0];
				$item1["TEXT"]=$item[0];
				$item1["DEPTH_LEVEL"] =$item[3]["DEPTH_LEVEL"];
				$item1["IS_PARENT"] =$item[3]["IS_PARENT"];
				$item1["UF_PROPERTY_ID"] =$item[3]["UF_PROPERTY_ID"];
				$item1["UF_SECTION_ID"] =$item[3]["UF_SECTION_ID"];
				$item1["UF_SUBSECTIONS"] =$item[3]["UF_SUBSECTIONS"];
				$arResult['menu'][]=$item1;
				
				if ($item1["UF_PROPERTY_ID"]  &&  $item1["UF_SECTION_ID"] ){
				
					//Получим детальную страницу раздела
					$section_url="";
					$res = CIBlockSection::GetByID($item1["UF_SECTION_ID"]);
					if($ar_res = $res->GetNext()){
						
					
						$section_url=$ar_res['SECTION_PAGE_URL'];
			
						
						}
				
					//Заполним все свойсва свойсв если для свойства они еще не заполнены
					if (!$props_array[$item1["UF_PROPERTY_ID"] ]){
					
						
					
							$property_enums = CIBlockPropertyEnum::GetList(Array(), Array("IBLOCK_ID"=>8, "CODE"=>strtoupper($item1["UF_PROPERTY_ID"] )));
							while($enum_fields = $property_enums->GetNext())
							{
							
	
							
							$props_array[$item1["UF_PROPERTY_ID"] ][]=$enum_fields;
							$props_xml[$enum_fields["ID"]]=$enum_fields["XML_ID"];
							}
					}
					
					
				
					//Выберем все элементы раздела
					
				/*	$section_prop_val_arr=array();
					$arSelect = Array("PROPERTY_".$item1["UF_PROPERTY_ID"]);
					$arFilter = Array("IBLOCK_ID"=>8, "SECTION_ID"=>$item1["UF_SECTION_ID"] ,  "INCLUDE_SUBSECTIONS"=>"Y", "ACTIVE"=>"Y");
					$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
					while($ob = $res->GetNextElement()){ 
					
							$arFields = $ob->GetFields();  
						if (!$section_prop_val_arr[$arFields["PROPERTY_".strtoupper($item1["UF_PROPERTY_ID"])."_ENUM_ID"]]){
						
							$section_prop_val_arr[$arFields["PROPERTY_".strtoupper($item1["UF_PROPERTY_ID"])."_ENUM_ID"]]=array("ID"=>$arFields["PROPERTY_".strtoupper($item1["UF_PROPERTY_ID"])."_ENUM_ID"], "NAME"=>$arFields["PROPERTY_".strtoupper($item1["UF_PROPERTY_ID"])."_VALUE"]);
						
						
						}
					
					}
					
					print "<pre>";
					print_r($section_prop_val_arr);
					print "</pre>";*/
					
					$arSelect = Array("PROPERTY_".$item1["UF_PROPERTY_ID"]);
					$arFilter = Array("IBLOCK_ID"=>8, "SECTION_ID"=>$item1["UF_SECTION_ID"] , "INCLUDE_SUBSECTIONS"=>"Y",  "ACTIVE"=>"Y");
					$res = CIBlockElement::GetList(Array(), $arFilter, array("PROPERTY_".$item1["UF_PROPERTY_ID"]), false, $arSelect);
					//$res = CIBlockElement::GetList(Array("ID"=>"RAND"), $arFilter,false, Array("nPageSize"=>300), $arSelect);
					while($ob = $res->GetNextElement()){ 
					
							
						
						$arFields = $ob->GetFields();  
				
						if ($arFields["PROPERTY_".strtoupper($item1["UF_PROPERTY_ID"])."_ENUM_ID"]){
						
						
						
						
					
							
					
							
							
								
						
						
									$property_sef=$props_root_sef[$item1["UF_PROPERTY_ID"]];
									
									$property_enum_sef=$sef_array[$props_xml[$arFields["PROPERTY_".strtoupper($item1["UF_PROPERTY_ID"])."_ENUM_ID"]]];
									
									if ($property_sef && $property_enum_sef && $section_url){
									
									$property_enum_link=$section_url."filter/".$property_sef."-".$property_enum_sef."/";
									}
									else{
									$property_enum_link="";
									}
								
									$props_by_section[$item1["UF_SECTION_ID"]][$item1["UF_PROPERTY_ID"]][]=array("NAME"=>$arFields["PROPERTY_".strtoupper($item1["UF_PROPERTY_ID"])."_VALUE"],"ID"=>$arFields["PROPERTY_".strtoupper($item1["UF_PROPERTY_ID"])."_ENUM_ID"],"XML_ID"=>$props_xml[$arFields["PROPERTY_".strtoupper($item1["UF_PROPERTY_ID"])."_ENUM_ID"]],"LINK"=>$property_enum_link,"CNT"=>$arFields['CNT']);
								
					
						
						}
					}
					
					
			

					
					
				
				
				}elseif ($item1["UF_SECTION_ID"]  &&  $item1["UF_SUBSECTIONS"] >0 ){
				
				//	print $item1["UF_SECTION_ID"] ;
					
					
					//Выберем подразделы раздела
					
					
					
				
					   $arFilter = array('IBLOCK_ID' => 8,'SECTION_ID' => $item1["UF_SECTION_ID"] ); // выберет потомков без учета активности
					   $rsSect = CIBlockSection::GetList(array('NAME' => 'asc'),$arFilter, false, array("NAME","SECTION_PAGE_URL"));
					   while ($arSect = $rsSect->GetNext())
					   {
						   // получаем подразделы
						  

							$arResult['SUBSECTIONS'][$item1["UF_SECTION_ID"]][]=$arSect;
						 /* print "<pre>";
						   print_r($arSect );
						   print "</pre>";*/
					   }
					
				
				
				
				
				
				}
				
			}
			
			

			
			/*print "<pre>";
			print_r($props_by_section);
			print "</pre>";*/
			
			$arResult['props_by_section']=$props_by_section;
			

$this->IncludeComponentTemplate();

?>

