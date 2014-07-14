<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if($arResult['VARIABLES']['ELEMENT_CODE'] == 'compare')
{
    include('compare.php');
}
else
{
	
	
	
	$ElementID=$APPLICATION->IncludeComponent("bedrosova:catalog.element", "",
		Array(
				"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
				"IBLOCK_ID" => $arParams["IBLOCK_ID"],
				"PROPERTY_CODE" => $arParams["DETAIL_PROPERTY_CODE"],
				"META_KEYWORDS" => $arParams["DETAIL_META_KEYWORDS"],
				"META_DESCRIPTION" => $arParams["DETAIL_META_DESCRIPTION"],
				"BROWSER_TITLE" => $arParams["DETAIL_BROWSER_TITLE"],
				"BASKET_URL" => $arParams["BASKET_URL"],
				"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
				"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
				"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
				"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
				"CACHE_TYPE" => $arParams["CACHE_TYPE"],
				"CACHE_TIME" => $arParams["CACHE_TIME"],
				"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
				"SET_TITLE" => $arParams["SET_TITLE"],
				"SET_STATUS_404" => $arParams["SET_STATUS_404"],
				"PRICE_CODE" => $arParams["PRICE_CODE"],
				"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
				"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
				"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
				"PRICE_VAT_SHOW_VALUE" => $arParams["PRICE_VAT_SHOW_VALUE"],
				"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
				"LINK_IBLOCK_TYPE" => $arParams["LINK_IBLOCK_TYPE"],
				"LINK_IBLOCK_ID" => $arParams["LINK_IBLOCK_ID"],
				"LINK_PROPERTY_SID" => $arParams["LINK_PROPERTY_SID"],
				"LINK_ELEMENTS_URL" => $arParams["LINK_ELEMENTS_URL"],

				"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
				"OFFERS_FIELD_CODE" => $arParams["DETAIL_OFFERS_FIELD_CODE"],
				"OFFERS_PROPERTY_CODE" => $arParams["DETAIL_OFFERS_PROPERTY_CODE"],
				"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
				"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],

				"ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
				"ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
				"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
				"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
				"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
				"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
				'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
				'CURRENCY_ID' => $arParams['CURRENCY_ID'],
				'USE_ELEMENT_COUNTER' => $arParams['USE_ELEMENT_COUNTER'],
		),
		$component
	);
	
	
	
	if($ElementID > 0)
	{
		CHTTP::SetStatus("200 OK");
	}
	
	/*
	
	
    ?>
<div class="margin-bottom"></div>
<div class="content-bg2">
    <div class="catalog-section-list">
    
    <?
    ob_start();
    $ElementID=$APPLICATION->IncludeComponent(
    	"bedrosova:catalog.element",
    	"",
    	Array(
    		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
    		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
    		"PROPERTY_CODE" => $arParams["DETAIL_PROPERTY_CODE"],
    		"META_KEYWORDS" => $arParams["DETAIL_META_KEYWORDS"],
    		"META_DESCRIPTION" => $arParams["DETAIL_META_DESCRIPTION"],
    		"BROWSER_TITLE" => $arParams["DETAIL_BROWSER_TITLE"],
    		"BASKET_URL" => $arParams["BASKET_URL"],
    		"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
    		"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
    		"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
    		"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
    		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
    		"CACHE_TIME" => $arParams["CACHE_TIME"],
    		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
    		"SET_TITLE" => $arParams["SET_TITLE"],
    		"SET_STATUS_404" => $arParams["SET_STATUS_404"],
    		"PRICE_CODE" => $arParams["PRICE_CODE"],
    		"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
    		"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
    		"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
    		"PRICE_VAT_SHOW_VALUE" => $arParams["PRICE_VAT_SHOW_VALUE"],
    		"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
    		"LINK_IBLOCK_TYPE" => $arParams["LINK_IBLOCK_TYPE"],
    		"LINK_IBLOCK_ID" => $arParams["LINK_IBLOCK_ID"],
    		"LINK_PROPERTY_SID" => $arParams["LINK_PROPERTY_SID"],
    		"LINK_ELEMENTS_URL" => $arParams["LINK_ELEMENTS_URL"],
    
    		"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
    		"OFFERS_FIELD_CODE" => $arParams["DETAIL_OFFERS_FIELD_CODE"],
    		"OFFERS_PROPERTY_CODE" => $arParams["DETAIL_OFFERS_PROPERTY_CODE"],
    		"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
    		"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
    
    		"ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
    		"ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
    		"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
    		"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
    		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
    		"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
    		'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
    		'CURRENCY_ID' => $arParams['CURRENCY_ID'],
    		'USE_ELEMENT_COUNTER' => $arParams['USE_ELEMENT_COUNTER'],
    	),
    	$component
    );
    $content = ob_get_contents();
    ob_end_clean();
    
    // ��������������� ������ ������
    if($ElementID > 0)
    {
        CHTTP::SetStatus("200 OK");
    }
    
    
    if(strpos($content,'<!--{#ANALOGY#}-->') > -1)
    {
         // �������
         ob_start();
         $APPLICATION->IncludeComponent("bxmaker:bxmaker.catalog.analogi", ".default", array(
            	"CACHE_TYPE" => "A",
            	"CACHE_TIME" => "36000000",
            	"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
            	"IBLOCK_ID" => $arParams["IBLOCK_ID"],
            	"ELEMENT_ID" => $ElementID,
                "ELEMENT_COUNT" => 100,
                "SHOW_QUANTITY" => 'Y',
                'QUANTITY_MORE' => 'Y',
                'RETURN' => 'Y',
            	),
            	false
            );
         $ANALOGI = ob_get_contents();
         ob_end_clean();
         
         // �����������
        /* ob_start();
         $APPLICATION->IncludeComponent("bxmaker:bxmaker.catalog.comments", ".default", array(
            	"CACHE_TYPE" => "A",
            	"CACHE_TIME" => "36000000",
            	"IBLOCK_TYPE" => '',
            	"IBLOCK_ID" => 23, // ����������� � �������
            	"ELEMENT_ID" => $ElementID
            	),
            	false
            );
         $COMMENTS = ob_get_contents();
         ob_end_clean();*/
         
        //echo  str_replace(array('<!--{#ANALOGY#}-->'), array($ANALOGI), $content);
    //}
    //else
   // {
    //    echo $content;
   // }
    
    
    /*
    
    if($arParams["USE_REVIEW"]=="Y" && IsModuleInstalled("forum") && $ElementID)
    {
        echo '<br />';
        $APPLICATION->IncludeComponent(
        	"bitrix:forum.topic.reviews",
        	"",
        	Array(
        		"CACHE_TYPE" 			=> $arParams["CACHE_TYPE"],
        		"CACHE_TIME" 			=> $arParams["CACHE_TIME"],
        		"MESSAGES_PER_PAGE" 	=> $arParams["MESSAGES_PER_PAGE"],
        		"USE_CAPTCHA" 			=> $arParams["USE_CAPTCHA"],
        		"PATH_TO_SMILE" 		=> $arParams["PATH_TO_SMILE"],
        		"FORUM_ID" 				=> $arParams["FORUM_ID"],
        		"URL_TEMPLATES_READ" 	=> $arParams["URL_TEMPLATES_READ"],
        		"SHOW_LINK_TO_FORUM" 	=> $arParams["SHOW_LINK_TO_FORUM"],
        		"ELEMENT_ID" 			=> $ElementID,
        		"IBLOCK_ID" 			=> $arParams["IBLOCK_ID"],
        		"AJAX_POST" 			=> $arParams["REVIEW_AJAX_POST"],
        		"POST_FIRST_MESSAGE" 	=> $arParams["POST_FIRST_MESSAGE"],
        		"URL_TEMPLATES_DETAIL" 	=> $arParams["POST_FIRST_MESSAGE"]==="Y"? $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"] :"",
        	),
        	$component
        );
    }
    
    
    if($arParams["USE_ALSO_BUY"] == "Y" && IsModuleInstalled("sale") && $ElementID)
    {
        $APPLICATION->IncludeComponent("bitrix:sale.recommended.products", ".default", array(
        	"ID" => $ElementID,
        	"MIN_BUYES" => $arParams["ALSO_BUY_MIN_BUYES"],
        	"ELEMENT_COUNT" => $arParams["ALSO_BUY_ELEMENT_COUNT"],
        	"LINE_ELEMENT_COUNT" => $arParams["ALSO_BUY_ELEMENT_COUNT"],
        	"DETAIL_URL" => $arParams["DETAIL_URL"],
        	"BASKET_URL" => $arParams["BASKET_URL"],
        	"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
        	"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
        	"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
        	"CACHE_TYPE" => $arParams["CACHE_TYPE"],
        	"CACHE_TIME" => $arParams["CACHE_TIME"],
        	"PRICE_CODE" => $arParams["PRICE_CODE"],
        	"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
        	"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
        	"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
        	'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
        	'CURRENCY_ID' => $arParams['CURRENCY_ID'],
        	),
        	$component
        );
    }

    if($arParams["USE_STORE"] == "Y" && IsModuleInstalled("catalog") && $ElementID)
    {
        $APPLICATION->IncludeComponent("bitrix:catalog.store.amount", ".default", array(
    		"PER_PAGE" => "10",
    		"USE_STORE_PHONE" => $arParams["USE_STORE_PHONE"],
    		"SCHEDULE" => $arParams["USE_STORE_SCHEDULE"],
    		"USE_MIN_AMOUNT" => $arParams["USE_MIN_AMOUNT"],
    		"MIN_AMOUNT" => $arParams["MIN_AMOUNT"],
    		"ELEMENT_ID" => $ElementID,
    		"STORE_PATH"  =>  $arParams["STORE_PATH"],
    		"MAIN_TITLE"  =>  $arParams["MAIN_TITLE"],
    	   ),
    	   $component
        );
    }
    ?>
        </div><!-- .catalog-section-list -->
    </div><!-- .content-bg2 -->
    
    <?
    
}
*/



	
/*$APPLICATION->IncludeComponent("yenisite:catalog.detail_meta", ".default", array( 
	"IBLOCK_TYPE" => "catalog", 
	"IBLOCK_ID" => "8", 
	"ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_CODE"], 
	"META_SPLITTER" => ",", 
	//"META_TITLE" => "h1 #NAME#", 
	//"META_TITLE_FORCE" => "H1", 
	//"META_TITLE2" => "title #NAME#", 
	//"META_TITLE2_FORCE" => "TITLE", 
	"META_KEYWORDS" => "#SECTION_NAME#, #NAME#", 
	"META_KEYWORDS_FORCE" => "-", 
	"META_DESCRIPTION" => "Купить #SECTION_NAME# #NAME# по низкой цене", 
	"META_DESCRIPTION_FORCE" => "-", 
	"CACHE_TYPE" => "A", 
	"CACHE_TIME" => "3600" 
	), 
	false 
	); */
	
}







