<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>



<? /*���� ������������ ��������� ��� �������, � ����������*/ $this->SetViewTarget('catalog_page_title_box');?>

<?

    $UF_PARAMS = array(
        'PRICE_MAX' => '',
        'PRICE_MIN' => '',
        'FILTER_PROPERTY_SHOW' => '' 
    );


    $TITLE_PREFIX = '';
	
	
		if($arParams['INCLUDE_SUBSECTIONS'] == 'Y' || $arParams['INCLUDE_SUBSECTIONS'] == 'A')
        {
            $UF_PARAMS['PRICE_MAX'] = $arResult['UF_PRICE_INCSEC_MAX'];
            $UF_PARAMS['PRICE_MIN'] = $arResult['UF_PRICE_INCSEC_MIN'];
        }
        else
        {
            $UF_PARAMS['PRICE_MAX'] = $arResult['UF_PRICE_MAX'];
            $UF_PARAMS['PRICE_MIN'] = $arResult['UF_PRICE_MIN'];
        }
		
		$UF_PARAMS['FILTER_PROPERTY_SHOW'] = unserialize($arResult['UF_PROPERTY_SHOW']); 
	
	

			
			$UF_PARAMS['FILTER_PROPERTY_SHOW']=$arResult['FILTER_PROPERTY_SHOW'];

?>

<h1 class="cat-title"><?=$arResult["ADDITIONAL_TITLE"]?></h1>
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
	    ?>
&nbsp;</span>
<!-- ���������� BXmaker -->
<div class="bxmaker-catalog-sorting" data-type="pe-sorting" data-cookie-uniq="PAGE_ELEMENT_SORTING_<?=$arParams['IBLOCK_ID'];?>" ><?=GetMessage('SORT_LABLE');?>
    <span class="fake-select" style="">  <?=GetMessage('SECT_SORT_'.$arParams['ELEMENT_SORT_FIELD_SET']);?></span>
   	<div class="fake-select-popup redirect">
  		<?foreach ($arParams['ELEMENT_SORT_FIELD_VARIATION'] as $sort => $val)
        {
            echo '<span data-sort="'.$sort.'" data-val="'.$val[0].'" class="catalog_sorting_goods '.($arParams['ELEMENT_SORT_FIELD_SET'] == $sort ? 'selected' : '' ).' " >'.GetMessage('SECT_SORT_'.$sort).'</span>';
        }
        ?>
   	</div>
</div>
<!-- ���������� ������������ ������� BXmaker -->
<div class="sorting">|</div>
<div class="bxmaker-catalog-sorting"  data-type="pe-count" data-cookie-uniq="PAGE_ELEMENT_COUNT_<?=$arParams['IBLOCK_ID'];?>"  >
    <span class="fake-select" style=""><?=$arParams["PAGE_ELEMENT_COUNT"];?></span>
    <div class="fake-select-popup redirect">
        <?for($i=0;$i<count($arParams['PAGE_ELEMENT_COUNT_VARIATION']);$i++)
        {
            echo '<span data-val="'.$arParams['PAGE_ELEMENT_COUNT_VARIATION'][$i].'" class="catalog_num_show_goods" >'.$arParams['PAGE_ELEMENT_COUNT_VARIATION'][$i].'</span>';
        }
        ?>
    </div>
   	<input class="select-input" type="hidden" value="10" /> <?=GetMessage('TSEC_NUM_SHOW_GOODS');?>
</div>
<!--/noindex-->
<? /*����� ����� ���������*/ $this->EndViewTarget();?>



<div class="catalog-section">
<?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?endif;?>

