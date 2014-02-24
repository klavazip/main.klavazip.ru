<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$arDeclen = array(
		'Аккумуляторы по моделям' => 'аккумулятора',
		'Аккумуляторы по ноутбукам' => 'аккумулятора',
		'Микросхемы' => 'микросхемы',
		'Видеокарты' => 'видеокарты',
		'Клавиатуры' => 'клавиатуры',
		'Кабели' => 'кабеля',
		'Матрицы' => 'матрицы',
		'Переходники' => 'переходника',
		'Корпусные детали' => 'корпусной детали',
		'Разъемы' => 'разъема',
		'Системы охлаждения' => 'системы охлаждения'
);

$arDeclenAll = $arDeclen[$arResult['SECTION']['PATH'][0]['NAME']];
//echo '<pre>'; print_r($arResult); echo '</pre>';


$arDeclenTitle = array(
		'Аккумуляторы по моделям' => 'аккумулятор',
		'Аккумуляторы по ноутбукам' => 'аккумулятор',
		'Микросхемы' => 'микросхему',
		'Видеокарты' => 'видеокарту',
		'Клавиатуры' => 'клавиатуру',
		'Кабели' => 'кабель',
		'Матрицы' => 'матрицу (экран)',
		'Переходники' => 'переходник',
		'Корпусные детали' => 'корпусную деталь',
		'Разъемы' => 'разъем',
		'Системы охлаждения' => 'систему охлаждения'
);

$arDeclenAllTitle = $arDeclenTitle[$arResult['SECTION']['PATH'][0]['NAME']];
//echo '<pre>'; print_r($arResult); echo '</pre>';


$arDeclenOtz = array(
		'Аккумуляторы по моделям' => 'аккумуляторе',
		'Аккумуляторы по ноутбукам' => 'аккумуляторе',
		'Микросхемы' => 'микросхеме',
		'Видеокарты' => 'видеокарте',
		'Клавиатуры' => 'клавиатуре',
		'Кабели' => 'кабеле',
		'Матрицы' => 'матрице',
		'Переходники' => 'переходнике',
		'Корпусные детали' => 'корпусной детали',
		'Разъемы' => 'разъеме',
		'Системы охлаждения' => 'системе охлаждения'
);

$arDeclenAllOtz = $arDeclenOtz[$arResult['SECTION']['PATH'][0]['NAME']];
//echo '<pre>'; print_r($arResult); echo '</pre>';


$arDeclenDlaNout = array(
		'Аккумуляторы по моделям' => 'для ноутбука',
		'Аккумуляторы по ноутбукам' => 'для ноутбука',
		'Микросхемы' => 'для ноутбука',
		'Видеокарты' => 'для ноутбука',
		'Клавиатуры' => 'для ноутбука',
		'Кабели' => 'для ноутбука',
		'Матрицы' => 'для ноутбука',
		'Переходники' => 'для ноутбука',
		'Корпусные детали' => 'для ноутбука',
		'Разъемы' => 'для ноутбука',
		'Системы охлаждения' => 'для ноутбука'
);

$arDeclenAllDlaNout = $arDeclenDlaNout[$arResult['SECTION']['PATH'][0]['NAME']];
?>
<? ///*noa? oi?ie?aiaiey caaieiaea aey ?acaaea, e oeeuo?aoee*/ $this->SetViewTarget('catalog_page_title_box');?>
<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/components/bitrix/catalog/bxmaker/bitrix/catalog.element/.default/jquery.elevateZoom-2.5.3.min.js"></script>

<h1 class="cat-title">
    <?
    $TITLE_PREFIX = '';
    $dbr = CIBlockSection::GetList(array(),array(
            'ID' => $arResult['IBLOCK_SECTION_ID'],
            'IBLOCK_ID' => $arResult['IBLOCK_ID']
        ),
        false,
        array(
            'UF_TITLE', // Title no?aieou
            'UF_TITLE2', // Caaieiaie no?aieou
            'UF_NEW_TITLE', // Iiaiene e yeaiaioai
            'UF_DESCRIPTION_UM'
        )
    );
    if($ar = $dbr->Fetch())
    {
        $TITLE_PREFIX = str_replace(array('#SECTION_NAME#'),array($ar['NAME']),$ar['UF_NEW_TITLE']);
        $TITLE_PREFIX_TITLE = str_replace(array('#SECTION_NAME#'),array($ar['NAME']),$ar['UF_NEW_TITLE']);
    }
    //echo '<pre>',print_r($arResult['PROPERTIES']['manufactur']),'</pre>';



    ?>
