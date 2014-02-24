<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<ul>
	<? 
	foreach ($arResult as $ar_Value)
	{
		?><li <?=($ar_Value['SELECTED']) ? 'class="active"' : ''?>><a href="<?=$ar_Value['LINK']?>"><?=$ar_Value['TEXT']?><span></span></a></li><?	
	}
	?>
</ul>
