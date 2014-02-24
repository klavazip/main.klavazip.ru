<?	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


	//echo '<pre>', print_r($arResult).'</pre>';


	$ar_Result = array();
	foreach ($arResult['arrProp'] as $ar_Value)
	{
		if( $ar_Value['CODE'] == 'NALICHIE_BITOGO_PIKSELYA')
		{
			$ar_Value['VALUE_LIST'] = array('Y' => 'Да', 'N' => 'Нет');
		}
		
		$ar_Result[] = array(
			'NAME' 		=> $ar_Value['NAME'],
			'CODE_FORM' => 'arrFilter_pf['.$ar_Value['CODE'].']',
			'CODE'		=> $ar_Value['CODE'],
			'VALUE' 	=> $ar_Value['VALUE_LIST']
		);
	}
	
	$ob_FilterLink = new KlavaCatalogFilter();
	?>

	<script type="text/javascript">
		$(document).ready(function() 
		{
			jQuery( "#slider-price .slider" ).slider({
				range  : true,
				min    : <?=intval($arParams['PRICE_INTERVAL']['MIN'])?>,
				max    : <?=intval($arParams['PRICE_INTERVAL']['MAX'])?>,
				step   : 50,
				values : [ 
					<?=( ! isset($_GET['price-from'])) ? intval($arParams['PRICE_INTERVAL']['MIN']) : intval($_GET['price-from'])?>, 
					<?=( ! isset($_GET['price-to']))   ? intval($arParams['PRICE_INTERVAL']['MAX']) : intval($_GET['price-to'])?>, 
				],
				slide  : function( event, ui )
				{
					jQuery( "#slider-price .left input" ).val(ui.values[ 0 ]);
					jQuery( "#slider-price .right input" ).val(ui.values[ 1 ]);
				},
				stop : function( event, ui )
				{
					$('#js-catalog-price-filter').submit();
				}
			});
		});
	</script>
					
	<div class="oneBlockFilter">
		<h3>Цена <a href="<?=$APPLICATION->GetCurPageParam("", array("price-from", "price-to"));  ?>"><span>сбросить</span></a></h3>
		<div class="slider-box boxSlideFilter" id="slider-price">
			<div class="inner priceInput">
				<form action="" method="get" id="js-catalog-price-filter">
				
				<? 
				if(strlen($_GET['filter']) > 0)
				{
					?><input type="hidden" name="filter" value="<?=$_GET['filter']?>" /><?
				}
				?>
				
				
				<div class="left">									
						<input 
							id="js-catalog-price-filter-from"
							type="text" 
							class="text priceProduct" 
							name="price-from" 
							placeholder="<?=intval($arParams['PRICE_INTERVAL']['MIN'])?>" 
							value="<?=( ! isset($_GET['price-from'])) ? intval($arParams['PRICE_INTERVAL']['MIN']) : intval($_GET['price-from'])?>" 
							
							/>
						<span class="unit">р. &mdash;</span>         
					</div>
					<div class="right">									
						<input 
							type="text" 
							id="js-catalog-price-filter-to" 
							class="text priceProduct" 
							name="price-to"
							data-max="<?=intval($arParams['PRICE_INTERVAL']['MAX'])?>" 
							placeholder="<?=intval($arParams['PRICE_INTERVAL']['MAX'])?>" 
							value="<?=( ! isset($_GET['price-to'])) ? intval($arParams['PRICE_INTERVAL']['MAX']) : intval($_GET['price-to'])?>" 
							
						/>
						<span class="unit">р.</span>                                                
					</div>
				</form>  
				<div class="clear"></div>
			</div>
			<div class="slider"></div>	
			<div class="lastPrice"><?=intval($arParams['PRICE_INTERVAL']['MAX'])?> <span><?=KlavaMain::RUB?></span></div>						
		</div> 
	</div>
	
	<? 
	foreach ($ar_Result as $ar_ValFilter)
	{
		?>
		<div class="oneBlockFilter">
			<h3><?=$ar_ValFilter['NAME']?> <a href="<?=$ob_FilterLink->clearProp($ar_ValFilter['CODE'])?>"><span>сбросить</span></a></h3>
			<div class="blockCheckbox">		
				<? 
				$s_UrlFilter = '';
				$i = 1;
				foreach ($ar_ValFilter['VALUE'] as $i_Val => $s_Val)
				{
					$b_Cheked = $ob_FilterLink->isValue($ar_ValFilter['CODE'], $i_Val);
					?>
					<div class="lineCheckbox">
						<span onclick="klava.localRedirect('<?=$ob_FilterLink->getFilterUrlAction($ar_ValFilter['CODE'], $i_Val)?>')"  style="display:inline-block" class="checkbox <?=($b_Cheked) ? 'checked' : ''?>"></span> 	
						<label title="<?=$s_Val?>" <?=($b_Cheked) ? 'class="active"' : ''?> for="filter-check_<?=$i?>"><?=TruncateText($s_Val, 20)?> <?/* ?><span>105</span><? */ ?>  </label>
						<div class="clear"></div>
					</div>
					<?
					$i++;
				}
				?>
			</div>
			<? 							
			if (count($ar_ValFilter['VALUE']) > 3)
			{
				?>
				<a class="showAllFilter" href="#"><span>Показать все</span></a>
				<a class="closeAllFilter" href="#"><span>Свернуть</span></a>
				<?	
			}	
			?>							
		</div>
		<?
	}
	?>
					
					
					<? /*?>
					<div class="oneBlockFilter">
					
						<h3>Производитель <a href="#"><span>сбросить</span></a></h3>
						<div class="blockCheckbox">						
							<div class="lineCheckbox">
								<input type="checkbox" name="check_1" class="styled check" style="position: absolute; left: -9999px;" id="check_1">
								<label for="check_1">Acer <span>105</span></label>
								<div class="clear"></div>
							</div>
							<div class="lineCheckbox">
								<input type="checkbox" name="check_2" class="styled check" style="position: absolute; left: -9999px;" id="check_2">
								<label for="check_2">Packard Bell <span>98</span></label>
								<div class="clear"></div>
							</div>
							<div class="lineCheckbox">
								<input type="checkbox" name="check_3" class="styled check" style="position: absolute; left: -9999px;" id="check_3">
								<label for="check_3">Samsung <span>65</span></label>
								<div class="clear"></div>
							</div>
							<div class="lineCheckbox">
								<input type="checkbox" name="check_4" class="styled check" style="position: absolute; left: -9999px;" id="check_4">
								<label for="check_4">Dell <span>81</span></label>
								<div class="clear"></div>
							</div>
							<div class="lineCheckbox">
								<input type="checkbox" name="check_5" class="styled check" style="position: absolute; left: -9999px;" id="check_5">
								<label for="check_5">Lenovo <span>80</span></label>
								<div class="clear"></div>
							</div>
							<div class="lineCheckbox">
								<input type="checkbox" name="check_6" class="styled check" style="position: absolute; left: -9999px;" id="check_6">
								<label for="check_6">Lenovo <span>80</span></label>
								<div class="clear"></div>
							</div>
							<div class="lineCheckbox">
								<input type="checkbox" name="check_7" class="styled check" style="position: absolute; left: -9999px;" id="check_7">
								<label for="check_7">Lenovo <span>80</span></label>
								<div class="clear"></div>
							</div>
						</div>
						<a class="showAllFilter" href="#"><span>Показать все</span></a>
						<a class="closeAllFilter" href="#"><span>Свернуть</span></a>
					</div>

					<div class="oneBlockFilter">
						<h3>Диагональ экрана <a href="#"><span>сбросить</span></a></h3>
						<div class="blockCheckbox">
							
							<div class="lineCheckbox">
								<input type="checkbox" name="check_8 " class="styled check" style="position: absolute; left: -9999px;" id="check_8">
								<label for="check_8">15” <span>68</span></label>
								<div class="clear"></div>								
							</div>
							
							<div class="lineCheckbox">
								<input type="checkbox" name="check_9 " class="styled check" style="position: absolute; left: -9999px;" id="check_9">
								<label for="check_9">14” <span>61</span></label>
								<div class="clear"></div>								
							</div>
							
							<div class="lineCheckbox">
								<input type="checkbox" name="check_10 " class="styled check" style="position: absolute; left: -9999px;" id="check_10">
								<label for="check_10">13,5” <span>50</span></label>
								<div class="clear"></div>
							</div>
							
							<div class="lineCheckbox">
								<input type="checkbox" name="check_11 " class="styled check" style="position: absolute; left: -9999px;" id="check_11">
								<label for="check_11">17” <span>42</span></label>
								<div class="clear"></div>
							</div>
							
							<div class="lineCheckbox">
								<input type="checkbox" name="check_12 " class="styled check" style="position: absolute; left: -9999px;" id="check_12">
								<label for="check_12">11,5” <span>41</span></label>
								<div class="clear"></div>
							</div>
							
							<div class="lineCheckbox">
								<input type="checkbox" name="check_13 " class="styled check" style="position: absolute; left: -9999px;" id="check_13">
								<label for="check_13">19” <span>39</span></label>
								<div class="clear"></div>
							</div>
							
							<div class="lineCheckbox">
								<input type="checkbox" name="check_14 " class="styled check" style="position: absolute; left: -9999px;" id="check_14">
								<label for="check_14">11” <span>36</span></label>
								<div class="clear"></div>
							</div>
							
							<div class="lineCheckbox">
								<input type="checkbox" name="check_15 " class="styled check" style="position: absolute; left: -9999px;" id="check_15">
								<label for="check_15">10” <span>12</span></label>
								<div class="clear"></div>
							</div>
							
							<div class="lineCheckbox">
								<input type="checkbox" name="check_16 " class="styled check" style="position: absolute; left: -9999px;" id="check_16">
								<label for="check_16">10,5” <span>11</span></label>
								<div class="clear"></div>
							</div>
							
							<div class="lineCheckbox">
								<input type="checkbox" name="check_17 " class="styled check" style="position: absolute; left: -9999px;" id="check_17">
								<label for="check_17">8” <span>3</span></label>
								<div class="clear"></div>
							</div>
							
							<div class="lineCheckbox">
								<input type="checkbox" name="check_18 " class="styled check" style="position: absolute; left: -9999px;" id="check_18">
								<label for="check_18">6” <span>2</span></label>
								<div class="clear"></div>
							</div>
						</div>
						<a class="showAllFilter" href="#"><span>Показать все</span></a>
						<a class="closeAllFilter" href="#"><span>Свернуть</span></a>
					</div>
					<? */ ?>












