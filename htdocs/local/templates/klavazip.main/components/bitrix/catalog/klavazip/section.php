<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?
if ($arParams["USE_COMPARE"] == "Y"):
    $APPLICATION->IncludeComponent(
	    "bitrix:catalog.compare.list", "store", Array(
	"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
	"IBLOCK_ID" => $arParams["IBLOCK_ID"],
	"NAME" => $arParams["COMPARE_NAME"],
	"DETAIL_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["element"],
	"COMPARE_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["compare"],
	    ), $component
    );
endif;
?>

<?
$fsession_id = false;
$session_id = null;
if (isset($_GET["SECTION_ID"])) {
    $session_id = $_GET["SECTION_ID"];
} else {
    $masURI = preg_split('#/#', $_SERVER["REQUEST_URI"]);
    //echo "<pre>"; print_r($masURI);echo "</pre>";

    foreach ($masURI as $value) {
	if ($fsession_id == true) {
	    $session_id = $value;
	    break;
	}
	if ($value == "catalog") {
	    $fsession_id = true;
	}
    }
}


//ECHO $session_id
if ($session_id != null && $session_id != ''):
    ?>

    <div class="margin-bottom">&nbsp;</div>
    <div class="page-title">
        <h1 class="cat-title">
    	    <?
    	    $res = CIBlockSection::GetList(array(), array('ID' => $arResult['VARIABLES']['SECTION_ID'], 'IBLOCK_ID' => $arParams['IBLOCK_ID']), false, array('UF_TITLE', 'UF_TITLE2'));
    	    if ($ar_res = $res->GetNext()) {
    		if (!empty($ar_res['UF_TITLE2'])) {
    		    echo $ar_res['UF_TITLE2'];
    		} else {
    		    echo $ar_res['NAME'];
    		}
    		if (!empty($ar_res['UF_TITLE'])) {
    		    $APPLICATION->SetPageProperty("title", $ar_res['UF_TITLE']);
    		}
    	    }
    	    ?>
	    </h1>
        <span class="cat-switch">&nbsp;</span>
        <span class="category-list">
	    <?
	    if (CModule::IncludeModule("iblock")):
		$listCatalogObj = CIBlockSection::GetList(Array("SORT" => "ASC"), Array("IBLOCK_ID" => $arParams["IBLOCK_ID"]), false);
		$listCatalog = $listCatalogObj->GetNext();
		echo '<a href="' . $listCatalog["SECTION_PAGE_URL"] . '" style="border:none">' . $listCatalog["NAME"] . '</a>';
		while ($listCatalog = $listCatalogObj->GetNext()) {
		    if ($listCatalog["NAME"] != "")
			echo '<a href="' . $listCatalog["SECTION_PAGE_URL"] . '">' . $listCatalog["NAME"] . '</a>';
		}
	    endif;
	    ?>&nbsp;</span>

	<?
	$arAvailableSort = array(
	    "name" => Array("name", "asc"),
	    "price" => Array('catalog_PRICE_' . '4', "asc"), //$arResult['_PRICE_ID']
	    //"price" => Array('PROPERTY_MINIMUM_PRICE', "asc"),
	    "date" => Array('PROPERTY_NEWPRODUCT', "desc"),
	);
	$sort = array_key_exists("sort", $_REQUEST) && array_key_exists(ToLower($_REQUEST["sort"]), $arAvailableSort) ? $arAvailableSort[ToLower($_REQUEST["sort"])][0] : "name";
	$sort_order = array_key_exists("order", $_REQUEST) && in_array(ToLower($_REQUEST["order"]), Array("asc", "desc")) ? ToLower($_REQUEST["order"]) : $arAvailableSort[$sort][1];
	?>

        <div class="sorting">cортировать
            <span class="fake-select" style="">
            <?
                if (isset($_GET["sort"])) 
                {
            	   echo GetMessage('SECT_SORT_' . $_GET["sort"]);
                } 
                else 
                {
            	   echo "по названию";
                }
           	?>
            </span>
        	<div class="fake-select-popup redirect">
    		<?foreach ($arAvailableSort as $key => $val):
                
                $url = $APPLICATION->GetCurPageParam('sort=' . $key . '&amp;order=' . $newSort, array('sort', 'order'));
                
                $url = preg_replace('/&(?!amp;)/',"&amp;",$url);
                
    		    $className = $sort == $val[0] ? 'selected' : '';
    		    if ($className)
    			$className .= $sort_order == 'asc' ? ' asc' : ' desc';
    		    $newSort = $sort == $val[0] ? $sort_order == 'desc' ? 'asc' : 'desc'  : $arAvailableSort[$key][1];
    		    ?>
    		    <a href="<?=$url  ?>" class="<?= $className ?>" rel="nofollow"><?= GetMessage('SECT_SORT_' . $key) ?></a>
    		<? endforeach; ?>
        	</div>
        </div>
	<?

	//создаем ссылку дл€ задани€ числа страниц
	function createHrefPaginatorCount($page) {
	    $rel = null;
	    $getmass = array();
	    if (isset($_GET)) {

		$getmass = $_GET;
		$getmass["pageCountCatalog"] = $page;
		$rel = http_build_query($getmass);
		$rel = htmlspecialchars($rel);
	    } else {
		$rel = "pageCountCatalog=$page";
	    }

	    $path_parts = parse_url($_SERVER["REQUEST_URI"]);
	    $return = $path_parts["path"] . "?" . $rel;
	    //$return = substr($_SERVER["REQUEST_URI"],0,strpos($_SERVER["REQUEST_URI"],"?")+1).$rel;
	    return $return;
	}
	?>    
        <div class="sorting">|</div>
        <div class="sorting">
        	<span class="fake-select" style="">
    		<?
    		if (isset($_GET["pageCountCatalog"])) {
    		    echo $_GET["pageCountCatalog"];
    		} elseif (isset($_SESSION["pageCountCatalog"])) {
    		    echo $_SESSION["pageCountCatalog"];
    		} else {
    		    echo "10";
    		}
    		?>
        	</span> 
        	<div class="fake-select-popup redirect">
        	    <a data-val="10" href="<?= createHrefPaginatorCount(10) ?>">10</a>
        	    <a data-val="20" href="<?= createHrefPaginatorCount(20) ?>">20</a>
        	    <a data-val="30" href="<?= createHrefPaginatorCount(30) ?>">30</a>
        	</div>
        	<input class="select-input" type="hidden" value="10" /> товаров на странице
        </div>   
    </div><!-- .page-title -->
