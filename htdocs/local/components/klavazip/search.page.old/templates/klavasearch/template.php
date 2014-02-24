<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<div class="margin-bottom"></div>
<div class="page-title">
	<h1 class="cat-title"><?=GetMessage('CT_BSP_SEACH_LABEL');?></h1>
     <div class="bxmaker-catalog-sorting" data-type="pe-sorting" data-cookie-uniq="SEARCH_PAGE_SORTING" >
        сортировать 
        <span class="fake-select" style="">
			<?if($arResult["REQUEST"]["HOW"]=="d"):?>
				по дате	
            <?else:?>
				по релевантности
			<?endif;?>
		</span>
        <div class="fake-select-popup">
            <span data-val="rank" data-sort="sort"  >по релевантности</span>
            <span data-val="date" data-sort="sort"  >по дате</span>
        </div>
        <input class="select-input" type="hidden" value="name" />
    </div>
   
    
    
   
</div>


<div class="content-bg2">	
	<?//echo "<pre>";print_r($arResult);echo "</pre>";?>	
	<div class="search-pane">
	<form action="" method="get">
		<input type="hidden" name="tags" value="<?echo $arResult["REQUEST"]["TAGS"]?>" />
		<input type="hidden" name="how" value="<?echo $arResult["REQUEST"]["HOW"]=="d"? "d": "r"?>" />
		
		<?//if($arParams["USE_SUGGEST"] === "Y"):
			if(strlen($arResult["REQUEST"]["~QUERY"]) && is_object($arResult["NAV_RESULT"]))
			{
				$arResult["FILTER_MD5"] = $arResult["NAV_RESULT"]->GetFilterMD5();
				$obSearchSuggest = new CSearchSuggest($arResult["FILTER_MD5"], $arResult["REQUEST"]["~QUERY"]);
				$obSearchSuggest->SetResultCount($arResult["NAV_RESULT"]->NavRecordCount);
			}
		?>
			
<?
$arParamsLocal = array(
    "SHOW_INPUT" => "Y",
    "INPUT_ID" => "title-search-input",
    "CONTAINER_ID" => "search",
    "PAGE" => "#SITE_DIR#search/index.php",
    "NUM_CATEGORIES" => "1",
    "TOP_COUNT" => "5",
    "USE_LANGUAGE_GUESS" => "N",
    "CHECK_DATES" => "N",
    "SHOW_OTHERS" => "Y",
    "CATEGORY_0_TITLE" => "Поиск",
    "CATEGORY_0" => $arParams['arrWHERE'],
    "CATEGORY_0_main" => "",
);
foreach($arParams['arrWHERE_ALL'] as $key=>$val)
{
    if(isset($arParams['arrFILTER_'.$key]))
    {
        $arParamsLocal["CATEGORY_0_".$key] = $arParams['arrFILTER_'.$key];
    }
    else
    {
        $arParamsLocal["CATEGORY_0_".$key] = array('all');
    }
}

$APPLICATION->IncludeComponent(
	"bitrix:search.title",
	"search_page",
	$arParamsLocal
);
?>
		<div class="search-where" style="">
            <?
            $se = '';
            foreach($arParams['arrWHERE_ALL'] as $key=>$val)
            {
                if(in_array($key,$arParams['arrWHERE_ACTIVE']))
                {
                    $se .= (strlen($se) > 0 ? ','.$key : $key);
                    
                }
                echo '<span data-val="'.$key.'" class="'.(in_array($key,$arParams['arrWHERE_ACTIVE']) ? 'active' : '').'" >'.$val.'</span>'; 
                
            }
            ?>
            <input type="hidden" name="search-where" value="<?=$se;?>"  />
        </div>
        <div class="results grey small" style="height: 100%!important;">
        	<?if(is_object($arResult["NAV_RESULT"])):?>
				 <?=GetMessage("CT_BSP_SEARCH_RESULT_COUNT",array('#COUNT#'=>$arResult["NAV_RESULT"]->SelectedRowsCount()));?>
			<?endif;?>
        </div>
        <? /**/ ?>
	</form>
</div>







<?if(isset($arResult["REQUEST"]["ORIGINAL_QUERY"])):
	?>
	<div class="search-language-guess">
		<?echo GetMessage("CT_BSP_KEYBOARD_WARNING", array("#query#"=>'<a href="'.$arResult["ORIGINAL_QUERY_URL"].'">'.$arResult["REQUEST"]["ORIGINAL_QUERY"].'</a>'))?>
	</div><br /><?