<?
//echo '<pre>', print_r($arResult).'</pre>';
/*


$masURI = preg_split('#/#', $_SERVER["REQUEST_URI"]);
$fsession_id = false;
$session_id = null;
foreach ($masURI as $value) {
	if ($fsession_id==true) {$session_id = $value; break;}
	if ($value=="catalog"){
		$fsession_id = true;
	}
}
//ECHO $session_id
?>
<?
$propMass = array();
if (isset($arResult["arrProp"])){
	foreach($arResult["arrProp"] as $prop)
	{
		$propMass[$prop["NAME"]]  = $prop["VALUE_LIST"];
	}
}
	
    
?>
<!--noindex-->
<form name="<?echo $arResult["FILTER_NAME"]."_form"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get" class="section_filter_box" >
	<div class="filter-wrap">
		<div class="filter">
			 <div class="hide-this">
	
				
			 	<?$i=0;?>
				<?foreach($arResult["ITEMS"] as $arItem):
					if(array_key_exists("HIDDEN", $arItem)):
						echo $arItem["INPUT"];
					endif;
				endforeach;?>				
					
					
					<?foreach($arResult["ITEMS"] as $arItem):?>
						<?if(!array_key_exists("HIDDEN", $arItem)):?>
						
							<?if ($arItem["NAME"]=="Интернет-магазин"):?>
								<?preg_match_all('/ name=(.*?) /',$arItem["INPUT"],$matches)?>								
								<?preg_match_all('/ value="([0-9]+)" /',$arItem["INPUT"],$matches1)?>
			                <div class="clearfix"></div>        
							<div class="margin10  relative">
			                    <span class="label">Цена, <span class="rubl">⃏</span></span>
			                    <div class="price-slider floatleft">
			                        <input type="hidden" class="price-min" name="<?=$matches[1][0]?>" value="<?=$matches1[1][0]?>" />
			                        <input type="hidden" class="price-max" name="<?=$matches[1][1]?>" value="<?=$matches1[1][1]?>" />
			                        <p class="relative noMarginTop">
			                            <span class="price-min2 italic"></span>
			                            <span class="price-max2 italic"></span>
			                        </p>
			                        
			                        <div class="price-slider2"><span>0</span> <div id="slider-range"></div> <span>50 000</span></div>
			                    </div>
			                    <div class="clearfix"></div>
			             	</div>
			             	<?elseif($arItem["NAME"]=="Производитель"): $manufactur_result = $arItem;?>
			             						             			
					         
							<?else:?>
								
								<?if (count($propMass[$arItem["NAME"]])>0):?>
									<div class="relative floatleft  margin10" style="margin-right:10px;">
									
					                    <span class="label" style="min-width:180px;"><?=$arItem["NAME"]?></span>
					                    <span class="fake-select"><?if ($arItem["INPUT_VALUE"]=="") echo "(не выбрано)"; else echo $propMass[$arItem["NAME"]][$arItem["INPUT_VALUE"]];?></span>
					                    <div class="fake-select-popup">
					                    	<a href="#" data-val="" >(не выбрано)</a>
					                    	<?foreach($propMass[$arItem["NAME"]] as $key=>$value):?>
					                        <a href="#" data-val="<?=$key?>" ><?=$value?></a>
					                        <?endforeach;?>
					                    </div>
					                    <input type="hidden" class="select-input" name="<?=$arItem["INPUT_NAME"]?>" value="<?=$arItem["INPUT_VALUE"]?>" />
					                </div>    
							
								<?endif;?>	
							<?endif;?>
													
							
							
							 <!---->
							<?endif?>
					<?endforeach;?>
					
					
					<!-- cost -->
					<div style="clear:both"></div>
					<div class="margin10 relative" style="height:80px">
						<span class="label">Цена, <span class="rubl">⃏</span></span>

						<div class="price-slider floatleft">
							<input type="hidden" class="price-min" name="price-minf" value="" />
							<input type="hidden" class="price-max" name="price-maxf" value="" />
							<p class="relative noMarginTop">
								<span class="price-min2 italic"></span>
								<span class="price-max2 italic"></span>
							</p>
							<div class="price-slider2">
								<span class='price-min-label'></span> 
								<div id="slider-range"></div> 
								<span class='price-max-label'></span>
							</div>
						</div>
						<div class="clearfix"></div>
						<div style='margin-left: 130px;margin-top: 10px'>
							<label><input class='nalichie' type='checkbox' <?=isset($_GET['nalichie'])?($_GET['nalichie']==1?'checked="checked"':''):''?> /> Показывать только товары, имеющиеся в наличии</label>
							<input type='hidden' name='nalichie' value='<?=isset($_GET['nalichie'])?($_GET['nalichie']==1?'1':'0'):'0'?>' />
							<script type="text/javascript" >
								$(document).ready(function(){ $('.nalichie').live('click',function(){ $('[name=nalichie]').val($('.nalichie').is(':checked')?'1':'0') }) })
							</script>
						</div>
					</div>
					<!-- cost -->
					
					
					<div class="relative  margin10" style="margin-right:10px;">
						<span class="label"><?=$manufactur_result["NAME"]?></span>
													
						<div class="checkboxes">
							<?/*foreach($propMass[$manufactur_result["NAME"]] as $key=>$value):?>
							<label><input type="checkbox" name="manufactur[]" <?if (isset($_REQUEST["manufactur"]) &&  is_array($_REQUEST["manufactur"])){ if (in_array($key, $_REQUEST["manufactur"])) echo 'checked="checked"';}?> value="<?=$key?>"> <?=$value?></label>					                        
							<?endforeach;/?>
						</div>	
						<?//echo "<pre>"; print_r($arResult); echo "</pre>";?>				                    
						<div class="clearfix"></div>
					</div>	
				<div class="margin10">
        		<span class="label w120">&nbsp;</span>
        			<input type="submit" name="set_filter" class="filter-button" value="" />
        			<input type="hidden" name="set_filter" value="Y" />
    			</div>
    			
    			<input type='hidden'  name="nodelprops" value='' />    			
    			
    			<input type="submit" style="background: none; border:none; border-bottom:1px dashed #808080; color:#808080;" class="reset-filter borderLink italic" onclick="clearManufactur();return false;" value="Сбросить настройки" name="del_filter"/>
    			<script>
    				function clearManufactur(){
    					$(".checkboxes").find("input:checked").removeAttr( "checked");
                        location.href='<?=$APPLICATION->GetCurPage();?>';
                    }
    				
    			</script>							
			</div>
            <p style="text-align:right"><a href="#" class="close-filter small12 borderLink" style="color:#ccc">скрыть фильтр</a></p>
	 	</div>
	 	
        <div class="clearfix">&nbsp;</div>
    </div>
   <div class="filter-fake">&nbsp;</div>
</form>
<!--/noindex-->

<? */ ?>
