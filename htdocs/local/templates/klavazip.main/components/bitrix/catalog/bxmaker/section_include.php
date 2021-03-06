<?	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


	if($arResult['VARIABLES']['SECTION_CODE'] == 'compare')
	{
	    include('compare.php');
	}
	else
	{
		////////////////////////////////////////////////////////////////////////////////
		/// __ BXmaker ?????? ????????? ?????????? ??????? ??? ?????????? ?? ????????
		$arPageElCount = array(15, 30, 45, 60, 100);
		if(isset($_COOKIE['PAGE_ELEMENT_COUNT_'.$arParams["IBLOCK_ID"]]) && in_array(intval($_COOKIE['PAGE_ELEMENT_COUNT_'.$arParams["IBLOCK_ID"]]),$arPageElCount))
		{
			$arParams["PAGE_ELEMENT_COUNT"] = intval($_COOKIE['PAGE_ELEMENT_COUNT_'.$arParams["IBLOCK_ID"]]);
		}
		else
			$arParams["PAGE_ELEMENT_COUNT"] = 15;
		
		
		///////////////////////////////////////////////////////////////////////////////
		/// __BXmaker ?????? ???????? ??????????
		
		$arPageSorting = array(
			'name'   			    => array('asc', 'desc'),
			'timestamp_x'   	    => array('asc', 'desc'),
			'catalog_PRICE_4'  	    => array('asc', 'desc'),
			'CATALOG_QUANTITY'      => array('asc', 'desc'),
			'PROPERTY_CML2_ARTICLE' => array('asc', 'desc'),
			'SORT' 					=> array('desc', 'desc'),
				
		);
		
		$arParams['ELEMENT_SORT_FIELD_SET'] = array_keys($arPageSorting);
		$arParams['ELEMENT_SORT_FIELD_SET'] = $arParams['ELEMENT_SORT_FIELD_SET'][5];
		
		if(isset($_COOKIE['PAGE_ELEMENT_SORTING_'.$arParams["IBLOCK_ID"]]))
		{
			$arSort = explode('::',$_COOKIE['PAGE_ELEMENT_SORTING_'.$arParams["IBLOCK_ID"]]);
			if(array_key_exists($arSort[0], $arPageSorting) && count($arSort) > 0)
			{
				if(in_array($arSort[1], $arPageSorting[$arSort[0]]))
				{
					$arParams['ELEMENT_SORT_FIELD_SET'] = $arSort[0];
					if($arSort[1] == $arPageSorting[$arSort[0]][0])
						$arPageSorting[$arSort[0]] = array_reverse($arPageSorting[$arSort[0]]);
				}
			}
			
			switch($arParams['ELEMENT_SORT_FIELD_SET'])
			{
				case 'name' 				 : $arParams["ELEMENT_SORT_FIELD"] = 'name'; break;
				case 'timestamp_x'		     : $arParams["ELEMENT_SORT_FIELD"] = 'timestamp_x'; break;
				case 'catalog_PRICE_4' 		 : $arParams["ELEMENT_SORT_FIELD"] = 'catalog_PRICE_4'; break;
				case 'CATALOG_QUANTITY' 	 : $arParams["ELEMENT_SORT_FIELD"] = 'CATALOG_QUANTITY'; break;
				case 'PROPERTY_CML2_ARTICLE' : $arParams["ELEMENT_SORT_FIELD"] = 'PROPERTY_CML2_ARTICLE'; break;
				case 'SORT' 				 : $arParams["ELEMENT_SORT_FIELD"] = 'SORT'; break;
			}
			 
			$arParams["ELEMENT_SORT_ORDER"] = $arPageSorting[$arParams['ELEMENT_SORT_FIELD_SET']][1];
		}
		else
		{
			$arParams["ELEMENT_SORT_ORDER"] = 'desc';
			$arParams["ELEMENT_SORT_FIELD"] = 'SORT';
		}	
		
		$ar_CurentSort = array(
			'name' 					=> array('asc' => 'по названию'),
			'timestamp_x' 			=> array('asc' => 'по поступлению'),
			'catalog_PRICE_4' 		=> array('asc' => 'по цене (сначала дешевые)', 'desc' => 'по цене (сначала дорогие)'),	
			'CATALOG_QUANTITY' 		=> array('asc' => 'по остатку (от меньшего)',  'desc' => 'по остатку (от большего)'),	
			'PROPERTY_CML2_ARTICLE' => array('asc' => 'по артикулу (от меньшего)', 'desc' => 'по артикулу (от большего)'),	
			'SORT' 					=> array('desc' => 'по популярности'),	
		);

		
		$UF_PARAMS = array(
			'PRICE_MAX' 			=> '',
			'PRICE_MIN' 			=> '',
			'FILTER_PROPERTY_SHOW' 	=> ''
		);
		
		$TITLE_PREFIX = '';
		$dbr = CIBlockSection::GetList(
				array(),
				array('CODE' => $arResult["VARIABLES"]["SECTION_CODE"], 'IBLOCK_ID' => KlavaCatalog::IBLOCK_ID),
				false,
				array(
					'UF_TITLE',
					'UF_TITLE2',
					'UF_NEW_TITLE',
					'UF_PRICE_MAX',
					'UF_PRICE_MIN',
					'UF_PRICE_INCSEC_MAX',
					'UF_PRICE_INCSEC_MIN',
					'UF_PROPERTY_SHOW'
				)
		);
		if($ar = $dbr->Fetch()) 
		{
	
			if($arParams['INCLUDE_SUBSECTIONS'] == 'Y' || $arParams['INCLUDE_SUBSECTIONS'] == 'A')
			{
				$UF_PARAMS['PRICE_MAX'] = $ar['UF_PRICE_INCSEC_MAX'];
				$UF_PARAMS['PRICE_MIN'] = $ar['UF_PRICE_INCSEC_MIN'];
			}
			else
			{
				$UF_PARAMS['PRICE_MAX'] = $ar['UF_PRICE_MAX'];
				$UF_PARAMS['PRICE_MIN'] = $ar['UF_PRICE_MIN'];
			}
		
			foreach ( unserialize($ar['UF_PROPERTY_SHOW']) as $s_Key => $ar_Value )
			{
				$ar_ResultExt['FILTER_PROPERTY_SHOW'][] = $s_Key;
			}
			
			
			$rs_Section = CIBlockSection::GetNavChain(KlavaCatalog::IBLOCK_ID, $ar['ID'], array('ID', 'NAME'));
			$ar_SectionTitle = array();
			while ( $ar_Section = $rs_Section->GetNext(true, false))
			{
				$ar_SectionTitle[] = $ar_Section['NAME']; 
			}	
			$s_SectionTitle = implode(' ', $ar_SectionTitle);
			
			# Внимание этот грандиозный костыль был согласован с Никитой,
			switch ($s_SectionTitle)
			{
				case 'Аккумуляторы по моделям': $s_SectionTitle = 'Аккумуляторы для ноутбуков'; break;
				case 'Запчасти': $s_SectionTitle = 'Запчасти для ноутбуков'; break;
				case 'Микросхемы': $s_SectionTitle = 'Микросхемы для ноутбуков'; break;
				case 'Блоки питания': $s_SectionTitle = 'Блоки питания для ноутбуков'; break;
				case 'Клавиатуры': $s_SectionTitle = 'Клавиатуры для ноутбуков'; break;
				case 'Матрицы': $s_SectionTitle = 'Матрицы для ноутбуков'; break;
			}
			
		}
		
		$s_CatalogListView = (isset($_COOKIE['KLAVA_CATALOG_LIST_VIEW'])) ? $_COOKIE['KLAVA_CATALOG_LIST_VIEW'] : 'type1';
		?>

		<div class="boxMain">	
			<h1><?$APPLICATION->ShowProperty("page_title");?></h1>
			
			
<?
$APPLICATION->SetPageProperty("page_title", $s_SectionTitle);
?>		
			<div class="border_1"></div>	
			<div class="blockCont">
				<div class="contentLeft" style="padding-top: 0; position: relative;">
					<? 
					/* $APPLICATION->IncludeComponent('klavazip.catalog:product.filter', '', array( 
							'SECTION_ID' 	 => $ar['ID'],
							'PROPERTY_SHOW'  => unserialize($ar['UF_PROPERTY_SHOW']),
							'PRICE_INTERVAL' => array('MAX' => $UF_PARAMS['PRICE_MAX'], 'MIN' => $UF_PARAMS['PRICE_MIN']),
						)
					); */
					

$BEDROSOVA_FILTER=$APPLICATION->IncludeComponent("bedrosova:catalog.smart.filter", "template2", Array(
	"IBLOCK_TYPE" => "catalog",	// Тип инфоблока
	"IBLOCK_ID" => "8",	// Инфоблок
	"SECTION_ID" => $ar["ID"],	// ID раздела инфоблока
	"FILTER_NAME" => "arrFilter",	// Имя выходящего массива для фильтрации
	"CACHE_TYPE" => "A",	// Тип кеширования
	"CACHE_TIME" => "36000000",	// Время кеширования (сек.)
	"CACHE_GROUPS" => "Y",	// Учитывать права доступа
	"SAVE_IN_SESSION" => "N",	// Сохранять установки фильтра в сессии пользователя
	"INSTANT_RELOAD" => "N",	// Мгновенная фильтрация при включенном AJAX
	"PRICE_CODE" => array(	// Тип цены
		0 => "Розничная",
	),
	"XML_EXPORT" => "N",	// Включить поддержку Яндекс Островов
	"SECTION_TITLE" => "-",	// Заголовок
	"SECTION_DESCRIPTION" => "-",	// Описание
	),
	false,
	array(
	"ACTIVE_COMPONENT" => "Y"
	)
);
					

					
					
					
					/*
					$APPLICATION->IncludeComponent(
						"bitrix:catalog.smart.filter", "",
						Array(
							"IBLOCK_TYPE" => "catalog",
							"IBLOCK_ID" => "8",
							"SECTION_ID" => $ar["ID"],
							"FILTER_NAME" => "arrFilter",
							"PRICE_CODE" => $arParams["PRICE_CODE"],
							"CACHE_TYPE" => "A",
							"CACHE_TIME" => "36000000",
							"CACHE_GROUPS" => "N",
							"SAVE_IN_SESSION" => "N",
							"INSTANT_RELOAD" => "N",
							"XML_EXPORT" => "N",
							"SECTION_TITLE" => "-",
							"SECTION_DESCRIPTION" => "-"
						),
						$component
					); 
					*/
					
					//visual_vertical
					//echo '<pre>', print_r($component).'</pre>';
					
					/*
					$APPLICATION->IncludeComponent("bitrix:catalog.filter", "", array(
								
							'PRICE_INTERVAL'		=> array('MAX' => $UF_PARAMS['PRICE_MAX'], 'MIN' => $UF_PARAMS['PRICE_MIN']),
								
							"IBLOCK_TYPE" 			=> $arParams["IBLOCK_TYPE"],
							"IBLOCK_ID" 			=> $arParams["IBLOCK_ID"],
							"FILTER_NAME" 			=> $arParams["FILTER_NAME"],
							"FIELD_CODE" 			=> $arParams["FILTER_FIELD_CODE"],
							//"PROPERTY_CODE" 		=> $arParams["FILTER_PROPERTY_CODE"],
							"PROPERTY_CODE" 		=> $ar_ResultExt['FILTER_PROPERTY_SHOW'],
							"PRICE_CODE" 			=> $arParams["FILTER_PRICE_CODE"],
							"OFFERS_FIELD_CODE" 	=> $arParams["FILTER_OFFERS_FIELD_CODE"],
							"OFFERS_PROPERTY_CODE" 	=> $arParams["FILTER_OFFERS_PROPERTY_CODE"],
							"CACHE_TYPE" 			=> $arParams["CACHE_TYPE"],
							"CACHE_TIME" 			=> $arParams["CACHE_TIME"],
							"CACHE_GROUPS" 			=> $arParams["CACHE_GROUPS"],
						),
						$component
					);
					*/
					
					?>
				</div>
				<div class="contentRight" style="padding-top: 0">
					<div id="ex-one">
						<div class="listView">
							<ul class="nav">
								<li class="view1"><a title="Плиткой" onclick="klava.setCatalogListWiev('type1'); return false;" href="#description1" <?=($s_CatalogListView == 'type1') ? 'class="current"' : ''?> ></a></li>	
								<li class="view2"><a title="Таблицей" onclick="klava.setCatalogListWiev('type2'); return false;" href="#description2" <?=($s_CatalogListView == 'type2') ? 'class="current"' : ''?>></a></li>	
								<li class="view3"><a title="Списком" onclick="klava.setCatalogListWiev('type3'); return false;" href="#description3" <?=($s_CatalogListView == 'type3') ? 'class="current"' : ''?>></a></li>							
							</ul>
						</div>
						<div class="blockNumbersPage">
							<a href="#"><span><?=$arParams["PAGE_ELEMENT_COUNT"]?> товаров</span></a> на странице
							<div class="sortOpenList">
								<div class="townNav">								
									<ul>
										<li class="first"><a onclick="klava.setCatalogListCount(15); return false;" href="#"><span>15 товаров</span></a></li>
										<li><a onclick="klava.setCatalogListCount(30); return false;" href="#"><span>30 товаров</span></a></li>									
										<li><a onclick="klava.setCatalogListCount(45); return false;" href="#"><span>45 товаров</span></a></li>									
										<li><a onclick="klava.setCatalogListCount(60); return false;" href="#"><span>60 товаров</span></a></li>									
										<li class="last"><a href="#" onclick="klava.setCatalogListCount(100); return false;"><span>100 товаров</span></a></li>
									</ul>
									<div class="townNavTop"></div>
								</div>
							</div>
						</div>
						<div class="blockSort">
							
							Отсортировано <a href="#"><span><?=$ar_CurentSort[$arParams["ELEMENT_SORT_FIELD"]][$arParams["ELEMENT_SORT_ORDER"]]?></span></a>
							
							<div class="sortOpenListName">
								<div class="townNav">
									<ul>
										<li class="first"><a onclick="klava.setCatalogListSort('SORT', 'asc'); return false;" href="#"><span>по популярности</span></a></li>
										<li class="first"><a onclick="klava.setCatalogListSort('name', 'asc'); return false;" href="#"><span>по названию</span></a></li>
										<li><a href="#" onclick="klava.setCatalogListSort('catalog_PRICE_4', 'asc'); return false;"><span>по цене (сначала дешевые)</span></a></li>
										<li><a href="#" onclick="klava.setCatalogListSort('catalog_PRICE_4', 'desc'); return false;"><span>по цене (сначала дорогие)</span></a></li>
										<li class="last"><a onclick="klava.setCatalogListSort('timestamp_x', 'asc'); return false;" href="#"><span>по поступлению</span></a></li>
									</ul>
									<div class="townNavTop"></div>
								</div>
							</div>
						</div>
						<div class="clear"></div>
						
						<?
						$APPLICATION->IncludeComponent("bedrosova:catalog.section", ".default", array(
	"IBLOCK_TYPE" => "catalog",
	"IBLOCK_ID" => $arParams["IBLOCK_ID"],
	"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
	"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
	"SECTION_USER_FIELDS" => array(
		0 => "",
		1 => "",
	),
	"ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
	"ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
	"FILTER_NAME" => $arParams["FILTER_NAME"],
	"INCLUDE_SUBSECTIONS" => "Y",
	"SHOW_ALL_WO_SECTION" => "N",
	"PAGE_ELEMENT_COUNT" => $arParams["PAGE_ELEMENT_COUNT"],
	"LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
	"PROPERTY_CODE" => array(
		0 => "",
		1 => $arParams["LIST_PROPERTY_CODE"],
		2 => "",
	),
	"OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],
	"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
	"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
	"BASKET_URL" => $arParams["BASKET_URL"],
	"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
	"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
	"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
	"PRODUCT_PROPS_VARIABLE" => "prop",
	"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
	"AJAX_MODE" => "N",
	"AJAX_OPTION_JUMP" => "N",
	"AJAX_OPTION_STYLE" => "Y",
	"AJAX_OPTION_HISTORY" => "N",
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => $arParams["CACHE_TIME"],
	"CACHE_GROUPS" => "N",
	"META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
	"META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
	"BROWSER_TITLE" => "-",
	"ADD_SECTIONS_CHAIN" => "Y",
	"DISPLAY_COMPARE" => "N",
	"SET_TITLE" => "N",
	"SET_STATUS_404" => "N",
	"CACHE_FILTER" => "N",
	"PRICE_CODE" => $arParams["PRICE_CODE"],
	"USE_PRICE_COUNT" => "N",
	"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
	"PRICE_VAT_INCLUDE" => "N",
	"PRODUCT_PROPERTIES" => array(
	),
	"USE_PRODUCT_QUANTITY" => "N",
	"CONVERT_CURRENCY" => "N",
	"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
	"DISPLAY_TOP_PAGER" => "N",
	"DISPLAY_BOTTOM_PAGER" => "N",
	"PAGER_TITLE" => $arParams["PAGER_TITLE"],
	"PAGER_SHOW_ALWAYS" => "N",
	"PAGER_DESC_NUMBERING" => "N",
	"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
	"PAGER_SHOW_ALL" => "N",
	"AJAX_OPTION_ADDITIONAL" => ""
	),
	false
);
						
						
						$APPLICATION->IncludeComponent('klavazip.catalog:product.producer', '', array(
								'SECTION_ID' 	 => $ar['ID'],
								//'PROPERTY_SHOW'  => unserialize($ar['UF_PROPERTY_SHOW']),
								'PROPERTY_SHOW'  =>$BEDROSOVA_FILTER,
							)
						);
						?>

					</div>
<div style="font: 12px Arial; color: rgb(128, 128, 128);"><?$APPLICATION->ShowProperty("page_description");?></div>
<?$APPLICATION->SetPageProperty("page_description", "");?>
				</div>
				<div class="clear"></div>
			</div>
		</div>
		
		
		
		<?
		/*
    	?>
		<div class="margin-bottom"></div>
		<div class="page-title">
			<h1 class="cat-title">
				<?
				global $APPLICATION;
				$APPLICATION->ShowTitle();
				?>
			</h1>
			<?$APPLICATION->ShowViewContent('catalog_page_title_box');?>
		</div>

		<div class="content-bg2">
    		<div class="catalog-section-list">
			    <?
			    if($arParams["USE_FILTER"]=="Y")
			    {
			        $APPLICATION->IncludeComponent(
			        	"bitrix:catalog.filter", "", array(
			        		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
			        		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			        		"FILTER_NAME" => $arParams["FILTER_NAME"],
			        		"FIELD_CODE" => $arParams["FILTER_FIELD_CODE"],
			        		"PROPERTY_CODE" => $arParams["FILTER_PROPERTY_CODE"],
			        		"PRICE_CODE" => $arParams["FILTER_PRICE_CODE"],
			        		"OFFERS_FIELD_CODE" => $arParams["FILTER_OFFERS_FIELD_CODE"],
			        		"OFFERS_PROPERTY_CODE" => $arParams["FILTER_OFFERS_PROPERTY_CODE"],
			        		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			        		"CACHE_TIME" => $arParams["CACHE_TIME"],
			        		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
			        	),
			        	$component
			        );
			        echo '<br />';
			    }
			    */


    
}


$APPLICATION->IncludeComponent("yenisite:catalog.section_meta", ".default", array(
	"IBLOCK_TYPE" => "catalog",
	"IBLOCK_ID" => "8",
	"SECTION_ID" => $arResult["VARIABLES"]["SECTION_CODE"],
	"META_SPLITTER" => ",",
	"META_KEYWORDS" => "#NAV_CHAIN#, купить",
	"META_KEYWORDS_FORCE" => "",
	"META_DESCRIPTION" => "Купить #NAV_CHAIN# по низкой цене",
	"META_DESCRIPTION_FORCE" => "",
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => "86400"
	),
	false,
	array(
	"ACTIVE_COMPONENT" => "N"
	)
);