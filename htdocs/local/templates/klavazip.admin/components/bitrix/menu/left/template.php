<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>



<?
/*if (!empty($arResult)):?>
<ul class="left-menu">

	<?
	foreach($arResult as $arItem):
		if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) 
			continue;
	?>
		<?if($arItem["SELECTED"]):?>
			<li><a href="<?=$arItem["LINK"]?>" class="selected"><?=$arItem["TEXT"]?></a></li>
		<?else:?>
			<li><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></li>
		<?endif?>
		
	<?endforeach?>

</ul>
<?endif */?>


<? 
//echo '<pre>', print_r($arResult).'</pre>';
?>

<div role="navigation" id="sidebar" class="col-xs-6 col-sm-3 sidebar-offcanvas">
	<div class="list-group">
		
		<? 
		foreach ($arResult as $ar_Value)
		{
			?><a class="list-group-item <?=($ar_Value['SELECTED']) ? 'active' : ''?>" href="<?=$ar_Value['LINK']?>"><?=$ar_Value['TEXT']?></a><?
		}
		?>
		
		<? /*?>
		<a class="list-group-item" href="#">Запросы на товары</a>
        <a class="list-group-item" href="#">Запросы на возврат</a>
        <a class="list-group-item" href="#">Link</a>
        <a class="list-group-item" href="#">Link</a>
        <a class="list-group-item" href="#">Link</a>
        <a class="list-group-item" href="#">Link</a>
        <a class="list-group-item" href="#">Link</a>
        <a class="list-group-item" href="#">Link</a>
        <a class="list-group-item" href="#">Link</a>
        <  */?>
	</div>
</div>
        
        
