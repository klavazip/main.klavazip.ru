<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


<div class="boxMain">				
	<h1>Сравнение товаров <span><?=count($arResult["ITEMS"])?></span></h1>				
	<div class="border_1"></div>
	<div class="boxCompare">
		<div class="compareLeft">
			<div class="compareNav">
				<ul>
					<li <?=($_GET['DIFFERENT'] !== 'Y') ? 'class="active"' : ''?>><a href="/catalog/compare/"><span>Все характеристики</span></a></li>
					<li <?=($_GET['DIFFERENT'] == 'Y')  ? 'class="active"' : ''?>><a href="/catalog/compare/?DIFFERENT=Y"><span>Только отличия</span></a></li>
				</ul>
			</div>
			<div class="border_compare"></div>
			<? /*?>
			<div class="oneLineCompare firstLine" id="line1"><p>Описание</p><div class="bg_hover"></div></div>
			<? */ ?>	
			<div class="border_compare"></div>
			
			<? 
			
			//unset($arResult['ITEMS']['DETAIL_TEXT']);
			
			//echo '<pre>', print_r($arResult['SHOW_PROPERTIES']).'</pre>';
			
			
			$b_Diff = ($_GET['DIFFERENT'] == 'Y');
			
			$ar_PropertyValues = array();
			foreach ($arResult["ITEMS"] as $ar_El)
			{
				foreach ($ar_El['PROPERTIES'] as $s => $v)
				{
					if(strlen($v['VALUE']) > 0)
						$ar_PropertyValues[$s][] = $v['VALUE'];
				}
			}
			
			$u = 1;
			foreach ( $arResult['SHOW_PROPERTIES'] as $s_PropertyCode => $ar_Value )
			{
				if( array_key_exists($s_PropertyCode, $ar_PropertyValues) )
				{
					if( !$b_Diff || count( array_unique($ar_PropertyValues[$s_PropertyCode])  ) > 1 )
					{
						?>
						<div class="oneLineCompare" id="line<?=$u?>"><p class="subcategory"><?=$ar_Value['NAME']?></p><div class="bg_hover"></div></div>
						<div class="border_compare"></div>
						<?
					}
					$u++;
				}	
			}
			?>
		</div>
		<div class="compareRight">
			<div class="compareFirst">
				<div class="blockCompareProducts">
					<table cellpadding="0" cellspacing="0">
						<tr>
							<? 
							
							foreach($arResult["ITEMS"] as $ar_Element)
							{
								?>
								<td>									
									<div class="oneProductCompare">
										<a href="/catalog/compare/?action=DELETE_FROM_COMPARE_RESULT&IBLOCK_ID=8&ID[]=<?=$ar_Element['ID']?>" class="compareBasket"></a>
										<a href="<?=$ar_Element['DETAIL_PAGE_URL']?>">
											<? 
											if(file_exists($_SERVER['DOCUMENT_ROOT'].$ar_Element['PREVIEW_PICTURE']['SRC']))
											{
												$img = CFile::ResizeImageGet($ar_Element['PREVIEW_PICTURE'], array('width'=>151, 'height'=>102),BX_RESIZE_IMAGE_PROPORTIONAL_ALT,false);
											}
											else
											{
												$img['src'] = '/bitrix/templates/klavazip/img/no-pic-big.jpg';
											}											
											?>
											<img src="<?=$img['src']?>" alt="" />
										</a>		
										<a href="<?=$ar_Element['DETAIL_PAGE_URL']?>" class="name"><?=$ar_Element['NAME']?></a>	
										<div class="clear"></div>
										<p class="price"><?=$ar_Element['PRICES']['Розничная']['VALUE']?> <span class="curency"><?=KlavaMain::RUB?></span></p>
										<p class="present"><?=( intval($ar_Element['CATALOG_QUANTITY']) > 0 ) ? 'Есть' : 'Нет' ?> в наличии </p>
										<div class="clear"></div>	
										
										<? 
										if(intval($ar_Element['CATALOG_QUANTITY']) > 0)
										{
											?><input type="button" onclick="klava.addBasket('<?=$ar_Element['ID']?>', 1)" class="buttonBuy" value="В корзину"/><?
										}
										else
										{
											?><input type="button" onclick="klava.catalog.showWindowNoticAddProduct('<?=$ar_Element['ID']?>'); return false;" class="buttonNotify" title="Сообщить о поступлении товара в продажу"  value="Уведомить"/><?
										}
										?>
																
									</div>
								</td>
								<?								
							}
							?>
						</tr>
					</table>
				</div>
			</div>
			<div class="compareSecond">
				<div class="blockCompareProducts">
					<table cellpadding="0" cellspacing="0">
						
							<? 
							/*
							 * <tr id="tableLine1">
							foreach ( $arResult["ITEMS"] as $ar_Element )
							{
								?><td><div class="oneLineTableCompare firstLine" style="overflow: auto;"><p><?=$ar_Element['DETAIL_TEXT']?></p></div></td><?								
							} 
							</tr>
							*/
							?>
						
				
						<?
						$j = 1;  
						foreach ( $arResult["SHOW_PROPERTIES"] as $s_PropCode => $ar_PropValue )
						{
							if( array_key_exists($s_PropCode, $ar_PropertyValues) )
							{
								if( ! $b_Diff || count( array_unique($ar_PropertyValues[$s_PropCode]) ) > 1 )
								{
									?><tr id="tableLine<?=$j?>"><?
										foreach ($arResult["ITEMS"] as $ar_El)
										{
											?>
											<td><div class="oneLineTableCompare">
												<p>
													<?=$ar_El['PROPERTIES'][$s_PropCode]['VALUE']?>
												</p>
											</div></td>
											<?
										}
									?></tr><?
								}
								$j++;
							}	
						}
						?>
					</table>
				</div>
			</div>
		</div>
		<div class="clear"></div>	
	</div>
