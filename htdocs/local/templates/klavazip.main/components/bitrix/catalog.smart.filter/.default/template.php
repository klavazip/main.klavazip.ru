<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


<form name="<?=$arResult["FILTER_NAME"]."_form"?>" action="<?=$arResult["FORM_ACTION"]?>" method="get" class="smartfilter">

	<?
	foreach($arResult["HIDDEN"] as $arItem)
	{
		?><input type="hidden" name="<?=$arItem["CONTROL_NAME"]?>" id="<?=$arItem["CONTROL_ID"]?>" value="<?=$arItem["HTML_VALUE"]?>" /><?
	}
	?>

	<div class="filtren">
		<ul>
			<?
			foreach($arResult["ITEMS"] as $arItem)
			{
				if($arItem["PROPERTY_TYPE"] == "N" || isset($arItem["PRICE"]))
				{
					?>
					<div class="oneBlockFilter">
						<h3>Цена <a href="/catalog/matritsy/"><span>сбросить</span></a></h3>
						<div id="slider-price" class="slider-box boxSlideFilter">
							<div class="inner priceInput">
								<form id="js-catalog-price-filter" method="get" action="">
									<div class="left">									
										<input 
											type="text" 
											value="<?=floatval($arItem["VALUES"]["MIN"]["VALUE"])?>" 
											name="<?=$arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>" 
											class="text priceProduct" 
											id="js-catalog-price-filter-from"
											onkeyup="smartFilter.keyup(this)"
											>
										<span class="unit">р. &mdash;</span>         
									</div>
									<div class="right">									
										<input 
											type="text" 
											value="<?= floatval($arItem["VALUES"]["MAX"]["VALUE"])?>" 
											data-max="20600" 
											name="<?=$arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>" 
											class="text priceProduct" 
											id="js-catalog-price-filter-to"
											onkeyup="smartFilter.keyup(this)"
											>
										<span class="unit">р.</span>                                                
									</div>
								</form>  
								<div class="clear"></div>
							</div>
							<div class="slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"><div class="ui-slider-range ui-widget-header" style="left: 0%; width: 100%;"></div><a href="#" class="ui-slider-handle ui-state-default ui-corner-all" style="left: 0%;"></a><a href="#" class="ui-slider-handle ui-state-default ui-corner-all" style="left: 100%;"></a></div>	
							<div class="lastPrice"><?=floatval($arItem["VALUES"]["MAX"]["VALUE"])?> <span>руб.</span></div>						
						</div> 
					</div>
					<? 
					
					/*
					?>
					<li class="lvl1"> 
						<a href="#" onclick="BX.toggle(BX('ul_<?=$arItem["ID"]?>')); return false;" class="showchild"><?=$arItem["NAME"]?></a>
						<ul id="ul_<?=$arItem["ID"]?>">
							<li class="lvl2">
								<table border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td><span class="min-price"><?=GetMessage("CT_BCSF_FILTER_FROM")?></span></td>
										<td><span class="max-price"><?=GetMessage("CT_BCSF_FILTER_TO")?></span></td>
									</tr>
									<tr>
										<td><input
											class="min-price"
											type="text"
											name="<?=$arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"
											id="<?=$arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"
											value="<?=$arItem["VALUES"]["MIN"]["HTML_VALUE"]?>"
											size="5"
											onkeyup="smartFilter.keyup(this)"
										/></td>
										<td><input
											class="max-price"
											type="text"
											name="<?=$arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
											id="<?=$arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"
											value="<?=$arItem["VALUES"]["MAX"]["HTML_VALUE"]?>"
											size="5"
											onkeyup="smartFilter.keyup(this)"
										/></td>
									</tr>
								</table>
							</li>
						</ul>
					</li>
					<?
					*/
				}
				elseif(!empty($arItem["VALUES"]))  
				{
					?>
					<div class="oneBlockFilter" id="ul_<?=$arItem["ID"]?>">
						<h3><?=$arItem["NAME"]?> 
							
							<? /*?>
							<a href="/catalog/matritsy/"><span>сбросить</span></a>
							<? */ ?>
							
						</h3>
						<div class="blockCheckbox">					
						<?
						foreach($arItem["VALUES"] as $val => $ar)
						{
							?>
							<div class="lineCheckbox">
								<input 
									type="checkbox" 
									class="styled <?=$ar["CHECKED"] ? 'check' : ''?>" 
									<?=$ar["CHECKED"] ? 'checked="checked"': ''?> 
									name="<?=$ar["CONTROL_NAME"]?>"
									value="<?=$ar["HTML_VALUE"]?>"
									id="<?=$ar["CONTROL_ID"]?>"
									onchange="smartFilter.click(this)"
									/>
								<label <?= ($ar["DISABLED"]) ? 'class="disable"' : ''?> for="<?=$ar["CONTROL_ID"]?>"><?=$ar["VALUE"];?></label>
								<div class="clear"></div>
							</div>
							
							<? /*?>							
							<div class="lineCheckbox">
								<span class="checkbox " style="display:inline-block"></span> 	
								<label for="filter-check_1" title="Да">Да   </label>
								<div class="clear"></div>
							</div>
							
							<div class="lineCheckbox">
								<span class="checkbox " style="display:inline-block"></span> 	
								<label for="filter-check_2" title="Нет">Нет   </label>
								<div class="clear"></div>
							</div>
							<? */ ?>						
							<? /*?>					
							<li class="lvl2<?=$ar["DISABLED"]? ' lvl2_disabled': ''?>">
								<input
									type="checkbox"
									value="<?=$ar["HTML_VALUE"]?>"
									name="<?=$ar["CONTROL_NAME"]?>"
									id="<?=$ar["CONTROL_ID"]?>"
									<?=$ar["CHECKED"] ? 'checked="checked"': ''?>
									onclick="smartFilter.click(this)"
								/>
								<label for="<?=$ar["CONTROL_ID"]?>"><?=$ar["VALUE"];?></label>
							</li>
							<?
							*/
						}
						?>
						</div>
					</div>
					
					<?
					
					
					
					
					/*
					?>
					<li class="lvl1"> 
					
					<a href="#" onclick="BX.toggle(BX('ul_<?=$arItem["ID"]?>')); return false;" class="showchild"><?=$arItem["NAME"]?></a>
					
						<ul id="ul_<?=$arItem["ID"]?>">
							<?
							foreach($arItem["VALUES"] as $val => $ar)
							{
								?>
								
								<div class="oneBlockFilter">
									<h3>Наличие битого пикселя <a href="/catalog/matritsy/"><span>сбросить</span></a></h3>
									
									<div class="blockCheckbox">		
										<div class="lineCheckbox">
											<span class="checkbox " style="display:inline-block" onclick="klava.localRedirect('/catalog/matritsy/?filter=NALICHIE_BITOGO_PIKSELYA:[Y]')"></span> 	
											<label for="filter-check_1" title="Да">Да   </label>
											<div class="clear"></div>
										</div>
										<div class="lineCheckbox">
											<span class="checkbox " style="display:inline-block" onclick="klava.localRedirect('/catalog/matritsy/?filter=NALICHIE_BITOGO_PIKSELYA:[N]')"></span> 	
											<label for="filter-check_2" title="Нет">Нет   </label>
											<div class="clear"></div>
										</div>
									</div>
								</div>
								
								
								
								<li class="lvl2<?=$ar["DISABLED"]? ' lvl2_disabled': ''?>">
									<input
										type="checkbox"
										value="<?=$ar["HTML_VALUE"]?>"
										name="<?=$ar["CONTROL_NAME"]?>"
										id="<?=$ar["CONTROL_ID"]?>"
										<?=$ar["CHECKED"] ? 'checked="checked"': ''?>
										onclick="smartFilter.click(this)"
									/>
									<label for="<?=$ar["CONTROL_ID"]?>"><?=$ar["VALUE"];?></label>
								</li>
								<?
							}
							?>
						</ul>
					</li>
					<?
					*/
				}
			}
			?>
		</ul>
		
		<? /*?>
		<input type="submit" id="set_filter" name="set_filter" value="<?=GetMessage("CT_BCSF_SET_FILTER")?>" />
		<input type="submit" id="del_filter" name="del_filter" value="<?=GetMessage("CT_BCSF_DEL_FILTER")?>" />
		<? */ ?>


		<div class="modef" id="modef" <?if(!isset($arResult["ELEMENT_COUNT"])) echo 'style="display:none"';?>>
			<?echo GetMessage("CT_BCSF_FILTER_COUNT", array("#ELEMENT_COUNT#" => '<span id="modef_num">'.intval($arResult["ELEMENT_COUNT"]).'</span>'));?>
			<a href="<?echo $arResult["FILTER_URL"]?>" class="showchild"><?echo GetMessage("CT_BCSF_FILTER_SHOW")?></a>
			<!--<span class="ecke"></span>-->
		</div>
		
	</div>
</form>
<script>
	var smartFilter = new JCSmartFilter('<?=CUtil::JSEscape($arResult["FORM_ACTION"])?>');
</script>