<?	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


	//echo '<pre>', print_r($arResult).'</pre>';


	$ar_Result = array();
	foreach ($arResult['ITEM'] as $ar_Value)
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
		$b_FilterControlVisible = (count($ar_ValFilter['VALUE']) <= 3); 
		
		?>
		<div class="oneBlockFilter" >
			<h3><?=$ar_ValFilter['NAME']?> <a href="<?=$ob_FilterLink->clearProp($ar_ValFilter['CODE'])?>"><span>сбросить</span></a></h3>
			<div class="blockCheckbox" <?=($_COOKIE['KLAVA_SHOW_PROPERTY_'.$ar_ValFilter['CODE']] == 'Y' || $b_FilterControlVisible) ? 'style="height: 100%;"' : '' ?>>		
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
				<a class="showAllFilter" <?=($_COOKIE['KLAVA_SHOW_PROPERTY_'.$ar_ValFilter['CODE']] == 'Y') ? 'style="display: none;"' : '' ?> onclick="klava.setFilterSelectedList('<?=$ar_ValFilter['CODE']?>');return false;" href="#"><span>Показать все</span></a>
				<a class="closeAllFilter" <?=($_COOKIE['KLAVA_SHOW_PROPERTY_'.$ar_ValFilter['CODE']] == 'Y') ? 'style="display: inline;"' : '' ?> onclick="klava.unsetFilterSelectedList('<?=$ar_ValFilter['CODE']?>');return false;" href="#"><span>Свернуть</span></a>
				<?	
			}	
			?>							
		</div>
		<?
	}
	?>
					