</div>	
<? /*?>
<div class="margin-bottom"></div>
<div class="page-title">
	<h1><?=GetMessage('CATALOG_COMPARE_TITLE',array('#QUANT#'=>($itemsCnt = count($arResult['ITEMS']))));?></h1>
</div>
<div class="content-bg2 order-content compare-bg">
<?

?>	
    <div class="compare-overflow relative">
    	<div class="c-shadow"></div>
        <div class="c-right"></div>
        <?$delUrlID = "";?>

        <table class="compare-table" width="100%" cellpadding="0" cellspacing="0">
        	<thead>
                <tr>
                    <td>
                    	<div class="relative compare-select" style="width:100%">
                    		<?
								echo '<p><a href="'.htmlspecialchars($APPLICATION->GetCurPageParam("DIFFERENT=N",array("DIFFERENT"))).'" rel="nofollow" class="'.(!$_REQUEST['DIFFERENT'] || $_REQUEST['DIFFERENT'] == 'N' ? 'active-compare' : '').'" ><span>'.GetMessage('CATALOG_COMPARE_ALL_HARACTERISTIKI').'</span></a></p>
                        		      <p><a href="'.htmlspecialchars($APPLICATION->GetCurPageParam("DIFFERENT=Y",array("DIFFERENT"))).'" rel="nofollow" class="'.($_REQUEST['DIFFERENT'] && $_REQUEST['DIFFERENT'] == 'Y' ? 'active-compare' : '').'"><span>'.GetMessage('CATALOG_COMPARE_TOLKO_RAZLICHIYA').'</span></a></p>';
                        	?>
                        	<div class="c-left"></div>
                        </div>
                    </td>
                   
                  	<?
                    foreach($arResult["ITEMS"] as $arElement):
                        $delUrlID .= "&ID[]=".$arElement["ID"];
                        
                       
                       if(file_exists($_SERVER['DOCUMENT_ROOT'].$arElement['PREVIEW_PICTURE']['SRC']))
				            {
				                $img =CFile::ResizeImageGet($arElement['PREVIEW_PICTURE'], array('width'=>151, 'height'=>102),BX_RESIZE_IMAGE_PROPORTIONAL_ALT,false);
				            }
				            else
				            {
				                $img['src'] = '/bitrix/templates/klavazip/img/no-pic-big.jpg';
				            }
              
                    
                        // 
                        if(isset($arElement['PROPERTIES']['SALELEADER']['VALUE']) && $arElement['PROPERTIES']['SALELEADER']['VALUE'] == 'Y' ) $bHIT = true; else $bHIT = false;
                        if(isset($arElement['PROPERTIES']['NEWPRODUCT']['VALUE']) && $arElement['PROPERTIES']['NEWPRODUCT']['VALUE'] == 'Y' ) $bNEW = true; else $bNEW = false;
                       
                   ?>
					<td>
                        <div class="relative">
                        	<div class="labels" style="z-index:100">
                                <?=($bHIT ? '<div class="label-hit"></div>' : ''); ?>
                                <?=($bNEW  ? '<div class="label-new"></div>' : ''); ?>
			                </div>
	                    	<p><a href="<?=urldecode($arElement['DETAIL_PAGE_URL'])?>"><img src="<?=$img["src"]?>" style="height:80px;" /></a></p>
	                        <p><a href="<?=urldecode($arElement['DETAIL_PAGE_URL'])?>"><?=$arElement['NAME']?></a></p>
	                        <a href="<?=htmlspecialchars($APPLICATION->GetCurPageParam("action=DELETE_FROM_COMPARE_RESULT&IBLOCK_ID=".$arParams['IBLOCK_ID']."&ID[]=".$arElement['ID'],array("action", "IBLOCK_ID", "ID")))?>" class="compare-remove"></a>
	                    </div><!-- .relative -->
                	</td>
                    <?
                    endforeach;
                    ?>
				</tr>
			</thead>			
			<tbody>
				<?
                $i=0;
                if(isset($arResult["ITEMS"][0]["FIELDS"]))
                foreach($arResult["ITEMS"][0]["FIELDS"] as $code=>$field)
                {
                    if($code == "NAME") continue;
                    if (($code!="DETAIL_PICTURE") && ($code!="PREVIEW_PICTURE"))
                    {
                        echo '<tr '.( $i%2==0 ? 'class="even"' : '' ).' ><td class="grey">'.GetMessage("IBLOCK_FIELD_".$code).'</td>';
                        foreach($arResult["ITEMS"] as $arElement)
                        {
                            echo '<td>';
                            switch($code)
                            {
                                case "NAME":
                                {
                                    echo '<a href="'.$arElement["DETAIL_PAGE_URL"].'">'.$arElement[$code].'</a>';
                                }
                                break;
                                case "PREVIEW_PICTURE":
                    			case "DETAIL_PICTURE":				
                    			break;
                    			default:
                    				echo $arElement["FIELDS"][$code];
                    			break;
                            }
                    		echo '</td>';
                        }
                        echo '</tr>';
                    }
                    $i++;
                }
                
                // ��������
                $i=0;
                foreach($arResult["SHOW_PROPERTIES"] as $code=>$arProperty)
                {
        	        $arCompare = Array();
                	foreach($arResult["ITEMS"] as $arElement)
                	{
                		$arPropertyValue = $arElement["DISPLAY_PROPERTIES"][$code]["VALUE"];
                		if(is_array($arPropertyValue))
                		{
                			sort($arPropertyValue);
                			$arPropertyValue = implode(" / ", $arPropertyValue);
                		}
                		$arCompare[] = $arPropertyValue;
                	}
                    
        	       $diff = (count(array_unique($arCompare)) > 1 ? true : false);
                   
        	       if($diff || !$arResult["DIFFERENT"])
                   {
                        echo '<tr '.($i%2==0 ? 'class="even"' : '' ).'><td class="grey">'.$arProperty["NAME"].'</td>';
                            
                    		foreach($arResult["ITEMS"] as $arElement)
                            {
                    			if($diff)
                                {
                                   echo '<td>'.(is_array($arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])	? implode("/ ", $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]) : $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"] ).'</td>';
                                }
                                else
                                {
                                   echo '<td>'.(is_array($arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]) ? implode("/ ", $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]) : $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]).'</td>';
                                }
                    		}
                            
                        echo '</tr>';
                    }
                    
                    $i++;
                }
                
                // ���� �������� �����������
                foreach($arResult["SHOW_OFFER_FIELDS"] as $code=>$field)
                {
           
        			echo '<tr class="even"><td class="gray">'.GetMessage("IBLOCK_FIELD_".$code).'</td>';
                    foreach($arResult["ITEMS"] as $arElement)
                    {
                        echo '<td>'.$arElement['OFFER_FIELDS'][$code].'</td>';
                    }
                    echo '</tr>';
                }
                
                // �������� �������� �����������
                foreach($arResult["SHOW_OFFER_PROPERTIES"] as $code=>$arProperty)
                {
                
                	$arCompare = Array();
                	foreach($arResult["ITEMS"] as $arElement)
                	{
                		$arPropertyValue = $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["VALUE"];
                		if(is_array($arPropertyValue))
                		{
                			sort($arPropertyValue);
                			$arPropertyValue = implode(" / ", $arPropertyValue);
                		}
                		$arCompare[] = $arPropertyValue;
                	}
                    
                	$diff = (count(array_unique($arCompare)) > 1 ? true : false);
                	if($diff || !$arResult["DIFFERENT"])
                    {
                        echo '<tr class="even"><td class="grey">'.$arProperty["NAME"].'</td>';
                        
                		foreach($arResult["ITEMS"] as $arElement)
                        {
                            if($diff)
                            {
                                echo '<td>'.(is_array($arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]) ? implode("/ ", $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]) : $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]).'</td>';
                            }
                            else
                            {
                                echo '<td>'.(is_array($arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]) ? implode("/ ", $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]) : $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]).'</td>';
                            }
                        }
                        echo '<tr>';
                	   $i++;
                    }
                }
                
                // ���� ������
                if(isset($arResult["ITEMS"][0]["PRICES"]))
                    foreach($arResult["ITEMS"][0]["PRICES"] as $code=>$arPrice)
                    {
                    	if($arPrice["CAN_ACCESS"])
                        {
                            echo '<tr class="even"><td class="grey">'.$arResult["PRICES"][$code]["TITLE"].'</td>';
                            foreach($arResult["ITEMS"] as $arElement)
                            {
                                echo '<td class="pink big">';
           
                                                
                            }
                            echo '</td></tr>';
                         }
                    }
                
                ?>
				
			<tr>
				<td class="grey data-column"><?=GetMessage('CATALOG_COMPARE_PRICE');?></td>
                    <?
                    foreach($arResult["ITEMS"] as $arElement)
                    {
			$arPrice = CPrice::GetBasePrice($arElement['ID']);
                        echo '<td class="pink big">'.$arPrice['PRICE'].'<span class="rubl">&#8399;</span></td>';
                      
                    } 
                    ?>
			</tr>
				<tr>
					<td class="grey data-column"></td>
                    <?
					foreach($arResult["ITEMS"] as $arElement)
                    { 
                        ?>
					<td>
						
						<?if (intval($arElement['CATALOG_QUANTITY']) > 0)
						{
						?>
						<span class="button-l">
                                                    <span class="button-r">
                                                        <a class="button catalog-item-buy" href="/?action=ADD2BASKET&id=<?=$arElement['ID']?>#buy"><?=GetMessage('CATALOG_COMPARE_BUY');?></a>
                                                    </span>
						</span>
						<?}?>	
							
					</td>
                        <? 
					} 
                    ?>
				</tr>
				
				
				
				
				
				<tr>
					<td></td>
					 <?
						if($arElement['CATALOG_QUANTITY'] <= 0)
						 	{
							 	// помимо наименований
							 	$c = '';
							 	ob_start();
							 	$APPLICATION->IncludeComponent("bxmaker:bxmaker.catalog.analogi", "main_analog", array (
							 			"CACHE_TYPE" => "A",
							 			"CACHE_TIME" => "36000000",
							 			"IBLOCK_TYPE" => $arElement["IBLOCK_TYPE"],
							 			"IBLOCK_ID" => $arElement["IBLOCK_ID"],
							 			"ELEMENT_ID" => $arElement['ID'],
							 			"ELEMENT_COUNT" => 100,
							 			"SHOW_QUANTITY" => 'Y',
							 			'QUANTITY_MORE' => 'Y'
							 	),
							 			false
							 	);
							 	$c = ob_get_contents();
							 	ob_end_clean();
							 
							 	$arResult["ANALOGI"][$arElement['ID']] = $c;
						     }
			
						foreach($arResult["ITEMS"] as $arElement)
	                    { 
	                    ?>
						
						<td>
						
                        <?
	                   	if ($arElement["CATALOG_QUANTITY"]>0)
                        {
						?>
						
	                        <div class="q-block">
	                           	<p class="big"><?=GetMessage('CATALOG_QUANTITY_LABEL');?></p>
                                <?
                                $qtmp = $qtmp = ( $arElement['CATALOG_QUANTITY'] > 10 ? '> 10' : $arElement['CATALOG_QUANTITY']);
                                ?>
	                            <span class="q-type q-yes" data-quantity="<?=$arElement['CATALOG_QUANTITY'];?>"   ><?=GetMessage('CATALOG_QUANTITY_ALL',array('#QUANT#'=>$qtmp));?></span>
	    					</div>
	                     
	                     <?
	                     }
	                     else
	                     {
	                     ?>
	                     
	                         <div class="q-block">
	                           	<p class="big"><?=GetMessage('CATALOG_QUANTITY_LABEL');?></p>
	                            <p class="quantity_zero_notice" >
	                                <span style="float:left;" ><?=GetMessage('CATALOG_ITEM_QUANTITY_ZERO');?>, &nbsp;</span>
	                                <a href="#" style="line-height:13px" data-name="<?=$arElement['NAME'];?>" data-id="<?=$arElement['ID'];?>" data-product-id="<?=$arElement['ID'];?>" class="notify-link pink italic"><span><?=GetMessage('CATALOG_NOTICE_LINK');?></span></a>
	                            </p>
	                           <div class="section_analogi_mini_info"><?=$c?></div> 
	                         </div>
	                         
	                         <div class="section-analogi-box-hidden"  data-product-id="<?=$arElement['ID'];?>" ></div>
	                     <?
	                     }
	                     ?>
	                    <p class="small12">                            	
	                        <span class="floatleft catalog-item-compare-link-box " data-product-id="<?=$arElement['ID'];?>"  ><?=GetMessage('CATALOG_COMPARE_LABEL');?></span>
							<span class="floatright catalog-item-wishlist-link-box "  data-product-id="<?=$arElement['ID'];?>" ><?=GetMessage('CATALOG_WISHLIST_LABEL');?></span>
	                     </p>
						</td>
					<?}?>
				</tr>
			</tbody>
		</table>
	</div>
</div>
</div>

<script type='text/javascript'>
$(document).ready(function(){
	$('.compare-table tbody tr').each(function(){
		var deltr=true;
		$(this).find('td:not(.grey)').each(function(){
			deltr=deltr & ($.trim($(this).html()).length==0);
		})
		//console.log(deltr)
		if(deltr) $(this).remove();
	})
	$('.compare-table tbody tr').removeClass('even');
	$('.compare-table tbody tr:even').addClass('even');



	
})
</script>

<? */ ?>