<? endif;?>



<div class="content-bg2">    

    <?
    if ($arParams["USE_FILTER"] == "Y"):
	$APPLICATION->IncludeComponent(
		"bitrix:catalog.filter", "", Array(
	    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
	    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
	    "FILTER_NAME" => "arrFilter", //$arParams["FILTER_NAME"],
	    "FIELD_CODE" => $arParams["FILTER_FIELD_CODE"],
	    "PROPERTY_CODE" => $arParams["FILTER_PROPERTY_CODE"],
	    "PRICE_CODE" => $arParams["FILTER_PRICE_CODE"],
	    "OFFERS_FIELD_CODE" => $arParams["FILTER_OFFERS_FIELD_CODE"],
	    "OFFERS_PROPERTY_CODE" => $arParams["FILTER_OFFERS_PROPERTY_CODE"],
	    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
	    "CACHE_TIME" => $arParams["CACHE_TIME"],
	    "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		), $component
	);
	global $arrFilter;
    
	if (isset($_REQUEST) && count($_REQUEST["manufactur"]) > 0) {
	    $arrFilter["PROPERTY"]["manufactur"] = $_REQUEST["manufactur"];
	}

    endif;
    ?>

    <? //‘»Ћ№“–?>
    <?php
    if (isset($_GET['price-minf'])) {
	$arrFilter[">=catalog_PRICE_4"] = $_GET['price-minf']; // √руппа цены - это еЄ ID в каталоге !!! (дл€ сортировки) //
	$arrFilter["<=catalog_PRICE_4"] = $_GET['price-maxf'];
	
    }



    if (isset($_GET['nalichie']))
	$nalusl = $_GET['nalichie'] == '1';
    else
	$nalusl = false;

    if ($nalusl)
	$arrFilter[">=CATALOG_QUANTITY"] = 1;
    ?>
 

    <?php
    $pageCount = 10;
    if (isset($_GET["pageCountCatalog"])) {
	$pageCount = $_GET["pageCountCatalog"];
	$_SESSION["pageCountCatalog"] = $_GET["pageCountCatalog"];
    } elseif (isset($_SESSION["pageCountCatalog"])) {
	$pageCount = $_SESSION["pageCountCatalog"];
    }



    $APPLICATION->IncludeComponent(
	    "bitrix:catalog.section", "", Array(
	"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
	"IBLOCK_ID" => $arParams["IBLOCK_ID"],
	"ELEMENT_SORT_FIELD" => $sort, //$arParams["ELEMENT_SORT_FIELD"],
	"ELEMENT_SORT_ORDER" => $sort_order, //$arParams["ELEMENT_SORT_ORDER"],
	"PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
	"META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
	"META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
	"BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
	"INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],
	"BASKET_URL" => $arParams["BASKET_URL"],
	"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
	"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
	"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
	"FILTER_NAME" => "arrFilter", //$arParams["FILTER_NAME"],
	"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
	"CACHE_TYPE" => $arParams["CACHE_TYPE"],
	"CACHE_TIME" => $arParams["CACHE_TIME"],
	"CACHE_FILTER" => $arParams["CACHE_FILTER"],
	"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
	"SET_TITLE" => $arParams["SET_TITLE"],
	"SET_STATUS_404" => $arParams["SET_STATUS_404"],
	"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
	"PAGE_ELEMENT_COUNT" => $pageCount,
	"LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
	"PRICE_CODE" => $arParams["PRICE_CODE"],
	"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
	"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
	"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
	"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
	"DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
	"PAGER_TITLE" => $arParams["PAGER_TITLE"],
	"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
	"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
	"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
	"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
	"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
	"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
	"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
	"OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
	"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
	"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
	"OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],
	"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
	"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
	"SECTION_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["section"],
	"DETAIL_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["element"],
	"COMPARE_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["compare"],
	"COMPARE_NAME" => $arParams["COMPARE_NAME"],
	"DISPLAY_IMG_WIDTH" => $arParams["DISPLAY_IMG_WIDTH"],
	"DISPLAY_IMG_HEIGHT" => $arParams["DISPLAY_IMG_HEIGHT"],
	"SHARPEN" => $arParams["SHARPEN"],
	"ADD_SECTIONS_CHAIN" => "Y"
	    ), $component
    );
    ?> 
</div>

