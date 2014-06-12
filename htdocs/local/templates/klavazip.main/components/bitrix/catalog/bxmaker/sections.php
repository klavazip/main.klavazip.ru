<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?




// �������� ����� ������� ��� �������� � ��������
$URL_RAW = $_SERVER['REQUEST_URI'];
if($p = strpos($URL_RAW,'?')) $URL_RAW = substr($URL_RAW,0,$p);
//BEDROSOVA 23.05.2014 Поддержка ЧПУ фильтра
//для того, чтобы поддерживать старый механизм, уберем из обработки фильтр...
//Фильтр будет обработан позже в классе KlavaCatalogFilter
if($p = strpos($URL_RAW,'filter/')) $URL_RAW = substr($URL_RAW,0,$p);
$URL = str_replace($arResult['FOLDER'],'',$URL_RAW);

// ���� ���������� �������� ����� �������� �������
if(strlen($URL) > 0 && $URL != '/' )
{
   // !!!! ������ ���  !!!! ������ ���  !!!! ������ ���  !!!! ������ ���  !!!! ������ ���  !!!! ������ ���  
    if(CModule::IncludeModule('iblock') && $URL != 'compare/')
    {
       
        if(substr($URL,-1) == '/') $URL = substr($URL,0,-1);
        
        $arTMPResult = array();
        $obCache = new CPHPCache();
        $cache_uniq = md5($URL.$arParams['IBLOCK_ID']);
        $cache_dir = '/catalog_'.$arParams['IBLOCK_ID'].'_bxmaker';
        
        
        
        if($obCache->InitCache($arParams["CACHE_TIME"],$cache_uniq,$cache_dir))
        {
            $arTMPResult = $obCache->GetVars();
        }
        else
        {
            if($obCache->StartDataCache())
            {
                $arFilter = array(
                    'IBLOCK_ID' => $arParams['IBLOCK_ID'],
                    'CODE' => $URL
                );
                $dbr = CIBlockSection::GetList(array(),$arFilter,false,array('ID','IBLOCK_ID','CODE'));
                if($ar = $dbr->Fetch())
                {
                    // ������ ������
                    $arTMPResult['IS_SECTION'] = 'Y';
                    $arTMPResult['CODE'] = $ar['CODE'];
                }
                else
                {
                    // ������ ������
                    $arTMPResult['IS_SECTION'] = 'N';
                    $arTMPResult['CODE'] = $URL;
                }
                $obCache->EndDataCache($arTMPResult);
            } 
        }
            
        if($arTMPResult['IS_SECTION'] == 'Y')
        {
            CHTTP::SetStatus("200 OK");
            //__���������� ������
            $arResult['VARIABLES']['SECTION_CODE'] = $arTMPResult['CODE'];
            include('section_include.php');
        }
        else
        {
            // __���������� �������
            $arResult['VARIABLES']['ELEMENT_CODE'] = $URL;
            include('element_include.php');
            
        }
    }
    else
    {
        include('compare.php');
    }
}
// ���� ������ ��� �������� ���
else
{
    ?>
    <div class="margin-bottom"></div>
    <div class="page-title">
    	<?$APPLICATION->ShowViewContent('catalog_page_title_box');?>
    </div>
    <div class="content-bg2">
        <div class="catalog-section-list">
    
    <?
    
    // �� ������� ������ ��������
    /*
    if($arParams["USE_COMPARE"]=="Y")
    {
        $APPLICATION->IncludeComponent(
        	"bitrix:catalog.compare.list",
        	"",
        	Array(
        		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
        		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
        		"NAME" => $arParams["COMPARE_NAME"],
        		"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
        		"COMPARE_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["compare"],
        	),
        	$component
        );
        echo '<br />';
    }
    */
    $APPLICATION->IncludeComponent(
    	"bitrix:catalog.section.list",
    	"",
    	Array(
    		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
    		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
    		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
    		"CACHE_TIME" => $arParams["CACHE_TIME"],
    		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
    		"COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
    		"TOP_DEPTH" => $arParams["SECTION_TOP_DEPTH"],
    		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"]
    	),
    	$component
    );
    ?>
        </div><!-- .catalog-section-list -->
    </div><!-- .content-bg2 -->
    <?
    
}
?>