endif;?>

	<div class="search-result">
	<?if($arResult["REQUEST"]["QUERY"] === false && $arResult["REQUEST"]["TAGS"] === false):?>
    
	<?elseif($arResult["ERROR_CODE"]!=0):?>
    
		<p><?=GetMessage("CT_BSP_ERROR")?></p>
		<?ShowError($arResult["ERROR_TEXT"]);?>
		<p><?=GetMessage("CT_BSP_CORRECT_AND_CONTINUE")?></p>
		<br /><br />
		<p><?=GetMessage("CT_BSP_SINTAX")?><br /><b><?=GetMessage("CT_BSP_LOGIC")?></b></p>
		<table border="0" cellpadding="5">
			<tr>
				<td align="center" valign="top"><?=GetMessage("CT_BSP_OPERATOR")?></td><td valign="top"><?=GetMessage("CT_BSP_SYNONIM")?></td>
				<td><?=GetMessage("CT_BSP_DESCRIPTION")?></td>
			</tr>
			<tr>
				<td align="center" valign="top"><?=GetMessage("CT_BSP_AND")?></td><td valign="top">and, &amp;, +</td>
				<td><?=GetMessage("CT_BSP_AND_ALT")?></td>
			</tr>
			<tr>
				<td align="center" valign="top"><?=GetMessage("CT_BSP_OR")?></td><td valign="top">or, |</td>
				<td><?=GetMessage("CT_BSP_OR_ALT")?></td>
			</tr>
			<tr>
				<td align="center" valign="top"><?=GetMessage("CT_BSP_NOT")?></td><td valign="top">not, ~</td>
				<td><?=GetMessage("CT_BSP_NOT_ALT")?></td>
			</tr>
			<tr>
				<td align="center" valign="top">( )</td>
				<td valign="top">&nbsp;</td>
				<td><?=GetMessage("CT_BSP_BRACKETS_ALT")?></td>
			</tr>
		</table>
	<?elseif(count($arResult["SEARCH"])>0):?>
	
	


	<?

	function VariationOfNumber($iNumber, $sclonForm1, $sclonForm2, $sclonForm3)
	{
		if ($iNumber == 11 || $iNumber == 12 || $iNumber == 13 || $iNumber == 14)
			return $sclonForm3;
			
			
		$iNumber = abs($iNumber) % 100;
		$iNumber = $iNumber % 10;
			
		if ($iNumber > 10 && $iNumber < 20)
			return $sclonForm3;
			
		if ($iNumber > 1 && $iNumber < 5)
			return $sclonForm2;
			
		if ($iNumber == 1)
			return $sclonForm1;
			
		return $sclonForm3;
	}
	
        
	foreach ($arResult['SECTION_RESULT_MAIN'] as $i_SectionID => $ar_Value)
	{
		
		$i_Count = count($ar_Value);
                
                
		?>
		
		
		
		
		
		
		
		
		
		
		
		
		<?if (count($arResult['SECTION_NAME_SEARCH_MAIN'][$i_SectionID]) !=0){
		?>
		
		<div style="margin-bottom: 20px">
			<span>
			<a class="link-search-all"  onclick="__toggleBlock('<?=$i_SectionID?>'); return false;" href="javascript:void(0)"><?=$arResult['SECTION_NAME_SEARCH_MAIN'][$i_SectionID] ?></a> - 
			<span style="color:#464e58; font-weight: bold; font-size: 14px;"><?=$i_Count.' '.VariationOfNumber($i_Count, 'товар', 'товара','товаров'); ?></span>
			
			</span>
		</div> 
		<? 
		}
		?>
		
		
		<div id="tovar-block-list-<?=$i_SectionID?>" style="display: none;">
		
			<?
			foreach ($ar_Value as $arItem)
			{
				
                            //echo '<pre>'.print_r($arItem).'</pre>';
                            
                            ?>
				<div class="search-item">
					<div class="small b3"><span class="pink"><?=$i?>.</span> <?echo $arItem["DATE_CHANGE"]?></div>
					<table class="search-table">
			        	<tr>
			        		<?if ($arItem["PARAM1"]=="catalog"):?>
			            		<td class="search-image relative">
				            		<?
	                                $img = array(
	                                    'src' => '/upload/no-photo.png',
	                                );
	                                if(isset($arItem['_BXM_EX_']['DETAIL_PICTURE']) && intval($arItem['_BXM_EX_']['DETAIL_PICTURE']) > 0)
	                                {
	                                    $img =  CFile::ResizeImageGet($arItem['_BXM_EX_']["DETAIL_PICTURE"],array("width"=>"160","height"=>""),BX_RESIZE_IMAGE_PROPORTIONAL_ALT ,false);       
	                                }
	                                ?>
				            		<div class='relative' style="border:none;padding:0">
	                                    <div class="labels" style='padding:0!important;border:none!important'>
	        				                <?if(isset($arItem['_BXM_EX_']['PROPERTY_SALELEADER_VALUE']) && strlen($arItem['_BXM_EX_']['PROPERTY_SALELEADER_VALUE']) > 0 )
	                                        {
	                                            echo '<div class="label-hit" style="width: 35px;padding:0!important;border:none!important"></div>';
	                                        }
	                                        if(isset($arItem['_BXM_EX_']['PROPERTY_NEWPRODUCT_VALUE']) && strlen($arItem['_BXM_EX_']['PROPERTY_NEWPRODUCT_VALUE']) > 0 )
	                                        {
	                                            echo '<div class="label-new" style="width: 37px;padding:0!important;border:none!important"></div>';
	                                        }
	                                        ?>
	                                   </div>
	                               </div>
				                	<div><img src="<?=$img["src"]?>" width="160" /></div>
	                                <?if(isset($arItem['_BXM_EX_']['PROPERTY_CML2_ARTICLE_VALUE'])):?>
	                                    <p style="text-align:center"><span class="artikul"><?=GetMessage('T_SEARCH_ARTICLE');?><?=$arItem['_BXM_EX_']['PROPERTY_CML2_ARTICLE_VALUE'];?></span></p>
	                                <?endif;?>                               
			                	</td>
			                	<td>
	                                <div class="cat-item-props">
	                                   	<p class="noMarginTop">	<a href="<?=urldecode($arItem["URL_WO_PARAMS"])?>" class="big16"><?=$arItem["TITLE_FORMATED"]?></a></p>
	                                    <?if(isset($arItem['_BXM_EX_']['PROPERTIES'])):?>
	                                    <p class="small12">
	                                        <?foreach($arItem['_BXM_EX_']['PROPERTIES'] as $value)
	                                        {
                                                    $masHar = array("diagonal","data_code","resolution","light","connector","surface","location_connector","manufactur","type_bga","state_bga","color","keyboard","volume_video","type_video","frequency","with_memory");
                                                    if (in_array($value["CODE"], $masHar) && strlen(trim($value["VALUE"]))>0)
	                                            {
	                                                echo '<i>'.$value["NAME"].'</i>: '.$value["VALUE"].'<br />';
	                                            }
	                                		}?> 
	                		            </p>
	                                    <?endif;?>
	                               	</div>
	                                <?=$arItem["PREVIEW_TEXT"];?>
				                </td>
	                            
	                            <td>
	                                <div class="cat-item-price bxm-section-box-analogs" style="margin: 0 0 0 auto;" >

										
										
										<?
										
											$arElement=$arItem['_BXM_EX_'];
											
											
											$datetime=$arElement["PROPERTIES"]["DATA_TRANZITA"]["VALUE"];
				if (ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})", $datetime, $regs)) {
					$datetime2=$regs[3].'.'.$regs[2].'.'.$regs[1];
				} else {
					$datetime2=$datetime;
				}			
							
							$today = date("d.m.Y");  
			$compare_date = $DB->CompareDates($datetime2,$today);
			if ($compare_date<0 || !isset($arResult["PROPERTIES"]["DATA_TRANZITA"]["VALUE"])) $valid_date=false;
			else $valid_date=true;
										
										?>
										
										<div class="item-buy pink">
	                                        <span class="price"> <?if($arItem['_BXM_EX_']['PRICE']) echo number_format($arItem['_BXM_EX_']['PRICE'],0,'.',' '); ?><span class="rubl">&#8399;</span></span> 
	                                        <?if(isset($arItem['_BXM_EX_']['CATALOG_QUANTITY']) && intval($arItem['_BXM_EX_']['CATALOG_QUANTITY']) > 0  || ($arElement["PROPERTIES"]["DATA_TRANZITA"]["VALUE"] && $valid_date) || ($arItem["ANALOGI_COUNT"]>0 && (strpos($arItem["TITLE_FORMATED"],'атрица') || strpos($arItem["TITLE_FORMATED"],'лавиатура') || strpos($arItem["TITLE_FORMATED"],'ккумулятор')))):?>
	                                            <span class="item-quantity-switch">	
	                                                    <input type="text" size="3" value="1" name="" class="q-input" />
	                                                    <span class="switch-top"></span>
	                                                    <span class="switch-bot"></span>
	                  		                    </span>
	                  		                    <a href="<?echo $arItem["URL_WO_PARAMS"].'?action=ADD2BASKET&id='.$arItem['ITEM_ID'];?>" class="button-buy catalog-item-buy" rel="nofollow"><?=GetMessage('T_SEARCH_ITEM_BUY_BTN_LABLE');?></a><div class="clearfix">&nbsp;</div>
	                                        <?endif;?>
	                                    </div>
										
									
										
										 <div class="q-block"  >
						
                            
                            <?
				
							
							
							if ($arElement['CATALOG_QUANTITY']>0)
                            {
                                ?>
								<p class="big">В наличии</p>
								<?
								$qtmp = ( $arElement['CATALOG_QUANTITY'] > 10 ? '> 10' : $arElement['CATALOG_QUANTITY']);
                                echo '<span class="q-type q-yes" data-quantity="'.$arElement['CATALOG_QUANTITY'].'"  >'.GetMessage('CATALOG_GOODS_QUANTITY_ALL',array('#QUANT#'=>$qtmp)).'</span>';
                            }
							
							 elseif($arElement["PROPERTIES"]["DATA_TRANZITA"]["VALUE"] && $valid_date){
								?>
<p class="big"></p>
<p class="quantity_zero_notice" >
<?


echo '<span style="float:left;" >Нет в наличии, отгрузка возможна '.$datetime2.'</span><br><br>';
								

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
                                
        			echo '<p class="quantity_zero_notice" ><span style="float:left;" >'.GetMessage('T_SEARCH_ITEM_QUANTITY_ZERO').', &nbsp;</span><a href="#" data-name="'.$arItem["TITLE"].'" data-id="'.$arItem['ID'].'" class="notify-link pink italic" style="line-height:13px"><span >'.GetMessage('T_SEARCH_NOTICE').'</span></a>';
	                                            echo '<div class="section_analogi_mini_info"></div>';
                       
                            }
                            ?> 
                        </div>
										
	                                    
									
										
	                                    <?if(isset($arItem["ANALOGI"])):?>
	                                    <div class="section-analogi-box-hidden"><?=$arItem["ANALOGI"];?></div>
	                                    <?endif;?>
	                                    <p class="small12">
	                                        <span class="floatleft  catalog-item-compare-link-box" data-product-id="<?=$arItem['ITEM_ID'];?>"><?=GetMessage('T_SEARCH_COMPARE');?></span>
	                                        <span class="floatright catalog-item-wishlist-link-box" data-product-id="<?=$arItem['ITEM_ID'];?>"><?=GetMessage('T_SEARCH_TO_WISHLIST');?></span>
	                                    </p>
	                                    <div class="clearfix">&nbsp;</div>
	                                </div>
	                            </td>
	                                
			                <?else:?>
			                <td>
			                	<p class="search-title"><a href="<?echo $arItem["URL"]?>"><?echo $arItem["TITLE_FORMATED"]?></a></p>
			                    <p class="grey"><?echo $arItem["BODY_FORMATED"]?></p>
			                </td>
			                <?endif;?>
			            </tr>
		        	</table>				
				</div>
				
				<?
				
				
				
				
			}
			
			?>
		</div>
		<?
		
	}
			
	endif;
	
	?>
                