<?
if(($p = strpos($arResult['DESCRIPTION'], '###')) !== false) echo substr( $arResult['DESCRIPTION'], 0, $p);
else echo $arResult['DESCRIPTION'];
?>
<table class="cat-items">
		
        
    <?
     if(count($arResult["ITEMS"]) > 0)
     {
         //���������� ������� ����
        $PRICE_ID = 0;
        foreach($arResult['PRICES'] as $k=>$v)
        {
            if($v['CAN_BUY'] == '1' && $v['CAN_VIEW'] == '1')
            {
                $PRICE_ID = $v['ID'];
                break;
            }
        }
        
        // ������� ������
        foreach($arResult["ITEMS"] as $cell=>$arElement):?>
		<?
		//$this->AddEditAction($arElement['ID'], $arElement['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
		//$this->AddDeleteAction($arElement['ID'], $arElement['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));
		               
        ?>
        <tr>
            <td id="<?=$this->GetEditAreaId($arElement['ID']);?>" >
            <table style="width: 100%;" >
            <tr>
                <td>
                    <div class="cat-item-pic"  >
                   	<?if (strlen($arElement["DETAIL_PICTURE"]['SRC'])>0)
                    {
                       // $img = CFile::ResizeImageGet($arElement["DETAIL_PICTURE"],array("width"=>"157","height"=>"87"),BX_RESIZE_IMAGE_PROPORTIONAL_ALT,false);
						//$img=$arElement["DETAIL_PICTURE"]['SRC'];
                        ?>
                        <a href="<?=urldecode($arElement["DETAIL_PAGE_URL"])?>" >
                                <img src="<?=$arElement["DETAIL_PICTURE"]['SRC'];/*=$img["src"]*/?>" alt="<?=$arElement['NAME']?>" title="<?=$arElement['NAME']?>" width="157" />
                        </a> <!--width="157"-->
                        <?
                    }
                    else
                    {
                        ?><img src="/bitrix/templates/klavazip/img/no-pic-big.jpg" style="width: 160px;" alt="nophoto" /><?
                        
                    }
                    ?><div class="labels"><?
                        if(isset($arElement['PROPERTIES']['SALELEADER']['VALUE']) && strlen(trim($arElement['PROPERTIES']['SALELEADER']['VALUE'])) > 0 )
                        {
                            ?><div class="label-hit">&nbsp;</div><?
                        }
                        if(isset($arElement['PROPERTIES']['NEWPRODUCT']['VALUE']) && strlen(trim($arElement['PROPERTIES']['NEWPRODUCT']['VALUE'])) >0 )
                        {
                            ?><div class="label-new">&nbsp;</div><?
                        }
                            
                    ?></div>
                    </div><!-- .cat-item-pic -->
                    <? 
                    if(isset($arElement["PROPERTIES"]["CML2_ARTICLE"]["VALUE"]) && strlen($arElement["PROPERTIES"]["CML2_ARTICLE"]["VALUE"]) >0)
                    {
                        ?><p style="text-align:center"><span class="artikul"><?=GetMessage('CATALOG_GOOD_ARTICLE');?><?=$arElement["PROPERTIES"]["CML2_ARTICLE"]["VALUE"]?></span></p><?
                    }
                    ?>                
                </td>
                <td class="cat-item-props ">
                    <div class="cat-item-props">
                        <p class="noMarginTop">
                            <a href="<?=urldecode($arElement["DETAIL_PAGE_URL"])?>" class="big16">
                                
                            <?
                            if(strlen($TITLE_PREFIX) > 0)
                            {
                                echo str_replace('#NAME#',$arElement['NAME'],$TITLE_PREFIX);
                            }
                            else
                            {
                                echo $arElement["NAME"];
                            }
                            ?>
                            </a>

                        </p>
                        <p class="small12">
                        	<?
                            $masHar = array("diagonal","data_code","resolution","light","connector","surface","location_connector","manufactur","type_bga","state_bga","color","keyboard","volume_video","type_video","frequency","with_memory");
                        	foreach($arElement["PROPERTIES"] as $value)
                            {
                                if (in_array($value["CODE"], $masHar) && strlen(trim($value["VALUE"])) > 0 )
                                {
                                    echo '<i>'.$value['NAME'].'</i>: '.$value['VALUE'].'<br />';
                                }
                            }
                        	?>
                        </p>
                    </div>
                </td>
                <td>
                    <div class="cat-item-price bxm-section-box-analogs">
                        
                       
							<?
							//if ($USER->GetID()==7429){
							
								$datetime=$arElement["PROPERTIES"]["DATA_TRANZITA"]["VALUE"];
								if (ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})", $datetime, $regs)) {
									$datetime2=$regs[3].'.'.$regs[2].'.'.$regs[1];
								} else {
									$datetime2=$datetime;
								}
							
								$today = date("d.m.Y");  
								$compare_date = $DB->CompareDates($datetime2,$today);
								if ($compare_date<0 || !isset($arElement["PROPERTIES"]["DATA_TRANZITA"]["VALUE"])) $valid_date=false;
								else $valid_date=true;
							
								
								?>
								
								<div class="item-buy pink">
									<?
									
									if(isset($arElement['CATALOG_PRICE_'.$PRICE_ID]))
									{
										echo '<span class="price">'.number_format($arElement['CATALOG_PRICE_'.$PRICE_ID],0,',',' ').'<span class="rubl">'.GetMessage('CATALOG_PRICE_SIMBOL').'</span></span>';
									}
									//здесь третье условие убрать, когда эксперимент с кнопкой купить будет завершен
									if ($arElement['CATALOG_QUANTITY']>0 || ($arElement["PROPERTIES"]["DATA_TRANZITA"]["VALUE"] && $valid_date && count($arResult['ITEMS_ANALOGS'][''.$arElement['ID']])<1) || (count($arResult['ITEMS_ANALOGS'][''.$arElement['ID']])>0 && (strpos($_SERVER['REQUEST_URI'],'matritsy') || strpos($_SERVER['REQUEST_URI'],'klaviatury') || strpos($_SERVER['REQUEST_URI'],'akkumulyatory-po-modelyam'))) ) {
										echo '<span class="item-quantity-switch">	
											<input type="text" size="3" value="1" name="" class="q-input">
											<span class="switch-top"></span>
											<span class="switch-bot"></span>
											</span>
											<a href="'.$arElement['ADD_URL'].'" class="button-buy catalog-item-buy" rel="nofollow">'.GetMessage('CATALOG_ADD_TO_BASKET').'</a>';
									}
									
									?>
									<div class="clearfix">&nbsp;</div>
								</div>
					
					
                        <div class="q-block"  >
						
                            
                            <?if ($arElement['CATALOG_QUANTITY']>0)
                            {
                                ?>
								<p class="big"><?=GetMessage('CATALOG_ITEM_QUANTITY_ISSET');?></p>
								<?
								$qtmp = ( $arElement['CATALOG_QUANTITY'] > 10 ? '> 10' : $arElement['CATALOG_QUANTITY']);
                                echo '<span class="q-type q-yes" data-quantity="'.$arElement['CATALOG_QUANTITY'].'"  >'.GetMessage('CATALOG_GOODS_QUANTITY_ALL',array('#QUANT#'=>$qtmp)).'</span>';
                            }
							
							elseif($arElement["PROPERTIES"]["DATA_TRANZITA"]["VALUE"] && $valid_date && count($arResult['ITEMS_ANALOGS'][''.$arElement['ID']])<1){
							

				
								?>
								<p class="big">
								<?
								echo '<span style="float:left;" >'.GetMessage('CATALOG_ITEM_QUANTITY_ZERO').', отгрузка возможна '.$datetime2.'</span><br><br>';
																

								?>
								</p>
								<?
								
								
							}
							elseif(count($arResult['ITEMS_ANALOGS'][''.$arElement['ID']])>=1){
								
								
								echo '<div class="section_analogi_mini_info"></div>';
								
								
								?>
								<div class="section-analogi-box-hidden" >
									<table class="analogi" style="width: 100%; padding:0; border-spacing: 0;">
										<tbody>
											<? 
											$bQuant = ($arParams['SHOW_QUANTITY'] == 'Y' ? true : false );
											for($i=0,$c = count($arResult['ITEMS_ANALOGS'][''.$arElement['ID']]);$i<$c; $i++)
											{
												$item = $arResult['ITEMS_ANALOGS'][''.$arElement['ID']][$i]; 
												
											   ?>
												<tr>
													<td>
														<a href="<?=$item['DETAIL_PAGE_URL'];?>" title="<?=$item['NAME'];?>" rel="nofollow"><?=$item['NAME'];?></a>
														<?
														$qtmp = ( $item['CATALOG_QUANTITY'] > 10 ? '>10' : $item['CATALOG_QUANTITY']);
														?>
														<span style="color:#898989;">&nbsp;(<?=$qtmp?>шт.)&nbsp;</span>
														<?
														//echo ( $bQuant ? GetMessage('CT_QUANTITY',array('#QUANT#'=>$qtmp)) : '');
														?>
														<?if(intval($item['CATALOG_QUANTITY']) > 0):?>
															<img src="<?=SITE_TEMPLATE_PATH;?>/img/q-yes.png" alt="close-popup" />
														<?else:?>
														<img src="<?=SITE_TEMPLATE_PATH;?>/img/close-popup.png" alt="close-popup" />
														<?endif;?>                        
													</td>
													<td class="pink big" style="text-align:right"><span class="price"><?=number_format($item['PRICES']['PRICE'],0,'.',' ');?><span class="rubl">&#8399;</span></span></td>
												</tr>
												<?
											}
											?>
										</tbody>
									 </table>
									</div>
								
								<?
							
							}
							
							
                            else
                            {
							?>
							<p class="big"></p>
							<?
                                
        			echo '<p class="quantity_zero_notice" ><span style="float:left;" >'.GetMessage('CATALOG_ITEM_QUANTITY_ZERO').', &nbsp;</span><a href="#" data-name="'.$arElement['NAME'].'" data-id="'.$arElement['ID'].'" data-product-id="'.$arElement['ID'].'" class="notify-link pink italic" style="line-height:13px"><span>'.GetMessage('CATALOG_NOTICE_LINK').'</span></a></p>';
                                echo '<div class="section_analogi_mini_info"></div>';
                       
                            }
                            ?> 
                        </div>
							
							
							<?/*}else{?>
							
							
									<div class="item-buy pink">
										<?
										
										if(isset($arElement['CATALOG_PRICE_'.$PRICE_ID]))
										{
											echo '<span class="price">'.number_format($arElement['CATALOG_PRICE_'.$PRICE_ID],0,',',' ').'<span class="rubl">'.GetMessage('CATALOG_PRICE_SIMBOL').'</span></span>';
										}
										if ($arElement['CATALOG_QUANTITY']>0 || $arElement["PROPERTIES"]["DATA_TRANZITA"]["VALUE"]) {
											echo '<span class="item-quantity-switch">	
												<input type="text" size="3" value="1" name="" class="q-input">
												<span class="switch-top"></span>
												<span class="switch-bot"></span>
												</span>
												<a href="'.$arElement['ADD_URL'].'" class="button-buy catalog-item-buy" rel="nofollow">'.GetMessage('CATALOG_ADD_TO_BASKET').'</a>';
										}
										
										?>
										<div class="clearfix">&nbsp;</div>
									</div>
								
									<div class="q-block"  >
									
										
										<?if ($arElement['CATALOG_QUANTITY']>0)
										{
											?>
											<p class="big"><?=GetMessage('CATALOG_ITEM_QUANTITY_ISSET');?></p>
											<?
											$qtmp = ( $arElement['CATALOG_QUANTITY'] > 10 ? '> 10' : $arElement['CATALOG_QUANTITY']);
											echo '<span class="q-type q-yes" data-quantity="'.$arElement['CATALOG_QUANTITY'].'"  >'.GetMessage('CATALOG_GOODS_QUANTITY_ALL',array('#QUANT#'=>$qtmp)).'</span>';
										}
										
										 elseif($arElement["PROPERTIES"]["DATA_TRANZITA"]["VALUE"]){
											?>
			<p class="big">
			<?
			echo '<span style="float:left;" >'.GetMessage('CATALOG_ITEM_QUANTITY_ZERO').', отгрузка возможна '.$arElement["PROPERTIES"]["DATA_TRANZITA"]["VALUE"].'</span><br><br>';
											

			?>
			</p>
											<?
											
											echo '<div class="section_analogi_mini_info"></div>';
										
										}
										
										
										else
										{
										?>
										<p class="big"></p>
										<?
											
								echo '<p class="quantity_zero_notice" ><span style="float:left;" >'.GetMessage('CATALOG_ITEM_QUANTITY_ZERO').', &nbsp;</span><a href="#" data-name="'.$arElement['NAME'].'" data-id="'.$arElement['ID'].'" data-product-id="'.$arElement['ID'].'" class="notify-link pink italic" style="line-height:13px"><span>'.GetMessage('CATALOG_NOTICE_LINK').'</span></a></p>';
											echo '<div class="section_analogi_mini_info"></div>';
								   
										}
										?> 
									</div>
							
							
							
							 <div class="section-analogi-box-hidden" >
								<!--{#ANALOGY_<?=$arElement['ID'];?>#}-->
							 </div>
							<?}*/?>
                        
                        <p class="small12" >
                            <?//if ($arElement['CATALOG_QUANTITY']>0):?>
                            <span class="floatleft  catalog-item-compare-link-box" data-product-id="<?=$arElement['ID']?>"  ><?=GetMessage('CATALOG_ITEM_COMPARE');?></span>
                            <span class="floatright catalog-item-wishlist-link-box" data-product-id="<?=$arElement['ID']?>"  ><?=GetMessage('CATALOG_LINK_ADD_TO_WISHLIST');?></span>
                            <?//endif;?>
                        </p>
                        <div class="clearfix">&nbsp;</div>
                    </div>
                </td>
            </tr>
            </table>
            </td>
        </tr><?
        endforeach;
    } // if count > 0
    else
    {
        if(isset($_GET['price-minf']))
        {
            echo '<tr><td><div class="empty-box" >'.GetMessage('CATALOG_FILTER_EMPTY').'</div></td></tr>';
        }
        else
        {
            echo '<tr><td><div class="empty-box" >'.GetMessage('CATALOG_EMPTY').'</div></td></tr>';
        }
    }    
        ?>
            
