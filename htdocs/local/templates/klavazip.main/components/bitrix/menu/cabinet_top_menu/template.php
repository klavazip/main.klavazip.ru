<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<ul>
	<? 
	foreach ($arResult as $ar_Value)
	{
		?><li><a href="<?=$ar_Value['LINK']?>" <?=($ar_Value['SELECTED']) ? 'class="current"' : '' ?>><?=$ar_Value['TEXT']?><span></span></a></li><?	
	}
	?>
</ul>