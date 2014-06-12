<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?$ob_FilterLink = new KlavaCatalogFilter(true); //BEDROSOVA 23.05.2014 Включена поддержка ЧПУ для фильтра?>

<?
$ar=array();
$ar2=array();
foreach($arResult["ITEMS"] as $arItem)
{
	if($arItem["PROPERTY_TYPE"] == "N" || isset($arItem["PRICE"]))
	{
		$ar[] = $arItem;
	}
	else
	{
		$ar2[]=$arItem;
	}
}
$arResult["ITEMS"]=$ar+$ar2;
?>


<form name="<?echo $arResult["FILTER_NAME"]."_form"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get">
	<?foreach($arResult["HIDDEN"] as $arItem):?>
		<input
			type="hidden"
			name="<?echo $arItem["CONTROL_NAME"]?>"
			id="<?echo $arItem["CONTROL_ID"]?>"
			value="<?echo $arItem["HTML_VALUE"]?>"
		/>
	<?endforeach;?>
	<div>
		<?foreach($arResult["ITEMS"] as $arItem):?>
		<?if (count($arItem["VALUES"])>0):?>
		<?$b_FilterControlVisible = (count($arItem['VALUES']) <= 3); ?>
		<div class="oneBlockFilter" >
			<?if($arItem["PROPERTY_TYPE"] == "N" || isset($arItem["PRICE"])):?>
<?
$arItem["VALUES"]["MIN"]["VALUE"] = intval($arItem["VALUES"]["MIN"]["VALUE"]);
$arItem["VALUES"]["MAX"]["VALUE"] = intval($arItem["VALUES"]["MAX"]["VALUE"]);

$current_min = $arItem["VALUES"]["MIN"]["VALUE"];
$current_max = $arItem["VALUES"]["MAX"]["VALUE"];

if (isset($_GET['price-from'])) $current_min = intval($_GET['price-from']);
if (isset($_GET['price-to'])) $current_max = intval($_GET['price-to']);


?>
			<h3><?=$arItem["NAME"]?><a href="<?=$ob_FilterLink->clearProp($arItem['CODE'])?>"><span style="margin-left:5px;">сбросить</span></a></h3>
				
				<div class="slider-box boxSlideFilter" id="slider-price">
			<div class="inner priceInput">
				<form action="" method="get" id="js-catalog-price-filter">
				
								
				
				<div class="left">									
						<input id="js-catalog-price-filter-from" type="text" class="text priceProduct" name="price-from" placeholder="<?echo $arItem["VALUES"]["MIN"]["VALUE"]?>" value="<?echo $current_min;?>">
						<span class="unit">р. —</span>         
					</div>
					<div class="right">									
						<input type="text" id="js-catalog-price-filter-to" class="text priceProduct" name="price-to" data-max="<?echo $arItem["VALUES"]["MAX"]["VALUE"]?>" placeholder="<?echo $arItem["VALUES"]["MAX"]["VALUE"]?>" value="<?echo $current_max;?>">
						<span class="unit">р.</span>                                                
					</div>
				</form>  
				<div class="clear"></div>
			</div>
			<div class="slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all">
				<a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 0%;"></a>
				<a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 100%;"></a>
			</div>	
			<div class="lastPrice"><?echo $arItem["VALUES"]["MAX"]["VALUE"]?> <span>руб.</span></div>						
		</div>

								
			<?elseif(!empty($arItem["VALUES"])):;?>
			<h3><?=$arItem["NAME"]?><a href="<?=$ob_FilterLink->clearProp($arItem['CODE'])?>"><span style="margin-left:5px;">сбросить</span></a></h3>
				
				<div class="blockCheckbox" <?=($_COOKIE['KLAVA_SHOW_PROPERTY_'.$arItem['CODE']] == 'Y' || $b_FilterControlVisible) ? 'style="height: 100%;"' : '' ?>>	
				<?$i = 1;?>
				<?//print_r ($arItem["VALUES"]);?>
					<?foreach($arItem["VALUES"] as $val => $ar):?>
					<?$b_Cheked = $ob_FilterLink->isValue($arItem['CODE'], $val);?>
					<a href="<?=$ob_FilterLink->getFilterUrlAction($arItem["CODE"], $val);?>" style="cursor:pointer;">
					<div class="lineCheckbox" style="cursor:pointer !important;">
						<span style="display:inline-block" class="checkbox <?=($b_Cheked) ? 'checked' : ''?>"></span> 	
						<label style="cursor:pointer;" title="<?=TruncateText($ar['VALUE'], 20)?>" <?=($b_Cheked) ? 'class="active"' : ''?> for="filter-check_<?=$i?>"><?=TruncateText($ar['VALUE'], 20)?> <?/* ?><span>105</span><? */ ?>  </label>
						<div class="clear"></div>
					</div>
					</a>
					<?$i++;?>
					<?endforeach;?>
			</div>
			<? 							
			if (count($arItem['VALUES']) > 3)
			{
				?>
				<a class="showAllFilter" <?=($_COOKIE['KLAVA_SHOW_PROPERTY_'.$arItem['CODE']] == 'Y') ? 'style="display: none;"' : '' ?> onclick="klava.setFilterSelectedList('<?=$arItem['CODE']?>');return false;" href="#"><span>Показать все</span></a>
				<a class="closeAllFilter" <?=($_COOKIE['KLAVA_SHOW_PROPERTY_'.$arItem['CODE']] == 'Y') ? 'style="display: inline;"' : '' ?> onclick="klava.unsetFilterSelectedList('<?=$arItem['CODE']?>');return false;" href="#"><span>Свернуть</span></a>
				<?	
			}	
			?>		
			<?endif;?>
		</div>
		<?endif;?>
		<?endforeach;?>
		</ul>

		<div class="modef" id="modef" <?if(!isset($arResult["ELEMENT_COUNT"])) echo 'style="display:none"';?>>
			<?echo GetMessage("CT_BCSF_FILTER_COUNT", array("#ELEMENT_COUNT#" => '<span id="modef_num">'.intval($arResult["ELEMENT_COUNT"]).'</span>'));?>
			<a href="<?echo $arResult["FILTER_URL"]?>" class="showchild"><?echo GetMessage("CT_BCSF_FILTER_SHOW")?></a>
			<!--<span class="ecke"></span>-->
		</div>
	</div>
</form>
<script>
	var smartFilter = new JCSmartFilter('<?echo CUtil::JSEscape($arResult["FORM_ACTION"])?>');
</script>