<script type="text/javascript">
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
        var uniq_id = ab.attr('data-product-id');
        
        if(ab.find('tr').length > 0)
        {
            var c = '';
			var a=0;
            var an = ab.find('tr');
            if(an.length > 0)
            {
                for(var i=0;i<an.length && i<3;i++)
                {
                    c += '<tr>' + an[i].innerHTML + '</tr>';
					a++;
                }
                abi.html('<p class="analogi-label" ><?=GetMessage('CATALOG_ITEM_ANALOGI_INFO_LABEL');?></p><table style="width:100%;" ><tbody>'+ c + '</tbody></table>'); 
                abi.parent().find('.quantity_zero_notice').remove();
				
				
				//abi.parent().parent().find('.button-buy').remove();
				//abi.parent().parent().find('.item-quantity-switch').remove();
			
			
				
                
            }
        } 
    }
// область поиска -каталог., нововсти , статьи..
$('.search-where > span').click(function(){
   $(this).toggleClass('active');
   var v = [];
   var s = $(this).parent().find('span.active');
   for(var i = 0; i < s.length; i++)
   {
        v.push(s.eq(i).attr('data-val'));
   }
   $(this).parent().find('input[name="search-where"]').val(v.join(','));
});


</script>	
	
	<script type="text/javascript">
	 function __toggleBlock(section_id){
			$('#tovar-block-list-' + section_id).toggle();
		}; 
	</script>