</h1>
<?// /*eiiao aeiea caaieiaea*/ $this->EndViewTarget();?>
<?
$arProduct = CCatalogProduct::GetByID($arResult['ID']);
$arProductPrice = GetCatalogProductPriceList($arResult['ID']);

?>

<table class="item-table" >
<tr>
<td class="item-left">

<?

?>

    <div class="item-left">
        <div class="item-pic relative">
            <div class="labels" style="z-index:100">
                <?
                if(isset($arResult['PROPERTIES']['SALELEADER']['VALUE']) &&  strlen(trim($arResult['PROPERTIES']['SALELEADER']['VALUE'])) > 0)
                {
                    ?><div class="label-hit"></div><?
                }
                if(isset($arResult['PROPERTIES']['NEWPRODUCT']['VALUE']) &&  strlen(trim($arResult['PROPERTIES']['NEWPRODUCT']['VALUE'])) > 0 )
                {
                    ?><div class="label-new"></div><?
                }
                ?>
            </div>
            <?

            //BXMDebug($arResult);

            if (isset($arResult['DETAIL_PICTURE']['SRC']))
            {

			 ?>
<? CModule::IncludeModule('yenisite.resizer2'); ?>


<div class="gallery-prevew" >
<div id="prevew"><img src="<?=CResizer2Resize::ResizeGD2($arResult['DETAIL_PICTURE']['SRC'], 1);?>" class="gallery-prevew-image" width="249"></div>
	
</div>

 


<?/*
<a href="<?=CResizer2Resize::ResizeGD2($arResult['DETAIL_PICTURE']['SRC'], 1);?>" class = "cloud-zoom" id="zoom1"  rel="adjustX: 25, adjustY:-3,zoomWidth:600,zoomHeight:400">
<img  src="<?=CResizer2Resize::ResizeGD2($arResult['DETAIL_PICTURE']['SRC'], 2);?>" width="249" alt="<?=GetMessage('P_FOTO_SEO').$arDeclenAll.' '.$arResult['NAME'].' '.$arResult['SECTION']['NAME']?>" title="<?=GetMessage('P_FOTO_SEO').$arDeclenAll.' '.$arResult['NAME'].' '.$arResult['SECTION']['NAME']?>"/> 
</a>
*/?>

</div>
<div class="item-pics">
		
		

<div style="text-align: center; width: 600px;">
<div class="gallery-list" >
	<div id="gallery-item-hover"></div>
	
	<span class="gallery-item" ><img src="<?=CResizer2Resize::ResizeGD2($arResult['DETAIL_PICTURE']['SRC'],1);?>" ></span>
	
	<?
		 
			
		foreach ($arResult["MORE_PHOTO"] as $PHOTO)
                {?>
				
				<span class="gallery-item" ><img src="<?=CResizer2Resize::ResizeGD2($PHOTO["SRC"],1);?>" ></span>
				
				<?}
		
	?>
		
		
		

	</div>
</div>



			 <div class="clearfix"></div>
        </div>




<?
				
            }
            else
            {
                $srcpic = SITE_TEMPLATE_PATH.'/img/no-pic-big.jpg';
                echo '<a href="'.$srcpic.'" title="'.$arResult["NAME"].'">
                        <span class="zoom"></span>
                        <img src="'.$srcpic.'" width="249" alt="'.'no-photo'.'" title="'.'no-photo'.'" />
                        </a>';
						
				?></div><?
            }
            ?>
        

        <div class="clearfix"></div>

        <?
        if(isset($arResult['PROPERTIES']['CML2_ARTICLE']['VALUE']) && strlen($arResult['PROPERTIES']['CML2_ARTICLE']['VALUE']) > 0 )
        {
            echo '<div style="text-align:center;"><span class="artikul" >'.GetMessage('P_FOTO_SEO').$arDeclenAll.' '.$arResult['NAME'].' '.$arResult['SECTION']['NAME'].' '.GetMessage('CATALOG_ITEM_ARTICUL',array('#ARTICUL#'=>$arResult['PROPERTIES']['CML2_ARTICLE']['VALUE'])).'</span></div>';
        }
        ?>

        <!--noindex-->
        <div class="social-links">
            <table>
                <tr>
                    <td style="width:40px">
                        <iframe src="//www.facebook.com/plugins/like.php?href=<? echo urlencode($_SERVER["REQUEST_URI"]) ?>&amp;send=false&amp;layout=button_count&amp;width=125&amp;show_faces=true&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21"  style="border:none; overflow:hidden; width:102px; height:20px;" ></iframe>
                    </td>
                    <td style='padding-bottom:10px'>
                        <!-- VK Widget Like -->
                        <div id="vk_like"></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <a href="https://twitter.com/share" class="twitter-share-button" data-via="KlavaZip" data-size="small">Tweet</a>
                        <script>!function(d, s, id) {
                                var js, fjs = d.getElementsByTagName(s)[0];
                                if (!d.getElementById(id)) {
                                    js = d.createElement(s);
                                    js.id = id;
                                    js.src = "//platform.twitter.com/widgets.js";
                                    fjs.parentNode.insertBefore(js, fjs);
                                }
                            }(document, "script", "twitter-wjs");
                        </script>
                    </td>
                    <td>
                        <script type="text/javascript">

                            // Iiianoeoa yoio oaa ooaa, aaa aie?ia ioia?a?aouny eiiiea +1.
                            document.write('<div style="overflow: hidden; width: 39px; height: 25px; padding: 0;"><g:plusone annotation="inline">&nbsp;</g:plusone></div>');
                            //Iiianoeoa yoio aucia ooieoee ioia?a?aiey a niioaaonoao?uaa ianoi.
                            window.___gcfg = {lang: 'ru'};

                            (function() {
                                var po = document.createElement('script');
                                po.type = 'text/javascript';
                                po.async = true;
                                po.src = 'https://apis.google.com/js/plusone.js';
                                var s = document.getElementsByTagName('script')[0];
                                s.parentNode.insertBefore(po, s);


                            })();


                        </script>
                    </td>
                </tr>
            </table>
        </div>

        <div class="item-links">
            <p>
                        <span>
                            <img src="<?= SITE_TEMPLATE_PATH ?>/img/icon-question.png" width="6" height="11" alt=" " />
                        </span>
                <a href="#" class="openQuestion" data-product-name="<?=$arResult["NAME"];?>" ><?=GetMessage('CATALOG_ITEM_ASK');?></a>
            </p>
            <?//if($arProduct["QUANTITY"] > 0):?>
            <p class=" catalog-item-compare-link-box" data-product-id="<?=$arResult['ID'];?>"  >
                        <span>
                            <img src="<?=SITE_TEMPLATE_PATH . '/img/icon-weight.png';?>" width="13" height="12" alt=" " />
                        </span>
                <span class="link"><?=GetMessage('CATALOG_ITEM_COMPARE');?></span>
            <p style="clear: both;margin:0;padding:0;"></p>
            <p></p>
            <p class="catalog-item-wishlist-link-box"  data-product-id="<?=$arResult['ID'];?>"   >
                        <span>
                            <img src="<?= SITE_TEMPLATE_PATH ?>/img/icon-heart.png" width="11" height="10" alt=" " />
                        </span>
                <span class="link" ><?=GetMessage('CATALOG_ITEM_TO_WISHLIST');?></span>
            <p style="clear: both;margin:0;padding:0;"></p>
            <p></p>
            <?//endif;?>

        </div>
        <!--/noindex-->
    </div>
