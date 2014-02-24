<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?
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
<form name="<?echo $arResult["FILTER_NAME"]."_form"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get">
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
							<?endforeach;*/?>
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
    			
    			<input type="submit" style="background: none; border:none; border-bottom:1px dashed #808080; color:#808080;" class="reset-filter borderLink italic" onclick="clearManufactur()" value="Сбросить настройки" name="del_filter"/>
    			<script>
    				function clearManufactur(){
    					$(".checkboxes").find("input:checked").removeAttr( "checked");
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