</table><!-- .cat-items -->

<?
if(($p = strpos($arResult['DESCRIPTION'], '###')) !== false) echo substr( $arResult['DESCRIPTION'], $p+3);
?>

<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
</div>
<script type="text/javascript">
$(document).ready(function(){
    
    //сокрытие ненужных свойств
    var arPropShow = <?=json_encode($UF_PARAMS['FILTER_PROPERTY_SHOW']);?>;
	if(typeof(arPropShow) != 'object') arPropShow = {};
    
	$('.floatleft:has([name^="arrFilter_pf["])').each(function()
    {
        var _self = $(this);
    
        var name = $(this).find('[name]').attr('name').split('arrFilter_pf[')[1].split(']')[0];
        if( !(name in arPropShow) )
        {
            $(this).hide();
        }
        else
        {
            var obPropValues = {};
            for(var i=0; i < arPropShow[name].length; i++)
            {
                obPropValues[arPropShow[name][i]] = 'Y';
            }
            
            // модификация, устранение отсутствующих полей
            var box = $(this).find('a[data-val]');
            box.each(function(){
               if($(this).attr('data-val').length > 0 && !($(this).attr('data-val') in obPropValues) ){
                    $(this).remove();   
               }
            });
        }
    });
    
    $('form.section_filter_box').show();
    
    $('.hide-this').find('div:has(.checkboxes)').remove();
	$('.hide-this').height($('.floatleft:has([name^="arrFilter_pf["]):visible').length*17+20+60+20+10+'px');

    
    
    // __фильтр по цене
    $( "input.price-min" ).val('<?=( isset($_GET['price-minf']) && !isset($_GET['del_filter']) ? $_GET['price-minf'] : intval($UF_PARAMS['PRICE_MIN']) );?>');
    $( "input.price-max" ).val('<?=( isset($_GET['price-maxf']) && !isset($_GET['del_filter']) ? $_GET['price-maxf'] : intval($UF_PARAMS['PRICE_MAX']) ); ?>');
    $( ".price-min-label" ).html('<?=( isset($_GET['price-minf']) && !isset($_GET['del_filter']) ? $_GET['price-minf'] : intval($UF_PARAMS['PRICE_MIN']) );?>');
    $( ".price-max-label" ).html('<?=( isset($_GET['price-maxf']) && !isset($_GET['del_filter']) ? $_GET['price-maxf'] : intval($UF_PARAMS['PRICE_MAX']) ); ?>');
		
	$( "#slider-range" ).slider({
		range: true,
		min: <?=intval($UF_PARAMS['PRICE_MIN']);?>,
		max: <?=intval($UF_PARAMS['PRICE_MAX']);?>,
		step:10,
		values: [ $( "input.price-min" ).val(), $( "input.price-max" ).val() ],
		slide: function( event, ui ) 
        {
            $( "input.price-min" ).val( ui.values[ 0 ] );
			$( "input.price-max" ).val( ui.values[ 1 ] );
			$('.price-min2').css({left: $('a.ui-slider-handle:first').position().left + 'px' }).html(ui.values[ 0 ]);
			$('.price-max2').css({left: $('a.ui-slider-handle:last').position().left + 'px' }).html(ui.values[ 1 ]);
		},
        start : function (event, ui) {
            $( "input.price-min" ).val( ui.values[ 0 ] );
			$( "input.price-max" ).val( ui.values[ 1 ] );
			$('.price-min2').css({left: $('a.ui-slider-handle:first').position().left + 'px' }).html(ui.values[ 0 ]);
			$('.price-max2').css({left: $('a.ui-slider-handle:last').position().left + 'px' }).html(ui.values[ 1 ]);
        },
        stop : function (event, ui) {
            $( "input.price-min" ).val( ui.values[ 0 ] );
			$( "input.price-max" ).val( ui.values[ 1 ] );
			$('.price-min2').css({left: $('a.ui-slider-handle:first').position().left + 'px' }).html(ui.values[ 0 ]);
			$('.price-max2').css({left: $('a.ui-slider-handle:last').position().left + 'px' }).html(ui.values[ 1 ]);
        }
	});

    $('.price-min2').html( $( "#slider-range" ).slider( "values", 0 ) ).css({left: $('a.ui-slider-handle:first').position().left });
    $('.price-max2').html( $( "#slider-range" ).slider( "values", 1 ) ).css({left: $('a.ui-slider-handle:last').position().left });
    
    // __сокрытие ненужных фильтров
	
	$butt = $('.hide-this').find('div:has(.filter-button)');
	$butt.height('27px').width($butt.find('.filter-button').width()).css({bottom:'30px',left:'100px',position:'absolute'});
    
	$('.reset-filter').css({bottom:'30px',position:'absolute'});
    
    /// __аналоги
    
    var boxs = $('.bxm-section-box-analogs');
    for(i=0;i<boxs.length;i++)
    {
        CheckAnalogs(boxs[i]);
    }
    
    function CheckAnalogs(b)
    {
        
        var abi = $(b).find('.section_analogi_mini_info');
        var ab = $(b).find('.section-analogi-box-hidden');
        if(ab.find('tr').length > 0)
        {
            
            var c = '';
            var an = ab.find('tr');
            if(an.length > 0)
            {
                
                for(var i=0;i<an.length && i<3;i++)
                {
                    c += '<tr>' + an[i].innerHTML + '</tr>';
                }
                
                abi.html('<p class="analogi-label" ><?=GetMessage('CATALOG_ITEM_ANALOGI_INFO_LABEL');?></p><table style="width:100%;" ><tbody>'+ c + '</tbody></table>'); 
                abi.parent().find('.quantity_zero_notice').remove();
                
            }
        } 
    }
    
    
});
</script>
<? /**/?>