</td>
<td class="item-right">
    <h1 class="item-title"><?=(strlen(trim($TITLE_PREFIX)) > 0 ? str_replace(array('#NAME#'),array($arResult['NAME']),$TITLE_PREFIX) : $arResult["NAME"] );?></h1>
    <div class="prices">
        <?
        for($i = 0;$i<count($arProductPrice);$i++)
        {
            echo '<div class="price-block '.($arProductPrice[$i]['BASE'] == 'Y' ? 'price1' : 'price2').'">
                            <span class="price-type">'.str_replace('RUB','<span class="rubl">&#8399;</span>',$arProductPrice[$i]['CATALOG_GROUP_NAME']).'</span>
                            <div class="price">'.number_format($arProductPrice[$i]['PRICE'],0,'.',' ').'<span class="rubl">&#8399;</span></div>
                          </div>';
        }

        ?>
        <div class="clearfix"></div>
    </div>
    <div class="nalichie" style="z-index:0">



        <?
        echo '<p class="big">'.GetMessage('CATALOG_ITEM_QUANTITY').'</p>';
        if($arProduct["QUANTITY"] > 0)
        {
            $qtmp = ( $arProduct["QUANTITY"] > 10 ? '> 10' : $arProduct["QUANTITY"]);
            echo '<span class="q-type q-yes"  data-quantity="'.$arProduct["QUANTITY"].'"   >'.GetMessage('CATALOG_ITEM_QUANTITY_ALL',array('#QUANT#'=>$qtmp)).'</span>';
        }
        elseif($arResult["PROPERTIES"]["DATA_TRANZITA"]["VALUE"]){
			?>
			Отгрузка возможна <?=$arResult["PROPERTIES"]["DATA_TRANZITA"]["VALUE"]?>
			<?
			
			echo '<p class="quantity_zero_notice" ><span style="float:left;" >'.GetMessage('CATALOG_ITEM_QUANTITY_ZERO').', &nbsp;</span> <a href="#" style="float:left;line-height:13px" data-name="'. $arResult['NAME'].'" data-id="'.$arResult['ID'].'" data-product-id="'.$arResult['ID'].'" class="notify-link pink italic"><span>'.GetMessage('CATALOG_ITEM_NOTICE_LINK').'</span></a></p>';
            echo '<div id="analogi_mini_info"></div>'; //<span class="q-type q-x">'.GetMessage('CATALOG_QUANTITY_ZERO').'</span>
		
		}
		else
        {
            echo '<p class="quantity_zero_notice" ><span style="float:left;" >'.GetMessage('CATALOG_ITEM_QUANTITY_ZERO').', &nbsp;</span> <a href="#" style="float:left;line-height:13px" data-name="'. $arResult['NAME'].'" data-id="'.$arResult['ID'].'" data-product-id="'.$arResult['ID'].'" class="notify-link pink italic"><span>'.GetMessage('CATALOG_ITEM_NOTICE_LINK').'</span></a></p>';
            echo '<div id="analogi_mini_info"></div>'; //<span class="q-type q-x">'.GetMessage('CATALOG_QUANTITY_ZERO').'</span>

        }
        ?>
        <div class="clearfix"></div>
        <br />
        <?=GetMessage('CATALOG_ITEM_MORE_INFO',array('#NAME#'=>$arResult["NAME"]));?>
    </div>
    <div class="item-buy">
        <?if($arProduct["QUANTITY"] > 0 /*|| $arResult["PROPERTIES"]["DATA_TRANZITA"]["VALUE"]*/)
        {
            echo '<span class="button-l">
                            <span class="button-r">
                                <a class="button catalog-item-buy" href="' . $arResult["ADD_URL"] . '#buy">'.GetMessage('CATALOG_ITEM_BUY').'</a>
                            </span>
                          </span>
                          <span class="item-quantity-switch">
                                <input class="q-input" type="text" maxlength="" name="" value="1" size="3">
                            <span class="switch-top"></span>
                            <span class="switch-bot"></span>
                          </span>';
        }
        ?>
    </div>
    <div class="item-tabs-a">
        <a href="javascript:void();" id="tab" class="noMargin active-tab-link" onclick=" $('.active-tab-link').removeClass('active-tab-link'); $(this).addClass('active-tab-link'); $('.tab2').hide(); $('.tab').show();"><h2><?=GetMessage('CATALOG_ITEM_HARACTERISTICI',array('#SECTION_CODE#'=> $arDeclenAll, '#NAME#' => $arResult["NAME"] ));?></h2></a>
        <a href="javascript:void();" id="tab2" class="noMargin" onclick=" $('.active-tab-link').removeClass('active-tab-link'); $(this).addClass('active-tab-link'); $('.tab').hide(); $('.tab2').show();"><span><?=GetMessage('CATALOG_ITEM_ANALOGI');?></span></a>
    </div>
    <div class="item-tabs">
        <div class="item-tab tab active-item-tab">
            <? // Auaia naienoa?>
            <?foreach ($arResult["PROPERTIES"] as $value): ?>
                <?
                //$masHar = array("Aeaaiiaeu ye?aia","?ac?aoaiea ye?aia","Aa?eaiou iianaaoie ye?aia","Eiiiaeoi? ia oeaeoa","Iiaa?oiinou ","?aniiei?aiea eiiiaeoi?a","I?iecaiaeoaeu","Oei BGA ?eia","Ninoiyiea BGA ?eia","Oaao","?aneeaaea eeaaeaoo?u");
                $masHar = array("diagonal", "data_code", "emkost", "kollichesto_jacheek", "naprjazhenie", "resolution", "light", "connector", "surface", "location_connector", "manufactur", "type_bga", "state_bga", "color", "keyboard", "volume_video", "type_video", "frequency", "with_memory","tip_chekhla","dlya_chego","material","OBEM_OPERATIVNOY_PAMYATI","OBEM_VSTROENNOY_PAMYATI",
"PROTSESSOR","FRONTALNAYA_KAMERA","FOTOKAMERA","TIP_SIM_KARTY","STANDART_SVYAZI","MODEL_VIDEOADAPTERA","TIP_ZU",
"SILA_TOKA","MODEL_TELEFONA_ILI_PLANSHETA","OPERATSIONNAYA_SISTEMA",

"KOLICHESTVO_YADER",
		"PODDERZHKA_KARTY_PAMYATI",
		"RAZEM",
		"VID_NAUSHNIKOV",
		"PRODOLZHITELNOST_RABOTY",
		"DLINA_PROVODA",
		"RAZYEM_NAUSHNIKOV",
		"PITANIE",
		"TIP_NAUSHNIKOV",
		"KOLICHESTVO_IZLUCHATELEY",
		"SVETOVOY_POTOK_LYUMEN",
		"DIAPAZON_VOSPROIZVODIMYKH_CHASTOT",
		"IMPEDANS",
		"FORMA_RAZEMA_NAUSHNIKOV",
		"TIP_KREPLENIYA",
		"POZOLOCHENNYE_RAZEMY",
		"OSOBENNOSTI",
		"PODKLYUCHENIE",
		"CHUVSTVITELNOST",
		"ZVUK",
		"MOSHCHNOST_KOLONOK",
		"OTNOSHENIE_SIGNAL_SHUM",
		"VKHODY",
		"FM_TYUNER",
		"TIP_MAGNITOLY",
		"KOLICHESTVO_RADIOSTANTSIY",
		"LINEYNYY_VKHOD",
		"VYKHOD_NA_NAUSHNIKI",
		"INTERFEYS_USB",
		"CHASY",
		"TIP_NAVIGATORA",
		"OBLAST_PRIMENENIYA",
		"PODDERZHKA_GLONASS",
		"PODDERZHKA_WAAS",
		"TIP_ANTENNY",
		"KONSTRUKTSIYA_VIDEOREGISTRATORA",
		"KOLICHESTVO_KANALOV_ZAPISI_VIDEO_ZVUKA",
		"PODDERZHKA_HD",
		"ZAPIS_VIDEO",
		"REZHIM_ZAPISI",
		"FUNKTSII",
		"UGOL_OBZORA",
		"NOCHNOY_REZHIM",
		"REZHIM_FOTOSEMKI",
		"DLITELNOST_ROLIKA",
		"REZHIMY_ZAPISI_VIDEO",
		"VIDEOKODEK",
		"VYKHODY",
		"PODKLYUCHENIE_K_KOMPYUTERU_PO_USB",
		"DLINA",
		"MATERIAL_OPLETKI",
		"DIAPAZON_K",
		"DIAPAZON_KA",
		"DIAPAZON_KU",
		"DIAPAZON_X",
		"DETEKTOR_LAZERNOGO_IZLUCHENIYA",
		"PODDERZHKA_REZHIMOV",
		"PRIEMNIK_SIGNALA_RADIOKANAL",
		"REZHIM_GOROD",
		"REZHIM_TRASSA",
		"OBNARUZHENIE_RADARA_TIPA_STRELKA",
		"ZASHCHITA_OT_OBNARUZHENIYA",
		"PAMYAT_NASTROEK",
		"OTOBRAZHENIE_INFORMATSII",
		"REGULIROVKA_YARKOSTI",
		"REGULIROVKA_GROMKOSTI",
		"OTKLYUCHENIE_ZVUKA",
		"TIP_DISPLEYA",
		"PODSVETKA",
		"PODDERZHIVAEMYE_FORMATY_TEKSTOVYE",
		"PODDERZHIVAEMYE_FORMATY_GRAFICHESKIE",
		"PODDERZHIVAEMYE_FORMATY_ZVUKOVYE",
		"PODDERZHIVAEMYE_FORMATY_DRUGIE",
		"ZASHCHITA_V_KOMPLEKTE",
);
                if (in_array($value["CODE"], $masHar) && strlen(trim($value["VALUE"])) > 0):
                    ?>
                    <p><span class="prop-name"><?= $value["NAME"] ?></span> <span class="prop-val"><?= $value["VALUE"] ?></span></p>
                <?
                endif;
            endforeach;
            ?>
            <p>
                <span class="prop-name"><?=GetMessage('CATALOG_ITEM_SOVMESTIMO');?></span>
                            <span class="prop-val">
                                <?
                                $j = 0;
                                if (is_array($arResult["PROPERTIES"]["RECOMMEND"]["VALUE"]))
                                {
                                    foreach ($arResult["PROPERTIES"]["RECOMMEND"]["VALUE"] as $elemRecmmend)
                                    {
                                        $itemRecomOb = CIBlockElement::GetList(array(), array("ID" => $elemRecmmend), false, false, array("DETAIL_PAGE_URL", "NAME"));
                                        $itemRecom = $itemRecomOb->Fetch();
                                        if($i > 5)
                                        {
                                            echo '<a href="'.$itemRecom["DETAIL_PAGE_URL"].'">'.$itemRecom["NAME"].'</a><br />';
                                        }
                                        else
                                        {
                                            echo '<a href="#" class="openProps italic borderLink">iieacaou iieiue nienie</a><br />
                                                    <span class="hidden-props">
                                                        <a href="'.$itemRecom["DETAIL_PAGE_URL"].'">'.$itemRecom["NAME"].'</a><br />
                                                    </span>';
                                        }
                                    }
                                }
                                ?>
                            </span>
            </p>
        </div>
        <div class="item-tab tab2" style="display: none;" id="catalog_item_analogi_box" >
            <!--<div class="item-tab tab2" style="display: none;" id="catalog_item_analogi_box" >-->
            <!--{#ANALOGY#}-->
        </div>
    </div>
</td>
</tr>



<? if(strlen($arResult['DETAIL_TEXT']) > 0){ ?>
    <tr>
        <td></td>
        <td>
            <h2 class="item-title">Описание</h2>
            <div class="item-description">
                <?=$arResult['DETAIL_TEXT']?>
            </div>
        </td>
    </tr>
<? } ?>






</table>
<div class="item-border"></div>
<div id="catalog-item-comments-box" >
    <h2 class="item-title"><?=GetMessage('CATALOG_ITEM_COMMENTS').$arDeclenAllOtz.' '.$arResult["NAME"] ?></h2>
    <!--{#COMMENTS#}-->
</div>

<script type="text/javascript">
    //  $(document).ready(function(){


    if($('#analogi_mini_info'))
    {
        var abi = $('#analogi_mini_info');
        var t = abi.html();
        var ab = $('#catalog_item_analogi_box');
        if(ab.find('tr').length > 0)
        {
            var abiCont = '';
            var abCont = ab.find('tr');
            for(i=0;i<abCont.length;i++)
            {
                if(i<3)
                {
                    abiCont += '<tr>' + abCont.eq(i).html() + '</tr>';
                }
            }
            //  alert(ab.find('tr').length);
            abi.html('<p><?=GetMessage('CATALOG_ITEM_ANALOGI_INFO_LABEL');?></p><table>'+ abiCont + '</table>');
            abi.parent().find('.quantity_zero_notice').remove();

        }

    }
    // });

</script>
    
    
