<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>
<div class="boxNav2">
	<ul>
		<?
		foreach($arResult as $itemIdex => $ar_Item)
		{
			?><li <?=( $ar_Item['SELECTED'] ) ? 'class="active"' : ''?>><a href="<?=$ar_Item["LINK"]?>" ><?=$ar_Item["TEXT"]?></a></li><?
		}
		?>
	</ul>
	<div class="clear"></div>
</div>